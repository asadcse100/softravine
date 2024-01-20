<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Setting\Facades\Setting;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;

class ThemeOptionSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('logo');

        $theme = Theme::getThemeName();

        Setting::newQuery()->where('key', 'LIKE', 'theme-' . $theme . '-%')->delete();

        $data = [
            'site_name' => 'Iori',
            'site_title' => 'Multipurpose Startup Laravel Script.',
            'site_description' => 'Iori is a Multipurpose Agency Laravel Script. It is a powerful, clean, modern, and fully responsive template. It is designed for agency, business, corporate, creative, freelancer, portfolio, photography, personal, resume, and any kind of creative fields.',
            'copyright' => sprintf('©%s Archi Elite JSC. All right reserved.', Carbon::now()->format('Y')),
            'favicon' => 'logo/favicon.png',
            'logo' => 'logo/logo.png',
            'primary_font' => 'Manrope',
            'primary_color' => '#024430',
            'success_color' => '#06D6A0',
            'danger_color' => '#EF476F',
            'warning_color' => '#FFD166',
            'secondary_color' => '#066a4c',
            'homepage_id' => 1,
            'authentication_background_image' => 'general/banner-authentication.png',
            'authentication_line_image' => 'icons/line.png',
            'hotline' => '0123456789',
            'action_button_text' => 'Get Started',
            'action_button_url' => '/register',
            'header_top_enabled' => 1,
            'cookie_consent_message' => 'Your experience on this site will be improved by allowing cookies',
            'cookie_consent_learn_more_url' => '/cookie-policy',
            'cookie_consent_learn_more_text' => 'Cookie Policy',
            'social_links' => [
                [
                    [
                        'key' => 'social-name',
                        'value' => 'Facebook',
                    ],
                    [
                        'key' => 'social-icon',
                        'value' => 'icons/facebook.png',
                    ],
                    [
                        'key' => 'social-url',
                        'value' => 'https://www.facebook.com/',
                    ],
                ],
                [
                    [
                        'key' => 'social-name',
                        'value' => 'Twitter',
                    ],
                    [
                        'key' => 'social-icon',
                        'value' => 'icons/twitter.png',
                    ],
                    [
                        'key' => 'social-url',
                        'value' => 'https://www.twitter.com/',
                    ],
                ],
                [
                    [
                        'key' => 'social-name',
                        'value' => 'Instagram',
                    ],
                    [
                        'key' => 'social-icon',
                        'value' => 'icons/instagram.png',
                    ],
                    [
                        'key' => 'social-url',
                        'value' => 'https://www.instagram.com/',
                    ],
                ],
                [
                    [
                        'key' => 'social-name',
                        'value' => 'Linkedin',
                    ],
                    [
                        'key' => 'social-icon',
                        'value' => 'icons/linkedin.png',
                    ],
                    [
                        'key' => 'social-url',
                        'value' => 'https://www.linkedin.com/',
                    ],
                ],
            ],
            '404_page_image' => 'icons/404.png',
            'step_create_account' => [
                [
                    [
                        'key' => 'title',
                        'value' => 'Register',
                    ],
                    [
                        'key' => 'description',
                        'value' => 'All you need is your name, email and a strong password, Or use your social media accounts.',
                    ],
                ],
                [
                    [
                        'key' => 'title',
                        'value' => 'Activate',
                    ],
                    [
                        'key' => 'description',
                        'value' => 'Use the code sent to your email to activate your account.',
                    ],
                ],
                [
                    [
                        'key' => 'title',
                        'value' => 'Open a trading account',
                    ],
                    [
                        'key' => 'description',
                        'value' => 'Create a real or demo trading account on our platform. No credit card required.',
                    ],
                ],
                [
                    [
                        'key' => 'title',
                        'value' => 'Connect with investors',
                    ],
                    [
                        'key' => 'description',
                        'value' => 'With a real-time analysis system you will become a professional investor.',
                    ],
                ],
                [
                    [
                        'key' => 'title',
                        'value' => 'Almost done',
                    ],
                    [
                        'key' => 'description',
                        'value' => 'Start your amazing journey on our platform.',
                    ],
                ],
            ],
        ];

        Setting::set($this->prepareThemeOptions($data));
        Setting::save();
    }
}
