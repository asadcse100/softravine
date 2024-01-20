<?php

namespace Database\Seeders;

use Botble\Base\Facades\MetaBox;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\BusinessService\Enums\PackageDuration;
use Botble\BusinessService\Models\Package;
use Botble\BusinessService\Models\Service;
use Botble\BusinessService\Models\ServiceCategory;
use Botble\Media\Facades\RvMedia;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BusinessServiceSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('business-services');

        ServiceCategory::query()->truncate();
        Service::query()->truncate();
        Package::query()->truncate();
        Slug::query()
            ->where('reference_type', ServiceCategory::class)
            ->orWhere('reference_type', Service::class)
            ->delete();
        MetaBoxModel::query()
            ->orWhere('reference_type', Package::class)
            ->delete();

        $fake = fake();

        $categories = [
            [
                'name' => 'Cross platform',
                'icon' => '/icons/cross-platform.png',
            ],
            [
                'name' => 'Digital marketing',
                'icon' => '/icons/brand-identity.png',
            ],
            [
                'name' => 'Social media',
                'icon' => '/icons/social-media.png',
            ],
            [
                'name' => 'Standard',
                'icon' => '/icons/business.png',
            ],
            [
                'name' => 'Creation',
                'icon' => '/icons/creation.png',
            ],
        ];

        foreach ($categories as $index => $item) {
            $index++;

            $serviceCategory = ServiceCategory::query()->create([
                'name' => $item['name'],
                'image' => "services/menu$index.png",
                'order' => $index,
            ]);

            if (array_key_exists('icon', $item)) {
                MetaBox::saveMetaBoxData($serviceCategory, 'icon', $item['icon']);
            }

            Slug::query()->create([
                'reference_type' => ServiceCategory::class,
                'reference_id' => $serviceCategory->getKey(),
                'key' => Str::slug($serviceCategory->name),
                'prefix' => SlugHelper::getPrefix(ServiceCategory::class),
            ]);
        }

        $services = [
            [
                'name' => 'TechBoost Solutions',
                'description' => 'Empowering businesses with cutting-edge IT consulting services for software development, cloud solutions, and cybersecurity, driving success in the digital landscape.',
                'content' => File::get(
                    database_path('seeders/contents/service-detail.html')
                ),
                'image' => 'general/banner-help-center-1.png',
                'images' => ['general/banner-help-center-1.png'],
            ],
            [
                'name' => 'EcoGreen Landscaping',
                'description' => 'Creating sustainable and beautiful outdoor spaces with eco-friendly designs and horticultural expertise, harmonizing with nature and enhancing brand identity.',
                'content' => File::get(
                    database_path('seeders/contents/service-detail.html')
                ),
                'image' => 'general/banner-help-center-2.png',
                'images' => ['general/banner-help-center-2.png'],
            ],
            [
                'name' => 'Precision Financial Advisors',
                'description' => 'Providing comprehensive financial planning and investment management services, securing your financial future with personalized strategies.',
                'content' => File::get(
                    database_path('seeders/contents/service-detail.html')
                ),
                'image' => 'general/box-image-1.png',
                'images' => ['general/box-image-1.png'],
            ],
            [
                'name' => 'HealthHub Wellness Programs',
                'description' => 'Designing customized wellness initiatives for companies, fostering a healthier and happier workforce, and promoting a positive corporate culture.',
                'content' => File::get(
                    database_path('seeders/contents/service-detail.html')
                ),
                'image' => 'general/box-image-2.png',
                'images' => ['general/box-image-2.png'],
            ],
            [
                'name' => 'SwiftDelivery Logistics',
                'description' => 'Streamlining supply chain solutions with an agile network and advanced technology, ensuring efficient transportation, warehousing, and distribution of products.',
                'content' => File::get(
                    database_path('seeders/contents/service-detail.html')
                ),
                'image' => 'general/branding-img-1.png',
                'images' => ['general/branding-img-1.png'],
            ],
            [
                'name' => 'In-house Logistics Services',
                'description' => 'Streamlining supply chain solutions with an agile network and advanced technology, ensuring efficient transportation, warehousing, and distribution of products.',
                'content' => File::get(
                    database_path('seeders/contents/service-detail.html')
                ),
                'image' => 'general/connect-with-us-1.png',
                'images' => ['general/connect-with-us-1.png'],
            ],
        ];

        $categoryIds = ServiceCategory::query()
            ->pluck('id')
            ->all();

        foreach ($services as $item) {
            ;
            $service = Service::query()->create(array_merge($item, [
                'category_id' => rand(1, count($categoryIds) - 1),
                'description' => 'We offer specialized departments for continental transports.',
                'is_featured' => $fake->boolean(),
                'views' => rand(0, 10000),
                'content' => str_replace(
                    'general/',
                    RvMedia::getImageUrl('general/'),
                    $item['content'],
                ),
            ]));

            Slug::query()->create([
                'reference_type' => Service::class,
                'reference_id' => $service->getKey(),
                'key' => Str::slug($service->name),
                'prefix' => SlugHelper::getPrefix(Service::class),
            ]);
        }

        $packages = [
            [
                'name' => 'Trial Plan',
                'description' => 'Protect for testing',
                'price' => 'FREE',
                'annual_price' => 'FREE',
                'duration' => PackageDuration::MONTHLY,
                'metadata' => [
                    'icon' => 'business-services/free.png',
                ],
                'features' => <<<HTML
                + Brand Awareness Ads
                + Retargeting Ads
                + Contextual, Demographic
                + Facebook Advertising
                - Global Certificates
                - Snapchat Advertising
                - TikTok Advertising
                - Advanced List Building
                HTML,
            ],
            [
                'name' => 'Standard',
                'description' => 'Advanced project',
                'price' => '$29',
                'annual_price' => '$348',
                'duration' => PackageDuration::MONTHLY,
                'metadata' => [
                    'icon' => 'business-services/standard.png',
                ],
                'features' => <<<HTML
                + Brand Awareness Ads
                + Retargeting Ads
                + Contextual, Demographic
                + Facebook Advertising
                + Global Certificates
                - Snapchat Advertising
                - TikTok Advertising
                - Advanced List Building
                HTML,
                'is_popular' => true,
            ],
            [
                'name' => 'Business',
                'description' => 'Protect for testing',
                'price' => '$99',
                'annual_price' => '$1,188',
                'duration' => PackageDuration::MONTHLY,
                'metadata' => [
                    'icon' => 'business-services/business.png',
                ],
                'features' => <<<HTML
                + Brand Awareness Ads
                + Retargeting Ads
                + Contextual, Demographic
                + Facebook Advertising
                + Global Certificates
                + Snapchat Advertising
                - TikTok Advertising
                - Advanced List Building
                HTML,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Protect for testing',
                'price' => '$299',
                'annual_price' => '$3,588',
                'duration' => PackageDuration::MONTHLY,
                'metadata' => [
                    'icon' => 'business-services/enterprise.png',
                ],
                'features' => <<<HTML
                + Brand Awareness Ads
                + Retargeting Ads
                + Contextual, Demographic
                + Facebook Advertising
                + Global Certificates
                + Snapchat Advertising
                + TikTok Advertising
                + Advanced List Building
                HTML,
            ],
        ];

        foreach ($packages as $item) {
            $package = Package::query()->create(array_merge(Arr::except($item, 'metadata'), [
                'content' => File::get(
                    database_path('seeders/contents/package-detail.html')
                ),
            ]));

            foreach ($item['metadata'] as $key => $value) {
                MetaBox::saveMetaBoxData($package, $key, $value);
            }

            Slug::query()->create([
                'reference_type' => Package::class,
                'reference_id' => $package->getKey(),
                'key' => Str::slug($package->name),
                'prefix' => SlugHelper::getPrefix(Package::class),
            ]);
        }
    }
}
