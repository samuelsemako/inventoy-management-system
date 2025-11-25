<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\setup\SetupPaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SetupPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     $payments = [
            ['payment_method_name' => 'Cash'],
            ['payment_method_name' => 'Bank Transfer'],
            ['payment_method_name' => 'Debit Card'],
            ['payment_method_name' => 'Gift Card'],
            ['payment_method_name' => 'Credit Card'],
            ['payment_method_name' => 'QR codes'],
            ['payment_method_name' => 'Mobile Wallets'],
            ['payment_method_name' => 'Cryptocurrency'],

        ];


        foreach ($payments as $payments) {
            SetupPaymentMethod::firstOrCreate(
                ['payment_method_name' => $payments['payment_method_name']] // Check column
            );
        }
    }
}
