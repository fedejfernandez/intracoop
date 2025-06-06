<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $laratrustConfig = Config::get('laratrust');
        $permissionUserTable = $laratrustConfig['tables']['permission_user'];
        $permissionsTable = $laratrustConfig['tables']['permissions'];
        $permissionForeignKey = $laratrustConfig['foreign_keys']['permission'];
        
        // Get the users table name from Laravel's default User model
        $userModel = new (Config::get('auth.providers.users.model'));
        $usersTable = $userModel->getTable();
        $userForeignKey = $laratrustConfig['foreign_keys']['user'];

        Schema::create($permissionUserTable, function (Blueprint $table) use ($permissionsTable, $permissionForeignKey, $usersTable, $userForeignKey) {
            $table->unsignedBigInteger($permissionForeignKey);
            $table->unsignedBigInteger($userForeignKey);
            $table->string('user_type');

            $table->foreign($permissionForeignKey)->references('id')->on($permissionsTable)
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign($userForeignKey)->references('id')->on($usersTable)
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary([$userForeignKey, $permissionForeignKey, 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Config::get('laratrust.tables.permission_user'));
    }
}; 