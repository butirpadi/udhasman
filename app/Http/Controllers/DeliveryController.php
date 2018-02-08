<?php 

// Created at : 08-12-2017

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\HtmlPdf;
use Spipu\Html2Pdf\Html2Pdf;

class DeliveryController extends Controller
{
	public function index(){
		$pengiriman = \DB::table('view_new_pengiriman')
						// ->where('state','!=','done')
						->orderBy('order_date','desc')
						->orderBy('created_at','desc')
						->paginate(Appsetting('paging_item_number'));
						// ->get();

		return view('delivery.index',[
				'pengiriman' => $pengiriman
			]);
	}

	public function create(){
		$drivers = \DB::table('res_partner')
							->select('res_partner.*','armada.nopol')
							->where('driver','Y')
							->join('armada','res_partner.armada_id','=','armada.id')
							->get();
		$driver = [];
		foreach($drivers as $dt){
			// $nopol = \DB::table('armada')->where('karyawan_id',$dt->id)->first();
			$driver[$dt->id] = $dt->nama . ' - ' . $dt->nopol;
		}

		$customer = \DB::table('res_partner')
					->whereCustomer('Y')
					->get();
		$select_customer = [];
		foreach($customer as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		$material = \DB::table('material')->get();
		$select_material = [];
		foreach($material as $dt){
			$select_material[$dt->id] = $dt->nama;
		}

		$lokasi_galian = \DB::table('lokasi_galian')->get();
		$select_lokasi = [];
		foreach($lokasi_galian as $dt){
			$select_lokasi[$dt->id] = $dt->nama;
		}

		return view('delivery.create',[
				'select_customer' => $select_customer,
				'lokasi_galian' => $select_lokasi,
				'material' => $select_material,
				'driver' => $driver,
			]);
	}

	public function insert(Request $req){
		$new_id=0;
		\DB::transaction(function()use($req,&$new_id){

			// generate tanggal
	        $order_date = $req->tanggal;
	        $arr_tgl = explode('-',$order_date);
	        $fix_order_date = new \DateTime();
	        $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 

	        // Generate DO Number
	        $month=date("m");
			$year=date("Y");
	        $prefix = \DB::table('appsetting')->where('name','pengiriman_prefix')->first()->value;
	        $counter = \DB::table('appsetting')->where('name','pengiriman_counter')->first()->value;
	        // $new_number = $prefix . '/'.$year.'/'.$month.'/'. $counter;
	        $new_number = $prefix . '/'.$year.'/'.$month. $counter;
	        // update counter
	        \DB::table('appsetting')
	        		->where('name','pengiriman_counter')
	        		->update([
	        			'value' => $counter+1
	        		  ]);


			$new_do_id = \DB::table('new_pengiriman')->insertGetId([
				'name' => $new_number,
				'order_date' => $fix_order_date,
				'customer_id' => $req->customer,
				'pekerjaan_id' => $req->pekerjaan,
				'karyawan_id' => $req->driver,
				'nopol' => $req->nopol,
				'material_id' => $req->material,
				'lokasi_galian_id' => $req->lokasi_galian,
				'nota_timbang' => $req->nota_timbang,
				'state' => 'draft',
			]);

			$new_pengiriman = \DB::table('new_pengiriman')->find($new_do_id);
			$new_id = $new_do_id;

			

		});

		// return redirect('delivery/show/'.$new_id);
		return redirect('delivery/edit/'.$new_id);

	}


	public function show($id){
		$pengiriman = \DB::table('view_new_pengiriman')->find($id);

		$drivers = \DB::table('res_partner')
							->select('res_partner.*','armada.nopol')
							->where('driver','Y')
							->join('armada','res_partner.armada_id','=','armada.id')
							->get();
		$driver = [];
		foreach($drivers as $dt){
			$driver[$dt->id] = $dt->nama . ' - ' . $dt->nopol;
		}

		$customer = \DB::table('res_partner')
					->whereCustomer('Y')
					->get();
		$select_customer = [];
		foreach($customer as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		$material = \DB::table('material')->get();
		$select_material = [];
		foreach($material as $dt){
			$select_material[$dt->id] = $dt->nama;
		}

		if($pengiriman){
			return view('delivery.show',[
				'select_customer' => $select_customer,
				'material' => $select_material,
				'driver' => $driver,
				'pengiriman' => $pengiriman,
			]);
		}else{
			return view('notfound',[]);
		}
	}

	public function edit($id){
		$pengiriman = \DB::table('view_new_pengiriman')->find($id);

		$drivers = \DB::table('res_partner')
							->select('res_partner.*','armada.nopol')
							->where('driver','Y')
							->join('armada','res_partner.armada_id','=','armada.id')
							->get();
		$driver = [];
		foreach($drivers as $dt){
			$driver[$dt->id] = $dt->nama . ' - ' . $dt->nopol;
		}

		$customer = \DB::table('res_partner')
					->whereCustomer('Y')
					->get();
		$select_customer = [];
		foreach($customer as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

		$material = \DB::table('material')->get();
		$select_material = [];
		foreach($material as $dt){
			$select_material[$dt->id] = $dt->nama;
		}

		$lokasi_galian = \DB::table('lokasi_galian')->get();
		$select_lokasi = [];
		foreach($lokasi_galian as $dt){
			$select_lokasi[$dt->id] = $dt->nama;
		}

		// $next = \DB::table('view_new_pengiriman')
		// 			->where('order_date','>=',$pengiriman->order_date)
		// 			->orderBy('created_at','asc')
		// 			->first();
		// $next = \DB::select("select * from view_new_pengiriman where order_date >= '". $pengiriman->order_date ."' and id > ". $pengiriman->id ." order by created_at asc limit 1");
		// $prev = \DB::table('view_new_pengiriman')
		// 			->where('order_date','<=',$pengiriman->order_date)
		// 			->orderBy('created_at','desc')
		// 			->first();
		// $prev = \DB::select("select * from view_new_pengiriman where order_date <= '". $pengiriman->order_date . "' and id < ". $pengiriman->id . " order by created_at desc limit 1");

		// $dt = \DB::select('call ordered_new_pengiriman()');
		// foreach($dt as $d){
		// 	echo $d->num . ' - ' . $d->name . '<br/>';
		// }

		if($pengiriman){
			return view('delivery.edit',[
				'select_customer' => $select_customer,
				'lokasi_galian' => $select_lokasi,
				'material' => $select_material,
				'driver' => $driver,
				'pengiriman' => $pengiriman,
			]);
		}else{
			return view('notfound',[]);
		}
	}

	public function update(Request $req){
		\DB::transaction(function()use($req){

			// generate tanggal
	        $order_date = $req->tanggal;
	        $arr_tgl = explode('-',$order_date);
	        $fix_order_date = new \DateTime();
	        $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 

	        $data_do = \DB::table('new_pengiriman')->find($req->original_id);

	        $qty = 1;
	        if ($req->kalkulasi == 'ton'){
	        	$qty = number_format($req->gross - $req->tare,2);
	        }elseif($req->kalkulasi == 'kubik'){
	        	$qty = number_format($req->panjang * $req->lebar * $req->tinggi,2);
	        }
	        $harga_total = $qty * str_replace(',', '', str_replace('.00','',$req->harga_satuan));

	        \DB::table('new_pengiriman')
	        ->where('id','=',$req->original_id )
	        ->update([
				'order_date' => $fix_order_date,
				'customer_id' => $req->customer,
				'pekerjaan_id' => $req->pekerjaan,
				'lokasi_galian_id' => $req->lokasi_galian,
				'karyawan_id' => $req->driver,
				'nopol' => $req->nopol,
				'material_id' => $req->material,
				'harga_satuan' => str_replace(',', '', str_replace('.00','',$req->harga_satuan)),
				'nota_timbang' => $req->nota_timbang,
				'harga_total' => $harga_total,
				'kalkulasi' => $req->kalkulasi,
			]);

	        // update data kalkulasi
			if($req->kalkulasi == 'rit'){
				\DB::table('new_pengiriman')
		        ->where('id','=',$data_do->id)
		        ->update([
					'qty' => 1,
					'panjang' => 0,
					'lebar' => 0,
					'tinggi' => 0,
					'volume' => 0,
					'gross' => 0,
					'tare' => 0,
					'netto' => 0,
				]);
			}elseif($req->kalkulasi == 'kubik'){
				\DB::table('new_pengiriman')
		        ->where('id','=',$data_do->id)
		        ->update([
					'qty' => 0,
					'panjang' => $req->panjang,
					'lebar' => $req->lebar,
					'tinggi' => $req->tinggi,
					'volume' => number_format($req->panjang * $req->lebar * $req->tinggi,2),
					'gross' => 0,
					'tare' => 0,
					'netto' => 0,
				]);
			}elseif($req->kalkulasi == 'ton'){
				\DB::table('new_pengiriman')
		        ->where('id','=',$data_do->id)
		        ->update([
					'qty' => 0,
					'panjang' => 0,
					'lebar' => 0,
					'tinggi' => 0,
					'volume' => 0,
					'gross' => $req->gross,
					'tare' => $req->tare,
					'netto' => number_format($req->gross - $req->tare,2),
				]);
			}

	   //      if($data_do->state != 'done'){
				// if(($req->kalkulasi != '' || $req->panjang != '' || $req->lebar != '' || $req->tinggi != '' || $req->gross != '' || $req->tare != '' || $req->harga_satuan != '')){
				// 	// set state ke open
				// 	\DB::table('new_pengiriman')
		  //       		->where('id','=',$data_do->id)
		  //       		->update([
				// 				'state' => 'open'
				// 				]);
				// }
	        	
	   //      }

		});

		return redirect('delivery/edit/'.$req->original_id);
	}

	public function toConfirm($id){
		\DB::table('new_pengiriman')
			->where('id',$id)
			->update([
				'state' => 'open'
			]);

		return redirect('delivery/edit/'.$id);
	}

	public function toValidate($id){
		\DB::transaction(function()use($id){

			$pengiriman = \DB::table('new_pengiriman')
							->find($id);

			\DB::table('new_pengiriman')
				->where('id',$id)
				->update([
					'state' => 'done'
				]);

				// GENERATE PIUTANG TELAH DI HANDLE OLEH TRIGGER DATATBASE
			
		});

		return redirect('delivery/edit/'.$id);
	}

	public function delete(Request $req){
		$dataid = json_decode($req->dataids);
		\DB::transaction(function()use($dataid){
			foreach($dataid as $dt){
				\DB::table('new_pengiriman')->delete($dt->id);	
			}	
		});

		return redirect('delivery');
		
	}	

	public function deleteSingle($id){
		\DB::table('new_pengiriman')->delete($id);
		return redirect('delivery');
	}

	public function toPdf($id){
		// $pdf = new HtmlPdf();
		// $pdf->SetFont('Arial','',12);
	 //    $pdf->AddPage();
	 //    $pdf->WriteHTML($this->showPdf($id));
	 //    $pdf->Output();
	 //    exit;

		// $html2pdf_path = base_path() . '\vendor\spipu\html2pdf\html2pdf.class.php';
        // \File::requireOnce($html2pdf_path);

        $html2pdf = new Html2Pdf('P', 'A4', 'en');
		$html2pdf->writeHTML($this->showPdf($id)->__toString());
		$html2pdf->output();

	}

	public function showPdf($id){
		$pengiriman = \DB::table('view_new_pengiriman')->find($id);
		$alamat_pekerjaan = \DB::table('view_pekerjaan')->find($pengiriman->pekerjaan_id);
		return view('delivery.pdf',[
				'pengiriman' => $pengiriman,
				'alamat' => $alamat_pekerjaan,
			]);
	}

	public function filter($filterby,$val){
		$pengiriman = \DB::table('view_new_pengiriman')
		->where($filterby,$val)
		->orderBy('order_date','desc')
		->paginate(Appsetting('paging_item_number'));

		return view('delivery.filter',[
				'pengiriman' => $pengiriman,
				'filterby' => $filterby,
				'filter' => $val,
			]);
	}

	public function groupby($val){
		$pengiriman = \DB::table('view_new_pengiriman')
		->select('*',\DB::raw('count(id) as jumlah'))
		->groupBy($val)
		->orderBy('order_date')
		->get();			

		return view('delivery.group',[
				'pengiriman' => $pengiriman,
				'group' => $val
			]);
		
	}

	public function groupdetail($groupby,$id){
		if($id != 0){
			$pengiriman = \DB::table('view_new_pengiriman')
			->where($groupby.'_id','=',$id)
			->orderBy('order_date')
			->get();			
		}else{
			$pengiriman = \DB::table('view_new_pengiriman')
			->where($groupby.'_id')
			->orderBy('order_date')
			->get();			
		}

		echo json_encode($pengiriman);
	}

	public function getSearch(){
		$val = \Input::get('val');
		$pengiriman = \DB::table('view_new_pengiriman')
						->where('name','like','%' . trim($val) . '%')
						->orWhere('customer','like','%' . $val . '%')
						->orWhere('state','like','%' . $val . '%')
						->orWhere('invoice_state','like','%' . $val . '%')
						->orWhere('pekerjaan','like','%' . $val . '%')
						->orWhere('karyawan','like','%' . $val . '%')
						->orWhere('nopol','like','%' . $val . '%')
						->orWhere('lokasi_galian','like','%' . $val . '%')
						->orWhere('material','like','%' . $val . '%')
						->orWhere('nota_timbang','like','%' . $val . '%')
						->orderBy('order_date','desc')
						->orderBy('created_at','desc')
						->paginate(Appsetting('paging_item_number'));
						// ->get();
		$pengiriman->appends(['val'=>$val]);

		return view('delivery.search',[
				'pengiriman' => $pengiriman,
				'search_val' => $val
			]);
	}

	// public function search(Request $req){
	// 	$pengiriman = \DB::table('view_new_pengiriman')
	// 					->where('name','like','%' . trim($req->val) . '%')
	// 					->orWhere('customer','like','%' . $req->val . '%')
	// 					->orWhere('state','like','%' . $req->val . '%')
	// 					->orWhere('pekerjaan','like','%' . $req->val . '%')
	// 					->orWhere('karyawan','like','%' . $req->val . '%')
	// 					->orWhere('nopol','like','%' . $req->val . '%')
	// 					->orWhere('lokasi_galian','like','%' . $req->val . '%')
	// 					->orWhere('material','like','%' . $req->val . '%')
	// 					->orWhere('nota_timbang','like','%' . $req->val . '%')
	// 					->orderBy('order_date','desc')
	// 					->orderBy('created_at','desc')
	// 					->paginate(Appsetting('paging_item_number'));
	// 					// ->get();

	// 	return view('delivery.search',[
	// 			'pengiriman' => $pengiriman,
	// 			'search_val' => $req->val
	// 		]);
	// }


}