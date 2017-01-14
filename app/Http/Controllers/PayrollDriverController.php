<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PayrollDriverController extends Controller
{
	  

	  public function showPayrollTable($tanggal){
	  	
	  	// get data driver
	  	$data = \DB::table('view_karyawan')
	  				->where('kode_jabatan','DV')
	  				->where('is_active','Y')
	  				->orderBy('created_at','desc')
	  				->get();

	 //  	// generate date
		$tanggal = $tanggal;
		$arr_tgl = explode('-',$tanggal);
		$tanggal_gaji = new \DateTime();
		$tanggal_awal = new \DateTime();
		$tanggal_akhir = new \DateTime();
		$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->modify('-7 day');
		$tanggal_akhir->modify('-1 day');

		// echo $tanggal_awal->format('d-m-Y') . '<br/>';
		// echo $tanggal_akhir->format('d-m-Y') . '<br/>';

	  	foreach($data as $dt){
	  		$dt->payroll = \DB::table('view_payroll_driver')
								->whereKaryawanId($dt->id)
								->wherePaymentDate($tanggal_gaji->format('Y-m-d'))
								->first();
			
	  	}

	  	

	  	return view('payroll.driver.payroll-table',[
	  			'data' => $data,
	  			'tanggal_penggajian' =>$tanggal_gaji->format('d-m-Y')
	  		]);
	  }

	  // insert / register payroll payment
	  public function addPay($karyawan_id,$tanggal){
  	 	// generate date
		$arr_tgl = explode('-',$tanggal);
		$tanggal_gaji = new \DateTime();
		$tanggal_awal = new \DateTime();
		$tanggal_akhir = new \DateTime();
		$tanggal_awal_siang_for_table_presensi = new \DateTime();
		$tanggal_akhir_siang_for_table_presensi = new \DateTime();
		$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal->modify('-7 day');
		$tanggal_akhir->modify('-1 day');

		$tanggal_awal_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_awal_siang_for_table_presensi->modify('-7 day');
		$tanggal_akhir_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
		$tanggal_akhir_siang_for_table_presensi->modify('-1 day');

	  	$data = \DB::table('view_karyawan')->find($karyawan_id);
	  	$data->total_hadir_pagi  = \DB::table('attend')
								->select('pagi')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->where('pagi','Y')
								->count();
		$data->total_hadir_siang = \DB::table('attend')
								->select('siang')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->where('siang','Y')
								->count();
		$data->presensi_pagi = \DB::table('attend')
								->select('pagi','tgl')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->orderBy('tgl')
								->get();
		$data->presensi_siang = \DB::table('attend')
								->select('siang','tgl')
								->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
								->whereKaryawanId($data->id)
								->orderBy('tgl')
								->get();

	  	return view('payroll.driver.add-pay',[
	  			'data' => $data,
	  			'tanggal_gaji' => $tanggal,
	  			'tanggal_awal' => $tanggal_awal,
	  			'tanggal_akhir' => $tanggal_gaji,
	  			'tanggal_awal_siang_for_table_presensi' => $tanggal_awal_siang_for_table_presensi,
	  			'tanggal_akhir_siang_for_table_presensi' => $tanggal_akhir_siang_for_table_presensi,
	  		]);
	  }

	  	// INSERT PAYROLL PAYMENT
		public function insertPay(Request $req){
			return \DB::transaction(function()use($req){
				// generate tanggal
		        $arr_tgl = explode('-',$req->pay_date);
		        $tanggal = new \DateTime();
		        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

		        // generate payroll
		        $counter = \DB::table('appsetting')->whereName('payroll_counter')->first()->value;
		        $prefix = \DB::table('appsetting')->whereName('payroll_prefix')->first()->value;
		        $payroll_number  = $prefix . '/'.date('Y').'/'.date('m').'/'.$counter++;


				$payroll_id = \DB::table('payroll_driver')->insertGetId([
						'status' => 'O',
						'payroll_number' => $payroll_number,
						'payment_date' => $tanggal,
						'karyawan_id' => $req->karyawan_id,
						'basic_pay' => $req->basic_pay,
						'total_pagi' => $req->total_pagi,
						'total_siang' => $req->total_siang,
						'potongan' => $req->potongan,
						'netpay' => (($req->total_pagi + $req->total_siang ) /2) * $req->basic_pay - $req->potongan,
						'user_id' => \Auth::user()->id
					]);


		        // update counter
		        \DB::table('appsetting')
		        	->whereName('payroll_counter')
		        	->update(['value'=>$counter]);

		        return redirect('payroll/payroll-driver/edit-pay/' . $payroll_id);
			});
	  	}

	  	// EDIT PAYROLL
	  	public function editPay($payroll_id){
	  		$data = \DB::table('view_payroll_driver')
	  						->whereId($payroll_id)
	  						->select('view_payroll_driver.*',\DB::raw('date_format(payment_date,"%d-%m-%Y") as payment_date_formatted'))
	  						->first();

	  		// generate date
				$arr_tgl = explode('-',$data->payment_date_formatted);
				$tanggal_gaji = new \DateTime();
				$tanggal_awal = new \DateTime();
				$tanggal_akhir = new \DateTime();
				$tanggal_awal_siang_for_table_presensi = new \DateTime();
				$tanggal_akhir_siang_for_table_presensi = new \DateTime();
				$tanggal_gaji->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_awal->modify('-7 day');
				$tanggal_akhir->modify('-1 day');

				$tanggal_awal_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_awal_siang_for_table_presensi->modify('-7 day');
				$tanggal_akhir_siang_for_table_presensi->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
				$tanggal_akhir_siang_for_table_presensi->modify('-1 day');


		  		$data->total_hadir_pagi  = \DB::table('attend')
									->select('pagi')
									->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
									->whereKaryawanId($data->karyawan_id)
									->where('pagi','Y')
									->count();
				$data->total_hadir_siang = \DB::table('attend')
										->select('siang')
										->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
										->whereKaryawanId($data->karyawan_id)
										->where('siang','Y')
										->count();
				$data->presensi_pagi = \DB::table('attend')
										->select('pagi','tgl')
										->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
										->whereKaryawanId($data->karyawan_id)
										->orderBy('tgl')
										->get();
				$data->presensi_siang = \DB::table('attend')
										->select('siang','tgl')
										->whereBetween('tgl',[$tanggal_awal->format('Y-m-d'),$tanggal_akhir->format('Y-m-d')])
										->whereKaryawanId($data->karyawan_id)
										->orderBy('tgl')
										->get();

	  		// cek apakah sudah ter-validate
	  		if($data->status == 'P'){
	  			return view('payroll/driver/validated-pay',[
		  				'data' => $data,
		  				'tanggal_awal' => $tanggal_awal,
			  			'tanggal_akhir' => $tanggal_gaji,
			  			'tanggal_awal_siang_for_table_presensi' => $tanggal_awal_siang_for_table_presensi,
			  			'tanggal_akhir_siang_for_table_presensi' => $tanggal_akhir_siang_for_table_presensi,
		  			]);
	  		}else{
		  		
		  		return view('payroll/driver/edit-pay',[
		  				'data' => $data,
		  				'tanggal_awal' => $tanggal_awal,
			  			'tanggal_akhir' => $tanggal_gaji,
			  			'tanggal_awal_siang_for_table_presensi' => $tanggal_awal_siang_for_table_presensi,
			  			'tanggal_akhir_siang_for_table_presensi' => $tanggal_akhir_siang_for_table_presensi,
		  			]);
	  			
	  		}

	  	}

	  	// UPDATE PAYROLL
	  	public function updatePay(Request $req){
			return \DB::transaction(function()use($req){
				\DB::table('payroll_driver')
						->whereId($req->payroll_id)
						->update([
						'total_pagi' => $req->total_pagi,
						'total_siang' => $req->total_siang,
						'potongan' => $req->potongan,
						'netpay' => (($req->total_pagi + $req->total_siang ) /2) * $req->basic_pay - $req->potongan,
					]);

		        return redirect()->back();
			});
	  	}

	  	// VALIDATE PAYROLL
	  	public function validatePay($payroll_id){
	  		return \DB::transaction(function()use($payroll_id){
				\DB::table('payroll_driver')
						->whereId($payroll_id)
						->update([
						'status' => 'P'
					]);

		        return redirect()->back();
			});
	  	}

	  	// RESET PAYROLL
	  	public function resetPay($payroll_id){
	  		return \DB::transaction(function()use($payroll_id){
	  			$data_payroll = \DB::table('payroll_driver')->find($payroll_id);

	  			$arr_tgl = explode('-',$data_payroll->payment_date);
	        	$tanggal = new \DateTime();
	        	$tanggal->setDate($arr_tgl[0],$arr_tgl[1],$arr_tgl[2]);

				\DB::table('payroll_driver')
						->delete($payroll_id);

		        return redirect('payroll/payroll-driver/pay/' . $data_payroll->karyawan_id . '/' . $tanggal->format('d-m-Y'));
			});
	  	}

	  	// PRINT PDF
	  	public function printPdf($payroll_id){
	  		$payroll_data = \DB::table('view_payroll_driver')->find($payroll_id);

	  		$pdf = new \App\Helpers\MyPdf('L','mm',array(210,148.5));
	  		$pdf->AddPage();  		

	  		$this->generatePdfContent($pdf, $payroll_data, false);

	  		$pdf->Output();
	  		exit;

	  	}

	  	public function generatePdfContent(&$pdf, $payroll_data, $with_copy = false){
	  		if($with_copy){
	  			GeneratePdfHeader($pdf,'SLIP GAJI [COPY]','#'.$payroll_data->payroll_number);

	  		}else{
	  			GeneratePdfHeader($pdf,'SLIP GAJI','#'.$payroll_data->payroll_number);

	  		}

	  		// TABLE HEADER
	  		$pdf->SetTextColor(0,0,0);
	  		$pdf->Ln(5);
	  		$pdf->SetXY(8,32);

	  		$pdf->SetFont('Arial','B',8);
	  		$pdf->Cell(30,5,'NAMA',0,0,'L',false);
	  		$pdf->Cell(2,5,':',0,0,'L',false);
	  		$pdf->SetFont('Arial',null,8);
	  		$pdf->Cell($pdf->GetPageWidth()/2-32,5,strtoupper($payroll_data->nama_karyawan),0,0,'L',false);


	  		$pdf->SetFont('Arial','B',8);
	  		$pdf->Cell(30,5,'PERIODE',0,0,'L',false);
	  		$pdf->Cell(2,5,':',0,0,'L',false);
	  		$pdf->SetFont('Arial',null,8);
	  		$pdf->Cell($pdf->GetPageWidth()/2-32,5,$payroll_data->payment_date_formatted,0,2,'L',false);

	  		$pdf->SetX(8);
	  		$pdf->SetFont('Arial','B',8);
	  		$pdf->Cell(30,5,'KODE KARYAWAN',0,0,'L',false);
	  		$pdf->Cell(2,5,':',0,0,'L',false);
	  		$pdf->SetFont('Arial',null,8);
	  		$pdf->Cell($pdf->GetPageWidth()/2-32,5,$payroll_data->kode_karyawan,0,0,'L',false);


	  		$pdf->SetFont('Arial','B',8);
	  		$pdf->Cell(30,5,'JABATAN',0,0,'L',false);
	  		$pdf->Cell(2,5,':',0,0,'L',false);
	  		$pdf->SetFont('Arial',null,8);
	  		$pdf->Cell($pdf->GetPageWidth()/2-32,5,'STAFF',0,2,'L',false);

	  		// TABLE HEADER
	  		$pdf->Ln(5);
	  		$pdf->SetX(8);
	  		$pdf->SetFont('Arial','B',8);
	  		$pdf->SetFillColor(0,128,128);
	  		$pdf->SetTextColor(255,255,255);
	  		$table_col_width = ($pdf->GetPageWidth()-16)/2;
	  		$pdf->Cell($table_col_width-1,8,'  PENDAPATAN',0,0,'L',true);
	  		$pdf->SetX(8);
	  		$pdf->Cell($table_col_width-1,8,'JUMLAH  ',0,0,'R',false);

	  		$pdf->Cell(1,5,null,0,0,'L',false); //separator

	  		$pdf->SetFillColor(0,0,0);
	  		$X = $pdf->GetX();
	  		$pdf->Cell($table_col_width,8,'  POTONGAN',0,0,'L',true);
	  		$pdf->SetX($X);
	  		$pdf->Cell($table_col_width,8,'JUMLAH   ',0,2,'R',false);

	  		$pdf->SetX(8);
	  		$pdf->SetFillColor(0,128,128);
	  		$pdf->Cell($table_col_width-1,1,null,0,2,'L',true);
	  		$pdf->Cell($table_col_width*2,1,null,0,2,'L',true);

	  		// TABLE CONTENT
	  		$pdf->Ln(5);
	  		$pdf->SetTextColor(0,0,0);
	  		$pdf->SetFont('Arial',null,8);

	  		$pdf->SetX(8);
	  		$pdf->Cell($table_col_width-1,5,'  JUMLAH PRESENSI',0,0,'L',false);
	  		$pdf->SetX(8);
	  		$pdf->Cell($table_col_width-1,5,($payroll_data->total_pagi+$payroll_data->total_siang)/2 .'   ',0,0,'R',false);

	  		$pdf->Cell(1,5,null,0,0,'L',false); //separator

	  		$X = $pdf->GetX();
	  		$pdf->Cell($table_col_width,5,'  POTONGAN',0,0,'L',false);
	  		$pdf->SetX($X);
	  		$pdf->Cell($table_col_width,5,number_format($payroll_data->potongan,0,',','.').'   ',0,2,'R',false);


	  		$pdf->SetX(8);
	  		$pdf->Cell($table_col_width-1,5,'  GAJI/HARI',0,0,'L',false);
	  		$pdf->SetX(8);
	  		$pdf->Cell($table_col_width-1,5,number_format($payroll_data->gaji_pokok,0,',','.').'   ',0,2,'R',false);

	  		// LINE 
	  		$pdf->SetX(8);
	  		$pdf->SetDrawColor(0,0,0);
	  		$pdf->SetLineWidth(0.2);
	  		$pdf->Cell($pdf->GetPageWidth()-16,2,null,'B',2,'L',false);

	  		// TOTAL POTONGAN & TOTAL PENDAPATAN
	  		$pdf->SetX(8);
	  		$pdf->Cell($table_col_width-1,5,'  TOTAL PENDAPATAN',0,0,'L',false);
	  		$pdf->SetX(8);
	  		$TOTAL_PENDAPATAN = ($payroll_data->total_pagi+$payroll_data->total_siang)/2 * $payroll_data->gaji_pokok;
	  		$pdf->Cell($table_col_width-1,5,number_format($TOTAL_PENDAPATAN,0,',','.').'   ',0,0,'R',false);

	  		$pdf->Cell(1,5,null,0,0,'L',false); //separator

	  		$X = $pdf->GetX();
	  		$pdf->Cell($table_col_width,5,'  TOTAL POTONGAN',0,0,'L',false);
	  		$pdf->SetX($X);
	  		$pdf->Cell($table_col_width,5,number_format($payroll_data->potongan,0,',','.').'   ',0,2,'R',false);

	  		// TERBILANG 
	  		$GAJI_BERSIH = $TOTAL_PENDAPATAN - $payroll_data->potongan;
	  		$pdf->Ln(5);
	  		$pdf->SetX(8);
	  		$pdf->SetFont('Arial','I',8);
	  		$pdf->Cell($pdf->GetPageWidth()/2 - 9,10,'* '.strtoupper(Terbilang($GAJI_BERSIH)) . ' RUPIAH','B',0,'L',false);
	  		// TOTAL GAJI BERSIH
	  		$pdf->SetFont('Arial','B',12);
	  		$pdf->SetTextColor(255,255,255);
	  		$pdf->SetX($pdf->GetPageWidth() - 90 - 8);
	  		$pdf->Cell(30,10,'GAJI BERSIH',0,0,'L',true);
	  		$pdf->Cell(60,10,number_format($GAJI_BERSIH,0,',','.'),0,2,'R',true);

	  		// CATATAN
	  		$pdf->Ln(10);
	  		$y = $pdf->GetY();
	  		$pdf->SetX(8);
	  		$pdf->SetTextColor(0,0,0);
	  		$pdf->SetFont('Arial',null,7);
	  		$CATATAN_1 = \DB::table('appsetting')->whereName('payslip_catatan_1')->first()->value;
	  		$CATATAN_2 = \DB::table('appsetting')->whereName('payslip_catatan_2')->first()->value;
	  		$CATATAN_3 = \DB::table('appsetting')->whereName('payslip_catatan_3')->first()->value;
	  		$pdf->Cell($table_col_width-1,4,$CATATAN_1,0,2,'L',false);
	  		$pdf->Cell($table_col_width-1,4,$CATATAN_2,0,2,'L',false);
	  		$pdf->Cell($table_col_width-1,4,$CATATAN_3,0,2,'L',false);

	  		// TERTANDA
	  		$pdf->SetFont('Arial',null,8);
	  		$pdf->SetXY($pdf->GetPageWidth()/2,$y);
	  		$pdf->Cell(0,5,'Hormat kami,',0,0,'R',false);

	  		$pdf->Ln(15);
	  		$pdf->SetX($pdf->GetPageWidth()/2);
	  		$pdf->Cell(0,5,\DB::table('users')->find($payroll_data->user_id)->name,0,0,'R',false);
	  		$pdf->Ln(3);
	  		$pdf->SetFont('Arial','B',8);
	  		$pdf->Cell(0,5,\DB::table('appsetting')->whereName('payslip_tertanda')->first()->value,0,2,'R',false);

	  		// if($with_copy){
	  		// 	$pdf->AddPage();
	  		// 	$this->generatePdfContent($pdf,$payroll_data,false);
	  		// 	$with_copy = false;
	  		// }
	  			// $this->generatePdfContent($pdf,$payroll_data,true);
	  	}

	  	// PRINT PDF & COPY
	  	public function printCopy($payroll_id){
	  		$payroll_data = \DB::table('view_payroll_driver')->find($payroll_id);

	  		$pdf = new \App\Helpers\MyPdf('L','mm',array(210,148.5));
	  		$pdf->AddPage();
	  		$this->generatePdfContent($pdf, $payroll_data, true);


	  		if(\DB::table('appsetting')->whereName('slip_copy_number')->first()->value == 3){
		  		$pdf->AddPage();
		  		$this->generatePdfContent($pdf, $payroll_data, true);
	  		}

	  		$pdf->AddPage();
	  		$this->generatePdfContent($pdf, $payroll_data, false);
	  		$pdf->Output();
	  		exit;
	  	}
	  	// PRINT DIRECT
	  	public function printDirect($payroll_id){
	  		echo 'print direct';
	  	}


}
