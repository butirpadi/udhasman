<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;

class ReportHutangController extends Controller
{
	public function index(){
		$dataPartner = \Db::table('res_partner')
							->get();
		$partners = [];
		foreach($dataPartner as $dt){
			$partners[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}
		return view('report.hutang.index',[
			'partners' => $partners
		]);
	}

	public function submitExcel(Request $req){
		$this->submit($req,'report.hutang.report',true);
	}

	public function submitPdf(Request $req){
		$dompdf = new Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->setPaper('A4', 'potrait');

        // Show Report
        $dompdf->loadHtml($this->submit($req,'report.hutang.report-pdf'));

        $dompdf->render();
        $dompdf->stream("ReportHutang.pdf", array("Attachment" => false));
        exit(0);
	}

	public function submit(Request $req, $defaultView = 'report.hutang.report', $excel = false){
		// Generate Tanggal
        $awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $tgl_awal = new \DateTime();
        $tgl_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        if ($req->tipe_report == 'summary'){

        }else if ($req->tipe_report == 'detail'){
        	$where = 'true';
        	if($req->group_by == 'partner_id'){
        		$where = $req->partner != '' ? 'partner_id = ' . $req->partner : 'true';
        	}else{
        		$where = $req->type != '' ? 'type = ' . $req->type : 'true';
        	}

			$data_group = \DB::table('view_hutang')
						->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
						->whereRaw($where)
						->groupBy($req->group_by)
						->orderBy('tanggal','desc')
						->get();

			foreach($data_group as $dg){
				// $whereList = 'true';
	   //      	if($req->group_by == 'partner_id'){
	   //      		$whereList = 'partner_id = ' . $dg->partner_id;
	   //      	}else{
	   //      		$whereList = 'type = ' . $dg->type;
	   //      	}	
				$dg->detail = \DB::table('view_hutang')
						->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
						->wherePartnerId($dg->partner_id)
						->orderBy('tanggal','desc')
						->get();
			}

			$sum_amount_due = \DB::table('view_hutang')
						->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
						->whereRaw($where)
						->sum('amount_due');

			$sum_jumlah = \DB::table('view_hutang')
						->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
						->whereRaw($where)
						->sum('jumlah');
			
        }

        $reportOptions = [
        		'data_group' => $data_group,
	        	'tanggal_awal' => $awal,
				'tanggal_akhir' => $akhir,
				'sum_amount_due' => $sum_amount_due,
				'sum_jumlah' => $sum_jumlah,
				'group_by' => $req->group_by
			];

        if($excel){
        	\Excel::create('Laporan_Detail_Hutang_'.date('dmY_His'), function($excel) use($defaultView,$reportOptions)  {
	            $excel->sheet('Report', function($sheet) use($defaultView,$reportOptions) {
	                $sheet->loadView($defaultView,$reportOptions);
	            });

	        })->download('xlsx');;
        }else{
	        return view($defaultView,$reportOptions);
        	
        }

	}


}
