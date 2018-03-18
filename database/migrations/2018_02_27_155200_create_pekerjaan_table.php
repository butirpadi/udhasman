<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePekerjaanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pekerjaan', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('partner_id')->nullable()->index('FK_pekerjaan_res_partner');
			$table->string('nama')->nullable();
			$table->string('alamat')->nullable();
			$table->char('desa_id', 10)->nullable();
			$table->date('tahun')->nullable();
			$table->string('spk', 50)->nullable();
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
		Schema::drop('pekerjaan');
	}

}