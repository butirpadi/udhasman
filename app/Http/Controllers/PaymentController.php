<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;
use App\Helpers\Helper;


class PaymentController extends Controller
{
	public function index(){
        $data = \DB::table('view_payment')
                ->orderBy('tanggal','desc')
                ->paginate(Appsetting('paging_item_number'));

        $saldo = \DB::table('view_payment')
                    ->sum('residual');

        return view('payment.index',[
            'data' => $data ,
            'saldo' => $saldo
        ]);
	}

    public function create(){
        $partners = \DB::table('res_partner')
                    ->get();
        $partner = [];
        foreach($partners as $dt){
            $partner[$dt->id] = $dt->nama . ' - ' . $dt->kode;
        }

        return view('payment.create',[
            'partner' => $partner
        ]);
    }

    public function insert(Request $req){
        // generate tanggal
        $arr_tgl = explode('-',$req->tanggal);
        $tgl = new \DateTime();
        $tgl->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

        $id = \DB::table('payment')
            ->insertGetId([
                'type' => $req->type,
                'partner_id' => $req->partner,
                'jumlah' => str_replace(',', '', str_replace('.00','',$req->jumlah)),
                'residual' => str_replace(',', '', str_replace('.00','',$req->jumlah)),
                'state' => 'draft',
                'memo' => $req->memo,
                'tanggal' => $tgl,
            ]);

        return redirect('finance/payment/edit/'.$id);

    }

    public function update(Request $req){
        // generate tanggal
        $arr_tgl = explode('-',$req->tanggal);
        $tgl = new \DateTime();
        $tgl->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

        \DB::table('payment')
            ->where('id',$req->original_id)
            ->update([
                'partner_id' => $req->partner,
                'jumlah' => str_replace(',', '', str_replace('.00','',$req->jumlah)),
                'residual' => str_replace(',', '', str_replace('.00','',$req->jumlah)),
                'memo' => $req->memo,
                'tanggal' => $tgl,
            ]);

        return redirect('finance/payment/edit/'.$req->original_id);        
    }

    public function edit($id){
        $data = \DB::table('view_payment')->find($id);

        $partners = \DB::table('res_partner')
                    ->get();
        $partner = [];
        foreach($partners as $dt){
            $partner[$dt->id] = $dt->nama . ' - ' . $dt->kode;
        }

        $piutang = [];
        if($data->state == 'post'){
            // if($data->type == 'so'){
                $piutang = \DB::table('view_piutang')
                                // ->where('type','so')
                                ->where('partner_id',$data->partner_id)
                                ->where('state','open')
                                ->get();
                
            // }
        }

        $sum_amount_due = \DB::table('view_piutang')
                            ->where('type','so')
                            ->where('partner_id',$data->partner_id)
                            ->where('state','open')
                            ->sum('amount_due');

        // $reconciled = \DB::select('select view_piutang_payment.payment_id,view_piutang_payment.id as piutang_payment_id,
        //     view_piutang_payment.name as piutang_payment_ref, view_piutang_payment.tanggal, 
        //     piutang_id,piutang.name as piutang_ref, new_pengiriman.id as pengiriman_id, 
        //     new_pengiriman.name as pengiriman_ref, new_pengiriman.harga_total ,
        //     piutang.jumlah as jumlah_piutang,view_piutang_payment.last_amount_due, view_piutang_payment.jumlah as paid,
        //     new_pengiriman.customer_id, customer.nama as customer, piutang.state as piutang_state
        //     from view_piutang_payment
        //     inner join piutang on view_piutang_payment.piutang_id = piutang.id
        //     left join new_pengiriman on piutang.so_id = new_pengiriman.id
        //     left join customer on new_pengiriman.customer_id = customer.id
            // where view_piutang_payment.payment_id = ' . $id);

        $reconciled = \DB::select('select view_piutang_payment.payment_id,view_piutang_payment.id as piutang_payment_id,
            view_piutang_payment.name as piutang_payment_ref, view_piutang_payment.tanggal, 
            piutang_id,piutang.name as piutang_ref, new_pengiriman.id as pengiriman_id, 
            new_pengiriman.name as pengiriman_ref, new_pengiriman.harga_total ,
            piutang.jumlah as jumlah_piutang,view_piutang_payment.last_amount_due, view_piutang_payment.jumlah as paid,
            new_pengiriman.customer_id, res_partner.nama as partner, piutang.state as piutang_state
            from view_piutang_payment
            inner join piutang on view_piutang_payment.piutang_id = piutang.id
            left join new_pengiriman on piutang.so_id = new_pengiriman.id
            left join res_partner on new_pengiriman.customer_id = res_partner.id
            where view_piutang_payment.payment_id = ' . $id);

        return view('payment.edit',[
            'data' => $data,
            'partner' => $partner,
            'piutang' => $piutang,
            'sum_amount_due' => $sum_amount_due,
            'reconciled' => $reconciled,
        ]);
    }

    public function toConfirm($id){
        // Generate Name
        $prefix = Appsetting('master_payment_in_prefix');
        $counter = Appsetting('master_payment_in_counter') + 1;
        UpdateAppsetting('master_payment_in_counter',$counter);
        // add leading zero
        if(strlen($counter) == 1){
            $counter = '0'.$counter;
        }
        // $nomor_inv = $prefix.'/'.date('Y/m').$counter;
        $month=date("m");
        $year=date("Y");
        $nomor_inv = $prefix . '/'.$year.'/'.$month. $counter;

        \DB::table('payment')
            ->whereId($id)
            ->update([
                'name' => $nomor_inv,
                'state' => 'post'
            ]);

        return redirect('finance/payment/edit/'.$id);
    }

    public function toReconcile($id){
        \DB::transaction(function()use($id){
            $payment = \DB::table('payment')
                            ->find($id);

            $piutang = \DB::table('view_piutang')
                            ->where('partner_id',$payment->partner_id)
                            ->where('state','open')
                            ->orderBy('tanggal','asc')
                            ->get();

            $payment_amount = $payment->residual;
            $paid = 0;
            foreach($piutang as $dt){
                if($payment_amount == 0){
                    break;
                }
                // generate payment number
                $prefix = Appsetting('payment_in_prefix');
                $counter = Appsetting('payment_in_counter');
                $ref = $prefix.'/'.date('Y/m').$counter++;
                UpdateAppsetting('payment_in_counter',$counter);

                $bayar = $payment_amount > $dt->amount_due ? $dt->amount_due : $payment_amount;
                \DB::table('piutang_payment')
                    ->insertGetId([
                        'name' => $ref,
                        'piutang_id' => $dt->id,
                        'last_amount_due' => $dt->amount_due,
                        'jumlah' => $bayar,
                        'tanggal' => $payment->tanggal,
                        'payment_id' => $payment->id
                    ]);

                // update amount due piutang
                \DB::table('piutang')
                    ->where('id',$dt->id)
                    ->update([
                        'amount_due' => $dt->amount_due - $bayar,
                        'state' => $dt->amount_due - $bayar == 0 ? 'paid' : 'open'
                    ]);

                // update invoice_state
                if($dt->amount_due - $bayar == 0){
                    \DB::table('new_pengiriman')
                        ->where('id',$dt->so_id)
                        ->update([
                            'invoice_state' => 'paid'
                        ]);
                }

                // kurangi payment amount
                $payment_amount -= $bayar;
            }

            // set state payment
            \DB::table('payment')
                ->where('id',$id)
                ->update([
                    'residual' => $payment_amount,
                    'state' => $payment_amount == 0 ? 'rec':'post',
                ]);
            
        });

        return redirect('finance/payment/edit/'.$id);

    }

    public function getAmountDue($customer_id){
        $amount_due = \DB::table('view_piutang')
                        ->where('partner_id',$customer_id)
                        ->where('state','open')
                        ->sum('amount_due');
        return $amount_due == '' ? 0 : $amount_due;
    }

    public function toUnreconcile($id){
        \DB::select('CALL reset_receive_payment(' . $id . ')');

        return redirect('finance/payment/edit/'.$id);
    }

    public function toCancel($id){
        \DB::table('payment')
            ->whereId($id)
            ->update([
                'state' => 'draft'
            ]);

        return redirect('finance/payment/edit/'.$id);
    }

    public function toDelete($id){
        \DB::table('payment')
            ->delete($id);

        return redirect('finance/payment');
    }    

    public function toPrint($id){
        $payment = \DB::table('view_payment')
                    ->find($id);
        $payment->terbilang = Helper::convertTerbilang(intval($payment->jumlah));
        
        $html2pdf = new Html2Pdf('P', 'A5', 'en');
        $html2pdf->writeHTML(view('payment.pdf',[
            'data' => $payment
        ])->__toString());
        $html2pdf->output();

    }

    public function filter($filterby,$val){
        $data = \DB::table('view_payment')
        ->where($filterby,$val)
        ->orderBy('tanggal','desc')
        ->paginate(Appsetting('paging_item_number'));

        $sum_residual = \DB::table('view_payment')
                        ->where('state','!=','draft')
                        ->sum('residual');

        return view('payment.filter',[
                'data' => $data,
                'filterby' => $filterby,
                'filter' => $val,
                'sum_residual' => $sum_residual,
            ]);
    }

    public function groupby($val){
        $data = \DB::table('view_payment')
                ->select('*',\DB::raw('count(id) as jumlah'),\DB::raw('sum(jumlah) as sum_jumlah'),\DB::raw('sum(residual) as sum_residual'))
                ->groupBy($val)
                ->get();         

         $sum_residual = \DB::table('view_payment')
                        ->where('state','!=','draft')
                        ->sum('residual');

        return view('payment.group',[
                'data' => $data,
                'groupby' => $val,
                'sum_residual' => $sum_residual,
            ]);
    }

    public function groupdetail($groupby, $id){
        $where = 'true';
        if($groupby == 'partner'){
            $where = 'partner_id = ' . $id;
        }
        $data = \DB::table('view_payment')
                ->whereRaw($where)
                ->orderBy('tanggal','desc')
                ->get();
        return $data;
    }

    public function getSearch(){
        $val = trim(\Input::get('val'));
        $payment = \DB::table('view_payment')
                        ->where('name','like','%' . trim($val) . '%')
                        ->orWhere('state','like','%' . $val . '%')
                        ->orWhere('partner','like','%' . $val . '%')
                        ->paginate(Appsetting('paging_item_number'));
                        // ->get();
        $payment->appends(['val'=>$val]);

        $sum_residual = \DB::table('view_payment')
                        ->where('state','!=','draft')
                        ->sum('residual');

        return view('payment.search',[
                'data' => $payment,
                'search_val' => $val,
                'sum_residual' => $sum_residual
            ]);
    }


}
