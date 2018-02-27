<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDailyhdTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dailyhd', function(Blueprint $table)
		{
			$table->foreign('alat_id', 'FK__alat')->references('id')->on('alat')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dailyhd', function(Blueprint $table)
		{
			$table->dropForeign('FK__alat');
		});
	}

}
