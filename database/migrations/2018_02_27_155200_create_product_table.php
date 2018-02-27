<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('kode', 50)->nullable();
			$table->string('nama')->nullable();
			$table->integer('product_unit_id')->nullable();
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
		Schema::drop('product');
	}

}
