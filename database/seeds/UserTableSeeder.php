<?php

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
        User::create([
           'username' => 'Zachariasz',
           'password' => bcrypt('Zachariasz')
        ]);
        User::create([
           'username' => 'Karol',
           'password' => bcrypt('Karol')
        ]);
        User::create([
           'username' => 'Rafał',
           'password' => bcrypt('Rafał')
        ]);
        User::create([
           'username' => 'Maciej',
           'password' => bcrypt('Maciej')
        ]);
        User::create([
           'username' => 'Mikołaj',
           'password' => bcrypt('Mikołaj')
        ]);
    }
}
