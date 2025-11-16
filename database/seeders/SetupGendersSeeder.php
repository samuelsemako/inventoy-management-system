<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setup\SetupGender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SetupGendersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
        ['gender_name' =>'MALE'],
        ['gender_name' =>'FEMALE'],  
        ];

        
        foreach ($genders as $gender) {
            SetupGender::firstOrCreate(     
                ['gender_name' => $gender['gender_name']] // Check column
            );
        }                      
    }
}
