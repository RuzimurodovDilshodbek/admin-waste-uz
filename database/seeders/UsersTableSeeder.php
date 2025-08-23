<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'ruzimurodovdilshod9@gmail.com',
                'phone'          => '+998913395682',
                'password'       => bcrypt('dilshod080401'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
