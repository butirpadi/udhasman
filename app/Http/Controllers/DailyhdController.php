<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DailyhdController extends Controller
{
	public function index(){
		$data = \DB::table('view_dailyhd')
				->orderBy('tanggal','desc')
				->paginate(Appsetting('paging_item_number'));
		
		return view('dailyhd.index',[
				'data' => $data
			]);
	}

	public function create(){
		$alat = \DB::table('alat')->get();
		$select_alat = [];
		foreach($alat as $dt){
			$select_alat[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}

		$galian = \DB::table('lokasi_galian')->get();
		$select_galian = [];
		foreach($galian as $dt){
			$select_galian[$dt->id] =  $dt->nama;
		}

		$staff = \DB::table('res_partner')->whereStaff('Y')->get();
		$select_staff = [];
		foreach($staff as $dt){
			$select_staff[$dt->id] = $dt->nama;
		}

		return view('dailyhd.create',[
				'selectAlat' => $select_alat,
				'selectGalian' => $select_galian,
				'selectStaff' => $select_staff,
			]);
	}

	public function insert(Request $req){
		return \DB::transaction(function()use($req){
			// generate number
			$dailyhd_counter = \DB::table('appsetting')->whereName('dailyhd_counter')->first()->value;
			$prefix = Appsetting('operasional_alat_prefix');
			$dailyhd_number = $prefix .'/'. date('Y') . '/000' . $dailyhd_counter++;
			\DB::table('appsetting')
				->whereName('dailyhd_counter')
				->update(['value'=>$dailyhd_counter]);

			// generate tanggal
            $tanggal = $req->tanggal;
            $arr_tgl = explode('-',$tanggal);
            $tanggal = new \DateTime();
            $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			$dailyhd_id = \DB::table('dailyhd')
				->insertGetId([
						'ref' => $dailyhd_number,
						'tanggal' => $tanggal,
						'alat_id' => $req->alat_id,
						'lokasi_galian_id' => $req->lokasi_id,
						'pengawas_id' => $req->pengawas_id,
						'operator_id' => $req->operator_id,
						'mulai' => $req->mulai,
						'selesai' => $req->selesai,
						'istirahat_mulai' => $req->istirahat_mulai,
						'istirahat_selesai' => $req->istirahat_selesai,
						'jam_kerja' => $req->total_jam_kerja,
						'oli' => $req->oli,
						'solar' => $req->solar,
						'desc' => $req->keterangan,
					]);
				
			// return redirect('dailyhd');
			return redirect('dailyhd/edit/'.$dailyhd_id);
		});

		
	}

	public function edit($id){
		$alat = \DB::table('alat')->get();
		$select_alat = [];
		foreach($alat as $dt){
			$select_alat[$dt->id] = $dt->kode . ' - ' . $dt->nama;
		}

		$galian = \DB::table('lokasi_galian')->get();
		$select_galian = [];
		foreach($galian as $dt){
			$select_galian[$dt->id] =  $dt->nama;
		}

		$staff = \DB::table('res_partner')->whereStaff('Y')->get();
		$select_staff = [];
		foreach($staff as $dt){
			$select_staff[$dt->id] = $dt->nama;
		}


		$data = \DB::table('view_dailyhd')->find($id);

		return view('dailyhd.edit',[
			'data'=>$data,
			'selectAlat' => $select_alat,
			'selectGalian' => $select_galian,
			'selectStaff' => $select_staff,
		]);
		
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			// generate tanggal
            $tanggal = $req->tanggal;
            $arr_tgl = explode('-',$tanggal);
            $tanggal = new \DateTime();
            $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			\DB::table('dailyhd')
				->whereId($req->dailyhd_id)
				->update([
						// 'ref' => $dailyhd_number,
						'tanggal' => $tanggal,
						'alat_id' => $req->alat_id,
						'lokasi_galian_id' => $req->lokasi_id,
						// 'pengawas_id' => $req->pengawas_id,
						'operator_id' => $req->operator_id,
						'mulai' => $req->mulai,
						'selesai' => $req->selesai,
						'istirahat_mulai' => $req->istirahat_mulai,
						'istirahat_selesai' => $req->istirahat_selesai,
						'jam_kerja' => $req->total_jam_kerja,
						'oli' => $req->oli,
						'solar' => $req->solar,
						'desc' => $req->keterangan,
					]);
				
			return redirect('dailyhd/edit/'.$req->dailyhd_id);
			// return redirect()->back();
		});
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		foreach($dataid as $dt){
			// echo $dt->id . '<br/>';
			\DB::table('dailyhd')->delete($dt->id);	
		}
		


	}

	public function cekDuplikasi($tgl, $alatid){
		// generate tanggal
        $tanggal = $tgl;
        $arr_tgl = explode('-',$tanggal);
        $tanggal = new \DateTime();
        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $tanggal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $data = \DB::table('dailyhd')
        		->where('tanggal',$tanggal_str)
        		->where('alat_id',$alatid)
        		->get();

        return count($data) > 0 ? 'true' : 'false';
	}

	public function toValidate(Request $req){
		\DB::table('dailyhd')->whereId($req->dailyhd_id)->update([
				'status' => 'V'
			]);

		// return redirect('dailyhd');
		return redirect()->back();
	}

	public function getSearch(){
		$val = \Input::get('val');
		$data = \DB::table('view_dailyhd')
						->where('alat','like','%' . trim($val) . '%')
						->orWhere('lokasi','like','%' . $val . '%')
						->orWhere('kode_lokasi','like','%' . $val . '%')
						->orWhere('kode_alat','like','%' . $val . '%')
						->orWhere('alat','like','%' . $val . '%')
						->orWhere('kode_pengawas','like','%' . $val . '%')
						->orWhere('nama_pengawas','like','%' . $val . '%')
						->orWhere('kode_operator','like','%' . $val . '%')
						->orWhere('nama_operator','like','%' . $val . '%')
						->orderBy('tanggal','desc')
						->orderBy('created_at','desc')
						->paginate(Appsetting('paging_item_number'));
						
		$data->appends(['val'=>$val]);

		return view('dailyhd.search',[
				'data' => $data,
				'search_val' => $val
			]);
	}

}
