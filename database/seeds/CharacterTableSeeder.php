<?php

use Illuminate\Database\Seeder;
use App\Character;

class CharacterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zachariaszAdmin = \App\User::where('username','=','Zachariasz_admin')->first();
        //$zachariaszUser = \App\User::where('username','=','Zachariasz_user')->first();

        $characterNormalType = new Character();
        $characterNormalType->name = 'Nowy cham';
        $characterNormalType->save();

        $characterOtherType = \App\Character::create([
            'name' => 'Nowa chamka',
            'fraction_id' => 2,
            'experience' => 123123
        ]);

        $zachariaszAdmin->characters()->saveMany([
            $characterNormalType,
            $characterOtherType
        ]);

    }
}
