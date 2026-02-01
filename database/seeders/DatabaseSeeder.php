<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CountrySeeder::class,
            ConfigurationOptionsSeeder::class,
            SubscriptionsSeeder::class,
            ConfigEmailLinkSeeder::class,
            ConfigTitleTextSeeder::class,
            ConfigImagesSeeder::class,
            UserSeeder::class,
            SpecialtiesSeeder::class,
            ContentTypesSeeder::class,
            GeneratedContentsSeeder::class, // Demo generated content for demo user
        ]);
    }
}
