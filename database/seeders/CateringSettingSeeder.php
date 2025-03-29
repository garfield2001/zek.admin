<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CateringSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Add any global catering settings here
        // This is just an example - adjust according to your needs
        $settings = [
            [
                'key' => 'minimum_notice_days',
                'value' => '7',
                'description' => 'Minimum days notice required for booking'
            ],
            [
                'key' => 'deposit_percentage',
                'value' => '50',
                'description' => 'Required deposit percentage'
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('catering_settings')->insert($setting);
        }
    }
}
