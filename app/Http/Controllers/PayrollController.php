<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PayrollController extends Controller
{

	// public function index(){
	// 	$data = \DB::table('payroll')

	// 	return view('payroll.index');
	// }

	public function index($defaultVal = null){
		return view('payroll.payroll.index',[
			'defaultVal' => $defaultVal
		]);
	}

	public function getPayDay(Request $req){
		$payday = \DB::table('appsetting')->where('name','payroll_day')->first()->value;

		$hari = "";
		if($payday == 0){
			$hari  = "Minggu";
		}else if($payday == 1){
			$hari  = "Senin";
		}else if($payday == 2){
			$hari  = "Selasa";
		}else if($payday == 3){
			$hari  = "Rabu";
		}else if($payday == 4){
			$hari  = "Kamis";
		}else if($payday == 5){
			$hari  = "Jumat";
		}else if($payday == 6){
			$hari  = "Sabtu";
		}


		$firstDateOfMonth = '01-' . $req->bulan;

		// generate tanggal
	$arr_tgl = explode('-',$firstDateOfMonth);
	$firstDateOfMonth = new \DateTime();
	$firstDateOfMonth->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

	$select_pay_day = [];
	// 0 = sunday

	for($i=1;$i<=$firstDateOfMonth->format('t');$i++){
		$aDate = new \DateTime();
		// echo $i . '  -   ' . $aDate->setDate($arr_tgl[2],$arr_tgl[1],$i)->format('w') .  '<br/>';
		if($aDate->setDate($arr_tgl[2],$arr_tgl[1],$i)->format('w') == $payday){
			array_push($select_pay_day, ['tanggal' => $i, 'tanggal_full' => $hari .', ' . $i .'-'.$arr_tgl[1].'-'.$arr_tgl[2]] );
		}
	}

	echo json_encode($select_pay_day);

	}

	// public function showPayrollTable(Request $req){
	// 	return $req->jabatan == 'ST' ? $this->showStaffTable($req) : $this->showDriverTable($req);
	// } 

	public function showPayrollTable($tanggal, $kode_jabatan){
		if($kode_jabatan == 'ST'){
			return redirect('payroll/payroll-staff/show-payroll-table/'.$tanggal);
		}else{
			return redirect('payroll/payroll-driver/show-payroll-table/'.$tanggal);

		}

		return $kode_jabatan == 'ST' ? $this->showStaffTable($tanggal) : $this->showDriverTable($tanggal);

	} 


}
