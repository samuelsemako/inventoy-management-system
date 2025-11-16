<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setup\SetupTitle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SetupTitlesSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            'MR.',
            'MRS.',
            'MS.',
        ];

        foreach ($titles as $titleName) {
            SetupTitle::firstOrCreate([
                'title_name' => $titleName
            ]);
        }
    }
}
