<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\Entities\User;
use App\Entities\Role;

class SeedUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $zachariaszAdmin = User::whereUsername('Zachariasz_admin')->first();
        $zachariaszUser = User::whereUsername('Zachariasz_user')->first();
        $mikolaj = User::whereUsername('Mikolaj')->first();
        $maciej = User::whereUsername('Maciej')->first();
        $rafal = User::whereUsername('Rafal')->first();
        $karol = User::whereUsername('Karol')->first();

        $userRole = Role::withName(Role::USER)->first();
        $adminRole = Role::withName(Role::ADMIN)->first();

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_users');
    }
}
