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
        $user = new User();
        $user->username = 'Zachariasz';
        $user->password = bcrypt('Zachariasz');
        $user->save();

        $user = new User();
        $user->username = 'Karol';
        $user->password = bcrypt('Karol');
        $user->save();

        $user = new User();
        $user->username = 'Rafal';
        $user->password = bcrypt('Rafał');
        $user->save();

        $user = new User();
        $user->username = 'Maciej';
        $user->password = bcrypt('Maciej');
        $user->save();

        $user = new User();
        $user->username = 'Mikołaj';
        $user->password = bcrypt('Mikołaj');
        $user->save();

    }
}
