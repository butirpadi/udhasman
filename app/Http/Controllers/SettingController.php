<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
	public function index(){
		return view('setting.index',[
				
			]);
	}

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			\DB::table('appsetting')
				->where('name','company_name')
				->update([
					'value' => $req->company_name,
				]);

			\DB::table('appsetting')
				->where('name','alamat_1')
				->update([
					'value' => $req->alamat_1,
				]);

			\DB::table('appsetting')
				->where('name','alamat_2')
				->update([
					'value' => $req->alamat_2,
				]);

			\DB::table('appsetting')
				->where('name','telp')
				->update([
					'value' => $req->telp,
				]);

			if($req->hasFile('company_logo')){
				echo 'ganti logo';
				// delete foto sebelumnya
				try{
					unlink(base_path() . '/public/img/'. Appsetting('company_logo'));					
				}catch(\ErrorException $e){

				}

				// insert foto baru
				$logo = $req->company_logo;
				$logo_name = 'logo_' . str_random(10) . '.'.$logo->getClientOriginalExtension();

				$logo->move(
					base_path() . '/public/img/', $logo_name
				);

				// update database
				\DB::table('appsetting')
					->where('name','company_logo')
					->update([
						'value' => $logo_name,
					]);				
			}

			return redirect()->back();
		});
	}

}
