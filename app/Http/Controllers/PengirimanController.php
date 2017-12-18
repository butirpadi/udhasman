<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PengirimanController extends Controller
{
	public function index(){
		$data_pengiriman = \DB::table('view_pengiriman')
					->orderBy('created_at','desc')
					->orderBy('status','asc')
					->get();

		// $nomor_penjualan = \DB::table('penjualan')
		// 					->select('id','order_number')
		// 					->orderBy('tanggal','desc')
		// 					->get();

		$nomor_penjualan = \DB::select("select * FROM penjualan WHERE ID IN (SELECT view_pengiriman.penjualan_id from view_pengiriman where (view_pengiriman.status = 'DRAFT' OR view_pengiriman.status = 'OPEN')) order by tanggal desc");
		$select_nomor_penjualan = [];

		foreach($nomor_penjualan as $dt){
			$select_nomor_penjualan[$dt->id] = $dt->order_number;
		}

		return view('pengiriman.index',[
				'data_pengiriman' => $data_pengiriman,
				'select_nomor_penjualan' => $select_nomor_penjualan,
			]);
	}

	public function edit($pengiriman_id){
		$data_pengiriman = \DB::table('view_pengiriman')->find($pengiriman_id);
		$data_pengiriman->pekerjaan = \DB::table('view_pekerjaan')->find($data_pengiriman->pekerjaan_id);
		$data_pengiriman->penjualan = \DB::table('view_penjualan')->find($data_pengiriman->penjualan_id);

		$lokasi_galian = \DB::table('lokasi_galian')->get();
		$select_lokasi_galian = [];
		foreach($lokasi_galian as $dt){
			$select_lokasi_galian[$dt->id] = $dt->nama;
		}

		$driver = \DB::table('view_driver')->get();
		$select_driver = [];
		foreach($driver as $dt){
			$select_driver[$dt->id] = $dt->nama . ' - ' . $dt->nopol;
		}



		return view('pengiriman.draft',[
				'data_pengiriman' => $data_pengiriman,
				'select_driver' => $select_driver,
				'select_lokasi_galian' => $select_lokasi_galian,
			]);
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			// generate tanggal
            $tanggal_kirim = $req->tanggal;
            $arr_tgl = explode('-',$tanggal_kirim);
            $tanggal_kirim = new \DateTime();
            $tanggal_kirim->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			\DB::table('pengiriman')
					->where('id',$req->pengiriman_id)
					->update([
							'tanggal' => $tanggal_kirim,
							'karyawan_id' => $req->driver,
							'lokasi_galian_id' => $req->lokasi_galian,
							'status' => 'OPEN'
						]);


			 return redirect('pengiriman/open/'.$req->pengiriman_id);
		});
	}

	public function open($pengiriman_id){
		// TAMPILKAN FORM PENGIRIMAN STATUS OPEN
			$data_pengiriman = \DB::table('view_pengiriman')->find($pengiriman_id);
			$data_pengiriman->pekerjaan = \DB::table('view_pekerjaan')->find($data_pengiriman->pekerjaan_id);
			$data_pengiriman->penjualan = \DB::table('view_penjualan')->find($data_pengiriman->penjualan_id);

			$lokasi_galian = \DB::table('lokasi_galian')->get();
			$select_lokasi_galian = [];
			foreach($lokasi_galian as $dt){
				$select_lokasi_galian[$dt->id] = $dt->nama;
			}

			$driver = \DB::table('view_driver')->get();
			$select_driver = [];
			foreach($driver as $dt){
				$select_driver[$dt->id] = $dt->nama . ' - ' . $dt->nopol;
			}

			return view('pengiriman.open',[
					'data_pengiriman' => $data_pengiriman,
					'select_driver' => $select_driver,
					'select_lokasi_galian' => $select_lokasi_galian,
				]);
	}

	// VALIDASI PENGIRIMAN
	public function validateData($pengiriman_id){
		\DB::table('pengiriman')
				->whereId($pengiriman_id)
				->update([
						'status' => 'VALIDATED'
					]);

		// tampilkan view validated
		return redirect('pengiriman/view-validated/' . $pengiriman_id);
	}

	public function viewValidated($pengiriman_id){
		// TAMPILKAN FORM PENGIRIMAN STATUS OPEN
			$data_pengiriman = \DB::table('view_pengiriman')->find($pengiriman_id);
			$data_pengiriman->pekerjaan = \DB::table('view_pekerjaan')->find($data_pengiriman->pekerjaan_id);
			$data_pengiriman->penjualan = \DB::table('view_penjualan')->find($data_pengiriman->penjualan_id);

			$lokasi_galian = \DB::table('lokasi_galian')->get();
			$select_lokasi_galian = [];
			foreach($lokasi_galian as $dt){
				$select_lokasi_galian[$dt->id] = $dt->nama;
			}

			$driver = \DB::table('view_driver')->get();
			$select_driver = [];
			foreach($driver as $dt){
				$select_driver[$dt->id] = $dt->nama . ' - ' . $dt->nopol;
			}

			return view('pengiriman.validated',[
					'data_pengiriman' => $data_pengiriman,
					'select_driver' => $select_driver,
					'select_lokasi_galian' => $select_lokasi_galian,
				]);
	}

	public function viewCanceled($pengiriman_id){
		// TAMPILKAN FORM PENGIRIMAN STATUS OPEN
			$data_pengiriman = \DB::table('view_pengiriman')->find($pengiriman_id);
			$data_pengiriman->pekerjaan = \DB::table('view_pekerjaan')->find($data_pengiriman->pekerjaan_id);
			$data_pengiriman->penjualan = \DB::table('view_penjualan')->find($data_pengiriman->penjualan_id);

			$lokasi_galian = \DB::table('lokasi_galian')->get();
			$select_lokasi_galian = [];
			foreach($lokasi_galian as $dt){
				$select_lokasi_galian[$dt->id] = $dt->nama;
			}

			$driver = \DB::table('view_driver')->get();
			$select_driver = [];
			foreach($driver as $dt){
				$select_driver[$dt->id] = $dt->nama . ' - ' . $dt->nopol;
			}

			return view('pengiriman.canceled',[
					'data_pengiriman' => $data_pengiriman,
					'select_driver' => $select_driver,
					'select_lokasi_galian' => $select_lokasi_galian,
				]);
	}

	public function viewDone($pengiriman_id){
		// TAMPILKAN FORM PENGIRIMAN STATUS DONE
			$data_pengiriman = \DB::table('view_pengiriman')->find($pengiriman_id);
			$data_pengiriman->pekerjaan = \DB::table('view_pekerjaan')->find($data_pengiriman->pekerjaan_id);
			$data_pengiriman->penjualan = \DB::table('view_penjualan')->find($data_pengiriman->penjualan_id);

			$lokasi_galian = \DB::table('lokasi_galian')->get();
			$select_lokasi_galian = [];
			foreach($lokasi_galian as $dt){
				$select_lokasi_galian[$dt->id] = $dt->nama;
			}

			$driver = \DB::table('view_driver')->get();
			$select_driver = [];
			foreach($driver as $dt){
				$select_driver[$dt->id] = $dt->nama . ' - ' . $dt->nopol;
			}

			return view('pengiriman.done',[
					'data_pengiriman' => $data_pengiriman,
					'select_driver' => $select_driver,
					'select_lokasi_galian' => $select_lokasi_galian,
				]);
	}

	// BATCH EDIT
	public function batchEdit(Request $req){
		$data_pengiriman  = \DB::table('view_pengiriman')
								->where('penjualan_id',$req->nomor_penjualan)
								->orderBy('id','desc')
								->orderBy('created_at','desc')
								->orderBy('material_id','desc')
								->get();

		$has_saved_count = \DB::table('view_pengiriman')
								->where('penjualan_id',$req->nomor_penjualan)
								->where('status','OPEN')
								->orderBy('material_id','desc')
								->count();

		// DATA PENJUALAN
		$data_penjualan = \DB::table('view_penjualan')
							->find($req->nomor_penjualan);
		$data_penjualan->pekerjaan = \DB::table('view_pekerjaan')
										->find($data_penjualan->pekerjaan_id);

		// DATA SELECT LOKASI GALIAN
		$lokasi_galian = \DB::table('lokasi_galian')
						->get();
		$select_lokasi_galian = [];
		foreach($lokasi_galian as $dt){
			$select_lokasi_galian[$dt->id] = $dt->nama;
		}

		// data select driver & nopol
		$driver = \DB::table('view_driver')
					->select('id','nama','nopol')
					->get();

		$select_driver = [];
		foreach($driver as $dt){
			$select_driver[$dt->id] = $dt->nama . ' --- '.$dt->nopol;
		}


		return view('pengiriman.batch-edit',[
				'data_pengiriman' => $data_pengiriman,
				'data_penjualan' => $data_penjualan,
				'select_lokasi_galian' => $select_lokasi_galian,
				'select_driver' => $select_driver,
				'has_saved_count' => $has_saved_count
			]);

	}

	// SIMPAN DATA BATCH EDIT
	public function batchEditSave(Request $req){
		return \DB::transaction(function()use($req){

			$data_master = json_decode($req->data_master);
			$data_detail = json_decode($req->data_detail)->pengiriman;

			foreach($data_detail as $dt){
				// generate tanggal kirim
				$tgl_kirim = $dt->tanggal;
	            $arr_tgl = explode('-',$tgl_kirim);
	            $tgl_kirim = new \DateTime();
	            $tgl_kirim->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 

				\DB::table('pengiriman')
					->where('id',$dt->pengiriman_id)
					->update([
							'tanggal' => $tgl_kirim,
							'karyawan_id' => $dt->driver,
							'lokasi_galian_id' => $dt->lokasi_galian,
							'status' => 'OPEN',
						]);
			}

			return redirect('pengiriman');
			
		});


	}

	public function batchEditSaveValidate(Request $req){
		return \DB::transaction(function()use($req){

			$data_master = json_decode($req->data_master);
			$data_detail = json_decode($req->data_detail)->pengiriman;

			foreach($data_detail as $dt){
				// generate tanggal kirim
				$tgl_kirim = $dt->tanggal;
	            $arr_tgl = explode('-',$tgl_kirim);
	            $tgl_kirim = new \DateTime();
	            $tgl_kirim->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 

				\DB::table('pengiriman')
					->where('id',$dt->pengiriman_id)
					->update([
							'tanggal' => $tgl_kirim,
							'karyawan_id' => $dt->driver,
							'lokasi_galian_id' => $dt->lokasi_galian,
							'status' => 'VALIDATED',
						]);
			}

			return redirect('pengiriman');
			
		});


	}

	// ==================E N D - O F - C L A S S=======================
}
