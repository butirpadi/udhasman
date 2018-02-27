<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->date('updated_at');
			$table->string('name');
			$table->string('username')->unique('username');
			$table->string('email');
			$table->string('password', 60);
			$table->string('remember_token', 100)->nullable();
			$table->integer('verified')->default(1);
			$table->enum('hide', array('Y','N'))->nullable()->default('N');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
