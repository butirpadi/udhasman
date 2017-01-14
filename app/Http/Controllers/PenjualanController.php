<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PenjualanController extends Controller
{
	public function index(){
		$data = \DB::table('penjualan')
					->orderBy('tanggal','desc')
					->get();
		return view('penjualan.index',[
				'data' => $data
			]);
	}

	public function create(){
		$select_customer = [];
		$customers = \DB::table('customer')
						->select('id','nama')
						->get();
		foreach($customers as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		$select_material = [];
		$materials = \DB::table('material')
						->select('id','nama')
						->get();
		foreach($materials as $dt){
			$select_material[$dt->id] = $dt->nama;
		}

		return view('penjualan.create',[
			'selectCustomer' => $select_customer,
			'selectMaterial' => $select_material,
		]);
	}
}
