<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContributionSetting;

class ContributionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        ContributionSetting::firstOrCreate([
            'id' => 1
        ], [
            'monthly_amount' => 500,
            'penalty_per_day' => 100,
            'due_day' => 15,
            'grace_day' => 16
        ]);
    }
}
