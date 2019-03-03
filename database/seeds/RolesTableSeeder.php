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
        $zachariaszAdmin = \App\User::where('username','=','Zachariasz_admin')->first();
        $zachariaszUser = \App\User::where('username','=','Zachariasz_user')->first();
        $mikolaj = \App\User::where('username','=','Mikołaj')->first();
        $maciej = \App\User::where('username','=','Maciej')->first();
        $rafal = \App\User::where('username','=','Rafał')->first();
        $karol = \App\User::where('username','=','Karol')->first();

        $userRole->users()->sync([
            $zachariaszUser->id,
            $zachariaszAdmin->id,
            $mikolaj->id,
            $maciej->id,
            $rafal->id,
            $karol->id,
        ]);
        $adminRole->users()->sync([
            $zachariaszAdmin->id,
            $mikolaj->id,
            $maciej->id,
            $rafal->id,
            $karol->id,
        ]);
    }
}
