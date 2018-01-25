<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
	public function index(){
		$data = \DB::table('res_partner')
				->where('supplier','Y')
				->orderBy('created_at','desc')
				->get();
		return view('supplier.index',[
				'data' => $data
			]);
	}

	public function create(){
		$armadas = \DB::select('select id,kode,nopol from armada where armada.id not in (select ifnull(armada_id,0) from res_partner)');
		$armada = [];
		foreach($armadas as $dt){
			$armada[$dt->id] = $dt->nopol;
		}

		return view('supplier.create',[
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
			}else if($req->partner_type == 'supplier'){
				$prefix = Appsetting('supplier_prefix');
		        $counter = Appsetting('supplier_counter') ;
		        UpdateAppsetting('supplier_counter',$counter+1);
		        $kode = $prefix . $this->addLeadingZero($counter+1,5);
			}else if($req->partner_type == 'supplier'){
				$prefix = Appsetting('supplier_prefix');
		        $counter = Appsetting('supplier_counter') ;
		        UpdateAppsetting('supplier_counter',$counter+1);
		        $kode = $prefix . $this->addLeadingZero($counter+1,5);
			}else if($req->partner_type == 'supplier'){
				$prefix = Appsetting('supplier_prefix');
		        $counter = Appsetting('supplier_counter') ;
		        UpdateAppsetting('supplier_counter',$counter+1);
		        $kode = $prefix . $this->addLeadingZero($counter+1,5);
			}

			$id = \DB::table('res_partner')->insertGetId([
					'kode' => $kode,
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
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


		return redirect('master/supplier/edit/'.$id);
		
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

		return view('supplier.edit',[
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
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
					'supplier' => $req->partner_type == 'supplier'?'Y':'N',
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


		return redirect('master/supplier/edit/'.$id);
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('res_partner')->delete($dt->id);
			}

			return redirect('master/supplier');

		});
	}


}
