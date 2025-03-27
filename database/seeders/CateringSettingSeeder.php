<?php

namespace Database\Seeders;

use App\Models\CateringSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CateringSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $settings = [
            [
                'key' => 'min_guests',
                'value' => '50',
                'type' => 'integer',
                'description' => 'Minimum number of guests required for any package',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'max_meal_selections',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Maximum number of meals that can be selected in any package',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'booking_notice_hours',
                'value' => '48',
                'type' => 'integer',
                'description' => 'Minimum hours of advance notice required for booking',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'operating_hours',
                'value' => json_encode([
                    'start' => '08:00',
                    'end' => '20:00'
                ]),
                'type' => 'json',
                'description' => 'Operating hours for catering services',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'delivery_radius_km',
                'value' => '25',
                'type' => 'integer',
                'description' => 'Maximum delivery radius in kilometers',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($settings as $setting) {
            CateringSetting::create($setting);
        }
    }
}
