<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GajiDriverController extends Controller
{
	public function index(){
		$data = \DB::table('view_gaji_driver')
					->orderBy('tanggal_gaji','desc')
					->paginate(Appsetting('paging_item_number'));
		// 		// ->select('alat.*',\DB::raw('(select count(id) from karyawan where karyawan.alat_id = alat.id) as ref'))
		// 		->orderBy('created_at','desc')
		// 		->get();
		return view('gaji.gaji_driver.index',[
				'data' => $data
			]);
	}

	public function create(){
		$partners = \DB::table('res_partner')
					->whereDriver('Y')
					->get();
		$selectPartner = [];
		foreach($partners as $dt){
			$selectPartner[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}
		return view('gaji.gaji_driver.create',[
			'partners' => $selectPartner
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
				array_push($select_pay_day, ['tanggal' => $i, 
											 'tanggal_format' => (strlen($i) == 1 ? '0'.$i:$i) .'-'.$arr_tgl[1].'-'.$arr_tgl[2],
											 'tanggal_full' => $hari .', ' . (strlen($i) == 1 ? '0'.$i:$i) .'-'.$arr_tgl[1].'-'.$arr_tgl[2]
											] );
			}
		}

		echo json_encode($select_pay_day);

	}

	public function save(Request $req){
		return \DB::transaction(function()use($req){
			// generate tanggal
			$tanggal = $req->tanggal;
	        $arr_tgl = explode('-',$tanggal);
			$tanggal_gaji = new \DateTime();
			$tanggal_awal = new \DateTime();
			$tanggal_akhir = new \DateTime();
			$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
			$tanggal_awal->modify('-7 day');
			$tanggal_akhir->modify('-1 day');

			// insert ke table gaji driver
	        $id  = \DB::table('gaji_driver')
	        	->insertGetId([
	        		'tanggal_gaji' => $tanggal_gaji,
	        		'partner_id' => $req->partner,
	        		'tanggal_awal' => $tanggal_awal,
	        		'tanggal_akhir' => $tanggal_akhir,
	        		'bulan' => $req->bulan,
	        		'state' => 'draft'
	        	]);

	        // insert ke table gaji driver detail
	        $data_pengiriman = \DB::select("insert into gaji_driver_detail (gaji_driver_id,material_id,pekerjaan_id,kalkulasi,volume,netto, rit) select 
	        	?, material_id,pekerjaan_id,kalkulasi,sum(volume)as sum_vol,sum(netto) as sum_net,sum(qty) as sum_rit
											from new_pengiriman
											where karyawan_id = ?
											and order_date between ? and ?
											group by material_id,pekerjaan_id,kalkulasi",[$id,$req->partner, $tanggal_awal,$tanggal_akhir]);


	        return redirect('gaji/gaji-driver/edit/'.$id);
			
		});
	}

	public function edit($id){
		$data = \DB::table('view_gaji_driver')
				->find($id);

		$data->detail = \DB::table('view_gaji_driver_detail')
							->where('gaji_driver_id',$id)
							->get();

		$data->payments = \DB::table('view_gaji_driver_payment')
								->where('gaji_driver_id',$id)
								->get();

		$partners = \DB::table('res_partner')
					->whereDriver('Y')
					->get();
		$selectPartner = [];
		foreach($partners as $dt){
			$selectPartner[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}

		$piutang = \DB::table('piutang')
					->where('partner_id',$data->partner_id)
					->sum('amount_due');

		return view('gaji.gaji_driver.edit',[
			'data' => $data,
			'partners' => $selectPartner,
			'piutang' => $piutang
		]);
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			// update gaji driver
			\DB::table('gaji_driver')
				->where('id',$req->gaji_driver_id)
				->update([
					'potongan_bahan' => $req->potongan_bahan,
					'gaji_nett' => $req->gaji_nett,
					'jumlah' => $req->jumlah,
					'amount_due' => $req->gaji_nett,
				]);

			$updateData = json_decode($req->update_data);
			foreach($updateData as $dt){
				\DB::table('gaji_driver_detail')
					->whereId($dt->gaji_driver_detail_id)
					->update([
						'harga' => $dt->harga,
						'jumlah' => \DB::raw("case when kalkulasi = 'rit' then rit * harga when kalkulasi = 'kubik' then volume * harga else netto * harga end")
					]);
			}

			return redirect()->back();

		});

	}

	public function confirm($id){
		return \DB::transaction(function()use($id){
			// generate payroll number
	        $counter = \DB::table('appsetting')->whereName('payroll_counter')->first()->value;
	        $prefix = \DB::table('appsetting')->whereName('payroll_prefix')->first()->value;
	        $payroll_number  = $prefix . '/'.date('Y').'/'.date('m').$counter++;
	        // update counter
	        \DB::table('appsetting')
	        	->whereName('payroll_counter')
	        	->update(['value'=>$counter]);
	        
			\DB::table('gaji_driver')
				->whereId($id)
				->update([
						'name' => $payroll_number,
						'state'=>'open'
					]);

			return redirect()->back();
			
		});
	}

	public function delete($id){
		return \DB::transaction(function()use($id){
			\DB::table('gaji_driver_detail')
				->where('gaji_driver_id',$id)
				->delete();
			\DB::table('gaji_driver')
				->delete($id);

			return redirect('gaji/gaji-driver');
		});
	}

	public function savePayment(Request $req){
		return \DB::transaction(function()use($req){
			$pay = json_decode($req->payment);

			// generate pay number
	        $counter = \DB::table('appsetting')->whereName('dp_counter')->first()->value;
	        $counter = strlen($counter) == 1 ? '0'.$counter : $counter;
	        $prefix = \DB::table('appsetting')->whereName('dp_prefix')->first()->value;
	        $dp_number  = $prefix . '/'.date('Y').'/'.date('m').$counter;
	        // update counter
	        \DB::table('appsetting')
	        	->whereName('dp_counter')
	        	->update(['value'=>$counter+1]);

	       	// insert payment
			$paymentId = \DB::table('gaji_driver_payment')
				->insertGetId([
					'name' => $dp_number,
					'gaji_driver_id' => $pay->gaji_driver_id,
					'jumlah' => $pay->jumlah,
					'tanggal' => date('Y-m-d'),
				]);

			// update amount due
			\DB::table('gaji_driver')
				->where('id',$pay->gaji_driver_id)
				->update([
					'amount_due' => \DB::raw('amount_due - ' . $pay->jumlah ),
					'dp' => 'Y',
					'state' => \DB::raw("case when amount_due = 0 then 'paid' else 'open' end ")
				]);

			// update amount due on payment table
			\DB::table('gaji_driver_payment')
				->whereId($paymentId)
				->update([
					'amount_due' => \DB::table('gaji_driver')->find($pay->gaji_driver_id)->amount_due
				]);

			return redirect()->back();
			
		});
	}

	public function toValidate($id){
		return \DB::transaction(function()use($id){
			\DB::table('gaji_driver')
				->where('id',$id)
				->update([
					'state' => 'paid',
					'amount_due' => 0
				]);

			return redirect()->back();
		});
	}

	public function printPdf($id){
		$data = \DB::table('view_gaji_driver')
				->find($id);

		$data->detail = \DB::table('view_gaji_driver_detail')
							->where('gaji_driver_id',$id)
							->get();

		$pdf = \App::make('snappy.pdf.wrapper');
		$pdf->setOption('margin-top', 15);
		$pdf->setOption('margin-bottom', 10);
		$pdf->setOption('margin-left', 10);
		$pdf->setOption('margin-right', 10);
		$pdf->loadHTML(view('gaji.gaji_driver.pdf',[
			'data' => $data,
			'dp' => $data->gaji_nett,
			'amount_due' => 0,
		]));
		return $pdf->inline();

		// return view('gaji.gaji_driver.pdf',[
		// 	'data' => $data
		// ]);
	}

	public function getPaymentInfo($paymentId){
        $data = \DB::table('view_gaji_driver_payment')->find($paymentId);
        return view('gaji.gaji_driver.payment-pop',[
            'data' => $data
        ]);
    }

    public function deleteDP($paymentId){
    	return \DB::transaction(function()use($paymentId){
    		// get payment 
    		$payment = \DB::table('gaji_driver_payment')
    					->find($paymentId);
    		// delete payment
    		\DB::table('gaji_driver_payment')
    			->delete($paymentId);
    		// update amount due
    		\DB::table('gaji_driver')
    			->where('id',$payment->gaji_driver_id)
    			->update([
    				'state' => 'open',
    				'amount_due' => \DB::raw('amount_due + ' . $payment->jumlah),
    				'dp' => \DB::raw("case when amount_due = gaji_nett then 'N' else 'Y' end")
    			]);

    		return redirect()->back();

    	}); 
    }

    public function toCancel($id){
    	return \DB::transaction(function()use($id){

    		// delete payment
    		\DB::table('gaji_driver_payment')
    			->whereGajiDriverId($id)
    			->delete();

    		// delete detail
    		\DB::table('gaji_driver_detail')
				->whereGajiDriverId($id)
    			->update([
    				'harga' => 0,
    				'jumlah' => 0,
    			]);    		

    		// clear data
    		\DB::table('gaji_driver')
    			->whereId($id)
    			->update([
    				'state' => 'draft',
    				'jumlah' => 0,
    				'potongan_bahan' => 0,
    				'gaji_nett' => 0,
    				'amount_due' => 0,
    				'dp' => 'N',
    				'name' => null,
    			]);

    		return redirect()->back();

    	});
    }

    public function paymentToPrint($paymentId){
    	$payment = \DB::table('gaji_driver_payment')
    				->find($paymentId);

    	$paymentBefore = \DB::table('view_gaji_driver_payment')
    						->where('tanggal','<',$payment->tanggal)
    						->get();

    	$data = \DB::table('view_gaji_driver')
				->find($payment->gaji_driver_id);

		$data->detail = \DB::table('view_gaji_driver_detail')
							->where('gaji_driver_id',$payment->gaji_driver_id)
							->get();

		$pdf = \App::make('snappy.pdf.wrapper');
		$pdf->setOption('margin-top', 15);
		$pdf->setOption('margin-bottom', 10);
		$pdf->setOption('margin-left', 10);
		$pdf->setOption('margin-right', 10);
		$pdf->loadHTML(view('gaji.gaji_driver.pdf',[
			'data' => $data,
			'dp' => $payment->jumlah,
			'amount_due' => $payment->amount_due,
			'paymentBefore' => $paymentBefore
		]));
		return $pdf->inline();
    }

    public function getSearch(){
    	$val = \Input::get('val');						

		$data = \DB::table('view_gaji_driver')
					->where('name','like','%' . trim($val) . '%')
					->orWhere('partner','like','%' . $val . '%')
					->orWhere('kode_partner','like','%' . $val . '%')
					->orWhere('state','like','%' . $val . '%')
					->orderBy('tanggal_gaji','desc')
					->paginate(Appsetting('paging_item_number'));

		$data->appends(['val'=>$val]);

		return view('gaji.gaji_driver.search',[
				'data' => $data,     
				'search_val' => $val
			]);
    }

    public function filter($filterby,$val){
		$data = \DB::table('view_gaji_driver')
		->where($filterby,$val)
		->orderBy('tanggal_gaji','desc')
		->paginate(Appsetting('paging_item_number'));

		return view('gaji.gaji_driver.filter',[
				'data' => $data,
				'filterby' => $filterby,
				'filter' => $val,
			]);
	}

	public function groupby($val){
		$data = \DB::table('view_gaji_driver')
		->select('*',\DB::raw('count(id) as jumlah'))
		->groupBy($val)
		->orderBy('tanggal_gaji')
		->get();			

		return view('gaji.gaji_driver.group',[
				'data' => $data,
				'group' => $val
			]);
		
	}

	public function groupdetail($groupby,$id){
		if($id != 0){
			$data = \DB::table('view_gaji_driver')
			->where($groupby,'=',$id)
			->orderBy('tanggal_gaji')
			->get();			
		}else{
			// $pengiriman = \DB::table('view_gaji_driver')
			// ->where($groupby.'_id')
			// ->orderBy('tanggal_gai')
			// ->get();			
		}

		echo json_encode($data);
	}


}
