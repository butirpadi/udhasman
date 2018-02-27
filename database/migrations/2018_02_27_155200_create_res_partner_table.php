<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResPartnerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('res_partner', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('user_id')->nullable()->comment('User yang meng-create data ini');
			$table->enum('customer', array('Y','N'))->nullable()->default('N');
			$table->enum('supplier', array('Y','N'))->nullable()->default('N');
			$table->enum('driver', array('Y','N'))->nullable()->default('N');
			$table->enum('staff', array('Y','N'))->nullable()->default('N');
			$table->string('kode', 50)->nullable();
			$table->string('nama')->nullable();
			$table->string('panggilan')->nullable();
			$table->string('ktp', 50)->nullable();
			$table->string('alamat')->nullable();
			$table->char('desa_id', 10)->nullable();
			$table->string('telp', 20)->nullable();
			$table->string('foto')->nullable();
			$table->string('tempat_lahir', 50)->nullable();
			$table->date('tgl_lahir')->nullable()->default('1970-01-01');
			$table->decimal('gaji_pokok', 20)->nullable();
			$table->string('npwp')->nullable();
			$table->string('owner')->nullable();
			$table->integer('armada_id')->nullable();
			$table->enum('active', array('Y','N'))->nullable()->default('Y');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('res_partner');
	}

}
