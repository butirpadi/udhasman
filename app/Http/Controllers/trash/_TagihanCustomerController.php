<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TagihanCustomerController extends Controller
{
	public function index(){
		$data = \DB::table('view_tagihan_customer')
				->orderBy('created_at','desc')
				->get();

		$total_tagihan = \DB::table('view_tagihan_customer')
							->where('status','!=','PAID')
							->sum('total');

		$select_customer = [];
		$customer = \DB::table('customer')
					->get();
		foreach($customer as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		return view('tagihan.customer.index',[
				'data' => $data,
				'total_tagihan' => $total_tagihan,
				'select_customer' => $select_customer
			]);

	}

	public function getPekerjaan($customer_id){
		$pekerjaan = \DB::table('pekerjaan')
						->where('customer_id',$customer_id)
						->select('id','nama')
						->get();
		return json_encode($pekerjaan);
	}

	// BATCH EDIT
	public function batchEdit(Request $req){
		$data_tagihan = \DB::table('view_tagihan_customer')
						->where('customer_id',$req->customer)
						->where('pekerjaan_id',$req->pekerjaan)
						->where('status','!=','PAID')
						->get();

		$customer = \DB::table('customer')->find($req->customer);
		$pekerjaan = \DB::table('pekerjaan')->find($req->pekerjaan);

		return view('tagihan.customer.batch-edit',[
				'data_tagihan' => $data_tagihan,
				'pekerjaan' => $pekerjaan,
				'customer' => $customer,
			]);
	}

	// FILTER DATA TAGIHAN CUSTOMER BY STATUS
	public function filterByStatus($status){
		$data_tagihan = \DB::table('view_tagihan_customer')
					->where('status',strtoupper($status))
					->orderBy('created_at','desc')
					->get();

		$total_tagihan = \DB::table('view_tagihan_customer')
							->where('status',strtoupper($status))
							->sum('total');

		$select_customer = [];
		$customer = \DB::table('customer')
					->get();
		foreach($customer as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		return view('tagihan.customer.filter-by-status',[
				'data_tagihan' => $data_tagihan,
				'total_tagihan' => $total_tagihan,
				'status' => $status,
				'select_customer' => $select_customer
			]);
	}

	// TAMPILKAN HALAMAN CETAK TAGIHAN
	public function cetakTagihan(){
		$select_customer = [];
		$customer = \DB::table('customer')
					->get();

		foreach($customer as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		$materials = \DB::table('material')->get();
		$selectMaterial = [];
		foreach($materials as $dt){
			$selectMaterial[$dt->id] = $dt->nama;
		}


		return view('tagihan.customer.cetak-tagihan-option',[
				'selectCustomer' => $select_customer,
				'selectMaterial' => $selectMaterial
			]);
	}

	// public function pembayaran(){
	// 	$select_customer = [];
	// 	$customer = \DB::table('customer')
	// 				->get();
	// 	foreach($customer as $dt){
	// 		$select_customer[$dt->id] = $dt->nama;
	// 	}


	// 	return view('tagihan.customer.cetak-tagihan-option',[
	// 			'select_customer' => $select_customer
	// 		]);
	// }

	// GET DATA TAGIHAN
	public function dataTagihan(Request $req){
		// generate tanggal
		$awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $awal = new \DateTime();
        $awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $awal_str = $arr_tgl[2] . '-' . $arr_tgl[1] . '-' . $arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $akhir = new \DateTime();
        $akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $akhir_str = $arr_tgl[2] . '-' . $arr_tgl[1] . '-' . $arr_tgl[0];

		$materials = \DB::table('view_cetak_tagihan_customer')
						->select('material','material_id')
						->whereBetween('tanggal_pengiriman',[$awal_str,$akhir_str])
						->wherePekerjaanId($req->pekerjaan)
						->distinct()
						->get();

		$data_tagihan_str = '{';
		$data_tagihan_str_end = '}';

		foreach($materials as $dt){
			$data_tagihan_str = $data_tagihan_str . '"'.$dt->material .'"' .':'.'""' .',';
		}

		$data_tagihan_str = rtrim($data_tagihan_str,', ');		
		$data_tagihan_str = $data_tagihan_str . $data_tagihan_str_end;


		$data_tagihan = json_decode($data_tagihan_str);

		echo json_encode($materials);

	}

	// GET DATA TAGIHAN BY MATERIAL
	public function dataTagihanByMaterial(Request $req){
		// generate tanggal
		$awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $awal = new \DateTime();
        $awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $awal_str = $arr_tgl[2] . '-' . $arr_tgl[1] . '-' . $arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $akhir = new \DateTime();
        $akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $akhir_str = $arr_tgl[2] . '-' . $arr_tgl[1] . '-' . $arr_tgl[0];

        $data_tagihan = json_decode('{"ritase":[],"kubikasi":[],"tonase":[]}');

		$kubikasi = \DB::table('view_cetak_tagihan_customer')
							->whereCustomerId($req->customer)
							->whereMaterialId($req->material)
							->whereBetween('tanggal_pengiriman',[$awal_str,$akhir_str])
							->wherePekerjaanId($req->pekerjaan)
							->whereKalkulasi('KUBIKASI')
							->orderBy('tanggal_pengiriman','asc')
							->get();

		$ritase = \DB::table('view_cetak_tagihan_customer')
							->whereCustomerId($req->customer)
							->whereMaterialId($req->material)
							->whereBetween('tanggal_pengiriman',[$awal_str,$akhir_str])
							->wherePekerjaanId($req->pekerjaan)
							->whereKalkulasi('RITASE')
							->orderBy('tanggal_pengiriman','asc')
							->get();

		$tonase = \DB::table('view_cetak_tagihan_customer')
							->whereCustomerId($req->customer)
							->whereMaterialId($req->material)
							->whereBetween('tanggal_pengiriman',[$awal_str,$akhir_str])
							->wherePekerjaanId($req->pekerjaan)
							->whereKalkulasi('TONASE')
							->orderBy('tanggal_pengiriman','asc')
							->get();

		$data_tagihan->kubikasi = $kubikasi;
		$data_tagihan->tonase = $tonase;
		$data_tagihan->ritase = $ritase;


		// return view('tagihan.customer.')

		echo json_encode($data_tagihan);
	}


}
