<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;

class ReportGajiDriverController extends Controller
{
	public function index(){
		$drivers = \DB::table('res_partner')
                        ->where('driver','Y')
                        ->orWhere('staff','Y')
                        ->get();
		$select_driver = [];
		foreach($drivers as $dt){
			$select_driver[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}

		return view('report.gaji_driver.index',[
			'driver' => $select_driver
		]);
	}

	public function submit(Request $req,$view = 'report.gaji_driver.report'){
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

        $where = 'true';
        if($req->driver != ''){
        	$where = 'partner_id = ' . $req->driver;
        }

        $data = \DB::table('view_union_gaji_karyawan')
        			->select('view_union_gaji_karyawan.*',\DB::raw('sum(gaji_nett) as gaji_nett'),\DB::raw('sum(amount_due) as amount_due'))
					->whereBetween('tanggal_gaji',[$tgl_awal_str,$tgl_akhir_str])
					->whereRaw($where)
					->whereState('paid')
					->groupBy('partner_id')
					->orderBy('tanggal_gaji','desc')
					->orderBy('id','desc')
					->get();

		if($req->tipe_report == 'detail'){
			foreach($data as $dt){
				$dt->details = \DB::table('view_union_gaji_karyawan')
					->whereBetween('tanggal_gaji',[$tgl_awal_str,$tgl_akhir_str])
					->whereState('paid')
					->wherePartnerId($dt->partner_id)
					->orderBy('tanggal_gaji','desc')
					->get();
			}
		}

		$sum_gaji_nett = \DB::table('view_union_gaji_karyawan')
					->whereBetween('tanggal_gaji',[$tgl_awal_str,$tgl_akhir_str])
					->whereRaw($where)
					->whereState('paid')
					->orderBy('tanggal_gaji','desc')
					->sum('gaji_nett');

		$sum_amount_due = \DB::table('view_union_gaji_karyawan')
					->whereBetween('tanggal_gaji',[$tgl_awal_str,$tgl_akhir_str])
					->whereRaw($where)
					->whereState('paid')
					->orderBy('tanggal_gaji','desc')
					->sum('amount_due');

		// echo var_dump($data);
		return view($view,[
			'data' => $data,
			'tanggal_awal' => $req->tanggal_awal,
			'tanggal_akhir' => $req->tanggal_akhir,
			'tipe_report' => $req->tipe_report,
			'sum_gaji_nett' => $sum_gaji_nett,
			'sum_amount_due' => $sum_amount_due,
		]);
	}

	public function submitPdf(Request $req){
		$pdf = \App::make('snappy.pdf.wrapper');
		$pdf->setOption('margin-top', 15);
		$pdf->setOption('margin-bottom', 10);
		$pdf->setOption('margin-left', 10);
		$pdf->setOption('margin-right', 10);
		$pdf->setOption('footer-font-size','8');
		$pdf->setOption('footer-left','Rekapiutlasi Gaji Driver');
		$pdf->setOption('footer-right','Printed at : ' . date('d-m-Y H:i:s'));
		$pdf->setOption('footer-center','Page [page] of [topage]');
		$pdf->loadHTML($this->submit($req,'report.gaji_driver.pdf'));
		return $pdf->inline();

		// return $this->submit($req,'report.gaji_driver.report');
	}

	
}
