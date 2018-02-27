<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRolePermissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('role_permission', function(Blueprint $table)
		{
			$table->foreign('permission_id', 'FK__permissions')->references('id')->on('permissions')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('role_id', 'FK__roles')->references('id')->on('roles')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('role_permission', function(Blueprint $table)
		{
			$table->dropForeign('FK__permissions');
			$table->dropForeign('FK__roles');
		});
	}

}
