<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
          [
            'level_id' => 1,
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => Hash::make('admin123'),
            'api_token' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ],
          [
            'level_id' => 2,
            'name' => 'Staff',
            'email' => 'staff@staff.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'password' => Hash::make('staff123'),
            'api_token' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
          ],
        ]);
    }
}
