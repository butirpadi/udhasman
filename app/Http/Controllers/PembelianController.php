<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PembelianController extends Controller
{
	public function index(){

		$data = \DB::table('view_pembelian')
					->orderBy('tanggal','desc')
					->orderBy('id','desc')
					->paginate(Appsetting('paging_item_number'));
					// ->get();
		// $amount_due = \DB::table('supplier_bill')->sum('amount_due');

		return view('pembelian.index',[
				'data' => $data,
				// 'amount_due' => $amount_due,
				// 'paging_item_number' => $paging_item_number
			]);
	}

	// ====================================================================================================

	public function create(){
		$supplier = \DB::table('res_partner')
						->whereSupplier('Y')
						->get();
		$select_supplier = [];
		foreach($supplier as $dt){
			$select_supplier[$dt->id] = $dt->nama;
		}

		$product = \DB::table('view_product')->get();
		$select_product = [];
		foreach($product as $dt){
			$select_product[$dt->id] = $dt->nama;
		}

		return view('pembelian.create',[
				'select_supplier' => $select_supplier,
				'product' => $product,
			]);
	}

	// ====================================================================================================

	public function insert(Request $req){
		return \DB::transaction(function()use($req){
			$master = json_decode($req->po_master);
			$detail = json_decode($req->po_product);
			$user =  \Auth::user();

			// generate tanggal
	        $arr_tgl = explode('-',$master->tanggal);
	        $tanggal = new \DateTime();
	        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			// generate nomor pembelian
	        $prefix = Appsetting('pembelian_prefix');
	        $counter = Appsetting('pembelian_counter');
	        $nomor_pembelian = $prefix.'/'.date('Y/').$counter++;

	        // insert into pembelian
	        $pembelian_id = \DB::table('pembelian')->insertGetId([
	        		'supplier_ref' => $master->nomor_nota,
	        		'ref' => $nomor_pembelian,
	        		'tanggal' => $tanggal,
	        		'supplier_id' => $master->supplier_id,
	        		'subtotal' => $master->subtotal,
	        		'disc' => $master->disc,
	        		'total' => $master->total,
	        		'user_id' =>$user->id,
	        	]);

	        // insert ke detail pembelian
	        foreach($detail->product as $dt){
	        	\DB::table('pembelian_detail')->insert([
	        			'pembelian_id' => $pembelian_id,
	        			'product_id' => $dt->id,
	        			'qty' => $dt->qty,
	        			'unit_price' => $dt->unit_price,
	        			'user_id' => $user->id,
	        		]);
	        }

	        // update counter
	        UpdateAppsetting('pembelian_counter',$counter);

	        // return ke halaman depan
	        // return redirect('pembelian');
	        return redirect('pembelian/edit/'.$pembelian_id);
			
		});
	}

	// ====================================================================================================

	public function edit($id){
		$data = \DB::table('view_pembelian')->find($id);
		$next = \DB::table('view_pembelian')
					->where('id','>',$id)
					->orderBy('id','asc')
					->first();
		$prev = \DB::table('view_pembelian')
					->where('id','<',$id)
					->orderBy('id','desc')
					->first();
		$data->detail = \DB::table('view_pembelian_detail')->wherePembelianId($id)->get();

		$supplier = \DB::table('res_partner')
					->whereSupplier('Y')
					->get();
		$select_supplier = [];
		foreach($supplier as $dt){
			$select_supplier[$dt->id] = $dt->nama;
		}

		$product = \DB::table('view_product')->get();
		$select_product = [];
		foreach($product as $dt){
			$select_product[$dt->id] = $dt->nama;
		}

		return view('pembelian.edit',[
				'select_supplier' => $select_supplier,
				'product' => $product,
				'data' => $data,
				'next' => $next,
				'prev' => $prev,
			]);

		// if($data->status == 'VALIDATED'){
		// 	return view('pembelian.validated',[
		// 			'data' => $data,
		// 			'next' => $next,
		// 			'prev' => $prev,
		// 		]);
		// }elseif($data->status == 'OPEN'){
		// 	$supplier = \DB::table('res_partner')
		// 				->whereSupplier('Y')
		// 				->get();
		// 	$select_supplier = [];
		// 	foreach($supplier as $dt){
		// 		$select_supplier[$dt->id] = $dt->nama;
		// 	}

		// 	$product = \DB::table('view_product')->get();
		// 	$select_product = [];
		// 	foreach($product as $dt){
		// 		$select_product[$dt->id] = $dt->nama;
		// 	}

		// 	return view('pembelian.edit',[
		// 			'select_supplier' => $select_supplier,
		// 			'product' => $product,
		// 			'data' => $data,
		// 			'next' => $next,
		// 			'prev' => $prev,
		// 		]);
		// }elseif($data->status == 'CANCELED'){
		// 	return view('pembelian.canceled',[
		// 			'data' => $data,					
		// 			'next' => $next,
		// 			'prev' => $prev,
		// 		]);
		// }

	}

	// ====================================================================================================

	public function update(Request $req){
		return \DB::transaction(function()use($req){
			$master = json_decode($req->po_master);
			$detail = json_decode($req->po_product);
			

			$data_org = \DB::table('pembelian')->find($master->id);
			$data_org->detail = \DB::table('pembelian_detail')->wherePembelianId($master->id)->get();

			// generate tanggal
	        $arr_tgl = explode('-',$master->tanggal);
	        $tanggal = new \DateTime();
	        $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

			// update data master
			\DB::table('pembelian')
				->whereId($master->id)
				->update([
						'supplier_ref' => $master->nomor_nota,
		        		'tanggal' => $tanggal,
		        		'supplier_id' => $master->supplier_id,
		        		'subtotal' => $master->subtotal,
		        		'disc' => $master->disc,
		        		'total' => $master->total,
					]);

			// // searching data yang lama, cek apakah ada perubahan jumlah atau harga
			// // foreach($data_org as $dt){
			// 	foreach($detail->product as $dt_new){
			// 		$found = \DB::table('pembelian_detail')
			// 					->where('pembelian_id',$master->id)
			// 					->where('product_id',$dt_new->id)
			// 					->count();

			// 		if($found > 0){
			// 			// update
			// 			\DB::table('pembelian_detail')
			// 			->where('pembelian_id',$master->id)
			// 			->where('product_id',$dt_new->id)
			// 			->update([
			// 					'qty' => $dt_new->qty,
			// 					'unit_price' => $dt_new->unit_price,
			// 				]);
			// 		}else{
			// 			// insert new
			// 			\DB::table('pembelian_detail')
			// 				->insert([
			// 						'pembelian_id' => $master->id,
			// 						'product_id' => $dt_new->id,
			// 						'qty' => $dt_new->qty,
			// 						'unit_price' => $dt_new->unit_price,
			// 						'user_id' => $data_org->user_id
			// 					]);
			// 		}
			// 	}
			// }

			// hapus data yang lama
			\DB::table('pembelian_detail')
					->where('pembelian_id',$master->id)
					->delete();
			// insert data yang baru
			foreach($detail->product as $dt_new){
				// insert new
				\DB::table('pembelian_detail')
					->insert([
							'pembelian_id' => $master->id,
							'product_id' => $dt_new->id,
							'qty' => $dt_new->qty,
							'unit_price' => $dt_new->unit_price,
							'user_id' => $data_org->user_id
						]);
				}

			return redirect('pembelian/edit/'.$master->id);
		});
	}

	// ================================================================================================

	public function validateIt($id){
		\DB::transaction(function()use($id){
			// update status pembelian ke validated
			\DB::table('pembelian')
				->where('id',$id)
				->update([
					'state' => 'done'
				]);
			$pembelian = \DB::table('view_pembelian')->find($id);

			// add to hutang
			// generate nomor hutang
	        $prefix = Appsetting('hutang_prefix');
	        $counter = Appsetting('hutang_counter');
	        $nomor_inv = $prefix.'/'.date('Y/m').$counter++;
	        // update counter
	        UpdateAppsetting('hutang_counter',$counter);
	        // insert hutang
			\DB::table('hutang')->insert([
				'name' => $nomor_inv,
				'partner_id' => $pembelian->supplier_id,
				'tanggal' => date('Y-m-d'),
				'state' => 'open',
				'source' => $pembelian->ref,
				'po_id' => $pembelian->id,
				'desc' => 'Hutang Dagang ' . $pembelian->supplier,
				'type' => 'pembelian',
				'jumlah' => $pembelian->total,
				'amount_due' => $pembelian->total,
			]);
			// update bill state di pembelian
			\DB::table('pembelian')
				->where('id',$id)
				->update([
					'bill_state' => 'open'
				]);
			
		});

		return redirect('pembelian/edit/'.$id);
	}

	public function cancelIt($id){
		\DB::table('pembelian')
			->whereId($id)
			->update([
				'state' => 'draft'
			]);

		return redirect()->back();
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataid);
		return \db::transaction(function()use($dataid){
			// delete dari database
			foreach($dataid as $dt){
				\DB::table('pembelian')->delete($dt->id);
			}

			return redirect('pembelian');

		});
	}

	public function search(Request $req){
		$data = \DB::table('view_pembelian')
					->where('supplier','like','%'.$req->val.'%')
					->orWhere('ref','like','%'.$req->val.'%')
					->orderBy('tanggal','desc')
					->paginate(Appsetting('paging_item_number'));

		return view('pembelian.search',[
				'data' => $data,
				'search_val' => $req->val
			]);
	}

	public function filter($filterby,$val){
		$data = \DB::table('view_pembelian')
					->where($filterby,$val)
					->orderBy('tanggal','desc')
					->paginate(Appsetting('paging_item_number'));

		return view('pembelian.filter',[
				'data' => $data,
				'filterby' => $filterby,
				'filter' => $val
			]);
	}

	public function groupby($val){
		$data = \DB::table('view_pembelian')
	        ->select('*',\DB::raw('count(id) as jumlah'),\DB::raw('sum(view_pembelian.total) as sum_total'))
	        ->groupBy($val)
	        ->paginate(Appsetting('paging_item_number'));     

        return view('pembelian.group',[
                'data' => $data,
                'groupby' => $val,
            ]);
	}

	public function groupdetail($groupby,$id){
		$pembelian = \DB::table('view_pembelian')
					->where($groupby.'_id','=',$id)
					->orderBy('tanggal','desc')
					->orderBy('id','desc')
					->get();

		return $pembelian;
	}

	


//. END OF CODE
}
