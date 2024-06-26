<?php

namespace Botble\PluginManagement\Services;

use Botble\Base\Supports\Core;
use Botble\Base\Supports\Zipper;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Throwable;

class MarketplaceService
{
    protected string $url;

    protected string|null $token;

    protected string $publishedPath;

    protected string $productId;

    public function __construct(string $url = null, string $token = null)
    {
        $core = Core::make()->getCoreFileData();

        $this->url = $url ?? $core['marketplaceUrl'];

        $this->token = $token ?? $core['marketplaceToken'];

        $this->publishedPath = storage_path('app/marketplace/');

        $this->productId = $core['productId'];
    }

    public function callApi(string $method, string $path, array $request = []): JsonResponse|Response
    {
        if (! config('packages.plugin-management.general.enable_marketplace_feature')) {
            abort(404);
        }

        try {
            $request = array_merge($request, [
                'product_id' => $this->productId,
                'site_url' => rtrim(url('')),
                'core_version' => get_core_version(),
            ]);

            $response = $this->request()->{$method}($this->url . $path, $request);

            if ($response->status() !== 200) {
                $body = json_decode($response->body(), true);

                return $this->responseReturn(
                    Arr::get($body, 'message') ?: trans('packages/plugin-management::marketplace.api_connect_error'),
                    true,
                    [],
                    $response->getStatusCode()
                );
            }

            return $response;
        } catch (Throwable $e) {
            report($e);

            return $this->responseReturn(trans('packages/plugin-management::marketplace.api_connect_error'), true);
        }
    }

    protected function request(): PendingRequest
    {
        return Http::asJson()
            ->withHeaders([
                'Authorization' => 'Token ' . $this->token,
            ])
            ->acceptJson()
            ->withoutVerifying()
            ->connectTimeout(100)
            ->timeout(300);
    }

    protected function extractFile(string $id, string $name): string
    {
        $destination = $this->publishedPath . $id . '/' . $name . '.zip';
        $pathTo = $this->publishedPath . $id;

        $zipper = new Zipper();

        if (! $zipper->extract($destination, $pathTo)) {
            return $this->responseReturn(trans('packages/plugin-management::marketplace.unzip_failed'), true);
        }

        File::delete($destination);

        return $pathTo;
    }

    protected function copyToPath(string $id, string $type, string $name): string
    {
        $pathTemp = $this->publishedPath . $id;
        $path = ($type == 'plugin' ? plugin_path($name) : theme_path($name));

        if (File::isDirectory($pathTemp)) {
            File::copyDirectory($pathTemp, $path);
            File::deleteDirectory($pathTemp);
        }

        return $path;
    }

    protected function responseReturn(
        string $message,
        bool $error = false,
        array $data = [],
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'error' => $error,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}
