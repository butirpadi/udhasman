<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PartnerController extends Controller
{
	public function index(){
		$data = \DB::table('res_partner')
				->orderBy('created_at','desc')
				->whereCustomer('N')
				->whereSupplier('N')
				->whereStaff('N')
				->whereDriver('N')
				->get();
				// ->paginate(Appsetting('paging_item_number'));
		return view('partner.index',[
				'data' => $data
			]);
	}

	public function create(){
		$armadas = \DB::select('select id,kode,nopol from armada where armada.id not in (select ifnull(armada_id,0) from res_partner)');
		$armada = [];
		foreach($armadas as $dt){
			$armada[$dt->id] = $dt->nopol;
		}

		return view('partner.create',[
			'armada' => $armada
		]);
	}

	private function addLeadingZero($char,$length){
		$res = $char;
		for($i=0;$i<$length-strlen($char);$i++){
			$res = "0".$res;
		}
		return $res;
	}

	public function insert(Request $req){
		$id = "";
		\DB::transaction(function()use($req,&$id){
			// generate tanggal
	        $arr_tgl = explode('-',$req->tanggal);
	        $tgl = new \DateTime();
	        $tgl->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

	        // generate kode
	        $kode = "";
	        if($req->partner_type == 'partner'){
	        	$prefix = Appsetting('partner_prefix');
		        $counter = Appsetting('partner_counter') ;
		        UpdateAppsetting('partner_counter',$counter+1);
		        $kode = $prefix . $this->addLeadingZero($counter+1,5);
			}else if($req->partner_type == 'supplier'){
				$prefix = Appsetting('supplier_prefix');
		        $counter = Appsetting('supplier_counter') ;
		        UpdateAppsetting('supplier_counter',$counter+1);
		        $kode = $prefix . $this->addLeadingZero($counter+1,5);
			}else if($req->partner_type == 'customer'){
				$prefix = Appsetting('customer_prefix');
		        $counter = Appsetting('customer_counter') ;
		        UpdateAppsetting('customer_counter',$counter+1);
		        $kode = $prefix . $this->addLeadingZero($counter+1,5);
			}else if($req->partner_type == 'staff'){
				$prefix = Appsetting('staff_prefix');
		        $counter = Appsetting('staff_counter') ;
		        UpdateAppsetting('staff_counter',$counter+1);
		        $kode = $prefix . $this->addLeadingZero($counter+1,5);
			}else if($req->partner_type == 'driver'){
				$prefix = Appsetting('driver_prefix');
		        $counter = Appsetting('driver_counter') ;
		        UpdateAppsetting('driver_counter',$counter+1);
		        $kode = $prefix . $this->addLeadingZero($counter+1,5);
			}

			$id = \DB::table('res_partner')->insertGetId([
					'kode' => $kode,
					'customer' => $req->partner_type == 'customer'?'Y':'N',
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
					'driver' => $req->partner_type == 'driver'?'Y':'N',
					'staff' => $req->partner_type == 'staff'?'Y':'N',
					'nama' => $req->nama,
					'panggilan' => $req->panggilan,
					'ktp' => $req->ktp,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'tempat_lahir' => $req->tempat_lahir,
					'tgl_lahir' => $tgl,
					'gaji_pokok' => str_replace(',', '', str_replace('.00','',$req->gaji_pokok)),
					'npwp' => $req->npwp,
					'owner' => $req->owner,
					'armada_id' => $req->armada,
					'user_id' =>  \Auth::user()->id,
				]);

			//insert foto
			$foto_name= "";
			if($req->foto){
				$foto = $req->foto;
				$foto_name = 'foto_' . str_random(10) . $id . '.'.$foto->getClientOriginalExtension();

				$foto->move(
					base_path() . '/public/foto/', $foto_name
				);

				// update ke table karyawan
				\DB::table('res_partner')
					->where('id',$id)->update([
						'foto' => $foto_name
					]);
			}
			
		});


		return redirect('master/partner/edit/'.$id);
		
	}


	public function edit($id){
		$data = \DB::table('res_partner')
					->select('res_partner.*','armada.nama as armada',\DB::raw('date_format(res_partner.tgl_lahir,"%d-%m-%Y") as tgl_lahir_format'),\DB::raw('desa.name as desa'),\DB::raw('kecamatan.name as kecamatan'),\DB::raw('kabupaten.name as kabupaten'),\DB::raw('provinsi.name as provinsi'))
					->leftJoin('armada','res_partner.armada_id','=','armada.id')
					->leftJoin('desa','res_partner.desa_id','=',\DB::raw('desa.id COLLATE utf8_unicode_ci'))
					->leftJoin('kecamatan','desa.kecamatan_id','=','kecamatan.id')
					->leftJoin('kabupaten','kecamatan.kabupaten_id','=','kabupaten.id')
					->leftJoin('provinsi','kabupaten.provinsi_id','=','provinsi.id')
					->where('res_partner.id',$id)
					->first();

		if($data->driver =='Y'){
			$where_armada_id = 'where res_partner.armada_id != ' . $data->armada_id ;
		}else{
			$where_armada_id = 'where true';
		}

		$armadas = \DB::select('select id,kode,nopol from armada where armada.id not in (select ifnull(armada_id,0) from res_partner ' . $where_armada_id . ' )');
		$armada = [];
		foreach($armadas as $dt){
			$armada[$dt->id] = $dt->nopol;
		}

		return view('partner.edit',[
			'data' => $data,
			'armada' => $armada
		]);
	}

	public function update(Request $req){
		$id = $req->original_id;
		\DB::transaction(function()use($req,&$id){
			// generate tanggal
	        $arr_tgl = explode('-',$req->tanggal);
	        $tgl = new \DateTime();
	        $tgl->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			\DB::table('res_partner')
					->whereId($id)
					->update([
					'customer' => $req->partner_type == 'customer'?'Y':'N',
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
					'driver' => $req->partner_type == 'driver'?'Y':'N',
					'staff' => $req->partner_type == 'staff'?'Y':'N',
					'nama' => $req->nama,
					'panggilan' => $req->panggilan,
					'ktp' => $req->ktp,
					'alamat' => $req->alamat,
					'desa_id' => $req->desa_id,
					'telp' => $req->telp,
					'tempat_lahir' => $req->tempat_lahir,
					'tgl_lahir' => $tgl,
					'gaji_pokok' => str_replace(',', '', str_replace('.00','',$req->gaji_pokok)),
					'npwp' => $req->npwp,
					'owner' => $req->owner,
					'armada_id' => $req->armada,
				]);

			//insert foto
			$foto_name= "";
			if($req->foto){
				// delete foto sebelumnya
				$foto_lama = \DB::table('res_partner')->find($id)->foto;
				 if(file_exists(base_path() . '/public/foto/'. $foto_lama)){
			        @unlink(base_path() . '/public/foto/'. $foto_lama);
			     }

				// insert foto baru
				$foto = $req->foto;
				$foto_name = 'foto_' . str_random(10) . $id . '.'.$foto->getClientOriginalExtension();

				$foto->move(
					base_path() . '/public/foto/', $foto_name
				);

				// update ke table karyawan
				\DB::table('res_partner')
					->where('id',$id)->update([
						'foto' => $foto_name
					]);
			}
			
		});


		return redirect('master/partner/edit/'.$id);
	}

	public function filter($val){
		
		if ($val == 'partner'){
			$data = \DB::table('res_partner')
					->whereCustomer('N')
					->whereSupplier('N')
					->whereStaff('N')
					->whereDriver('N')
					->orderBy('created_at','desc')
					->paginate(Appsetting('paging_item_number'));
		}else{
			$data = \DB::table('res_partner')
				->where($val,'Y')
				->orderBy('created_at','desc')
				->paginate(Appsetting('paging_item_number'));	
		}
		return view('partner.filter',[
				'data' => $data,
				'filter' => $val
			]);
	}

	public function search(){
		$val = \Input::get('val');
		$data = \DB::table('res_partner')
				->where('kode','like','%'.$val.'%')
				->orWhere('nama','like','%'.$val.'%')
				->orderBy('created_at','desc')
				->paginate(Appsetting('paging_item_number'));	

		return view('partner.search',[
				'data' => $data
			]);
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('res_partner')->delete($dt->id);
			}

			return redirect('master/partner');

		});
	}


}
