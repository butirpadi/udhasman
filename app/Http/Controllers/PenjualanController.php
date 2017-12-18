<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\MyPdf;

class PenjualanController extends Controller
{
	public function index(){
		$data = \DB::table('view_penjualan')
					->orderBy('tanggal','desc')
					->get();

		$select_customer = [];
		$customers = \DB::table('customer')
						->select('id','nama')
						->get();
		foreach($customers as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		$select_pekerjaan = [];
		$select_pekerjaan[0] = 'Semua Pekerjaan';
		$pekerjaan = \DB::table('pekerjaan')
					->get();
		foreach($pekerjaan as $dt){
			$select_pekerjaan[$dt->id] = $dt->nama;
		}

		return view('penjualan.index',[
				'data' => $data,
				'select_customer' => $select_customer,
				'select_pekerjaan' => $select_pekerjaan,
			]);
	}

	// ====================================================================================================

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

	// ====================================================================================================

	public function insert(Request $req){
		return \DB::transaction(function()use($req){
			$master = json_decode($req->so_master);
			$detail = json_decode($req->so_material);

			// #### generate order number
			$counter = Appsetting('penjualan_counter');
			$prefix = Appsetting('penjualan_prefix');
			$number = $prefix . date('Ym').$counter;

			// #### generate tanggal
	        $arr_tgl = explode('-',$master->order_date);
	        $tanggal = new \DateTime();
	        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

	        // #### Insert ke table penjualan
			$master_id = \DB::table('penjualan')->insertGetId([
					'is_direct_sales' => 'N',
					'order_number' => $number,
					'customer_id' => $master->customer_id,
					'pekerjaan_id' => $master->pekerjaan_id,
					'status' => 'OPEN',
					'tanggal' => $tanggal,
					'user_id' => \Auth::user()->id,
				]);

			// #### Insert ke table detail
			foreach($detail->material as $dt){
				\DB::table('penjualan_detail')
					->insert([
							'penjualan_id' => $master_id,
							'material_id' => $dt->id,
							'qty' => $dt->qty,
							'user_id' => \Auth::user()->id,
						]);
			}

			// update counter
			$counter = $counter+1;
			UpdateAppsetting('penjualan_counter',$counter);

			// return redirect('penjualan');
			 return redirect('penjualan/edit/' . $master_id);

		});
	}

	// ====================================================================================================

	public function edit($id){
		$data_master = \DB::table('view_penjualan')->find($id);
		$data_master->detail = \DB::table('view_penjualan_detail')->where('penjualan_id',$id)->get();
		$data_master->pekerjaan = \DB::table('view_pekerjaan')->find($data_master->pekerjaan_id);

		$pekerjaan = \DB::table('view_pekerjaan')
					->where('customer_id',$data_master->customer_id)
					->get();

		$select_pekerjaan = [];
		foreach($pekerjaan as $dt){
			$select_pekerjaan[$dt->id] = $dt->nama;
		}

		$select_material = [];
		$materials = \DB::table('material')
						->select('id','nama')
						->get();
		foreach($materials as $dt){
			$select_material[$dt->id] = $dt->nama;
		}

		// tentukan view untuk direct sales atau bukan
		$view = '';
		if($data_master->is_direct_sales == 'Y'){
			if($data_master->status == 'OPEN'){
				$view = 'penjualan.edit-direct-sales';
			}else if($data_master->status == 'VALIDATED'){
				$view = 'penjualan.validated-direct-sales';
			}else if($data_master->status == 'DONE'){

			}else if($data_master->status == 'CANCELED'){
				$view = 'penjualan.canceled-direct-sales';
			}

		} else{
			if($data_master->status == 'OPEN'){
				$view = 'penjualan.edit';
			}else if($data_master->status == 'VALIDATED'){
				$view = 'penjualan.validated';
			}else if($data_master->status == 'DONE'){
				$view = 'penjualan.validated';
			}else if($data_master->status == 'CANCELED'){
				$view = 'penjualan.canceled';
			}
		}


		// tampilkan data ke view
		// if($data_master->status == 'OPEN'){
			return view($view,[
				'data_master' => $data_master,
				// 'data_detail' => $data_detail,
				'select_pekerjaan' => $select_pekerjaan,
				'selectMaterial' => $select_material,
				'pekerjaan' => $pekerjaan
			]);
		// }elseif($data_master->status == 'V' ){
		// 	// get jumlah DO
		// 	$delivery_order_count = \DB::table('delivery_order')->where('sales_order_id',$id)->count();
		// 	return view($view,[
		// 			'data_master' => $data_master,
		// 			// 'data_detail' => $data_detail,
		// 			'select_pekerjaan' => $select_pekerjaan,
		// 			'delivery_order_count' => $delivery_order_count,
		// 		]);
		// }elseif($data_master->status == 'D'){
		// 	$delivery_order_count = \DB::table('delivery_order')->where('sales_order_id',$id)->count();
		// 	$invoices_count = \DB::table('customer_invoices')->where('order_id',$id)->count();
		// 	return view($view,[
		// 			'data_master' => $data_master,
		// 			// 'data_detail' => $data_detail,
		// 			'select_pekerjaan' => $select_pekerjaan,
		// 			'delivery_order_count' => $delivery_order_count,
		// 			'invoices_count' => $invoices_count,
		// 			'selectMaterial' => $select_material,
		// 		]);
		// }
	}

	// ====================================================================================================

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('penjualan')->delete($dt->id);
			}

			return redirect('penjualan');

		});
	}

	// ====================================================================================================

	public function insertDirectSales(Request $req){
		return \DB::transaction(function()use($req){
			$master = json_decode($req->so_master);
			$detail = json_decode($req->so_material);

			// #### generate order number
			$counter = Appsetting('penjualan_counter');
			$prefix = Appsetting('penjualan_prefix');
			$number = $prefix . date('/Y/m/').$counter++;

			// #### generate tanggal
	        $arr_tgl = explode('-',$master->order_date);
	        $tanggal = new \DateTime();
	        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

	        // #### Insert ke table penjualan
			$master_id = \DB::table('penjualan')->insertGetId([
					'is_direct_sales' => 'Y',
					'nopol_direct_sales' => $master->nopol,
					'order_number' => $number,
					'customer_id' => $master->customer_id,
					'status' => 'OPEN',
					'tanggal' => $tanggal,
					'total' => $master->total,
					'user_id' => \Auth::user()->id,
				]);

			// #### Insert ke table detail
			foreach($detail->material as $dt){
				\DB::table('penjualan_detail')
					->insert([
							'penjualan_id' => $master_id,
							'material_id' => $dt->id,
							'qty' => $dt->qty,
							'unit_price' => $dt->unit_price,
							'user_id' => \Auth::user()->id,
							'total' => $dt->qty * $dt->unit_price
						]);
			}

			// update counter
			Appsetting('penjualan_counter',$counter);

			return redirect('penjualan');

		});
	}

	// ====================================================================================================

	public function updateDirectSales(Request $req){
		return \DB::transaction(function()use($req){
			$master = json_decode($req->so_master);
			$detail = json_decode($req->so_material);
			// $detail = $detail->material;

			// #### generate tanggal
	        $arr_tgl = explode('-',$master->order_date);
	        $tanggal = new \DateTime();
	        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			// get data master
			$master_org = \DB::table('penjualan')
							->find($master->penjualan_id);
			$master_org->detail = \DB::table('penjualan_detail')
								->where('penjualan_id',$master->penjualan_id)
								->get();

			// update data master
			\DB::table('penjualan')
				->whereId($master->penjualan_id)
				->update([
						'tanggal' => $tanggal,
						'nopol_direct_sales' => $master->nopol,
						'total' => $master->total,
					]);

			// update data penjualan detail
			$material_baru = [];
			
			foreach($detail->material as $dt){
				array_push($material_baru, $dt->id);
				
				$is_exist = false;
				foreach($master_org->detail as $dto){
					if($dt->id == $dto->material_id){
						$is_exist = true;
					}
				}

				if($is_exist){
					echo 'update penjualan detail lama';
					// update material lama
					\DB::table('penjualan_detail')
						->where('penjualan_id',$master->penjualan_id)
						->whereMaterialId($dt->id)
						->update([
								'qty' => $dt->qty,
								'unit_price' => $dt->unit_price,
							]);
				}else{
					echo 'insert penjualan detail baru';
					// insert material baru
					\DB::table('penjualan_detail')
						->insert([
								'penjualan_id' => $master->penjualan_id,
								'material_id' => $dt->id,
								'qty' => $dt->qty,
								'kalkulasi' => 'RIT',
								'unit_price' => $dt->unit_price,
								'total' => $dt->qty * $dt->unit_price,
								'user_id' => $master_org->user_id
							]);
				}

			}

			// delete data penjualan detail yang tidak ada dalam daftar material_baru
			\DB::table('penjualan_detail')
					->where('penjualan_id',$master->penjualan_id)
					->whereNotIn('material_id',$material_baru)
					->delete();

			// echo $req->so_master;

			return redirect('penjualan');
 			
		});


		
	}

	// ====================================================================================================

	// VALIDATE DIRECT SALES
	public function validateDirectSales($penjualan_id){
		return \DB::transaction(function()use($penjualan_id){
			\DB::table('penjualan')
				->whereId($penjualan_id)
				->update([
						'status' => 'VALIDATED'
					]);

			return redirect()->back();
		});
	}

	// ====================================================================================================

	// validasi penjualan
	// setelah penjualan di validasi makan akan men-generatei surat jalan/delivery order (untuk penjualan tidak langsung)
	// Untuk penjualan langsung (direct sales) setelah di validasi maka akan langsung mengenerate ke invoice
	public function validateIt($penjualan_id){
		$data_penjualan = \DB::table('penjualan')
								->find($penjualan_id);

		// UPDATE STATUS DATA PESANAN PENJUALAN
		\DB::table('penjualan')
			->whereId($penjualan_id)
			->update([
					'status' => 'VALIDATED'
				]);

		// return redirect('penjualan');
		return redirect('penjualan/edit/' . $penjualan_id);
	}

	// ====================================================================================================

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			$master = json_decode($req->so_master);
			$detail = json_decode($req->so_material);
			// $detail = $detail->material;

			// #### generate tanggal
	        $arr_tgl = explode('-',$master->order_date);
	        $tanggal = new \DateTime();
	        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			// get data master
			$master_org = \DB::table('penjualan')
							->find($master->penjualan_id);
			$master_org->detail = \DB::table('penjualan_detail')
								->where('penjualan_id',$master->penjualan_id)
								->get();

			// update data master
			\DB::table('penjualan')
				->whereId($master->penjualan_id)
				->update([
						'pekerjaan_id' => $master->pekerjaan_id,
						'tanggal' => $tanggal
					]);

			// update data penjualan detail
			$material_baru = [];
			
			foreach($detail->material as $dt){
				array_push($material_baru, $dt->id);
				
				$is_exist = false;
				foreach($master_org->detail as $dto){
					if($dt->id == $dto->material_id){
						$is_exist = true;
					}
				}

				if($is_exist){
					echo 'update penjualan detail lama';
					// update material lama
					\DB::table('penjualan_detail')
						->where('penjualan_id',$master->penjualan_id)
						->whereMaterialId($dt->id)
						->update([
								'qty' => $dt->qty,
							]);
				}else{
					echo 'insert penjualan detail baru';
					// insert material baru
					\DB::table('penjualan_detail')
						->insert([
								'penjualan_id' => $master->penjualan_id,
								'material_id' => $dt->id,
								'qty' => $dt->qty,
								'user_id' => $master_org->user_id
							]);
				}

			}

			// delete data penjualan detail yang tidak ada dalam daftar material_baru
			\DB::table('penjualan_detail')
					->where('penjualan_id',$master->penjualan_id)
					->whereNotIn('material_id',$material_baru)
					->delete();

			// echo $req->so_master;

			return redirect('penjualan');
 			
		});
	}

	// ====================================================================================================
	
	public function cancelPenjualan($penjualan_id){
		\DB::table('penjualan')
			->whereId($penjualan_id)
			->update(['status'=>'CANCELED']);

		return redirect('penjualan');
	}

	// ====================================================================================================
	
	public function editPengiriman($penjualan_id){
		$data = \DB::table('view_pengiriman')
					->wherePenjualanId($penjualan_id)
					->get();

		$data_penjualan = \DB::table('view_penjualan')
							->find($penjualan_id);

		$data_pekerjaan = \DB::table('view_pekerjaan')
							->find($data_penjualan->pekerjaan_id);

		$select_lokasi_galian = [];
		$lokasi_galian = \DB::table('view_lokasi_galian')
						->get();
		foreach($lokasi_galian as $dt){
			$select_lokasi_galian[$dt->id] = $dt->nama;
		}

		$select_driver = [];
		$select_nopol = [];
		$driver = \DB::table('view_driver')
						// ->whereKodeJabatan('DV')
						->select('id','nama','nopol')
						->get();
		foreach($driver as $dt){
			$select_driver[$dt->id] = $dt->nama . ' [' . $dt->nopol . ']';
			$select_nopol[$dt->id] = $dt->nopol;
		}

		// $status_pengiriman  = 'DRAFT';
		$status_pengiriman  = \DB::table('penjualan')->find($penjualan_id)->status_pengiriman;
		$can_validate = false;

		$jml_pengiriman_ter_set = \DB::table('view_pengiriman')
									->wherePenjualanId($penjualan_id)
									->whereNotNull('karyawan_id')
									// ->where('karyawan_id','!=','')
									->count();

		// if($jml_pengiriman_ter_set > 0){
		// 	$status_pengiriman = 'OPEN';
		// }

		$jml_pengiriman_ter_set_complete = \DB::table('view_pengiriman')
										->wherePenjualanId($penjualan_id)
										->whereNotNull('tanggal')
										->whereNotNull('karyawan_id')
										->whereNotNull('lokasi_galian_id')
										->count();

		if($jml_pengiriman_ter_set_complete == count($data)){
			$can_validate = true;
		}

		$view_pengiriman = 'penjualan.pengiriman';

		// jika pengiriman sudah di validate
		// maka tampilkan view validated.pengiriman
		if($status_pengiriman == 'VALIDATED') {
			$view_pengiriman = 'penjualan.validated-pengiriman';
		}

		return view($view_pengiriman,[
				'data' => $data,
				'data_penjualan' => $data_penjualan,
				'data_pekerjaan' => $data_pekerjaan,
				'select_lokasi_galian' => $select_lokasi_galian,
				'select_driver' => $select_driver,
				'select_nopol' => $select_nopol,
				'status_pengiriman' => $status_pengiriman,
				'can_validate' => $can_validate,
			]);
	}

	// ====================================================================================================

	public function updatePengiriman(Request $req){

		return \DB::transaction(function()use($req){

			$master = json_decode($req->master);
			$detail = json_decode($req->detail);

			// echo $req->detail;

			// update pengiriman
			foreach($detail->detail as $dt){
				// generate tanggal
	            $tanggal = $dt->tanggal;
	            $arr_tgl = explode('-',$tanggal);
	            $tanggal = new \DateTime();
	            $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

				\DB::table('pengiriman')
					->whereId($dt->pengiriman_id)
					->update([
							'tanggal' => $tanggal,
							'karyawan_id' => $dt->driver,
							'lokasi_galian_id' => $dt->lokasi_galian,
							'status' => ($tanggal != '' && $dt->driver != '' && $dt->lokasi_galian != '' ? 'OPEN' : 'DRAFT')
						]);

			}

			// set status pengiriman
			\DB::table('penjualan')
					->whereId($master->penjualan_id)
					->update([
							'status_pengiriman' => 'OPEN'
						]);

			return redirect('penjualan/edit/pengiriman/'.$master->penjualan_id);
		});

	}

		

	// ====================================================================================================

	// VALIDATE PENGIRIMAN PENJUALAN
	public function validatePengiriman($penjualan_id){

		return \DB::transaction(function()use($penjualan_id){
			// validasi pengiriman rubabh status pengiriman di table penjualan
			\DB::table('penjualan')
				->whereId($penjualan_id)
				->update([
						'status_pengiriman' => 'VALIDATED'
					]);

			// update status pengiriman di table pengiriman itu sendiri
			\DB::table('pengiriman')
				->whereIn('penjualan_detail_id',function($query)use($penjualan_id){
                                $query->select('id')
                                    ->from('penjualan_detail')
                                    ->where('penjualan_id',$penjualan_id)
                                    ->get();  
                            })
				->update([
						'status' => 'VALIDATED'
					]);

			return redirect('penjualan/edit/pengiriman/' . $penjualan_id);
		});

	}

	// ====================================================================================================
	
	// VIEW KALKULASI BOBOT PENGIRIMAN
	public function kalkulasiPengiriman($pengiriman_id){

		return view('penjualan.kalkulasi-pengiriman',[
			]);
	}

	// ====================================================================================================
	
	public function cetakSuratJalan($id){
		$data = \DB::table('view_delivery_order')->find($id);

		$tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
		$file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
		$handle = fopen($file, 'w');
		$condensed = Chr(27) . Chr(33) . Chr(4);
		$bold1 = Chr(27) . Chr(69);
		$bold0 = Chr(27) . Chr(70);
		$initialized = chr(27).chr(64);
		$condensed1 = chr(15);
		$condensed0 = chr(18);
		$Data  = $initialized;
		$Data .= $condensed1;
		$Data .= "================================================================================\n";
		$Data .= "  ".$bold1."UD Hasil Mancing".$bold0."      |\n";
		$Data .= "  ".$bold1."Ngaban Rt 5 RW 2 ".$bold0."      |\n";
		$Data .= "  ".$bold1."Tanggulangin, Sidoarjo 61272".$bold0."      |\n";
		$Data .= "================================================================================\n";
		$Data .= "Kepada : " . $data->customer . " \n";
		$Data .= "Pekerjaan : " . $data->pekerjaan . "\n";
		$Data .= "Alamat : " . $data->alamat_pekerjaan . ', ' . $data->desa . "\n";
		$Data .= "         " . $data->kecamatan . ', ' . $data->kabupaten . "\n";
		
		$Data .= "--------------------------\n";

		// echo $Data;

		fwrite($handle, $Data);
		fclose($handle);
		copy($file, "//localhost/LX-300");  # Lakukan cetak
		unlink($file);
	}

	// ====================================================================================================

	// FILTER PENJUALAN
	public function filter(Request $req){
		$filter_data = [];
		// #### generate tanggal
		if($req->tanggal_awal != ''){
			$arr_tgl = explode('-',$req->tanggal_awal);
			$tanggal_awal = new \DateTime();
			$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			$arr_tgl = explode('-',$req->tanggal_akhir);
			$tanggal_akhir = new \DateTime();
			$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			$filter_data[count($filter_data)] = json_decode('{"tanggal_awal":$tanggal_awal_str,"tanggal_akhir":$tanggal_akhir_str}');
			
		}


		// generate where clause
		$where = '';

		if($req->ck_tanggal){
			$where .= 'tanggal between "' . $tanggal_awal_str . '" and "' . $tanggal_akhir_str . '" ';
		}else{
			$where .= "tanggal like '%%' ";
		}

		if($req->ck_customer){
			$where .="and customer_id = " . $req->customer .' ';
		}else{
			$where .="and customer_id like '%%' " ;
		}

		if($req->ck_pekerjaan){
			$where .= "and pekerjaan_id = " . $req->pekerjaan.' ';
		}else{
			$where .= "and pekerjaan_id like '%%' " ;
		}

		if($req->ck_status){
			$where .= "and pekerjaan_id = " . $req->pekerjaan.' ';
		}else{
			$where .= "and pekerjaan_id like '%%' " ;
		}

		if($where != ''){
			$data = \DB::table('view_penjualan')
						->whereRaw($where)
						->orderBy('tanggal','desc')
						->get();

		}else{
			$data = \DB::table('view_penjualan')
						->orderBy('tanggal','desc')
						->get();			
		}

		// echo json_encode($data);

		$select_customer = [];
		$customers = \DB::table('customer')
						->select('id','nama')
						->get();
		foreach($customers as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		$select_pekerjaan = [];
		$pekerjaan = \DB::table('pekerjaan')
					->get();
		foreach($pekerjaan as $dt){
			$select_pekerjaan[$dt->id] = $dt->nama;
		}

		// exec('evince -w /media/eries/DATA/Books/bukalangsunglaris.pdf');

		return view('penjualan.filter',[
				'data' => $data,
				'select_customer' => $select_customer,
				'select_pekerjaan' => $select_pekerjaan,
				'filter_tanggal_awal' => $req->tanggal_awal,
				'filter_tanggal_akhir' => $req->tanggal_akhir,
				'filter_pekerjaan' => \DB::table('pekerjaan')->find($req->pekerjaan),
				'filter_customer' => \DB::table('customer')->find($req->customer),
				'filter_status' => $req->status,
			]);
	}

	// public function cetakFilterByTanggal($data, $tanggal_awal, $tanggal_akhir, $customer,$pekerjaan,$status){

	// }

	// public function cetakFilterByCustomer($data){

	// }

	// public function cetakFilterByPekerjaan($data){

	// }

	// public function cetakFilterByCustomerPekerjaan($data){

	// }


	// GENERATE PDF BY TANGGAL
	function generatePdfTableByTanggalDefaultFilter(&$doPdf,$data){
		// $page_content_width = $doPdf->GetPageWidth()-16;
		
		// TABLE HEADER
		$separator_width = 1;
		$table_width = $doPdf->GetPageWidth() - 16 ; 
		$col_no = 5/100*$table_width;
		$col_no_penjualan = 20/100*$table_width;
		$col_tanggal = 10/100*$table_width;
		$col_customer = 25/100*$table_width;
		$col_pekerjaan = 25/100*$table_width;
		$col_qty = 5/100*$table_width;
		$col_status = 10/100*$table_width;
		$tb_header_h = 8;
		$tb_content_h = 5;

		// $doPdf->SetFillColor(0,0,0);
		$doPdf->SetFont('Arial','B',8);
		// $doPdf->SetTextColor(255,255,255);
		$doPdf->Ln(2);
		$doPdf->SetX(8);

		$doPdf->Cell($col_no,$tb_header_h,'NO',1,0,'C',false);
		$doPdf->Cell($col_tanggal,$tb_header_h,'TANGGAL',1,0,'C',false);
		$doPdf->Cell($col_no_penjualan,$tb_header_h,'NOMOR',1,0,'C',false);
		$doPdf->Cell($col_customer,$tb_header_h,'CUSTOMER',1,0,'C',false);
		$doPdf->Cell($col_pekerjaan,$tb_header_h,'PEKERJAAN',1,0,'C',false);
		$doPdf->Cell($col_status,$tb_header_h,'STATUS',1,0,'C',false);
		$doPdf->Cell($col_qty,$tb_header_h,'QTY',1,2,'C',false);

		// TABLE CONTENT
		// $doPdf->SetTextColor(0,0,0);
		$doPdf->SetFont('Arial',null,8);

		$rownum=1;
		foreach($data as $dt){
			$doPdf->SetX(8);
			$doPdf->Cell($col_no,$tb_content_h,$rownum++,1,0,'C',false);
			$doPdf->Cell($col_tanggal,$tb_content_h,$dt->tanggal_format,1,0,'C',false);
			$doPdf->Cell($col_no_penjualan,$tb_content_h,$dt->order_number,1,0,'C',false);
			$doPdf->Cell($col_customer,$tb_content_h,$dt->nama_customer,1,0,'L',false);
			$doPdf->Cell($col_pekerjaan,$tb_content_h,$dt->nama_pekerjaan,1,0,'L',false);
			$doPdf->Cell($col_status,$tb_content_h,$dt->status,1,0,'C',false);
			$doPdf->Cell($col_qty,$tb_content_h,$dt->qty,1,2,'R',false);
		}
	}
	// END GENERATE PDF BY CUSTOMER

	// GENERATE PDF BY TANGGAL
	function generatePdfTableByCustomerDefaultFilter(&$doPdf,$data){
		// $page_content_width = $doPdf->GetPageWidth()-16;
		
		// TABLE HEADER
		$separator_width = 1;
		$table_width = $doPdf->GetPageWidth() - 16 ; 
		$col_no = 5/100*$table_width;
		$col_no_penjualan = 20/100*$table_width;
		$col_tanggal = 10/100*$table_width;
		// $col_customer = 25/100*$table_width;
		$col_pekerjaan = 50/100*$table_width;
		$col_qty = 5/100*$table_width;
		$col_status = 10/100*$table_width;
		$tb_header_h = 8;
		$tb_content_h = 5;

		// $doPdf->SetFillColor(0,0,0);
		$doPdf->SetFont('Arial','B',8);
		// $doPdf->SetTextColor(255,255,255);
		$doPdf->Ln(2);
		$doPdf->SetX(8);

		$doPdf->Cell($col_no,$tb_header_h,'NO',1,0,'C',false);
		$doPdf->Cell($col_tanggal,$tb_header_h,'TANGGAL',1,0,'C',false);
		$doPdf->Cell($col_no_penjualan,$tb_header_h,'NOMOR',1,0,'C',false);
		// $doPdf->Cell($col_customer,$tb_header_h,'CUSTOMER',1,0,'C',false);
		$doPdf->Cell($col_pekerjaan,$tb_header_h,'PEKERJAAN',1,0,'C',false);
		$doPdf->Cell($col_status,$tb_header_h,'STATUS',1,0,'C',false);
		$doPdf->Cell($col_qty,$tb_header_h,'QTY',1,2,'C',false);

		// TABLE CONTENT
		// $doPdf->SetTextColor(0,0,0);
		$doPdf->SetFont('Arial',null,8);

		$rownum=1;
		foreach($data as $dt){
			$doPdf->SetX(8);
			$doPdf->Cell($col_no,$tb_content_h,$rownum++,1,0,'C',false);
			$doPdf->Cell($col_tanggal,$tb_content_h,$dt->tanggal_format,1,0,'C',false);
			$doPdf->Cell($col_no_penjualan,$tb_content_h,$dt->order_number,1,0,'C',false);
			// $doPdf->Cell($col_customer,$tb_content_h,$dt->nama_customer,1,0,'L',false);
			$doPdf->Cell($col_pekerjaan,$tb_content_h,$dt->nama_pekerjaan,1,0,'L',false);
			$doPdf->Cell($col_status,$tb_content_h,$dt->status,1,0,'C',false);
			$doPdf->Cell($col_qty,$tb_content_h,$dt->qty,1,2,'R',false);
		}
	}
	// END GENERATE PDF BY CUSTOMER

	// GENERATE PDF BY CUSTOMER & PEKERJAAN
	function generatePdfTableByCustomerPekerjaanDefaultFilter(&$doPdf,$data){
		// $page_content_width = $doPdf->GetPageWidth()-16;
		
		// TABLE HEADER
		$separator_width = 1;
		$table_width = $doPdf->GetPageWidth() - 16 ; 
		$col_no = 5/100*$table_width;
		$col_no_penjualan = 70/100*$table_width;
		$col_tanggal = 10/100*$table_width;
		// $col_customer = 25/100*$table_width;
		// $col_pekerjaan = 25/100*$table_width;
		$col_qty = 5/100*$table_width;
		$col_status = 10/100*$table_width;
		$tb_header_h = 8;
		$tb_content_h = 5;

		// $doPdf->SetFillColor(0,0,0);
		$doPdf->SetFont('Arial','B',8);
		// $doPdf->SetTextColor(255,255,255);
		$doPdf->Ln(2);
		$doPdf->SetX(8);

		$doPdf->Cell($col_no,$tb_header_h,'NO',1,0,'C',false);
		$doPdf->Cell($col_tanggal,$tb_header_h,'TANGGAL',1,0,'C',false);
		$doPdf->Cell($col_no_penjualan,$tb_header_h,'NOMOR',1,0,'C',false);
		// $doPdf->Cell($col_customer,$tb_header_h,'CUSTOMER',1,0,'C',false);
		// $doPdf->Cell($col_pekerjaan,$tb_header_h,'PEKERJAAN',1,0,'C',false);
		$doPdf->Cell($col_status,$tb_header_h,'STATUS',1,0,'C',false);
		$doPdf->Cell($col_qty,$tb_header_h,'QTY',1,2,'C',false);

		// TABLE CONTENT
		// $doPdf->SetTextColor(0,0,0);
		$doPdf->SetFont('Arial',null,8);

		$rownum=1;
		foreach($data as $dt){
			$doPdf->SetX(8);
			$doPdf->Cell($col_no,$tb_content_h,$rownum++,1,0,'C',false);
			$doPdf->Cell($col_tanggal,$tb_content_h,$dt->tanggal_format,1,0,'C',false);
			$doPdf->Cell($col_no_penjualan,$tb_content_h,$dt->order_number,1,0,'C',false);
			// $doPdf->Cell($col_customer,$tb_content_h,$dt->nama_customer,1,0,'L',false);
			// $doPdf->Cell($col_pekerjaan,$tb_content_h,$dt->nama_pekerjaan,1,0,'L',false);
			$doPdf->Cell($col_status,$tb_content_h,$dt->status,1,0,'C',false);
			$doPdf->Cell($col_qty,$tb_content_h,$dt->qty,1,2,'R',false);
		}
	}
	// END GENERATE PDF BY CUSTOMER & PEKERJAAN

	// CETAK FILTER PDF
	public function cetakFilter(Request $req){
		$filter_data = [];

		// #### generate tanggal
		if($req->tanggal_awal != '-' && $req->tanggal_awal != ''){
			$arr_tgl = explode('-',$req->tanggal_awal);
			$tanggal_awal = new \DateTime();
			$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			$arr_tgl = explode('-',$req->tanggal_akhir);
			$tanggal_akhir = new \DateTime();
			$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			$filter_data[count($filter_data)] = json_decode('{"tanggal_awal":$tanggal_awal_str,"tanggal_akhir":$tanggal_akhir_str}');
			
			$tgl_awal = $req->tanggal_awal;
			$tgl_akhir = $req->tanggal_akhir;
		}else{
			$tgl_awal = '';
			$tgl_akhir = '';
		}

		
		$customer = \DB::table('customer')->find($req->customer_id);
		$pekerjaan = \DB::table('customer')->find($req->pekerjaan_id);
		$status = $req->status;


		// generate where clause
		$where = '';

		// if($req->ck_tanggal){
			$where .= 'tanggal between "' . $tanggal_awal_str . '" and "' . $tanggal_akhir_str . '" ';
		// }else{
		// 	$where .= "tanggal like '%%' ";
		// }

		if($req->customer_id != '-' && $req->customer_id != ''){
			$where .="and customer_id = " . $req->customer_id .' ';
		}else{
			$where .="and customer_id like '%%' " ;
		}

		if($req->pekerjaan_id != '-' && $req->pekerjaan_id != ''){
			$where .= "and pekerjaan_id = " . $req->pekerjaan_id .' ';
		}else{
			$where .= "and pekerjaan_id like '%%' " ;
		}

		if($req->status != '-' && $req->status != ''){
			$where .= "and status = " . $req->status.' ';
		}else{
			$where .= "and status like '%%' " ;
		}

		if($where != ''){
			$data = \DB::table('view_penjualan')
						->whereRaw($where)
						->orderBy('tanggal','asc')
						->get();

		}else{
			$data = \DB::table('view_penjualan')
						->orderBy('tanggal','asc')
						->get();			
		}

		// GENERATE PDF

		// $doPdf = new MyPdf('L','mm',array(210,148.5));
		$doPdf = new MyPdf('P','mm','A4');
		$doPdf->SetAutoPageBreak(true,10);
		$doPdf->AddPage();
		// GeneratePdfHeader($doPdf,'SURAT JALAN','xxx');

		// defined
		$page_content_width = $doPdf->GetPageWidth()-16;
		$space_height = 4;

		// KONTENT HEADER
		// $doPdf->Ln(8);
		$doPdf->SetX(8);
		// $doPdf->SetTextColor(0,0,0);

		// Tanggal
		$doPdf->SetFont('Arial','B',12);
		$doPdf->Cell(20,$space_height*2,'DATA PESANAN PENJUALAN',0,2,false);
		
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,$space_height,'Tanggal',0,0,false);
		$doPdf->Cell(2,$space_height,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(($page_content_width/2)-22,$space_height,str_replace('-', '/', $tgl_awal) .' - '.str_replace('-', '/', $tgl_akhir),0,0,false);

		// STATUS
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,$space_height,'Status',0,0,false);
		$doPdf->Cell(2,$space_height,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(($page_content_width/2)-22,$space_height,$status != '' ? $status : '-',0,2,false);

		$doPdf->SetX(8);
		// CUSTOMER
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,'Customer',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(($page_content_width/2)-22,5,$customer ? $customer->nama : '-',0,0,false);

		// PEKERJAAN
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,$space_height,'Pekerjaan',0,0,false);
		$doPdf->Cell(2,$space_height,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(($page_content_width/2)-22,$space_height,$pekerjaan ? $pekerjaan->nama : '-',0,2,false);
		$doPdf->Ln();

		// GENERATE TABLE CONTENT BERDASAR JENIS FILTER
		// if($customer  && $pekerjaan  && $status != ''){

		// }elseif($customer && $pekerjaan){
		// 	$this->generatePdfTableByCustomerPekerjaanDefaultFilter($doPdf,$data);
		// }elseif($customer){
		// 	$this->generatePdfTableByCustomerDefaultFilter($doPdf,$data);
		// }else{
		// 	$this->generatePdfTableByTanggalDefaultFilter($doPdf,$data);
		// }

		$this->generatePdfTableByTanggalDefaultFilter($doPdf,$data);

		// OUTPUT PDF
		$doPdf->Output(Appsetting('default_pdf_action'),'DataPenjualan_'.date('dmYhms').'.pdf');
		exit;
	}

	public function cetakFilterDetail(Request $req){
		$filter_data = [];

		// #### generate tanggal
		if($req->tanggal_awal != '-' && $req->tanggal_awal != ''){
			$arr_tgl = explode('-',$req->tanggal_awal);
			$tanggal_awal = new \DateTime();
			$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			$arr_tgl = explode('-',$req->tanggal_akhir);
			$tanggal_akhir = new \DateTime();
			$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

			$filter_data[count($filter_data)] = json_decode('{"tanggal_awal":$tanggal_awal_str,"tanggal_akhir":$tanggal_akhir_str}');
			
			$tgl_awal = $req->tanggal_awal;
			$tgl_akhir = $req->tanggal_akhir;
		}else{
			$tgl_awal = '';
			$tgl_akhir = '';
		}

		
		$customer = \DB::table('customer')->find($req->customer_id);
		$pekerjaan = \DB::table('customer')->find($req->pekerjaan_id);
		$status = $req->status;


		// generate where clause
		$where = '';

		if($req->ck_tanggal){
			$where .= 'tanggal between "' . $tanggal_awal_str . '" and "' . $tanggal_akhir_str . '" ';
		}else{
			$where .= "tanggal like '%%' ";
		}

		if($req->ck_customer){
			$where .="and customer_id = " . $req->customer .' ';
		}else{
			$where .="and customer_id like '%%' " ;
		}

		if($req->ck_pekerjaan){
			$where .= "and pekerjaan_id = " . $req->pekerjaan.' ';
		}else{
			$where .= "and pekerjaan_id like '%%' " ;
		}

		if($req->ck_status){
			$where .= "and pekerjaan_id = " . $req->pekerjaan.' ';
		}else{
			$where .= "and pekerjaan_id like '%%' " ;
		}

		if($where != ''){
			$data = \DB::table('view_penjualan')
						->whereRaw($where)
						->orderBy('tanggal','asc')
						->get();

		}else{
			$data = \DB::table('view_penjualan')
						->orderBy('tanggal','asc')
						->get();			
		}

		// GENERATE PDF

		// $doPdf = new MyPdf('L','mm',array(210,148.5));
		$doPdf = new MyPdf('P','mm','A4');
		$doPdf->showPageNumber(true);
		$doPdf->setFooterDesc('UD Hasil Mancing');
		$doPdf->SetAutoPageBreak(true,15);
		$doPdf->AddPage();
		// GeneratePdfHeader($doPdf,'SURAT JALAN','xxx');

		// defined
		$page_content_width = $doPdf->GetPageWidth()-16;
		$space_height = 6;
		$sub_space_height = 4;

		// KONTENT HEADER
		// $doPdf->Ln(8);
		$doPdf->SetX(8);
		// $doPdf->SetTextColor(0,0,0);

		// Tanggal
		$doPdf->SetFont('Arial','B',12);
		$doPdf->Cell(20,$space_height*2,'DATA PESANAN PENJUALAN',0,2,false);
		
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,$space_height,'Tanggal',0,0,false);
		$doPdf->Cell(2,$space_height,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(($page_content_width/2)-22,$space_height,str_replace('-', '/', $tgl_awal) .' - '.str_replace('-', '/', $tgl_akhir),0,0,false);

		// STATUS
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,$space_height,'Status',0,0,false);
		$doPdf->Cell(2,$space_height,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(($page_content_width/2)-22,$space_height,$status != '' ? $status : '-',0,2,false);

		$doPdf->SetX(8);
		// CUSTOMER
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,'Customer',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(($page_content_width/2)-22,5,$customer ? $customer->nama : '-',0,0,false);

		// PEKERJAAN
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,$space_height,'Pekerjaan',0,0,false);
		$doPdf->Cell(2,$space_height,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(($page_content_width/2)-22,$space_height,$pekerjaan ? $pekerjaan->nama : '-',0,2,false);
		// $doPdf->Ln();

		// TABLE HEADER
		$separator_width = 1;
		$table_width = $page_content_width ; 
		$col_no = 5/100*$table_width;
		$col_no_penjualan = 15/100*$table_width;
		$col_tanggal = 10/100*$table_width;
		$col_customer = 25/100*$table_width;
		$col_pekerjaan = 25/100*$table_width;
		$col_status = 15/100*$table_width;
		$col_qty = 5/100*$table_width;
		// $col_status = 10/100*$table_width;
		$tb_header_h = 8;
		$tb_content_h = 8;
		$tb_sub_content_h = 6;

		// $doPdf->SetFillColor(0,0,0);
		$doPdf->SetFont('Arial','B',8);
		// $doPdf->SetTextColor(255,255,255);
		$doPdf->Ln(2);
		$doPdf->SetX(8);

		$doPdf->Cell($col_no,$tb_header_h,'NO',1,0,'C',false);
		$doPdf->Cell($col_tanggal,$tb_header_h,'TANGGAL',1,0,'C',false);
		$doPdf->Cell($col_no_penjualan,$tb_header_h,'NOMOR',1,0,'C',false);
		$doPdf->Cell($col_customer,$tb_header_h,'CUSTOMER',1,0,'C',false);
		$doPdf->Cell($col_pekerjaan,$tb_header_h,'PEKERJAAN',1,0,'C',false);
		$doPdf->Cell($col_status,$tb_header_h,'STATUS',1,0,'C',false);
		$doPdf->Cell($col_qty,$tb_header_h,'QTY',1,2,'C',false);

		// TABLE CONTENT
		// $doPdf->SetTextColor(0,0,0);
		$doPdf->SetFont('Arial',null,8);

		$rownum=1;
		foreach($data as $dt){
			$doPdf->SetX(8);
			$doPdf->Cell($col_no,$tb_content_h,$rownum++,'T',0,'C',false);
			$doPdf->Cell($col_tanggal,$tb_content_h,$dt->tanggal_format,'T',0,'C',false);
			$doPdf->Cell($col_no_penjualan,$tb_content_h,$dt->order_number,'T',0,'C',false);
			$doPdf->Cell($col_customer,$tb_content_h,$dt->nama_customer,'T',0,'L',false);
			$doPdf->Cell($col_pekerjaan,$tb_content_h,$dt->nama_pekerjaan,'T',0,'L',false);
			$doPdf->Cell($col_status,$tb_content_h,$dt->status,'T',0,'C',false);
			$doPdf->Cell($col_qty,$tb_content_h,null,'T',0,'L',false);

			$x = $doPdf->GetX();
			// bottom line per master row
			$doPdf->Ln();
			$doPdf->SetX(8);
			$doPdf->SetDrawColor(0,0,0);
			$doPdf->SetDash(1,1);
			$doPdf->Line(8,$doPdf->GetY(),$x,$doPdf->GetY());
			// $doPdf->Ln();
			$doPdf->SetDash();
			$doPdf->SetDrawColor(0,0,0);

			// get data detail
			$data_detail = \DB::table('view_penjualan_detail')
							->wherePenjualanId($dt->id)
							->get();
			foreach($data_detail as $dd){
				$doPdf->SetX(8);
				$doPdf->Cell($col_no,$tb_sub_content_h,null,0,0,'C',false);
				$doPdf->Cell($col_tanggal,$tb_sub_content_h,null,0,0,'C',false);
				$doPdf->Cell($col_no_penjualan,$tb_sub_content_h,null,0,0,'C',false);
				$doPdf->Cell($col_customer,$tb_sub_content_h,null,0,0,'L',false);
				$doPdf->Cell($col_pekerjaan + $col_status,$tb_sub_content_h,$dd->nama_material,0,0,'L',false);
				$doPdf->Cell($col_qty,$tb_sub_content_h,$dd->qty,0,2,'R',false);
			}


			// col total qty
			$doPdf->SetX(8);
			$doPdf->Cell($table_width - $col_qty - $col_pekerjaan - $col_status,$tb_sub_content_h,null,0,0,0,false);
			$doPdf->Cell($col_pekerjaan + $col_status + $col_qty,$tb_sub_content_h,$dt->qty,'T',2,'R',false);

			// empty space row
			$doPdf->SetX(8);
			$doPdf->Cell($table_width,$tb_sub_content_h,null,0,2,null,false);
		}

		// The last row bottom line
			// $doPdf->Ln();
			$doPdf->SetX(8);
			$doPdf->SetDrawColor(0,0,0);
			// $doPdf->SetDash(1,1);
			$doPdf->Line(8,$doPdf->GetY(),$x,$doPdf->GetY());

		// OUTPUT PDF
		$doPdf->Output(Appsetting('default_pdf_action'),'DataPenjualan_'.date('dmYhms').'.pdf');
		exit;
	}

	// =========================================================================
	// END OF CLASS
	// =========================================================================
}
