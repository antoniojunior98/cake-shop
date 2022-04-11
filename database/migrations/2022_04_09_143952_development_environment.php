<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
class DevelopmentEnvironment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = new User();
        $user->name = "Admin";
        $user->email = "admin@gmail.com";
        $user->password = password_hash("admin2022", PASSWORD_DEFAULT);
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
