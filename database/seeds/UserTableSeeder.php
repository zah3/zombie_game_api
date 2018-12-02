<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'Zachariasz_admin',
            'password' => Hash::make('Zachariasz'),
            'is_active' => true
        ]);
        User::create([
            'username' => 'Zachariasz_user',
            'password' => Hash::make('Zachariasz'),
            'is_active' => true
        ]);
        User::create([
            'username' => 'Karol',
            'password' => Hash::make('Karol'),
            'is_active' => true
        ]);
        User::create([
            'username' => 'Rafał',
            'password' => Hash::make('Rafał'),
            'is_active' => true
        ]);
        User::create([
            'username' => 'Maciej',
            'password' => Hash::make('Maciej'),
            'is_active' => true
        ]);
        User::create([
            'username' => 'Mikołaj',
            'password' => Hash::make('Mikołaj'),
            'is_active' => true
        ]);
    }
}
