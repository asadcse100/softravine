<?php

namespace Botble\Base\Supports;

use Botble\Base\Events\SystemUpdateAvailable;
use Botble\Base\Events\SystemUpdateCachesCleared;
use Botble\Base\Events\SystemUpdateCachesClearing;
use Botble\Base\Events\SystemUpdateChecked;
use Botble\Base\Events\SystemUpdateChecking;
use Botble\Base\Events\SystemUpdateDBMigrated;
use Botble\Base\Events\SystemUpdateDBMigrating;
use Botble\Base\Events\SystemUpdateDownloaded;
use Botble\Base\Events\SystemUpdateDownloading;
use Botble\Base\Events\SystemUpdateExtractedFiles;
use Botble\Base\Events\SystemUpdatePublished;
use Botble\Base\Events\SystemUpdatePublishing;
use Botble\Base\Events\SystemUpdateUnavailable;
use Botble\Base\Exceptions\MissingCURLExtensionException;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Services\ClearCacheService;
use Botble\Base\Supports\ValueObjects\CoreProduct;
use Botble\Setting\Facades\Setting;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Services\ThemeService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Session\Session;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Str;
use Throwable;
use ZipArchive;

/**
 * DO NOT MODIFY THIS FILE.
 *
 * @readonly
 */
final class Core
{
    private string $basePath;

    private string $coreDataFilePath;

    private string $productId;

    private string $productSource;

    private string $version = '1.0.0';

    private string $minimumPhpVersion = '8.0.2';

    private int $verificationPeriod = 1;

    public function __construct(
        private CacheRepository $cache,
        private Filesystem $files,
        private Session $session
    ) {
        $this->basePath = base_path();
        $this->coreDataFilePath = core_path('core.json');

        $this->parseDataFromCoreDataFile();
    }

    public static function make(): self
    {
        return app(self::class);
    }

    public function version(): string
    {
        return $this->version;
    }

    public function minimumPhpVersion(): string
    {
        return $this->minimumPhpVersion;
    }

    public function getLatestVersion(): CoreProduct|false
    {
        $response = $this->createRequest('/api/check_update', [
            'product_id' => $this->productId,
            'current_version' => '0.0.0',
        ]);

        return $this->parseProductUpdateResponse($response);
    }

    public function getUpdateSize(string $updateId): float
    {
        $sizeUpdateResponse = $this->createRequest('/api/get_update_size/' . $updateId, method: 'HEAD');

        return (float) $sizeUpdateResponse->header('Content-Length') ?: 1;
    }

    public function updateFilesAndDatabase(string $version): bool
    {
        return $this->updateFiles($version) && $this->updateDatabase();
    }

    public function updateFiles(string $version): bool
    {
        $filePath = $this->getUpdatedFilePath($version);

        if (! $this->files->exists($filePath)) {
            return false;
        }

        $this->cleanCaches();

        $coreTempPath = storage_path('app/core.json');

        try {
            $this->files->copy($this->coreDataFilePath, $coreTempPath);
            $zip = new Zipper();

            if ($zip->extract($filePath, $this->basePath)) {
                $this->files->delete($filePath);

                SystemUpdateExtractedFiles::dispatch();

                $this->files->delete($coreTempPath);

                return true;
            }

            if ($this->files->exists($coreTempPath)) {
                $this->files->move($coreTempPath, $this->coreDataFilePath);
            }

            return false;
        } catch (Throwable $exception) {
            if ($this->files->exists($coreTempPath)) {
                $this->files->move($coreTempPath, $this->coreDataFilePath);
            }

            $this->logError($exception);

            throw $exception;
        }
    }

    public function updateDatabase(): bool
    {
        try {
            $this->runMigrationFiles();

            return true;
        } catch (Throwable $exception) {
            rescue(fn () => $this->runMigrationFiles());

            $this->logError($exception);

            return false;
        }
    }

    public function publishUpdateAssets(): void
    {
        $this->publishCoreAssets();
        $this->publishPackagesAssets();
        $this->publishPluginsAssets();
        $this->publishThemesAssets();
    }

    public function publishCoreAssets(): bool
    {
        SystemUpdatePublishing::dispatch();

        $this->publishAssets(core_path());

        return true;
    }

    public function publishPackagesAssets(): bool
    {
        $this->publishAssets(package_path());

        return true;
    }

    public function publishPluginsAssets(): bool
    {
        $this->publishAssets(plugin_path());

        return true;
    }

    public function publishThemesAssets(): bool
    {
        $this->files->delete(theme_path(Theme::getThemeName() . '/public/css/style.integration.css'));

        $customCSS = Theme::getStyleIntegrationPath();

        if ($this->files->exists($customCSS)) {
            $this->files->copy($customCSS, storage_path('app/style.integration.css.') . time());
        }

        app(ThemeService::class)->publishAssets();

        SystemUpdatePublished::dispatch();

        return true;
    }

    public function cleanCaches(): void
    {
        try {
            SystemUpdateCachesClearing::dispatch();

            ClearCacheService::make()->purgeAll();

            SystemUpdateCachesCleared::dispatch();
        } catch (Throwable $exception) {
            $this->logError($exception);
        }
    }

    public function cleanUp(): bool
    {
        $this->cleanCaches();

        return true;
    }

    public function logError(Exception|Throwable $exception): void
    {
        logger()->error($exception->getMessage() . ' - ' . $exception->getFile() . ':' . $exception->getLine());
    }

    private function publishPaths(): array
    {
        return array_merge(
            IlluminateServiceProvider::pathsToPublish(null, 'cms-lang'),
            IlluminateServiceProvider::pathsToPublish(null, 'cms-public')
        );
    }

    public function publishAssets(string $path): void
    {
        foreach ($this->publishPaths() as $from => $to) {
            if (! Str::contains($from, $path)) {
                continue;
            }

            $this->files->ensureDirectoryExists(dirname($to));
            $this->files->copyDirectory($from, $to);
        }
    }

    private function runMigrationFiles(): void
    {
        SystemUpdateDBMigrating::dispatch();

        $migrator = app('migrator');

        $migrator->run(database_path('migrations'));

        $paths = [
            core_path(),
            package_path(),
            plugin_path(),
            theme_path(),
        ];

        foreach ($paths as $path) {
            foreach (BaseHelper::scanFolder($path) as $module) {
                if ($path == plugin_path() && ! is_plugin_active($module)) {
                    continue;
                }

                if ($path == theme_path() && $module !== Theme::getThemeName()) {
                    continue;
                }

                $modulePath = $path . '/' . $module;

                if (! $this->files->isDirectory($modulePath)) {
                    continue;
                }

                if ($this->files->isDirectory($moduleMigrationPath = $modulePath . '/database/migrations')) {
                    $migrator->run($moduleMigrationPath);
                }
            }
        }

        SystemUpdateDBMigrated::dispatch();
    }

    private function validateUpdateFile(string $filePath): bool
    {
        if (! class_exists('ZipArchive', false)) {
            return true;
        }

        $zip = new ZipArchive();

        if ($zip->open($filePath)) {
            if ($zip->getFromName('.env')) {
                return false;
            }

            /**
             * @var array{
             *     productId: string,
             *     source: string,
             *     apiUrl: string,
             *     apiKey: string,
             *     version: string,
             *     minimumPhpVersion?: string,
             * }|null $content
             */
            $content = json_decode($zip->getFromName('platform/core/core.json'), true);

            if (! $content) {
                return false;
            }

            if (! Validator::make($content, [
                'productId' => ['required', 'string'],
                'source' => ['required', 'string'],
                'apiUrl' => ['required', 'url'],
                'apiKey' => ['required', 'string'],
                'version' => ['required', 'string'],
                'marketplaceUrl' => ['required', 'url'],
                'marketplaceToken' => ['required', 'string'],
                'minimumPhpVersion' => ['nullable', 'string'],
            ])->stopOnFirstFailure()->fails()) {
                if ($content['productId'] !== $this->productId) {
                    $zip->close();

                    return false;
                }

                if (version_compare($content['version'], $this->version, '<')) {
                    $zip->close();

                    return false;
                }

                if (isset($content['minimumPhpVersion'])
                    && version_compare($content['minimumPhpVersion'], phpversion(), '>')) {
                    $zip->close();

                    return false;
                }
            } else {
                $zip->close();

                return false;
            }
        }

        $zip->close();

        return true;
    }

    public function getCoreFileData(): array
    {
        try {
            return json_decode($this->files->get($this->coreDataFilePath), true) ?: [];
        } catch (FileNotFoundException) {
            return [];
        }
    }

    private function parseProductUpdateResponse(Response $response): CoreProduct|false
    {
        $data = $response->json();

        if ($response->ok() && Arr::get($data, 'status')) {
            return new CoreProduct(
                Arr::get($data, 'update_id'),
                Arr::get($data, 'version'),
                Carbon::createFromFormat('Y-m-d', Arr::get($data, 'release_date')),
                trim((string) Arr::get($data, 'summary')),
                trim((string) Arr::get($data, 'changelog')),
                (bool) Arr::get($data, 'has_sql')
            );
        }

        return false;
    }

    private function getUpdatedFilePath(string $version): string
    {
        $version = str_replace('.', '_', $version);

        return base_path('update_main_' . $version . '.zip');
    }

}
