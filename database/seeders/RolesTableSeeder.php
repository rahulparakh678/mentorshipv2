<?php

namespace Database\Seeders;

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'    => 1,
                'title' => 'Admin',
            ],
            [
                'id'    => 2,
                'title' => 'User',
            ],
            [
                'id'    => 3,
                'title' => 'Mentor',
            ],
            [
                'id'    => 4,
                'title' => 'Mentee',
            ],
        ];

        Role::insert($roles);
    }
}
