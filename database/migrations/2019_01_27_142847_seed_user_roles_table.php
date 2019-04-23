<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Role;

class SeedUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $zachariaszAdmin = User::withUsername('Zachariasz_admin')->first();
        $zachariaszUser = User::withUsername('Zachariasz_user')->first();
        $mikolaj = User::withUsername('Mikolaj')->first();
        $maciej = User::withUsername('Maciej')->first();
        $rafal = User::withUsername('Rafal')->first();
        $karol = User::withUsername('Karol')->first();

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
