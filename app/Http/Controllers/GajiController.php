<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GajiController extends Controller
{

	public function savePay(Request $req){
		$payDetail = json_decode($req->pay_detail);

		return \DB::transaction(function()use($payDetail){
			$total = 0;
			foreach($payDetail as $dt){
				\DB::select("update payroll_driver_detail 
						set harga_satuan = " . $dt->harga_satuan . ", 
						jumlah = case 
							when kalkulasi = 'rit' then qty * harga_satuan
							when kalkulasi = 'ton' then netto * harga_satuan
							else volume * harga_satuan 
						end
						where id = " . $dt->payroll_driver_detail_id);

				$payroll_driver_id = \DB::table('payroll_driver_detail')->find($dt->payroll_driver_detail_id)->payroll_driver_id;

				// query set total
				\DB::select("update payroll_driver set total = (select sum(jumlah) 
						from payroll_driver_detail where payroll_driver_id = " . $payroll_driver_id . ") , amount_due = total
						where payroll_driver.id = " . $payroll_driver_id);
			}


			return redirect()->back();
			
		});

	}

	public function indexDriver(){
		$data = \DB::table('view_payroll_driver')
				->groupBy('bulan')
				->orderBy('payment_date','desc')
				->paginate(Appsetting('paging_item_number'));
				// ->get();
		return view('gaji.driver.index',[
			'data' => $data
		]);	
	}

	public function getPayrollAtMonth($bulan){
		$data = \DB::table('view_payroll_driver')
				->whereBulan($bulan)
				->groupBy('payment_date')
				->orderBy('payment_date','desc')
				->get();
		return json_encode($data);
	}

	public function driverAdd(){
		return view('gaji.driver.add');
	}

	public function showPayrollTable($tanggal){
		$tanggal_gaji;
		// get data driver
	  	$data = \DB::table('res_partner')
	  				->where('driver','Y')
	  				// ->where('is_active','Y')
	  				->orderBy('created_at','desc')
	  				->orderBy('id','asc')
	  				->get();
  		\DB::transaction(function()use(&$data,$tanggal,&$tanggal_gaji){

		 	// Generate Date
			$arr_tgl = explode('-',$tanggal);
			$tanggal_gaji = new \DateTime();
			$tanggal_awal = new \DateTime();
			$tanggal_akhir = new \DateTime();
			$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal->modify('-7 day');
			$tanggal_akhir->modify('-1 day');

			// cek apakah data tersedia, jika tidak tersedia maka inputkan ke database
			$jumlah_data_di_table = \DB::table('payroll_driver')
									->wherePaymentDate($tanggal_gaji->format('Y-m-d'))
									->count();
			// echo 'jumlah data : ' . $jumlah_data_di_table;
			$can_insert = $jumlah_data_di_table > 0 ? false : true;

			// Generate data payment at draft state
			if($can_insert){
				foreach($data as $dt){
					$this->insertToTable($dt->id,$tanggal);
				}
			}

			// Get data to view
			foreach($data as $dt){
				$dt->payroll = \DB::table('view_payroll_driver')
									->whereKaryawanId($dt->id)
									->wherePaymentDate($tanggal_gaji->format('Y-m-d'))
									->first();	
			}

		  // 	foreach($data as $dt){
		  // 		// $dt->payroll = \DB::table('view_payroll_driver')
				// 					// ->whereKaryawanId($dt->id)
				// 					// ->wherePaymentDate($tanggal_gaji->format('Y-m-d'))
				// 					// ->first();	

				// // echo 'jumlah detail payroll :' . var_dump($dt->payroll) . '<br/>--------------<br/>';

				// // insert to database table as draft state
				// if($can_insert){
					
				// }
		  // 	}

		});

		// foreach($data as $dt){
		// 	echo var_dump($dt->payroll) .'<br/>-------<br/>';
		// }

	  	return view('gaji.driver.payroll-table',[
  			'data' => $data,
  			'tanggal_penggajian' =>$tanggal_gaji->format('d-m-Y')
  		]);
	  	
	}

	public function getPayDay(Request $req){
		$payday = \DB::table('appsetting')->where('name','payroll_day')->first()->value;

		$hari = "";
		if($payday == 0){
			$hari  = "Minggu";
		}else if($payday == 1){
			$hari  = "Senin";
		}else if($payday == 2){
			$hari  = "Selasa";
		}else if($payday == 3){
			$hari  = "Rabu";
		}else if($payday == 4){
			$hari  = "Kamis";
		}else if($payday == 5){
			$hari  = "Jumat";
		}else if($payday == 6){
			$hari  = "Sabtu";
		}


		$firstDateOfMonth = '01-' . $req->bulan;

		// generate tanggal
		$arr_tgl = explode('-',$firstDateOfMonth);
		$firstDateOfMonth = new \DateTime();
		$firstDateOfMonth->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

		$select_pay_day = [];
		// 0 = sunday

		for($i=1;$i<=$firstDateOfMonth->format('t');$i++){
			$aDate = new \DateTime();
			// echo $i . '  -   ' . $aDate->setDate($arr_tgl[2],$arr_tgl[1],$i)->format('w') .  '<br/>';
			if($aDate->setDate($arr_tgl[2],$arr_tgl[1],$i)->format('w') == $payday){
				array_push($select_pay_day, ['tanggal' => $i, 'tanggal_full' => $hari .', ' . $i .'-'.$arr_tgl[1].'-'.$arr_tgl[2]] );
			}
		}

		echo json_encode($select_pay_day);

	}

	public function addPay($karyawan_id,$tanggal){
	 	// generate date
		$arr_tgl = explode('-',$tanggal);
		$tanggal_gaji = new \DateTime();
		$tanggal_awal = new \DateTime();
		$tanggal_akhir = new \DateTime();
		$tanggal_awal_siang_for_table_presensi = new \DateTime();
		$tanggal_akhir_siang_for_table_presensi = new \DateTime();
		$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->modify('-7 day');
		$tanggal_akhir->modify('-1 day');

		$tanggal_awal_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal_siang_for_table_presensi->modify('-7 day');
		$tanggal_akhir_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_akhir_siang_for_table_presensi->modify('-1 day');

	  	$data = \DB::table('view_karyawan')->find($karyawan_id);
	  	$data->total_hadir_pagi  = \DB::table('attend')
								->select('pagi')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->where('pagi','Y')
								->count();
		$data->total_hadir_siang = \DB::table('attend')
								->select('siang')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->where('siang','Y')
								->count();
		$data->presensi_pagi = \DB::table('attend')
								->select('pagi','tgl')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->orderBy('tgl')
								->get();
		$data->presensi_siang = \DB::table('attend')
								->select('siang','tgl')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->orderBy('tgl')
								->get();

	  	return view('gaji.driver.add-pay',[
  			'data' => $data,
  			'tanggal_gaji' => $tanggal,
  			'tanggal_awal' => $tanggal_awal,
  			'tanggal_akhir' => $tanggal_gaji,
  			'tanggal_awal_siang_for_table_presensi' => $tanggal_awal_siang_for_table_presensi,
  			'tanggal_akhir_siang_for_table_presensi' => $tanggal_akhir_siang_for_table_presensi,
  		]);
	}

	public function insertPay(Request $req){
		// return \DB::transaction(function()use($req){
			// // generate tanggal
	  //       $arr_tgl = explode('-',$req->pay_date);
	  //       $tanggal = new \DateTime();
	  //       $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

	  //       // generate payroll
	  //       $counter = \DB::table('appsetting')->whereName('payroll_counter')->first()->value;
	  //       $prefix = \DB::table('appsetting')->whereName('payroll_prefix')->first()->value;
	  //       $payroll_number  = $prefix . '/'.date('Y').'/'.date('m').'/'.$counter++;


			// $payroll_id = \DB::table('payroll_driver')->insertGetId([
			// 		'status' => 'O',
			// 		'payroll_number' => $payroll_number,
			// 		'payment_date' => $tanggal,
			// 		'karyawan_id' => $req->karyawan_id,
			// 		'basic_pay' => $req->basic_pay,
			// 		'total_pagi' => $req->total_pagi,
			// 		'total_siang' => $req->total_siang,
			// 		'potongan' => $req->potongan,
			// 		'netpay' => (($req->total_pagi + $req->total_siang ) /2) * $req->basic_pay - $req->potongan,
			// 		'user_id' => \Auth::user()->id
			// 	]);


	  //       // update counter
	  //       \DB::table('appsetting')
	  //       	->whereName('payroll_counter')
	  //       	->update(['value'=>$counter]);

			$this->insertToTable($req->pay_date,$req->karyawan_id);

	        return redirect('gaji/driver/edit-pay/' . $payroll_id);
		// });
  	} 

  	private function insertToTable($karyawan_id,$tanggal){
  		// generate tanggal
        $arr_tgl = explode('-',$tanggal);
        $tanggal = new \DateTime();
        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

        // // generate payroll number
        // $counter = \DB::table('appsetting')->whereName('payroll_counter')->first()->value;
        // $prefix = \DB::table('appsetting')->whereName('payroll_prefix')->first()->value;
        // $payroll_number  = $prefix . '/'.date('Y').'/'.date('m').$counter++;
        // // update counter
        // \DB::table('appsetting')
        // 	->whereName('payroll_counter')
        // 	->update(['value'=>$counter]);

        // Generate tanggal
        $tanggal_awal = new \DateTime();
		$tanggal_akhir = new \DateTime();
		$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->modify('-7 day');
		$tanggal_akhir->modify('-1 day');

		$payroll_id = \DB::table('payroll_driver')->insertGetId([
				'state' => 'draft',
				// 'payroll_number' => $payroll_number,
				'payment_date' => $tanggal,
				'tanggal_awal' => $tanggal_awal,
				'tanggal_akhir' => $tanggal_akhir,
				'karyawan_id' => $karyawan_id,
				'user_id' => \Auth::user()->id
			]);

        
  	}

  	public function editPay($payroll_id){
  		$data = \DB::table('view_payroll_driver')
  						->whereId($payroll_id)
  						->select('view_payroll_driver.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as payment_date_formatted'))
  						->first();

  		// generate date
			$arr_tgl = explode('-',$data->payment_date_formatted);
			$tanggal_gaji = new \DateTime();
			$tanggal_awal = new \DateTime();
			$tanggal_akhir = new \DateTime();
			$tanggal_awal_siang_for_table_presensi = new \DateTime();
			$tanggal_akhir_siang_for_table_presensi = new \DateTime();
			$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal->modify('-7 day');
			$tanggal_akhir->modify('-1 day');

			$tanggal_awal_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal_siang_for_table_presensi->modify('-7 day');
			$tanggal_akhir_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_akhir_siang_for_table_presensi->modify('-1 day');

			// data payrol detail
			$data->detail = \DB::table('view_payroll_driver_detail')
						->wherePayrollDriverId($payroll_id)
						->get();


  		// // cek apakah sudah ter-validate
  		// if($data->state == 'paid'){
  		// 	return view('gaji/driver/validated-pay',[
	  	// 			'data' => $data,
	  	// 			'tanggal_awal' => $tanggal_awal,
		  // 			'tanggal_akhir' => $tanggal_akhir,
		  // 			// 'tanggal_awal_siang_for_table_presensi' => $tanggal_awal_siang_for_table_presensi,
		  // 			// 'tanggal_akhir_siang_for_table_presensi' => $tanggal_akhir_siang_for_table_presensi,
	  	// 		]);
  		// }else{
	  		
	  		return view('gaji/driver/edit-pay',[
	  				'data' => $data,
	  				'tanggal_awal' => $tanggal_awal->format('d-m-Y'),
		  			'tanggal_akhir' => $tanggal_akhir->format('d-m-Y'),
		  			// 'tanggal_awal_siang_for_table_presensi' => $tanggal_awal_siang_for_table_presensi,
		  			// 'tanggal_akhir_siang_for_table_presensi' => $tanggal_akhir_siang_for_table_presensi,
	  			]);
  			
  		// }

  	}

  	public function getPengiriman($karyawan_id,$tanggal_awal,$tanggal_akhir,$pay_id = 0){
  		// generate tanggal
  		$arr_tgl_awal = explode('-',$tanggal_awal);
  		$arr_tgl_akhir = explode('-',$tanggal_akhir);
		$awal = new \DateTime();
		$akhir = new \DateTime();
		$awal->setDate($arr_tgl_awal[2],$arr_tgl_awal[1],$arr_tgl_awal[0]);
		$akhir->setDate($arr_tgl_akhir[2],$arr_tgl_akhir[1],$arr_tgl_akhir[0]);
  		$data = \DB::table('view_new_pengiriman')
  					->select('*',\DB::raw('sum(qty) as sum_rit'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'))
  					->whereBetween('order_date',[$awal,$akhir])
  					->whereKaryawanId($karyawan_id)
  					->whereState('done')
  					->groupBy('pekerjaan_id')
  					->groupBy('material_id')
  					->groupBy('kalkulasi')
  					->get();

  		if(count($data) > 0){
	  		\DB::transaction(function()use($karyawan_id,$awal,$akhir,$pay_id,$data){

	  			// delete data lama , hanya untuk mode development saja
	  			\DB::table('payroll_pengiriman_driver')
	  					->where('payroll_driver_id',$pay_id)
	  					->delete();
	  			\DB::table('payroll_driver_detail')
	  					->where('payroll_driver_id',$pay_id)
	  					->delete();

		  		// insert data pengiriman ke payrol_driver_detail
		  		\DB::select("insert into payroll_pengiriman_driver (payroll_driver_id, new_pengiriman_id) select " . $pay_id . ",id from new_pengiriman
								where order_date between '" . $awal->format('Y-m-d') . "' and '" . $akhir->format('Y-m-d') . "'
								and karyawan_id = " . $karyawan_id . "
								and new_pengiriman.state  = 'done'");

		  		// insert data payroll_driver_detail
		  	// 	\DB::select("insert into payroll_driver_detail (payroll_driver_id,material_id,kalkulasi,volume,netto,qty)
					// select " . $pay_id . ",material_id,kalkulasi,sum(volume) as volume, sum(netto) as netto,sum(qty) as qty 
					// from new_pengiriman
					// where order_date between '" . $awal->format('Y-m-d') . "' and '" . $akhir->format('Y-m-d') . "'
					// and karyawan_id = " . $karyawan_id . "
					// group by material_id, kalkulasi");

		  		foreach($data as $dt){
		  			\DB::table('payroll_driver_detail')
		  					->insert([
		  						'payroll_driver_id'=>$pay_id,
		  						'pekerjaan_id'=>$dt->pekerjaan_id,
		  						'material_id'=>$dt->material_id,
		  						'kalkulasi'=>$dt->kalkulasi,
		  						'volume'=>$dt->sum_vol,
		  						'netto'=>$dt->sum_net,
		  						'qty'=>$dt->sum_rit
		  					]);
		  		}

		  		// generate payroll number
		        $counter = \DB::table('appsetting')->whereName('payroll_counter')->first()->value;
		        $prefix = \DB::table('appsetting')->whereName('payroll_prefix')->first()->value;
		        $payroll_number  = $prefix . '/'.date('Y').'/'.date('m').$counter++;
		        // update counter
		        \DB::table('appsetting')
		        	->whereName('payroll_counter')
		        	->update(['value'=>$counter]);
		        // update pay number
		        \DB::table('payroll_driver')
		        	->whereId($pay_id)
		        	->update([
		        			'payroll_number' => $payroll_number,
		        			'state' => 'open'
		        		]);
	  		});  			
  		}
  		
  		return json_encode($data);

  	}


}
