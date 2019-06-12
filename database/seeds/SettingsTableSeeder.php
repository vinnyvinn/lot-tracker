<?php

use Illuminate\Database\Seeder;
use App\Setting;
class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::truncate();
        Setting::create(['enable_inspection' => 0]);
    }
}
