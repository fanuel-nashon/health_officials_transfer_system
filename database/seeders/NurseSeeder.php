<?php

namespace Database\Seeders;

use App\Models\EmployeeRecords;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NurseSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure at least some facilities exist to assign nurses to
        if (Facility::count() === 0) {
            $facilityNames = [
                'Muhimbili National Hospital',
                'Kilimanjaro Christian Medical Centre',
                'Bugando Medical Centre',
                'Mwananyamala Regional Hospital',
                'Temeke Regional Hospital',
            ];
            foreach ($facilityNames as $name) {
                Facility::create(['name' => $name]);
            }
        }

        $facilityIds = Facility::pluck('id')->toArray();

        $titles = [
            'Registered Nurse',
            'Senior Nurse',
            'Nurse Officer',
            'Medical Doctor',
            'General Physician',
            'Clinical Officer',
            'Specialist Doctor',
            'Consultant Physician',
        ];

        $nurses = [
            ['name' => 'Amina Rashidi',        'email' => 'amina.rashidi@health.go.tz'],
            ['name' => 'Rehema Juma',           'email' => 'rehema.juma@health.go.tz'],
            ['name' => 'Neema Kimaro',          'email' => 'neema.kimaro@health.go.tz'],
            ['name' => 'Fatuma Abdalla',        'email' => 'fatuma.abdalla@health.go.tz'],
            ['name' => 'Zainab Mwangosi',       'email' => 'zainab.mwangosi@health.go.tz'],
            ['name' => 'Mwanaidi Salum',        'email' => 'mwanaidi.salum@health.go.tz'],
            ['name' => 'Zawadi Mushi',          'email' => 'zawadi.mushi@health.go.tz'],
            ['name' => 'Upendo Lyimo',          'email' => 'upendo.lyimo@health.go.tz'],
            ['name' => 'Furaha Mrema',          'email' => 'furaha.mrema@health.go.tz'],
            ['name' => 'Tumaini Ngowi',         'email' => 'tumaini.ngowi@health.go.tz'],
            ['name' => 'Hamisi Msangi',         'email' => 'hamisi.msangi@health.go.tz'],
            ['name' => 'Rajabu Mwita',          'email' => 'rajabu.mwita@health.go.tz'],
            ['name' => 'Daudi Mlay',            'email' => 'daudi.mlay@health.go.tz'],
            ['name' => 'Omari Temu',            'email' => 'omari.temu@health.go.tz'],
            ['name' => 'Juma Maganga',          'email' => 'juma.maganga@health.go.tz'],
            ['name' => 'Saidi Mwenda',          'email' => 'saidi.mwenda@health.go.tz'],
            ['name' => 'Yusufu Mwambene',       'email' => 'yusufu.mwambene@health.go.tz'],
            ['name' => 'Rashidi Mwakasege',     'email' => 'rashidi.mwakasege@health.go.tz'],
            ['name' => 'Halima Mwanaisha',      'email' => 'halima.mwanaisha@health.go.tz'],
            ['name' => 'Salama Mkwawa',         'email' => 'salama.mkwawa@health.go.tz'],
        ];

        foreach ($nurses as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('Nurse@1234'),
                    'email_verified_at' => now(),
                ]
            );

            $user->assignRole('nurse_doctor');

            EmployeeRecords::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'title'       => $titles[array_rand($titles)],
                    'facility_id' => $facilityIds[array_rand($facilityIds)],
                ]
            );
        }

        $this->command->info('Seeded 20 nurse/doctor users. Default password: Nurse@1234');
    }
}
