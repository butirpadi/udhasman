<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePresensiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('presensi', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('karyawan_id')->nullable()->index('FK_attend_res_partner');
			$table->date('tgl')->nullable();
			$table->time('in_time')->nullable();
			$table->time('out_time')->nullable();
			$table->enum('status', array('Y','N'))->nullable()->default('Y');
			$table->enum('pagi', array('Y','N'))->nullable();
			$table->enum('siang', array('Y','N'))->nullable();
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
		Schema::drop('presensi');
	}

}
