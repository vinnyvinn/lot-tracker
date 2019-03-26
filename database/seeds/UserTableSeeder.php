<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        User::truncate();
        User::insert([
            [
                'name' => 'admin',
                'email' => 'lottracker@wizag.biz',
                'password' => bcrypt('qwerty123'),
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'vinny',
                'email' => 'vinn@wizag.biz',
                'password' => bcrypt('12345678'),
                'created_at' => $now,
                'updated_at' => $now
            ]
            ,[
                'name' => 'Sudo',
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

    }
}
