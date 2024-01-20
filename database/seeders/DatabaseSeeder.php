<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;

class DatabaseSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->prepareRun();

        $this->call([
            LanguageSeeder::class,
            UserSeeder::class,
            PageSeeder::class,
            CurrencySeeder::class,
            SettingSeeder::class,
            ThemeOptionSeeder::class,
            ProductCategorySeeder::class,
            ProductCollectionSeeder::class,
            ProductLabelSeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ProductAttributeSeeder::class,
            ProductTagSeeder::class,
            FaqSeeder::class,
            TestimonialSeeder::class,
            BlogSeeder::class,
            TeamSeeder::class,
            CareerSeeder::class,
            BusinessServiceSeeder::class,
            MenuSeeder::class,
            WidgetSeeder::class,
            ReviewSeeder::class,
            CustomerSeeder::class,
            ShippingSeeder::class,
        ]);

        $this->finished();
    }
}
