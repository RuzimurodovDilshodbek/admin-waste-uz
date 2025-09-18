<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
//            SampleData::class,
//        StatisticsTableSeeder::class
<<<<<<< HEAD
=======
        SectionsTableSeeder::class
>>>>>>> 899a13156fc68b212d74217b0da706aef3dee2de
        ]);
    }
}
