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
        $userRole = Role::create([
            'name' => 'user',
            'description' => 'Game user',
            ]);

        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Game admin',
            ]);
        $zachariasz = \App\User::where('username','=','Zachariasz')->first();
        $mikolaj = \App\User::where('username','=','Mikołaj')->first();
        $maciej = \App\User::where('username','=','Maciej')->first();
        $rafal = \App\User::where('username','=','Rafał')->first();
        $karol = \App\User::where('username','=','Karol')->first();

        $userRole->users()->sync([
            $zachariasz->id,
            $mikolaj->id,
            $maciej->id,
            $rafal->id,
            $karol->id,
        ]);
        $adminRole->users()->sync([
            $zachariasz->id,
            $mikolaj->id,
            $maciej->id,
            $rafal->id,
            $karol->id,
        ]);
    }
}
