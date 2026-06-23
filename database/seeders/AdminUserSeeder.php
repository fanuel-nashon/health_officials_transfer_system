<?php

namespace Database\Seeders;

use App\Models\OfficialRecords;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@health.go.tz'],
            [
                'name'     => 'System Administrator',
                'password' => Hash::make('Admin@1234'),
            ]
        );

        $admin->assignRole('admin');

        OfficialRecords::firstOrCreate(
            ['user_id' => $admin->id],
            ['title' => 'System Administrator', 'facility_id' => null]
        );
    }
}
