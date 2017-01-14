<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class BillController extends Controller
{
	public function index(){
		$data = \DB::table('view_bill_pembelian')
				->whereIsDeleted('N')
				->orderBy('tanggal','desc')
				->get();
		$total_amount_due = \DB::table('view_bill_pembelian')
							->whereIsDeleted('N')
							->sum('amount_due');
		return view('bill.index',[
				'data' => $data,
				'total_amount_due' => $total_amount_due
			]);
	}

	public function edit($id){
		$data_bill = \DB::table('view_bill_pembelian')->find($id);

		$data_pembelian = \DB::table('view_pembelian')->find($data_bill->pembelian_id);
		$data_pembelian->detail = \DB::table('view_pembelian_detail')->wherePembelianId($data_bill->pembelian_id)->get();

		$data_pembayaran = \DB::table('bill_pembelian_payment')
							->where('bill_pembelian_id',$id)
							->select('bill_pembelian_payment.*',\DB::raw('date_format(bill_pembelian_payment.tanggal,"%d-%m-%Y") as tanggal_format'))
							->get();
		$total_pembayaran = \DB::table('bill_pembelian_payment')->where('bill_pembelian_id',$id)->sum('total');


		return view('bill.edit',[
				'data_bill' => $data_bill,
				'data_pembelian' => $data_pembelian,
				'data_pembayaran' => $data_pembayaran,
				'total_pembayaran' => $total_pembayaran
			]);
	}

	public function registerPembayaran($bill_id){
		$data_bill = \DB::table('view_bill_pembelian')->find($bill_id);

		return view('bill.register-pembayaran',[
				'data_bill' => $data_bill
			]);
	}

	public function saveRegisterPembayaran(Request $req){
		// GENERATE TANGGAL
        $arr_tgl = explode('-',$req->tanggal);
        $tanggal = new \DateTime();
        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

		\DB::table('bill_pembelian_payment')->insert([
				'tanggal' => $tanggal,
				'bill_pembelian_id' => $req->bill_id,
				'total' => $req->jumlah_bayar,
				'user_id' => \Auth::user()->id,
			]);

		return redirect('billinvoice/bill-pembelian/edit/'.$req->bill_id);
	}


}
