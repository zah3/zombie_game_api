<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'user',
            'description' => 'Game user',
            ]);

        Role::create([
            'name' => 'admin',
            'description' => 'Game admin',
            ]);
    }
}
