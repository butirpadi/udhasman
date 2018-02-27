<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLokasiGalianTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lokasi_galian', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamps();
			$table->string('kode', 50)->nullable();
			$table->string('nama')->nullable();
			$table->char('desa_id', 10)->nullable();
			$table->string('alamat')->nullable();
			$table->integer('user_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lokasi_galian');
	}

}
