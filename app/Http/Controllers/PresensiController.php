<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PresensiController extends Controller
{
	public function index(){
		$data = \DB::table('view_attend')
					->select('*',\DB::raw('(select count(tgl) from (select bulan,tgl from view_attend
					group by bulan,tgl) as attend_by_tgl where attend_by_tgl.bulan = view_attend.bulan) as jumlah'))
					->where('tgl','!=','')
					->groupBy('bulan')
					->orderBy('tgl','desc')
					->orderBy('bulan_idx','desc')
					->get();

		return view('presensi.index',[
			'data' => $data
		]);
	}

	public function getPresensiByBulan($bulan){
		$data = \DB::table('view_attend')
					->select('tgl_format',\DB::raw('count(*) as jumlah'))
					->where('bulan',$bulan)
					->groupBy('tgl')
					->orderBy('tgl','asc')
					->get();
		return json_encode($data);
	}
}