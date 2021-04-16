<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'John Doe',
            'user_name' => 'john_doe',
            'email' => 'john.doe@test.com',
            'password' => Hash::make('@dm1n123'),
            'verification_pin' => Str::random(6),
            'avatar' => 'avatar.jpg',
            'user_role' => 'admin'
        ]);
    }
}
