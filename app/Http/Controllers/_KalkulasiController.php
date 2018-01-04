<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class KalkulasiController extends Controller
{
	public function index(){
		$data_kalkulasi = \DB::table('view_kalkulasi')
					->orderBy('created_at','desc')
					->orderBy('status','asc')
					->get();

		$driver = \DB::table('view_driver')
							->whereRaw(\DB::raw('id in (select karyawan_id from view_kalkulasi where status = "DRAFT" or status = "OPEN")'))
							->get();
		$select_driver = [];
		foreach($driver as $dt){
			$select_driver[$dt->id] = $dt->nama . ' --- ' . $dt->nopol;
		}

		$select_pekerjaan = [];
		$pekerjaan = \DB::table('pekerjaan')->get();
		foreach($pekerjaan as $dt){
			$select_pekerjaan[$dt->id] = $dt->nama;
		}

		return view('kalkulasi.index',[
				'data_kalkulasi' => $data_kalkulasi,
				'select_driver' => $select_driver,
				'select_pekerjaan' => $select_pekerjaan,
			]);

	}

	public function edit($kalkulasi_id){
		$data_kalkulasi = \DB::table('view_kalkulasi')->find($kalkulasi_id);
		
		return view('kalkulasi.edit',[
				'data_kalkulasi' => $data_kalkulasi
			]);
	}

	// BUKA FORM KALKULASI YANG BERSTATUS DRAFT
	public function stDraft($kalkulasi_id){
		$data_kalkulasi = \DB::table('view_kalkulasi')->find($kalkulasi_id);
		$data_kalkulasi->pekerjaan = \DB::table('view_pekerjaan')->find($data_kalkulasi->pekerjaan_id);

		return view('kalkulasi.st-draft',[
				'data_kalkulasi' => $data_kalkulasi
			]);
	}

	// BUKA FORM KALKULASI YANG BERSTATUS OPEN
	public function stOpen($kalkulasi_id){
		$data_kalkulasi = \DB::table('view_kalkulasi')->find($kalkulasi_id);
		$data_kalkulasi->pekerjaan = \DB::table('view_pekerjaan')->find($data_kalkulasi->pekerjaan_id);

		return view('kalkulasi.st-open',[
				'data_kalkulasi' => $data_kalkulasi
			]);
	}

	// BUKA FORM KALKULASI YANG BERSTATUS VALIDATED
	public function stValidated($kalkulasi_id){
		$data_kalkulasi = \DB::table('view_kalkulasi')->find($kalkulasi_id);
		$data_kalkulasi->pekerjaan = \DB::table('view_pekerjaan')->find($data_kalkulasi->pekerjaan_id);

		return view('kalkulasi.st-validated',[
				'data_kalkulasi' => $data_kalkulasi
			]);
	}

	// BUKA FORM KALKULASI YANG BERSTATUS CANCELED
	public function stCanceled($kalkulasi_id){
		$data_kalkulasi = \DB::table('view_kalkulasi')->find($kalkulasi_id);
		$data_kalkulasi->pekerjaan = \DB::table('view_pekerjaan')->find($data_kalkulasi->pekerjaan_id);

		return view('kalkulasi.st-canceled',[
				'data_kalkulasi' => $data_kalkulasi
			]);
	}

	// UPDATE DATA KALKULASI
	public function update(Request $req){
		// echo 'update data kalkulasi';

		// generate nota timbang
		$nota_timbang = $req->nota_timbang;
		if($req->nota_timbang == ""){
			// generate auto nota timbang
			$prefix = Appsetting('nota_timbang_prefix');
			$counter = Appsetting('nota_timbang_counter');
			$nota_timbang = $prefix.date('mY').$counter;
		}

		if ($req->kalkulasi == 'RITASE') {
			\DB::table('kalkulasi')
				->where('id',$req->kalkulasi_id)
				->update([
						'kalkulasi' => $req->kalkulasi,
						'qty' => 1,
						'unit_price' => $req->harga_satuan,
						'total' => round($req->harga_satuan * 1),
						'status' => 'OPEN',
						'no_nota_timbang' => $nota_timbang
					]);
		}else if ($req->kalkulasi == 'KUBIKASI'){
			\DB::table('kalkulasi')
				->where('id',$req->kalkulasi_id)
				->update([
						'kalkulasi' => $req->kalkulasi,
						'panjang' => $req->panjang,
						'lebar' => $req->lebar,
						'tinggi' => $req->tinggi,
						'volume' => $req->panjang * $req->lebar * $req->tinggi,
						'unit_price' => $req->harga_satuan,
						'total' => round($req->harga_satuan * ($req->panjang * $req->lebar * $req->tinggi)),
						'status' => 'OPEN',
						'no_nota_timbang' => $nota_timbang
					]);
		}else if ($req->kalkulasi == 'TONASE'){
			\DB::table('kalkulasi')
				->where('id',$req->kalkulasi_id)
				->update([
						'kalkulasi' => $req->kalkulasi,
						'gross' => $req->gross,
						'tare' => $req->tare,
						'netto' => $req->gross - $req->tare,
						'unit_price' => $req->harga_satuan,
						'total' => round($req->harga_satuan * ($req->gross - $req->tare)),
						'status' => 'OPEN',
						'no_nota_timbang' => $nota_timbang
					]);
		}

		// update nota timbang counter
		if($req->nota_timbang == ""){
			// update nota timbang ketika auto generate
			UpdateAppsetting('nota_timbang_counter',$counter+1);
		}

		return redirect('kalkulasi/st-open/' . $req->kalkulasi_id);

	}

	// VALIDATE KALKULASI
	public function validateKalkulasi($kalkulasi_id){
		\DB::table('kalkulasi')
			->whereId($kalkulasi_id)
			->update(['status' => 'VALIDATED']);

		// tampilkan view validated
		return redirect('kalkulasi/st-validated/'.$kalkulasi_id);
	}

	// BATCH EDIT KALKULASI
	public function batchEdit(Request $req){
		$data_karyawan = \DB::table('view_driver')
						->find($req->select_driver);

		// $data_kalkulasi = \DB::table('view_kalkulasi')
		// 					->where('karyawan_id',$data_karyawan->id)
		// 					->get();

		$data_kalkulasi = \DB::table('view_kalkulasi')
							->where('karyawan_id',$data_karyawan->id)
							->where('status','!=','VALIDATED')
							->where('status','!=','DONE')
							->get();

		return view('kalkulasi/batch-edit',[
				'data_kalkulasi' => $data_kalkulasi,
				'data_karyawan' => $data_karyawan
			]);
	}

	public function batchEditByPekerjaan(Request $req){
		$data_pekerjaan = \DB::table('view_pekerjaan')
						->find($req->select_pekerjaan);

		$data_kalkulasi = \DB::table('view_kalkulasi')
							->where('pekerjaan_id',$data_pekerjaan->id)
							->where('status','!=','VALIDATED')
							->where('status','!=','DONE')
							->get();

		return view('kalkulasi/batch-edit-by-pekerjaan',[
				'data_kalkulasi' => $data_kalkulasi,
				'data_pekerjaan' => $data_pekerjaan
			]);
	}

	public function batchEditUpdate(Request $req){
		return \DB::transaction(function()use($req){
			$data_kalkulasi = json_decode($req->data_kalkulasi);

			foreach($data_kalkulasi->data as $dt){
				// generate nota timbang
				$nota_timbang = $dt->nota_timbang;
				if($dt->nota_timbang == ""){
					// generate auto nota timbang
					$prefix = Appsetting('nota_timbang_prefix');
					$counter = Appsetting('nota_timbang_counter');
					$nota_timbang = $prefix.date('mY').$counter;

					// update nota timbang
					UpdateAppsetting('nota_timbang_counter',$counter+1);
				}

				if($dt->kalkulasi == 'KUBIKASI'){
					\DB::table('kalkulasi')
						->where('id',$dt->kalkulasi_id)
						->update([
								'no_nota_timbang' => $nota_timbang,
								'status' => 'OPEN',
								'kalkulasi' => $dt->kalkulasi,
								'panjang' => $dt->panjang,
								'lebar' => $dt->lebar,
								'tinggi' => $dt->tinggi,
								'volume' => $dt->volume,
								'unit_price' => $dt->harga_satuan,
								'total' => $dt->total,
							]);
				}else if($dt->kalkulasi == 'TONASE'){
					\DB::table('kalkulasi')
						->where('id',$dt->kalkulasi_id)
						->update([
								'no_nota_timbang' => $nota_timbang,
								'status' => 'OPEN',
								'kalkulasi' => $dt->kalkulasi,
								'gross' => $dt->gross,
								'tare' => $dt->tare,
								'netto' => $dt->netto,
								'unit_price' => $dt->harga_satuan,
								'total' => $dt->total,
							]);
				}else if($dt->kalkulasi == 'RITASE'){
					\DB::table('kalkulasi')
						->where('id',$dt->kalkulasi_id)
						->update([
								'no_nota_timbang' => $nota_timbang,
								'status' => 'OPEN',
								'kalkulasi' => $dt->kalkulasi,
								'qty' => 1,
								'unit_price' => $dt->harga_satuan,
								'total' => $dt->total,
							]);
				}
			}

			return redirect('kalkulasi');
			
		});

	}

	public function filterByStatus($status){
		$data_kalkulasi = \DB::table('view_kalkulasi')
					->where('status',strtoupper($status))
					->orderBy('created_at','desc')
					->get();

		$driver = \DB::table('view_driver')
							->whereRaw(\DB::raw('id in (select karyawan_id from view_kalkulasi where status = "DRAFT" or status = "OPEN")'))
							->get();
		$select_driver = [];
		foreach($driver as $dt){
			$select_driver[$dt->id] = $dt->nama . ' --- ' . $dt->nopol;
		}

		return view('kalkulasi.filter-by-status',[
				'data_kalkulasi' => $data_kalkulasi,
				'select_driver' => $select_driver,
				'status' => $status
			]);
	}


	// ==================E N D - O F - C L A S S=======================
}
