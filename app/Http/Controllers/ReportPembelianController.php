<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;

class ReportPembelianController extends Controller
{
	public function index(){
		$supplier = \DB::table('supplier')->get();
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
        if($req->supplier != '' && $req->supplier != null){
        	// echo 'inside single supplier';
        	$pembelian = \DB::table('view_pembelian')
        			->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
        			->where('supplier_id',$req->supplier)
        			->orderBy('tanggal','asc')
        			->get();
	        $sum_total = \DB::table('view_pembelian')
	        			->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
	        			->where('supplier_id',$req->supplier)
	        			->sum('total');
        	$html2pdf->writeHTML($this->defaultReportHtml([
				'tanggal_awal' => $awal,
				'tanggal_akhir' => $akhir,
        		'pembelian'=>$pembelian,
        		'sum_total'=>$sum_total,
        	]));
        }else{
        	// echo 'inside multi supplier';
        	$pembelian = \DB::table('view_pembelian')
        					->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
        					->orderBy('tanggal','asc')
        					->get();
	        $sum_total = \DB::table('view_pembelian')->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])->sum('total');
        	$html2pdf->writeHTML($this->defaultReportHtml([
				'tanggal_awal' => $awal,
				'tanggal_akhir' => $akhir,
        		'pembelian'=>$pembelian,
        		'sum_total'=>$sum_total,
        	]));	
        }

		// echo $req->supplier;
		$html2pdf->output();

	}

	public function defaultReportHtml($data){
		return view('report.pembelian.default-report',$data);
	}

	public function defaultReportSingleHtml($data){
		return view('report.pembelian.default-report-single',$data);	
	}

	public function groupReport(Request $req){
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

		$group_report = \DB::table('view_pembelian')
					->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
					->orderBy('tanggal','asc')
					->groupBy('supplier_id')
					->get();

		$sum_total = \DB::table('view_pembelian')
					->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
					->sum('total');

		foreach($group_report as $dt){
			$dt->reports = \DB::table('view_pembelian')
							->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
							->whereSupplierId($dt->supplier_id)
							->get();			
		}

		foreach($group_report as $dt){
			foreach($dt->reports as $rp){
				$rp->products = \DB::table('view_pembelian_detail')
								->where('pembelian_id',$rp->id)
								->get();
			}
		}

		$html2pdf = new Html2Pdf('L', 'A4', 'en');
		$html2pdf->writeHTML(view('report.pembelian.group-report',[
			'tanggal_awal' => $awal,
			'tanggal_akhir' => $akhir,
			'pembelian' => $group_report,
			'sum_total' => $sum_total,
		]));
		$html2pdf->output();
	}

}