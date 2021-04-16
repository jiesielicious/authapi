<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_name', 20)->after('name');
            $table->string('verification_pin', 6)->after('password');
            $table->string('avatar')->after('remember_token');
            $table->string('user_role', 20)->after('avatar');
            $table->timestamp('registered_at')->nullable()->after('user_role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_name');
            $table->dropColumn('verification_pin');
            $table->dropColumn('avatar');
            $table->dropColumn('user_role');
            $table->dropColumn('registered_at');
        });
    }
}
