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
        CateringSetting::create([
            'key' => 'minimum_guests',
            'value' => 50,
            'type' => 'integer',
            'description' => 'Minimum number of guests required for catering services',
        ]);
    }
}
