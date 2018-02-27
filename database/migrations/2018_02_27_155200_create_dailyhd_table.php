<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDailyhdTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dailyhd', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('ref', 50)->nullable();
			$table->date('tanggal')->nullable();
			$table->integer('alat_id')->nullable()->index('FK__alat');
			$table->integer('lokasi_galian_id')->nullable();
			$table->time('mulai')->nullable();
			$table->time('selesai')->nullable();
			$table->time('istirahat_mulai')->nullable();
			$table->time('istirahat_selesai')->nullable();
			$table->decimal('jam_kerja', 10)->nullable();
			$table->decimal('solar', 10)->nullable();
			$table->decimal('oli', 10)->nullable();
			$table->string('desc')->nullable();
			$table->integer('pengawas_id')->nullable();
			$table->integer('operator_id')->nullable();
			$table->enum('status', array('O','V'))->nullable()->default('O');
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
		Schema::drop('dailyhd');
	}

}
