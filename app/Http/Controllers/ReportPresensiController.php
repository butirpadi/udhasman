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
		$dataAlat = \Db::table('alat')
							->get();
		$alats = [];
		foreach($dataAlat as $dt){
			$alats[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}

		$dataLokasi = \Db::table('lokasi_galian')
							->get();
		$lokasis = [];
		foreach($dataLokasi as $dt){
			$lokasis[$dt->id] = $dt->nama;
		}

		$dataPartner = \Db::table('res_partner')
							->whereStaff('Y')
							->get();
		$partners = [];
		foreach($dataPartner as $dt){
			$partners[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}



		return view('report.presensi.index',[
			'partners' => $partners,
			'alat' => $alats,
			'lokasi' => $lokasis,
		]);
	}

	public function submitExcel(Request $req){
		$this->submit($req,'report.presensi.report',true);
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

        // $akhir = $req->tanggal_akhir;
        $akhir = $jumlah_hari . '-' . $req->tanggal_awal;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];
        $day_akhir = $arr_tgl[0];

     //    $where = 'true';
    	// if($req->group_by == 'alat_id'){
    	// 	$where = $req->alat != '' ? 'alat_id = ' . $req->alat : 'true';
    	// }else if($req->group_by == 'lokasi_galian_id'){
    	// 	$where = $req->lokasi_galian != '' ? 'lokasi_galian_id = ' . $req->lokasi_galian : 'true';
    	// }else if($req->group_by == 'pengawas_id'){
    	// 	$where = $req->pengawas != '' ? 'pengawas_id = ' . $req->pengawas : 'true';
    	// }else if($req->group_by == 'operator_id'){
    	// 	$where = $req->operator != '' ? 'operator_id = ' . $req->operator : 'true';
    	// }

		$data_group = \DB::table('view_presensi')
					->whereBetween('tgl',[$tgl_awal_str,$tgl_akhir_str])
					// ->whereRaw($where)
					// ->groupBy($req->group_by)
					->groupBy('bulan')
					->orderBy('tgl','asc')
					->get();

		$karyawan = \DB::table('res_partner')
					->whereStaff('Y')
					->get();
		foreach($karyawan as $kary){
			$pres_by_karyawan = \DB::table('view_presensi')
								->whereBetween('tgl',[$tgl_awal_str,$tgl_akhir_str])
								->whereKaryawanId($kary->id)
								->orderBy('tgl','asc')
								->get();
			$kary->presensi = $pres_by_karyawan;
		}

		// foreach($data_group as $dg){
		// 	$day_list = \DB::table('view_presensi')
		// 			->whereBetween('tgl',[$tgl_awal_str,$tgl_akhir_str])
		// 			->whereBulan($dg->bulan)
		// 			->orderBy('tgl','asc')
		// 			->get();
		// }

   //      if ($req->tipe_report == 'detail'){        	

			// foreach($data_group as $dg){
			// 	if($req->group_by == 'alat_id'){
			// 		$dg->detail = \DB::table('view_presensi')
			// 				->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
			// 				->whereAlatId($dg->alat_id)
			// 				->orderBy('tanggal','desc')
			// 				->orderBy('id','desc')
			// 				->get();					
			// 	}else if($req->group_by == 'lokasi_galian_id'){
			// 		$dg->detail = \DB::table('view_presensi')
			// 				->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
			// 				->whereLokasiGalianId($dg->lokasi_galian_id)
			// 				->orderBy('tanggal','desc')
			// 				->orderBy('id','desc')
			// 				->get();					
			// 	}else if($req->group_by == 'pengawas_id'){
			// 		$dg->detail = \DB::table('view_presensi')
			// 				->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
			// 				->wherePengawasId($dg->pengawas_id)
			// 				->orderBy('tanggal','desc')
			// 				->orderBy('id','desc')
			// 				->get();					
			// 	}else if($req->group_by == 'operator_id'){
			// 		$dg->detail = \DB::table('view_presensi')
			// 				->whereBetween('tanggal',[$tgl_awal_str,$tgl_akhir_str])
			// 				->whereOperatorId($dg->operator_id)
			// 				->orderBy('tanggal','desc')
			// 				->orderBy('id','desc')
			// 				->get();					
			// 	}
			// }
			
   //      }

        $reportOptions = [
        		'data_group' => $data_group,
        		'karyawan' => $karyawan,
	        	'tanggal_awal' => $awal,
				'tanggal_akhir' => $akhir,
				// 'sum_amount_due' => $sum_amount_due,
				// 'sum_jumlah' => $sum_jumlah,
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
