<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;

class ReportPembelianController extends Controller
{
	public function index(){
		$supplier = \DB::table('res_partner')
                        ->whereSupplier('Y')
                        ->get();
		$select_supplier = [];
		foreach($supplier as $dt){
			$select_supplier[$dt->id] = $dt->nama;
		}

		return view('report.pembelian.index',[
			'select_supplier' => $select_supplier
		]);
	}

	public function defaultReport(Request $req){
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
        

        $html2pdf = new Html2Pdf('P', 'A4', 'en');

        $where_supplier = 'true';
        if($req->supplier != '' ){
        	$where_supplier = 'supplier_id = ' . $req->supplier;
        }

        // echo 'inside single supplier';
    	$pembelian = \DB::table('view_pembelian')
    			->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
    			->whereRaw($where_supplier)
    			->orderBy('tanggal','asc')
    			->get();

        $sum_total = \DB::table('view_pembelian')
        			->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
        			->whereRaw($where_supplier)
        			->sum('total');

    	$html2pdf->writeHTML($this->defaultReportHtml([
			'tanggal_awal' => $awal,
			'tanggal_akhir' => $akhir,
    		'pembelian'=>$pembelian,
    		'sum_total'=>$sum_total,
    	]));

		$html2pdf->output();
		// echo count($pembelian);

	}

	public function defaultReportHtml($data){
		return view('report.pembelian.default-report',$data);
	}

	public function defaultReportSingleHtml($data){
		return view('report.pembelian.default-report-single',$data);	
	}

	public function submitExcel(Request $req){
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

        $where_supplier = 'true';
        if($req->supplier != '' ){
        	$where_supplier = 'supplier_id = ' . $req->supplier;
        }


		$group_report = \DB::table('view_pembelian')
					->select('view_pembelian.*',\DB::raw('sum(total) as total'))
					->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
					->whereRaw($where_supplier)
					->orderBy('tanggal','asc')
					->groupBy('supplier_id')
					->get();

		$sum_total = \DB::table('view_pembelian')
					->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
					->whereRaw($where_supplier)
					->sum('total');

		foreach($group_report as $dt){
			$dt->po = \DB::table('view_pembelian')
							->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
							->whereSupplierId($dt->supplier_id)
							->get();	

			foreach($dt->po as $rp){
				$rp->products = \DB::table('view_pembelian_detail')
								->where('pembelian_id',$rp->id)
								->get();
			}		
		}

		$reportOptions = [
        		'tanggal_awal' => $awal,
				'tanggal_akhir' => $akhir,
				'pembelian' => $group_report,
				'sum_total' => $sum_total,
				'tipe_report' =>$req->tipe_report
			];

    	return \Excel::create('Laporan_Pembelian_'.date('dmY_His'), function($excel) use($reportOptions)  {
            $excel->sheet('Report', function($sheet) use($reportOptions) {
            	$sheet->setAutoSize(true);
                $sheet->loadView('report.pembelian.excel',$reportOptions);
            });
        })->download('xlsx');

	        // return view('report.pembelian.excel',$reportOptions);
       
	}

	public function submitPdf(Request $req){
		// $dompdf = new Dompdf();
  //       $dompdf->set_option('isHtml5ParserEnabled', true);
  //       $dompdf->setPaper('A4', 'landscape');

  //       // Show Report
  //       $dompdf->loadHtml($this->submit($req,'report.pembelian.pdf'));

  //       $dompdf->render();
  //       $dompdf->stream("ReportPembelian.pdf", array("Attachment" => false));
  //       exit(0);

		// using wkhtml
		$pdf = \App::make('snappy.pdf.wrapper');
        $pdf->setOption('margin-top', 15);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('margin-left', 10);
        $pdf->setOption('margin-right', 10);
        // $pdf->setOption('orientation', 'landscape');
        // $pdf->setOption('header-html','<!DOCTYPE html><html>Laporan Piutang</html>');
        $pdf->setOption('footer-font-size','8');
        $pdf->setOption('footer-left','Rekapitulasi Pembelian');
        $pdf->setOption('footer-right','Printed at : ' . date('d-m-Y H:i:s'));
        $pdf->setOption('footer-center','Page [page] of [topage]');

        // $reportOption = [
        //         'pengiriman_by_group' => $pengiriman_by_group,
        //         'tanggal_awal' => $awal,
        //         'tanggal_akhir' => $akhir,
        //         'group_by' => $req->group_by,
        //         'dicetak'=>date('d-m-Y H:i:s')
        //     ];

        $pdf->loadHTML($this->submit($req,'report.pembelian.pdf-wkhtml'));
        return $pdf->inline();
	}

	// public function groupReport(Request $req){
	public function submit(Request $req, $defaultView = 'report.pembelian.default', $excel = false){
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

        $where_supplier = 'true';
        if($req->supplier != '' ){
        	$where_supplier = 'supplier_id = ' . $req->supplier;
        }


		$group_report = \DB::table('view_pembelian')
					->select('view_pembelian.*',\DB::raw('sum(total) as total'))
					->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
					->whereRaw($where_supplier)
					->orderBy('tanggal','asc')
					->groupBy('supplier_id')
					->get();

		$sum_total = \DB::table('view_pembelian')
					->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
					->whereRaw($where_supplier)
					->sum('total');

		foreach($group_report as $dt){
			$dt->reports = \DB::table('view_pembelian')
							->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
							->whereSupplierId($dt->supplier_id)
							->get();	

			foreach($dt->reports as $rp){
				$rp->products = \DB::table('view_pembelian_detail')
								->where('pembelian_id',$rp->id)
								->get();
			}		
		}

		$reportOptions = [
        		'tanggal_awal' => $awal,
				'tanggal_akhir' => $akhir,
				'pembelian' => $group_report,
				'sum_total' => $sum_total,
				'tipe_report' =>$req->tipe_report
			];

		if($excel){
        	// \Excel::create('Laporan_Pembelian_'.date('dmY_His'), function($excel) use($defaultView,$reportOptions)  {
	        //     $excel->sheet('Report', function($sheet) use($defaultView,$reportOptions) {
	        //         $sheet->loadView($defaultView,$reportOptions);
	        //     });

	        // })->download('xlsx');;
	        return view($defaultView,$reportOptions);
        }else{
	        return view($defaultView,$reportOptions);
        	
        }

		// $html2pdf = new Html2Pdf('L', 'A4', 'en');
        
		// $html2pdf->writeHTML(view('report.pembelian.group-report',[
		// 	'tanggal_awal' => $awal,
		// 	'tanggal_akhir' => $akhir,
		// 	'pembelian' => $group_report,
		// 	'sum_total' => $sum_total,
		// ]));
		// $html2pdf->output();
	}

}
