<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setup\SetupCounter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SetupCountsSeeder extends Seeder
{

    public function run(): void
{
    $counters = [
        ['counter_id' => 'ADMIN', 'counter_value' => 0, 'counter_desc' => 'COUNT NUMBER OF ADMIN USERS'],
        ['counter_id' => 'PROD', 'counter_value' => 0, 'counter_desc' => 'COUNT NUMBER OF PRODUCTS'],
        ['counter_id' => 'SUPP', 'counter_value' => 0, 'counter_desc' => 'COUNT NUMBER OF SUPPLIERS'],
        ['counter_id' => 'CATG', 'counter_value' => 0, 'counter_desc' => 'COUNT NUMBER OF CATEGORIES'],
        ['counter_id' => 'SALE', 'counter_value' => 0, 'counter_desc' => 'COUNT NUMBER OF SALES'],
        ['counter_id' => 'ALAT', 'counter_value' => 0, 'counter_desc' => 'COUNT NUMBER OF ALERTS'],
        ['counter_id' => 'SLITM', 'counter_value' => 0, 'counter_desc' => 'COUNT NUMBER OF SALES ITEMS'],

        
    ];

    foreach ($counters as $counter) {
        SetupCounter::firstOrCreate(
            ['counter_id' => $counter['counter_id']], // Check column
            ['counter_value' => $counter['counter_value'], 'counter_desc' => $counter['counter_desc']] // Insert if not exists
        );
    }
}
}
