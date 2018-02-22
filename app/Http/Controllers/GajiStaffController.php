<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GajiStaffController extends Controller
{
	public function index(){
		$data = \DB::table('view_gaji_staff')
					->orderBy('tanggal_gaji','desc')
					->paginate(Appsetting('paging_item_number'));
		
		return view('gaji.gaji_staff.index',[
				'data' => $data
			]);
	}

	public function create(){
		$partners = \DB::table('res_partner')
					->whereStaff('Y')
					->get();
		$selectPartner = [];
		foreach($partners as $dt){
			$selectPartner[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}
		return view('gaji.gaji_staff.create',[
			'partners' => $selectPartner
		]);
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

			// cek data tersedia
			$ketemu = \DB::table('gaji_staff')
						->where('tanggal_gaji',$tanggal_gaji->format('Y-m-d'))
						->where('partner_id', $req->partner)
						->first();

			if($ketemu){
				$id = $ketemu->id;
			}else{
				$partner = \DB::table('res_partner')
								->find($req->partner);

		        // get kehadiran
		        $pagi = \DB::table('presensi')
				        	->whereKaryawanId($req->partner)
				        	->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
				        	->wherePagi('Y')
				        	->count('id');
				$siang = \DB::table('presensi')
				        	->whereKaryawanId($req->partner)
				        	->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
				        	->whereSiang('Y')
				        	->count('id');

				// hitung gaji
				$jumlah = $partner->gaji_pokok * ($pagi+$siang) / 2;

				// insert ke table gaji staff
		        $id  = \DB::table('gaji_staff')
		        	->insertGetId([
		        		'tanggal_gaji' => $tanggal_gaji,
		        		'partner_id' => $req->partner,
		        		'tanggal_awal' => $tanggal_awal,
		        		'tanggal_akhir' => $tanggal_akhir,
		        		'bulan' => $req->bulan,
		        		'masuk_pagi' => $pagi,
		        		'masuk_siang' => $siang,
		        		'state' => 'draft',
		        		'jumlah' => $jumlah,
		        		'gaji_nett' => $jumlah,
		        		'amount_due' => $jumlah,
		        	]);
				
			}

	        return redirect('gaji/gaji-staff/edit/'.$id);
			
		});
	}

	public function edit($id){
		$data = \DB::table('view_gaji_staff')
				->find($id);
		$data->partner_data = \DB::table('res_partner')->find($data->partner_id);
		$data->payments = \DB::table('view_gaji_staff_payment')
								->where('gaji_staff_id',$id)
								->get();

		$partners = \DB::table('res_partner')
					->whereStaff('Y')
					->get();
		$selectPartner = [];
		foreach($partners as $dt){
			$selectPartner[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}

		$piutang = \DB::table('piutang')
					->where('partner_id',$data->partner_id)
					->sum('amount_due');

		return view('gaji.gaji_staff.edit',[
			'data' => $data,
			'partners' => $selectPartner,
			'piutang' => $piutang
		]);
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			// update gaji staff
			\DB::table('gaji_staff')
				->where('id',$req->gaji_staff_id)
				->update([
					'potongan_bahan' => $req->potongan_bahan,
					'gaji_nett' => $req->gaji_nett,
					'jumlah' => $req->jumlah,
					'amount_due' => $req->gaji_nett,
				]);

			$updateData = json_decode($req->update_data);
			foreach($updateData as $dt){
				\DB::table('gaji_staff_detail')
					->whereId($dt->gaji_staff_detail_id)
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
	        
			\DB::table('gaji_staff')
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
			\DB::table('gaji_staff')
				->delete($id);

			return redirect('gaji/gaji-staff');
		});
	}

	public function toValidate($id){
		return \DB::transaction(function()use($id){
			\DB::table('gaji_staff')
				->where('id',$id)
				->update([
					'state' => 'paid',
					'amount_due' => 0
				]);

			return redirect()->back();
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
			$paymentId = \DB::table('gaji_staff_payment')
				->insertGetId([
					'name' => $dp_number,
					'gaji_staff_id' => $pay->gaji_staff_id,
					'jumlah' => $pay->jumlah,
					'tanggal' => date('Y-m-d'),
				]);

			// update amount due
			\DB::table('gaji_staff')
				->where('id',$pay->gaji_staff_id)
				->update([
					'amount_due' => \DB::raw('amount_due - ' . $pay->jumlah ),
					'dp' => 'Y',
					'state' => \DB::raw("case when amount_due = 0 then 'paid' else 'open' end ")
				]);

			// update amount due on payment table
			\DB::table('gaji_staff_payment')
				->whereId($paymentId)
				->update([
					'amount_due' => \DB::table('gaji_staff')->find($pay->gaji_staff_id)->amount_due
				]);

			return redirect()->back();
			
		});
	}

	public function getPaymentInfo($paymentId){
        $data = \DB::table('view_gaji_staff_payment')->find($paymentId);
        return view('gaji.gaji_staff.payment-pop',[
            'data' => $data
        ]);
    }

    public function deleteDP($paymentId){
    	return \DB::transaction(function()use($paymentId){
    		// get payment 
    		$payment = \DB::table('gaji_staff_payment')
    					->find($paymentId);
    		// delete payment
    		\DB::table('gaji_staff_payment')
    			->delete($paymentId);
    		// update amount due
    		\DB::table('gaji_staff')
    			->where('id',$payment->gaji_staff_id)
    			->update([
    				'state' => 'open',
    				'amount_due' => \DB::raw('amount_due + ' . $payment->jumlah),
    				'dp' => \DB::raw("case when amount_due = gaji_nett then 'N' else 'Y' end")
    			]);

    		return redirect()->back();

    	}); 
    }

    public function paymentToPrint($paymentId){
    	$payment = \DB::table('gaji_staff_payment')
    				->find($paymentId);

    	$paymentBefore = \DB::table('view_gaji_staff_payment')
    						->where('tanggal','<',$payment->tanggal)
    						->get();

    	$data = \DB::table('view_gaji_staff')
				->find($payment->gaji_staff_id);

		$data->partner_data = \DB::table('res_partner')->find($data->partner_id);


		$pdf = \App::make('snappy.pdf.wrapper');
		$pdf->setOption('margin-top', 15);
		$pdf->setOption('margin-bottom', 10);
		$pdf->setOption('margin-left', 10);
		$pdf->setOption('margin-right', 10);
		$pdf->loadHTML(view('gaji.gaji_staff.pdf',[
			'data' => $data,
			'dp' => $payment->jumlah,
			'amount_due' => $payment->amount_due,
			'paymentBefore' => $paymentBefore
		]));
		return $pdf->inline();
    }

    public function printPdf($id){
		$data = \DB::table('view_gaji_staff')
				->find($id);
		$data->partner_data = \DB::table('res_partner')->find($data->partner_id);


		$pdf = \App::make('snappy.pdf.wrapper');
		$pdf->setOption('margin-top', 15);
		$pdf->setOption('margin-bottom', 10);
		$pdf->setOption('margin-left', 10);
		$pdf->setOption('margin-right', 10);
		$pdf->loadHTML(view('gaji.gaji_staff.pdf',[
			'data' => $data,
			'dp' => $data->gaji_nett,
			'amount_due' => 0,
		]));
		return $pdf->inline();
	}




}
