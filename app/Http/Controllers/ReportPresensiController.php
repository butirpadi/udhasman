<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;

class ReportPresensiController extends Controller
{
	public function index(){
		$dataPartner = \Db::table('res_partner')
							->whereStaff('Y')
							->get();
		$partners = [];
		foreach($dataPartner as $dt){
			$partners[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}

		return view('report.presensi.index',[
			'karyawan' => $partners,
		]);
	}

	public function submitExcel(Request $req){
		return $this->submit($req,'report.presensi.report-excel',true);
	}

	public function submitPdf(Request $req){
		$dompdf = new Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->setPaper('A4', 'landscape');

        // Show Report
        $dompdf->loadHtml($this->submit($req,'report.presensi.report-pdf'));

        $dompdf->render();
        $dompdf->stream('Laporan_Detail_Operasional_Alat_Berat_'.date('dmY_His').".pdf", array("Attachment" => false));
        exit(0);
	}

	public function submit(Request $req, $defaultView = 'report.presensi.report', $excel = false){
		// Generate Tanggal
        $awal = '01-' . $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $tgl_awal = new \DateTime();
        $tgl_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];
        $day_awal = $arr_tgl[0];

        $jumlah_hari = cal_days_in_month(CAL_GREGORIAN,$arr_tgl[1],$arr_tgl[2]);

        $akhir = $jumlah_hari . '-' . $req->tanggal_awal;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];
        $day_akhir = $arr_tgl[0];

        $where = 'true';
        if($req->karyawan != ''){
        	$where = 'karyawan_id = ' . $req->karyawan;
        }


		$data_group = \DB::table('view_presensi')
					->whereBetween('tgl',[$tgl_awal_str,$tgl_akhir_str])
					->whereRaw($where)
					// ->groupBy($req->group_by)
					->groupBy('bulan')
					->orderBy('tgl','asc')
					->get();

		$karyawan = \DB::table('res_partner')
					->whereStaff('Y')
					->get();

		if($req->karyawan != ''){
			$karyawan = \DB::table('res_partner')
						->whereId($req->karyawan)
						->get();			
		}
		
		foreach($karyawan as $kary){
			$pres_by_karyawan = \DB::table('view_presensi')
								->whereBetween('tgl',[$tgl_awal_str,$tgl_akhir_str])
								->whereKaryawanId($kary->id)
								->orderBy('tgl','asc')
								->get();
			$kary->presensi = $pres_by_karyawan;
		}

        $reportOptions = [
        		'data_group' => $data_group,
        		'karyawan' => $karyawan,
	        	'tanggal_awal' => $awal,
				'tanggal_akhir' => $akhir,
				'group_by' => $req->group_by,
				'tipe_report' => $req->tipe_report,
				'excel' => $excel
			];

        if($excel){
        	\Excel::create('Laporan_Detail_Operasional_Alat_Berat_'.date('dmY_His'), function($excel) use($defaultView,$reportOptions)  {
	            $excel->sheet('Report', function($sheet) use($defaultView,$reportOptions) {
	                $sheet->loadView($defaultView,$reportOptions);
	            });

	        })->download('xlsx');
        }else{
	        return view($defaultView,$reportOptions);
        	
        }

	}


}
