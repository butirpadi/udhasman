<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHutangPaymentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hutang_payment', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('hutang_id')->nullable()->index('FK__hutang');
			$table->string('name', 60)->nullable();
			$table->decimal('jumlah', 20)->nullable();
			$table->date('tanggal')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hutang_payment');
	}

}
