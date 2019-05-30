<?php

use Illuminate\Database\Seeder;
use App\Warehouse;
class WareHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::truncate();

        Warehouse::create([
            'name' => 'Master'
        ]);
    }
}
