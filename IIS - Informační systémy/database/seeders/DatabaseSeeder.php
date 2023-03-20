<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            KurzSeeder::class,
            StudentSeeder::class,
            MistnostSeeder::class,
            TerminSeeder::class,
            TermindatumSeeder::class,
            LektorSeeder::class,
            HodnoceniSeeder::class,
            StudentKurzSeeder::class
        ]);
    }
}
