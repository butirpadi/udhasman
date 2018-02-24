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

			\DB::table('appsetting')
				->where('name','partner_prefix')
				->update([
					'value' => $req->partner_prefix,
				]);

			\DB::table('appsetting')
				->where('name','alat_prefix')
				->update([
					'value' => $req->alat_prefix,
				]);

			\DB::table('appsetting')
				->where('name','driver_prefix')
				->update([
					'value' => $req->driver_prefix,
				]);	
			\DB::table('appsetting')
				->where('name','customer_prefix')
				->update([
					'value' => $req->customer_prefix,
				]);
			\DB::table('appsetting')
				->where('name','supplier_prefix')
				->update([
					'value' => $req->supplier_prefix,
				]);
			\DB::table('appsetting')
				->where('name','pembelian_prefix')
				->update([
					'value' => $req->pembelian_prefix,
				]);
			\DB::table('appsetting')
				->where('name','pengiriman_prefix')
				->update([
					'value' => $req->pengiriman_prefix,
				]);
			\DB::table('appsetting')
				->where('name','operasional_alat_prefix')
				->update([
					'value' => $req->operasional_alat_prefix,
				]);
			\DB::table('appsetting')
				->where('name','payroll_prefix')
				->update([
					'value' => $req->payroll_prefix,
				]);
			\DB::table('appsetting')
				->where('name','hutang_prefix')
				->update([
					'value' => $req->hutang_prefix,
				]);
			\DB::table('appsetting')
				->where('name','piutang_prefix')
				->update([
					'value' => $req->piutang_prefix,
				]);
			\DB::table('appsetting')
				->where('name','cashbook_debit_prefix')
				->update([
					'value' => $req->cashbook_debit_prefix,
				]);
			\DB::table('appsetting')
				->where('name','cashbook_credit_prefix')
				->update([
					'value' => $req->cashbook_credit_prefix,
				]);
			\DB::table('appsetting')
				->where('name','master_payment_in_prefix')
				->update([
					'value' => $req->master_payment_in_prefix,
				]);
			\DB::table('appsetting')
				->where('name','paging_item_number')
				->update([
					'value' => $req->paging_item_number,
				]);

			if($req->hasFile('login_background')){
				// delete foto sebelumnya
				try{
					unlink(base_path() . '/public/img/'. Appsetting('login_background'));					
				}catch(\ErrorException $e){

				}

				// insert foto baru
				$login_bg = $req->login_background;
				$login_bg_name = 'logo_' . str_random(10) . '.'.$login_bg->getClientOriginalExtension();

				$login_bg->move(
					base_path() . '/public/img/', $login_bg_name
				);

				// update database
				\DB::table('appsetting')
					->where('name','login_background')
					->update([
						'value' => $login_bg_name,
					]);				
			}



			return redirect()->back();
		});
	}

}
