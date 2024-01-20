<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Theme\Facades\Theme;
use Botble\Widget\Models\Widget;

class WidgetSeeder extends BaseSeeder
{
    public function run(): void
    {
        Widget::query()->truncate();

        $data = [
            [
                'widget_id' => 'NewsletterWidget',
                'sidebar_id' => 'pre_footer_sidebar',
                'position' => 0,
                'data' => [
                    'name' => __('Subscribe to Newsletter.'),
                    'description' => __('Subscribe to get latest updates and information.'),
                    'title' => __('Subscribe our newsletter'),
                    'subtitle' => __('By clicking the button, you are agreeing with our Term & Conditions'),
                    'image' => 'general/newsletter-image.png',
                    'icon_primary' => 'logo/logo-circle.png',
                ],
            ],
            [
                'widget_id' => 'SiteInformationWidget',
                'sidebar_id' => 'footer_menu',
                'position' => 1,
                'data' => [
                    'logo' => 'logo/logo.png',
                    'url' => '#',
                    'address' => '4517 Washington Ave.Manchester, Kentucky 39495',
                    'working_hours_start' => '08:00',
                    'working_hours_end' => '17:00',
                ],
            ],
            [
                'widget_id' => 'CustomMenuWidget',
                'sidebar_id' => 'footer_menu',
                'position' => 2,
                'data' => [
                    'id' => 'CustomMenuWidget',
                    'name' => 'About Us',
                    'menu_id' => 'about-us',
                ],
            ],
            [
                'widget_id' => 'CustomMenuWidget',
                'sidebar_id' => 'footer_menu',
                'position' => 3,
                'data' => [
                    'id' => 'CustomMenuWidget',
                    'name' => 'Resources',
                    'menu_id' => 'resources',
                ],
            ],
            [
                'widget_id' => 'CustomMenuWidget',
                'sidebar_id' => 'footer_menu',
                'position' => 4,
                'data' => [
                    'id' => 'CustomMenuWidget',
                    'name' => 'We offer',
                    'menu_id' => 'we-offer',
                ],
            ],
            [
                'widget_id' => 'AppsDownloadWidget',
                'sidebar_id' => 'footer_menu',
                'position' => 5,
                'data' => [
                    'id' => 'AppsDownloadWidget',
                    'title' => __('App & Payment'),
                    'subtitle' => __('Download our Apps and get extra 15% Discount on your first Order.'),
                    'platform_google_play_logo' => 'general/google.png',
                    'platform_google_play_url' => 'https://play.google.com/store',
                    'platform_apple_store_logo' => 'general/apple.png',
                    'platform_apple_store_url' => 'https://www.apple.com/store',
                ],
            ],
            [
                'widget_id' => 'BlogSearchWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'BlogSearchWidget',
                    'name' => 'Blog Search',
                ],
            ],
            [
                'widget_id' => 'BlogCategoriesWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'BlogCategoriesWidget',
                    'name' => 'Blog Categories',
                ],
            ],
            [
                'widget_id' => 'BlogPostsWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 3,
                'data' => [
                    'id' => 'BlogPostsWidget',
                    'name' => 'Blog Posts',
                    'type' => 'popular',
                    'limit' => 5,
                    'style' => 'sidebar',
                ],
            ],
            [
                'widget_id' => 'BlogTagsWidget',
                'sidebar_id' => 'blog_sidebar',
                'position' => 4,
                'data' => [
                    'id' => 'BlogTagsWidget',
                    'name' => 'Blog Tags',
                ],
            ],
            [
                'widget_id' => 'FeaturedProductCategoryWidget',
                'sidebar_id' => 'product_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'FeaturedProductCategoryWidget',
                    'title' => __('Ready to capture every wonderful moment'),
                    'subtitle' => __('CAMERA COLLECTION'),
                    'image_primary' => 'general/banner-product.png',
                    'image_secondary' => 'general/bg-banner-secondary.png',
                    'category_ids' => ['2', '3', '4', '5'],
                ],
            ],
            [
                'widget_id' => 'ProductsWidget',
                'sidebar_id' => 'product_list_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'ProductsWidget',
                    'title' => __('Popular Items'),
                    'number_of_display' => 6,
                ],
            ],
        ];

        $theme = Theme::getThemeName();

        foreach ($data as $item) {
            Widget::query()->create(
                array_merge($item, [
                    'theme' => $theme,
                ])
            );
        }
    }
}
