<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HutangController extends Controller
{
	public function index(){
		$data = \DB::table('view_hutang')
				->orderBy('created_at','desc')
				->get();
		$sum_jumlah = \DB::table('view_hutang')
						->where('state','!=','paid')
						->sum('jumlah');
		return view('hutang.index',[
				'data' => $data,
				'sum_jumlah' => $sum_jumlah,
			]);
	}

	public function create(){
		return view('hutang.create');
	}

	public function insert(Request $req){
		// generate nomor hutang
        $prefix = Appsetting('hutang_prefix');
        $counter = Appsetting('hutang_counter');
        $nomor_inv = $prefix.'/'.date('Y/m').$counter++;
        UpdateAppsetting('hutang_counter',$counter);

        // generate tanggal
        $arr_tgl = explode('-',$req->tanggal);
        $tanggal = new \DateTime();
        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);


		$id = \DB::table('hutang')->insertGetId([
			'name' => $nomor_inv,
			'tanggal' => $tanggal,
			'desc' => $req->desc,
			'type' => 'lain',
			'state' => 'draft',
			'jumlah' => str_replace(',', '', str_replace('.00','',$req->jumlah))
		]);

		return redirect('finance/hutang/edit/'.$id);
	}

	public function edit($id){
		$data = \DB::table('view_hutang')->find($id);

		// if($data->state == 'V' || $data->state == 'P'){
		// 	if($data->type == 'pembelian'){
		// 		$data->po = \DB::table('view_pembelian')
		// 					->where('ref',$data->source)
		// 					->first();
		// 	}

		// 	// get data payment
		// 	$payments = \DB::table('view_hutang_payment')
		// 					->where('hutang_id',$id)
		// 					->get();

		// 	return view('hutang.validated',[
		// 		'data' => $data,
		// 		'payments' => $payments,
		// 	]);
			
		// }else{
		// 	return view('hutang.edit',[
		// 		'data' => $data
		// 	]);			
		// }

		if($data->type == 'pembelian'){
				$data->po = \DB::table('view_pembelian')
							->where('ref',$data->source)
							->first();
			}


		// get data payment
		$payments = \DB::table('view_hutang_payment')
						->where('hutang_id',$id)
						->get();
		return view('hutang.edit',[
				'data' => $data,
				'payments' => $payments,
			]);
	}

	public function update(Request $req){
		// generate tanggal
        $arr_tgl = explode('-',$req->tanggal);
        $tanggal = new \DateTime();
        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

        $data = \DB::table('hutang')->find($req->original_id);

        if($data->type == 'lain'){
			\DB::table('hutang')
						->where('id',$req->original_id)
						->update([
							'tanggal' => $tanggal,
							'desc' => $req->desc,
							'jumlah' => str_replace(',', '', str_replace('.00','',$req->jumlah))
						]);        	
        }

		return redirect('finance/hutang');
	}

	public function showPo($hutangId,$id){
		$data = \DB::table('view_pembelian')
					->find($id);
		$data->detail = \DB::table('view_pembelian_detail')->wherePembelianId($id)->get();
		$data->hutang = \DB::table('view_hutang')->find($hutangId);
		return view('hutang.show-po',[
			'data' => $data
		]);
	}

	public function validateHutang($id){
		\DB::table('hutang')
			->where('id',$id)
			->update([
				'state' => 'V'
			]);

		return redirect('finance/hutang/edit/'.$id);
	}

	public function regPayment($id){
		$data = \DB::table('view_hutang')->find($id);

		return view('hutang.reg-payment',[
			'data' => $data
		]);
	}

	public function insertPayment(Request $req){
		\DB::transaction(function()use($req){
			$arr_tgl = explode('-',$req->tanggal);
	        $tanggal = new \DateTime();
        	$tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			// Generate Payment Reference
			$prefix = Appsetting('payment_out_prefix');
	        $counter = Appsetting('payment_out_counter');
	        $ref = $prefix.'/'.date('Y/m').$counter++;
	        UpdateAppsetting('payment_out_counter',$counter);

			\DB::table('hutang_payment')
					->insert([
						'hutang_id' => $req->hutang_id,
						'tanggal' => $tanggal,
						'name' => $ref,
						'jumlah' => str_replace(',', '', str_replace('.00','',$req->jumlah_bayar)),
					]);			
			// update payment amount
			$payment_amount = \DB::table('hutang')->find($req->hutang_id)->payment_amount;
			\DB::table('hutang')
					->where('id',$req->hutang_id)
					->update([
						'payment_amount' => $payment_amount - str_replace(',', '', str_replace('.00','',$req->jumlah_bayar))
					]);
			// set paid
			if($payment_amount - str_replace(',', '', str_replace('.00','',$req->jumlah_bayar)) == 0){
				\DB::table('hutang')
					->where('id',$req->hutang_id)
					->update([
						'state' => 'paid'
					]);
			}
		});

		return redirect('finance/hutang/edit/'.$req->hutang_id);

	}

	public function getPaymentInfo($paymentId){
		$data = \DB::table('view_hutang_payment')->find($paymentId);
		return view('hutang.payment-pop',[
			'data' => $data
		]);
	}

	public function delPayment($paymentId){
		$payment = \DB::table('hutang_payment')->find($paymentId);
		$hutang = \DB::table('hutang')->find($payment->hutang_id);
		\DB::transaction(function()use(&$hutang,&$payment){
			\DB::table('hutang_payment')->delete($payment->id);
			// set hutang state to open
			// recalculate payment amount
			\DB::table('hutang')
			->where('id',$hutang->id)
			->update([
				'state' => 'open',
				'payment_amount' => $hutang->payment_amount + $payment->jumlah
			]);
		});

		return redirect('finance/hutang/edit/'.$hutang->id);
	}

	public function cancelHutang($hutangId){
		$hutang = \DB::table('view_hutang')->find($hutangId);
		$payment_count = \DB::table('hutang_payment')->where('hutang_id',$hutangId)->count();
		\DB::transaction(function()use($hutang,$payment_count){
			if($payment_count == 0){
				// cek apakah jenis hutang pembelian
				if($hutang->type == 'pembelian'){
					// delete hutang
					\DB::table('hutang')->delete($hutang->id);
					// set state pembelian to open
					$pembelian = \DB::table('pembelian')->where('ref',$hutang->source)->first();
					\DB::table('pembelian')
						->where('id',$pembelian->id)
						->update([
							'status' => 'OPEN',
							'bill_state' => NULL
						]);
				}else{
					// delete hutang
					\DB::table('hutang')->delete($hutang->id);
				}
			}
		});

		return redirect('finance/hutang');
	}


	public function toDelete($id){
		\DB::table('hutang')->delete($id);
		return redirect('finance/hutang');
	}

	public function toConfirm($id){
		\DB::table('hutang')
			->whereId($id)
			->update([
				'state' => 'open'
			]);

		return redirect('finance/hutang/edit/'.$id);
	}

	public function toCancel($id){
		$hutang = \DB::table('hutang')->find($id);
		$url = '';

		\DB::transaction(function()use($id,$hutang,&$url){
			if($hutang->type == 'pembelian'){
				$pembelian = \DB::table('pembelian')->find($hutang->po_id);
				
				// delete hutang
				\DB::table('hutang')->delete($id);

				\DB::table('pembelian')
					->whereId($hutang->po_id)
					->update([
							'status' => 'OPEN',
							'bill_state' => null
						]);

				$url = 'finance/hutang';
				
			}else{
				\DB::table('hutang')
					->whereId($id)
					->update([
						'state' => 'draft'
					]);

				$url = 'finance/hutang/edit/'.$id;
			}
		});

		return redirect($url);
	}


}
