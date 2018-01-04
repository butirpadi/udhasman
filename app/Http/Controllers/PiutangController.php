<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PiutangController extends Controller
{
	public function index(){
        $data = \DB::table('view_piutang')
                ->orderBy('tanggal','desc')
                ->get();
        $sum_jumlah = \DB::table('piutang')
                        ->where('state','!=','paid')
                        ->sum('jumlah');

        return view('piutang.index',[
                'data' => $data,
                'sum_jumlah' => $sum_jumlah
        ]);
	}

    public function edit($id){
        $data = \DB::table('view_piutang')
                ->find($id);

        $karyawans = \DB::table('karyawan')
                    ->get();
        $karyawanArr = [];
        foreach($karyawans as $dt){
            $karyawanArr[$dt->id] = $dt->nama . ' - ' . $dt->kode;
        }

        $payments = \DB::table('piutang_payment')
                        ->select('*',\DB::raw("date_format(tanggal,'%d-%m-%Y') as tanggal_format"))
                        ->where('piutang_id',$id)
                        ->get();

        return view('piutang.edit',[
            'data' => $data,
            'karyawans' => $karyawanArr,
            'payments' => $payments,
        ]);
    }

    public function update(Request $req){
        // generate tanggal
        $arr_tgl = explode('-',$req->tanggal);
        $tgl = new \DateTime();
        $tgl->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

        \DB::table('piutang')
                ->where('id',$req->original_id)
                ->update([
                    'tanggal' => $tgl,
                    'type' => $req->type,
                    'karyawan_id' => $req->karyawan,
                    'desc' => $req->desc,
                    'jumlah' => str_replace(',', '', str_replace('.00','',$req->jumlah)),
                    'amount_due' => str_replace(',', '', str_replace('.00','',$req->jumlah)),
                ]);

        return redirect('finance/piutang/edit/'.$req->original_id);
    }

    public function create(){
        $karyawans = \DB::table('karyawan')
                    ->get();
        $karyawanArr = [];
        foreach($karyawans as $dt){
            $karyawanArr[$dt->id] = $dt->nama . ' - ' . $dt->kode;
        }

        return view('piutang.create',[
            'karyawans' => $karyawanArr
        ]);
    }

    public function insert(Request $req){
        $id = $this->insertPiutang($req->tanggal,$req->type,'draft',null,null,$req->karyawan,$req->desc, $req->jumlah);
        return redirect('finance/piutang/edit/' . $id);
    }



	public function insertPiutang($tanggal, $type, $state, $source=null, $soId=null, $karyawanId=null, $desc, $jumlah){
		// generate nomor hutang
        $prefix = Appsetting('piutang_prefix');
        $counter = Appsetting('piutang_counter') + 1;
        UpdateAppsetting('piutang_counter',$counter);
        // add leading zero
        if(strlen($counter) == 1){
        	$counter = '0'.$counter;
        }
        $nomor_inv = $prefix.'/'.date('Y/m').$counter;

        // generate tanggal
        $arr_tgl = explode('-',$tanggal);
        $tgl = new \DateTime();
        $tgl->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

        // set desc
        if($type == 'pk'){
            $karyawan = \DB::table('karyawan')->find($karyawanId);
            $desc = $karyawan->nama . ' - ' . $karyawan->kode;
        }

        $id = \DB::table('piutang')
        	->insertGetId([
        		'name' => $nomor_inv,
        		'tanggal' => $tgl,
        		'type' => $type,
        		'state' => $state,
        		'source' => $source,
        		'so_id' => $soId,
        		'karyawan_id' => $karyawanId,
                'desc' => $desc,
                'jumlah' => str_replace(',', '', str_replace('.00','',$jumlah)),
        		'amount_due' => str_replace(',', '', str_replace('.00','',$jumlah)),
        	]);

        return $id;
	}

    public function toDelete($id){
        \DB::table('piutang')->delete($id);

        return redirect('finance/piutang');
    }

    public function toConfirm($id){
        \DB::table('piutang')
            ->where('id',$id)
            ->update([
                'state' => 'open'
            ]);
        return redirect('finance/piutang/edit/'.$id);
    }

    public function toCancel($id){
        $piutang = \DB::table('piutang')
                        ->find($id);
        if($piutang->type == 'so'){
            // delete piutang dan 
            \DB::table('piutang')
                    ->delete($id);
            // set open penjualan/pengiriman
            \DB::table('new_pengiriman')
                ->where('id',$piutang->so_id)
                ->update([
                    'state' => 'open',
                    'invoice_state' => null
                ]);

            return redirect('finance/piutang');
        }else{
            // set piutang state to draft
            \DB::table('piutang')
                ->where('id',$id)
                ->update([
                    'state' => 'draft'
                ]);

            return redirect('finance/piutang/edit/'.$id);
        }

    }

    public function toPay($id){
        $data = \DB::table('view_piutang')
                ->find($id);

        return view('piutang.pay',[
            'data' => $data
        ]);
    }

    public function addPay(Request $req){
        \DB::transaction(function()use($req){
            $piutang = \DB::table('piutang')->find($req->piutang_id);

            // generate tanggal
            $arr_tgl = explode('-',$req->tanggal);
            $tgl = new \DateTime();
            $tgl->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

            // generate payment number
            $prefix = Appsetting('payment_in_prefix');
            $counter = Appsetting('payment_in_counter');
            $ref = $prefix.'/'.date('Y/m').$counter++;
            UpdateAppsetting('payment_in_counter',$counter);

            $jumlah_bayar = str_replace(',', '', str_replace('.00','',$req->jumlah_bayar));

            $id = \DB::table('piutang_payment')
                ->insertGetId([
                    'name' => $ref,
                    'piutang_id' => $req->piutang_id,
                    'jumlah' => $jumlah_bayar,
                    'tanggal' => $tgl,
                ]);

            // update amount due piutang
            \DB::table('piutang')
                ->where('id',$req->piutang_id)
                ->update([
                    'amount_due' => $piutang->amount_due - $jumlah_bayar,
                    'state' => $piutang->amount_due - $jumlah_bayar == 0 ? 'paid' : 'open'
                ]);

            // update invoice_state
            if($piutang->type == 'so' && $piutang->amount_due - $jumlah_bayar == 0){
                \DB::table('new_pengiriman')
                    ->where('id',$piutang->so_id)
                    ->update([
                        'invoice_state' => 'paid'
                    ]);
            }
            
        });
        return redirect('finance/piutang/edit/'.$req->piutang_id);
    }

    public function getPaymentInfo($paymentId){
        $data = \DB::table('view_piutang_payment')->find($paymentId);
        return view('piutang.payment-pop',[
            'data' => $data
        ]);
    }

    public function delPayment($paymentId){
        $payment = \DB::table('piutang_payment')->find($paymentId);
        $piutang = \DB::table('piutang')->find($payment->piutang_id);
        \DB::transaction(function()use(&$piutang,&$payment){
            // delte payment
            \DB::table('piutang_payment')->delete($payment->id);
            // set piutang state to open
            // recalculate payment amount
            \DB::table('piutang')
            ->where('id',$piutang->id)
            ->update([
                'state' => 'open',
                'amount_due' => $piutang->amount_due + $payment->jumlah
            ]);
        });

        return redirect('finance/piutang/edit/'.$piutang->id);
    }



}
