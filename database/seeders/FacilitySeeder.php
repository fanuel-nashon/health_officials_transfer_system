<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Location;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Dar es Salaam
            [
                'location' => ['name' => 'Ilala', 'district' => 'Ilala', 'region' => 'Dar es Salaam'],
                'facilities' => [
                    'Muhimbili National Hospital',
                    'Ilala District Hospital',
                    'Mwananyamala Regional Referral Hospital',
                ],
            ],
            [
                'location' => ['name' => 'Kinondoni', 'district' => 'Kinondoni', 'region' => 'Dar es Salaam'],
                'facilities' => [
                    'Amana Regional Referral Hospital',
                    'Sinza Hospital',
                    'Mwananyamala District Hospital',
                ],
            ],
            [
                'location' => ['name' => 'Temeke', 'district' => 'Temeke', 'region' => 'Dar es Salaam'],
                'facilities' => [
                    'Temeke Regional Referral Hospital',
                    'Mbagala Range Health Centre',
                ],
            ],

            // Arusha
            [
                'location' => ['name' => 'Arusha', 'district' => 'Arusha Urban', 'region' => 'Arusha'],
                'facilities' => [
                    'Mount Meru Regional Referral Hospital',
                    'Arusha Lutheran Medical Centre',
                    'Selian Lutheran Hospital',
                ],
            ],

            // Kilimanjaro
            [
                'location' => ['name' => 'Moshi', 'district' => 'Moshi Urban', 'region' => 'Kilimanjaro'],
                'facilities' => [
                    'Kilimanjaro Christian Medical Centre',
                    'Mawenzi Regional Referral Hospital',
                ],
            ],
            [
                'location' => ['name' => 'Rombo', 'district' => 'Rombo', 'region' => 'Kilimanjaro'],
                'facilities' => [
                    'Mkuu District Hospital',
                    'Rombo Health Centre',
                ],
            ],

            // Mwanza
            [
                'location' => ['name' => 'Mwanza', 'district' => 'Nyamagana', 'region' => 'Mwanza'],
                'facilities' => [
                    'Bugando Medical Centre',
                    'Sekou Toure Regional Referral Hospital',
                    'Nyamagana District Hospital',
                ],
            ],
            [
                'location' => ['name' => 'Ilemela', 'district' => 'Ilemela', 'region' => 'Mwanza'],
                'facilities' => [
                    'Ilemela District Hospital',
                ],
            ],

            // Dodoma
            [
                'location' => ['name' => 'Dodoma', 'district' => 'Dodoma Urban', 'region' => 'Dodoma'],
                'facilities' => [
                    'Benjamin Mkapa Hospital',
                    'Dodoma Regional Referral Hospital',
                    'Chamwino District Hospital',
                ],
            ],

            // Morogoro
            [
                'location' => ['name' => 'Morogoro', 'district' => 'Morogoro Urban', 'region' => 'Morogoro'],
                'facilities' => [
                    'Morogoro Regional Referral Hospital',
                    'Morogoro District Hospital',
                ],
            ],
            [
                'location' => ['name' => 'Kilosa', 'district' => 'Kilosa', 'region' => 'Morogoro'],
                'facilities' => [
                    'Kilosa District Hospital',
                ],
            ],

            // Tanga
            [
                'location' => ['name' => 'Tanga', 'district' => 'Tanga Urban', 'region' => 'Tanga'],
                'facilities' => [
                    'Bombo Regional Referral Hospital',
                    'Tanga District Hospital',
                ],
            ],
            [
                'location' => ['name' => 'Korogwe', 'district' => 'Korogwe Urban', 'region' => 'Tanga'],
                'facilities' => [
                    'Korogwe District Hospital',
                ],
            ],

            // Mbeya
            [
                'location' => ['name' => 'Mbeya', 'district' => 'Mbeya Urban', 'region' => 'Mbeya'],
                'facilities' => [
                    'Mbeya Zonal Referral Hospital',
                    'Mbeya District Hospital',
                ],
            ],
            [
                'location' => ['name' => 'Rungwe', 'district' => 'Rungwe', 'region' => 'Mbeya'],
                'facilities' => [
                    'Rungwe District Hospital',
                ],
            ],

            // Iringa
            [
                'location' => ['name' => 'Iringa', 'district' => 'Iringa Urban', 'region' => 'Iringa'],
                'facilities' => [
                    'Iringa Regional Referral Hospital',
                    'Iringa District Hospital',
                ],
            ],

            // Mara
            [
                'location' => ['name' => 'Musoma', 'district' => 'Musoma Urban', 'region' => 'Mara'],
                'facilities' => [
                    'Musoma Regional Referral Hospital',
                    'Shirati Hospital',
                ],
            ],

            // Kagera
            [
                'location' => ['name' => 'Bukoba', 'district' => 'Bukoba Urban', 'region' => 'Kagera'],
                'facilities' => [
                    'Kagera Regional Referral Hospital',
                    'Bukoba District Hospital',
                ],
            ],

            // Lindi
            [
                'location' => ['name' => 'Lindi', 'district' => 'Lindi Urban', 'region' => 'Lindi'],
                'facilities' => [
                    'Lindi Regional Referral Hospital',
                ],
            ],

            // Mtwara
            [
                'location' => ['name' => 'Mtwara', 'district' => 'Mtwara Urban', 'region' => 'Mtwara'],
                'facilities' => [
                    'Ligula Regional Referral Hospital',
                    'Mtwara District Hospital',
                ],
            ],

            // Zanzibar
            [
                'location' => ['name' => 'Zanzibar Town', 'district' => 'Mjini', 'region' => 'Zanzibar Urban/West'],
                'facilities' => [
                    'Mnazi Mmoja Hospital',
                    'Kidongo Chekundu Hospital',
                ],
            ],
            [
                'location' => ['name' => 'Chake Chake', 'district' => 'Chake Chake', 'region' => 'Pemba South'],
                'facilities' => [
                    'Chake Chake Hospital',
                ],
            ],
        ];

        foreach ($data as $entry) {
            $location = Location::firstOrCreate(
                ['district' => $entry['location']['district'], 'region' => $entry['location']['region']],
                ['name' => $entry['location']['name']]
            );

            foreach ($entry['facilities'] as $facilityName) {
                Facility::firstOrCreate(
                    ['name' => $facilityName],
                    ['location_id' => $location->id]
                );
            }
        }

        $this->command->info(
            'Seeded ' . Facility::count() . ' facilities across ' . Location::count() . ' locations.'
        );
    }
}
