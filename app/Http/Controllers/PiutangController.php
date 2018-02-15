<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;

class PiutangController extends Controller
{
	public function index(){
        $data = \DB::table('view_piutang')
                // ->where('state','!=','paid')
                ->orderBy('tanggal','desc')
                ->orderBy('id','desc')
                ->paginate(Appsetting('paging_item_number'));
                
         $sum_jumlah = \DB::table('piutang')
                        ->where('state','open')
                        ->sum('jumlah');
        $sum_amount_due = \DB::table('piutang')
                        ->where('state','open')
                        ->sum('amount_due');

        return view('piutang.index',[
                'data' => $data,
                'sum_jumlah' => $sum_jumlah,
                'sum_amount_due' => $sum_amount_due,
            ]);
	}

    public function getSearch(){
        $val = trim(\Input::get('val'));
        $piutang = \DB::table('view_piutang')
                        ->where('name','like','%' . trim($val) . '%')
                        ->orWhere('state','like','%' . $val . '%')
                        ->orWhere('source','like','%' . $val . '%')
                        ->orWhere('partner','like','%' . $val . '%')
                        ->orWhere('pekerjaan','like','%' . $val . '%')
                        ->paginate(Appsetting('paging_item_number'));
                        // ->get();
        $piutang->appends(['val'=>$val]);

        $sum_jumlah = \DB::table('piutang')
                        ->where('state','open')
                        ->sum('jumlah');

        return view('piutang.search',[
                'data' => $piutang,
                'search_val' => $val,
                'sum_jumlah' => $sum_jumlah
            ]);
    }

    public function formView(){
        $data = \DB::table('view_piutang')
                // ->where('state','!=','paid')
                ->orderBy('tanggal','desc')
                ->simplePaginate(1);

        $karyawans = \DB::table('karyawan')
                    ->get();
        $karyawanArr = [];
        foreach($karyawans as $dt){
            $karyawanArr[$dt->id] = $dt->nama . ' - ' . $dt->kode;
        }

        // foreach($data as $dt){
        //     $payments = \DB::table('piutang_payment')
        //                     ->select('*',\DB::raw("date_format(tanggal,'%d-%m-%Y') as tanggal_format"))
        //                     ->where('piutang_id',$dt->id)
        //                     ->get();
            
        // }

        return view('piutang.form-view',[
                'datas' => $data,
                'karyawans' => $karyawanArr,
                // 'payments' => $payments,
        ]);
    }

    public function edit($id){
        $data = \DB::table('view_piutang')
                ->find($id);

        $karyawans = \DB::table('res_partner')
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
                    'partner_id' => $req->karyawan,
                    'penerima' => $req->penerima,
                    'desc' => $req->desc,
                    'jumlah' => str_replace(',', '', str_replace('.00','',$req->jumlah)),
                ]);

        return redirect('finance/piutang/edit/'.$req->original_id);
    }

    public function create(){
        $karyawans = \DB::table('res_partner')
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
        $id = $this->insertPiutang($req->tanggal,'pl','draft',null,null,$req->karyawan,$req->penerima,$req->desc, $req->jumlah);
        return redirect('finance/piutang/edit/' . $id);
    }



	public function insertPiutang($tanggal, $type, $state, $source=null, $soId=null, $karyawanId=null, $penerima, $desc, $jumlah){
		// generate nomor hutang
        return \DB::transaction(function()use($tanggal, $type, $state, $source, $soId, $karyawanId, $penerima, $desc, $jumlah){
            $nomor_inv = 'Draft Piutang';
            if($state == 'open'){
                $prefix = Appsetting('piutang_prefix');
                $counter = Appsetting('piutang_counter') + 1;
                UpdateAppsetting('piutang_counter',$counter);
                // add leading zero
                if(strlen($counter) == 1){
                	$counter = '0'.$counter;
                }
                // $nomor_inv = $prefix.'/'.date('Y/m').$counter;
                $month=date("m");
                $year=date("Y");
                $nomor_inv = $prefix . '/'.$year.'/'.$month. $counter;
            }

            // generate tanggal
            $arr_tgl = explode('-',$tanggal);
            $tgl = new \DateTime();
            $tgl->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

            // set desc
            // if($type == 'pk'){
            //     $karyawan = \DB::table('karyawan')->find($karyawanId);
            //     $desc = $karyawan->nama . ' - ' . $karyawan->kode;
            // }

            $id = \DB::table('piutang')
            	->insertGetId([
            		'name' => $nomor_inv,
            		'tanggal' => $tgl,
            		'type' => $type,
            		'state' => $state,
            		'source' => $source,
            		'so_id' => $soId,
            		'partner_id' => $karyawanId,
                    'penerima' => $penerima,
                    'desc' => $desc,
                    'jumlah' => str_replace(',', '', str_replace('.00','',$jumlah)),
            		'amount_due' => $state == 'draft' ? null : str_replace(',', '', str_replace('.00','',$jumlah)),
            	]);

            return $id;            
        });
	}

    public function toDelete($id){
        \DB::table('piutang')->delete($id);

        return redirect('finance/piutang');
    }

    public function toConfirm($id){
        // generate nomor hutang
        $prefix = Appsetting('piutang_prefix');
        $counter = Appsetting('piutang_counter') + 1;
        UpdateAppsetting('piutang_counter',$counter);
        // add leading zero
        if(strlen($counter) == 1){
            $counter = '0'.$counter;
        }
        $nomor_inv = $prefix.'/'.date('Y/m').$counter;

        \DB::table('piutang')
            ->where('id',$id)
            ->update([
                'state' => 'open',
                'name' => $nomor_inv,
                'amount_due' => \DB::raw('jumlah')
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

    public function paymentToPrint($id){
        $payment = \DB::table('view_piutang_payment')
                    ->find($id);
        $payment->piutang = \DB::table('view_piutang')
                            ->find($payment->piutang_id);
        $html2pdf = new Html2Pdf('P', 'A5', 'en');
        $html2pdf->writeHTML(view('piutang.payment-pdf',[
            'data' => $payment
        ])->__toString());
        $html2pdf->output();
        
    }

    public function toPdf($id){
        $html2pdf = new Html2Pdf('P', 'A5', 'en');
        $html2pdf->writeHTML($this->viewPdf($id)->__toString());
        $html2pdf->output();
    }

    public function viewPdf($id){
        $piutang = \DB::table('view_piutang')
                    ->find($id);
        return view('piutang.pdf',[
            'data' => $piutang
        ]);
    }

    public function showSo($id){
        $piutang = \DB::table('view_piutang')->find($id);
        $piutang->so = \DB::table('view_new_pengiriman')->find($piutang->so_id);

        $drivers = \DB::table('karyawan')->where('driver',1)->get();
        $driver = [];
        foreach($drivers as $dt){
            $nopol = \DB::table('armada')->where('karyawan_id',$dt->id)->first();
            $driver[$dt->id] = $dt->nama . ' - ' . $nopol->nopol;
        }

        $customer = \DB::table('customer')->get();
        $select_customer = [];
        foreach($customer as $dt){
            $select_customer[$dt->id] = $dt->nama;
        }

        $material = \DB::table('material')->get();
        $select_material = [];
        foreach($material as $dt){
            $select_material[$dt->id] = $dt->nama;
        }

        return view('piutang.show-so',[
                'select_customer' => $select_customer,
                'material' => $select_material,
                'driver' => $driver,
                'data' => $piutang
            ]);

    }

    public function receivePay(){
        $karyawans = \DB::table('karyawan')
                    ->get();
        $karyawanArr = [];
        foreach($karyawans as $dt){
            $karyawanArr[$dt->id] = $dt->nama . ' - ' . $dt->kode;
        }

        $customer = \DB::table('customer')->get();
        $select_customer = [];
        foreach($customer as $dt){
            $select_customer[$dt->id] = $dt->nama;
        }

        return view('piutang.receive-pay',[
            'karyawan' => $karyawanArr,
            'customer' => $select_customer,
        ]);
    }

    public function filter($filterby,$val){
        $data = \DB::table('view_piutang')
        ->where($filterby,$val)
        ->orderBy('tanggal','desc')
        ->paginate(Appsetting('paging_item_number'));

        $sum_jumlah = \DB::table('piutang')
                        ->where('state','open')
                        ->sum('jumlah');

        return view('piutang.filter',[
                'data' => $data,
                'filterby' => $filterby,
                'filter' => $val,
                'sum_jumlah' => $sum_jumlah,
            ]);
    }

    public function groupby($val){
        $data = \DB::table('view_piutang')
        ->select('*',\DB::raw('count(id) as jumlah'),\DB::raw('sum(jumlah) as sum_jumlah'),\DB::raw('sum(amount_due) as amount_due'))
        ->groupBy($val)
        ->get();         

         $sum_jumlah = \DB::table('piutang')
                        ->where('state','open')
                        ->sum('jumlah');
        $sum_amount_due = \DB::table('piutang')
                        ->where('state','open')
                        ->sum('amount_due');      

        return view('piutang.group',[
                'data' => $data,
                'groupby' => $val,
                'sum_jumlah' => $sum_jumlah,
                'sum_amount_due' => $sum_amount_due,
            ]);
    }

    public function groupdetail($groupby, $id){
        $where = 'true';
        if($groupby == 'partner'){
            $where = 'partner_id = ' . $id;
        }
        $data = \DB::table('view_piutang')
                ->whereRaw($where)
                ->orderBy('tanggal','desc')
                ->get();
        return $data;
    }



}
