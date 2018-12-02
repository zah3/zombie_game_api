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
           'username' => 'Zachariasz',
           'password' => Hash::make('Zachariasz')
        ]);
        User::create([
           'username' => 'Karol',
           'password' => Hash::make('Karol')
        ]);
        User::create([
           'username' => 'Rafał',
           'password' => Hash::make('Rafał')
        ]);
        User::create([
           'username' => 'Maciej',
           'password' => Hash::make('Maciej')
        ]);
        User::create([
           'username' => 'Mikołaj',
           'password' => Hash::make('Mikołaj')
        ]);
    }
}
