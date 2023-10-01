<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \Modules\Authentication\Models\User::factory(10)->create();

        // \Modules\Authentication\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $feature = new Feature();
        $feature->setTranslation('name', 'en', 'trip program');
        $feature->setTranslation('name', 'ar', 'برنامج الرحلة');
        $feature->save();
    }
}
