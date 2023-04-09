<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'user_id' => 1,
            'data' => [
                'login' => 'success',
                'token' => 'abc123',
                'timestamp' => '2022-01-01 12:00:00'
            ]
        ]);
    }
}
