<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setup\SetupStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SetupStatusesSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'ACTIVE',
            'INACTIVE',
            'PENDING',
            'SUSPENDED',
            'SOLD',
            'RETURNED',
            'DAMAGED',
            'OUT OF STOCK',
            'READ',
            'UNREAD'
        ];

        foreach ($statuses as $statusName) {
            SetupStatus::firstOrCreate([
                'status_name' => $statusName
            ]);
        }
    }
}
