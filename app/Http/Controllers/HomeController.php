<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
	public function index(){
		// $open_so_count = \DB::table('sales_order')
		// 					->where('status','O')
		// 					->count();

		// $open_do_count = \DB::table('delivery_order')
		// 					->where('status','D')
		// 					->count();

		// $open_ci_count = \DB::table('customer_invoices')
		// 					->where('status','O')
		// 					->count();
		$open_so_count = 0;

		$open_do_count = \DB::table('new_pengiriman')->where('state','draft')->count();

		$open_ci_count = 0;

		return view('home',[
				'open_so_count' => $open_so_count,
				'open_do_count' => $open_do_count,
				'open_ci_count' => $open_ci_count,
			]);
	}
}
