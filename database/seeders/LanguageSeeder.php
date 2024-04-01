<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $language = Language::factory()->create([
            'name' => 'English',
            'short_form' => 'EN',
        ]);

        $language = Language::factory()->create([
            'name' => 'Français',
            'short_form' => 'FR',
        ]);
    }
}
