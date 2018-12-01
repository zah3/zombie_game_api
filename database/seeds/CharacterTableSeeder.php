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
        $zachariasz = \App\User::where('username','=','Zachariasz')->first();

        $characterNormalType = new Character();
        $characterNormalType->name = 'Nowy cham';
        $characterNormalType->save();

        $characterOtherType = \App\Character::create([
            'name' => 'Nowa chamka',
            'fraction_id' => 2,
            'experience' => 123123
        ]);

        $zachariasz->characters()->saveMany([
            $characterNormalType,
            $characterOtherType
        ]);

    }
}
