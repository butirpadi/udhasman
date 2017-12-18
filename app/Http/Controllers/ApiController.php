<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\MyPdf;

class ApiController extends Controller
{
	public function getAutoCompleteProvinsi(Request $req){
		$provinsi = \DB::select('select id as data,name as value from provinsi where name like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $provinsi];

		return json_encode($data_res);
	}

	public function getAutoCompleteKabupaten(Request $req){
		$kabupaten = \DB::select('select id as data,name as value from kabupaten where provinsi_id = ' . $req->provinsi_id . ' and name like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $kabupaten];

		return json_encode($data_res);
	}

	public function getAutoCompleteKecamatan(Request $req){
		$data = \DB::select('select id as data,name as value from kecamatan where kabupaten_id = ' . $req->kabupaten_id . ' and name like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteDesa(Request $req){
		$data = \DB::select('select id as data,name as value from desa where kecamatan_id = ' . $req->kecamatan_id . ' and name like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteCustomer(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from customer where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data = \DB::select('select id as data,nama as value, nama from customer where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteSupplier(Request $req){
		$data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from supplier where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteLokasiGalian(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from lokasi_galian where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data = \DB::select('select id as data,nama as value, nama from lokasi_galian where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteAlat(Request $req){
		$data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from alat where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteArmada(Request $req){

		// $data = \DB::select('select id as data,concat("[",kode,"] ",nopol ," - ","[",kode_karyawan,"] ",karyawan) as value, nama
		$data = \DB::select('select id as data,concat(nopol ," - ",karyawan) as value, nama
				from view_armada
				where karyawan_id is not NULL and (
				nama like "%'.$req->get('nama').'%"
				or kode like "%'.$req->get('nama').'%"
				or kode_karyawan like "%'.$req->get('nama').'%"
				or karyawan like "%'.$req->get('nama').'%"
				or nopol like "%'.$req->get('nama').'%"
				)');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteDriver(Request $req){

		$data = \DB::select('select id as data,concat("[",kode,"] ", nopol ," - ","[",kode_karyawan,"] ",karyawan) as value, nama , nopol
				from view_armada
				where karyawan_id is not NULL and (
				nama like "%'.$req->get('nama').'%"
				or kode like "%'.$req->get('nama').'%"
				or kode_karyawan like "%'.$req->get('nama').'%"
				or karyawan like "%'.$req->get('nama').'%"
				or nopol like "%'.$req->get('nama').'%"
				)');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteStaff(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from view_karyawan where kode_jabatan = "ST" and (nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%")');
		$data = \DB::select('select id as data,nama as value, nama from view_karyawan where kode_jabatan = "ST" and (nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%")');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteMaterial(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama from material where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data = \DB::select('select id as data,nama as value, nama from material where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getAutoCompleteProduct(Request $req){
		// $data = \DB::select('select id as data,concat("[",kode,"] ",nama) as value, nama, from material where nama like "%'.$req->get('nama').'%" or kode like "%'.$req->get('nama').'%"');
		$data = \DB::select('select product.id as data,concat("[",kode,"] ",product.nama) as value, product.nama,
							product_unit.nama AS unit,
							product.product_unit_id
								FROM
							product_unit
							INNER JOIN product
	 						ON product_unit.id = product.product_unit_id where product.nama like "%'.$req->get('nama').'%" or product.kode like "%'.$req->get('nama').'%"');
		$data_res = ['query'=>'Unit','suggestions' => $data];

		return json_encode($data_res);
	}

	public function getSelectCustomer(){
		$data = \DB::table('view_customer')->get();
		$selectCustomer = [];
		foreach($data as $dt){
			$selectCustomer[$dt->id] = '[' . $dt->kode . '] ' . $dt->nama;
		}

		return json_encode($selectCustomer);
	}

	public function getSelectPekerjaan($customer_id){
		$data = \DB::table('view_pekerjaan')->where('customer_id',$customer_id)->get();
		$selectPekerjaan = [];
		foreach($data as $dt){
			$selectPekerjaan[$dt->id] =  $dt->nama;
		}

		return json_encode($selectPekerjaan);
	}

	public function getPekerjaanByCustomer($customer_id){
		$pekerjaan = \DB::table('pekerjaan')
                                ->where('customer_id',$customer_id)
                                ->select('id','nama')
                                ->get();

                return json_encode($pekerjaan);
	}

	// cetak Delivery Order (Direct Printing)
	public function doCetak($id){
		$data = \DB::table('view_delivery_order')->find($id);
		// $data = \DB::table('view_delivery_order')->find($id);

		$tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
        $file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
        $handle = fopen($file, 'w');
        $condensed = Chr(27) . Chr(33) . Chr(4);
        $bold1 = Chr(27) . Chr(69);
        $bold0 = Chr(27) . Chr(70);
        $draft_font = Chr(27). Chr(120).Chr(48);
        $roman_font = Chr(27). Chr(107).Chr(48);
        $initialized = Chr(27).Chr(64);
        $condensed1 = Chr(15);
        $condensed0 = Chr(18);
        $double_width1 = Chr(14);
        $double_width0 = Chr(20);
        $centering = Chr(27).Chr(97).Chr(1);
        $left = Chr(27).Chr(97).Chr(0);
        $right = Chr(27).Chr(97).Chr(2);
        $left_margin = Chr(27) . Chr(108) .Chr(5); 
        $right_margin = Chr(27) . Chr(81) .Chr(5);
        $reverse_linefeed = Chr(27). Chr(106). Chr(1);
        $page_width_in_line = Chr(27). Chr(67). Chr(127); //27 67 n;
        $page_width_in_inch = Chr(27). Chr(67). Chr(48). Chr(21); //27 67 48 n;
        $company_name = Appsetting('company_name');
        $alamat_1 = Appsetting('alamat_1');
        $alamat_2 = Appsetting('alamat_2');
        $telp = Appsetting('telp');
        $email = Appsetting('email');
        $LF = Chr(10); // Line Feed
        $max_line = 137;

        // INITIALISASI PRINTER
        $Data = $initialized;
        // $Data .= $page_width_in_inch;
        // $Data  .= $left_margin;
        // $Data  .= $right_margin;
        $Data .= $draft_font;
        // $Data .= $condensed1;

        // HEADER
        $Data .= $centering;
        $Data .= $condensed1;
        $Data .= $company_name.$LF;
        $Data .= $alamat_1 . ' ' . $alamat_2.$LF;
        $Data .= "T. " . $telp . " | E. " . $email . $LF;
        $Data .= $condensed0;
        $Data .= $centering.$double_width1."SURAT JALAN".$double_width0.$LF;
        $Data .= $condensed1;

        // CONTENT HEADER
        $space_left = str_repeat(' ', 60);
        $space_right = str_repeat(' ', 60);

     //    // Kepada
     //    $kepada =     		'Kepada        : ' . $data->customer;
     //    $kepada = substr_replace(str_repeat(' ',60), $kepada, 0,strlen($kepada));
        
     //    $nomor =          	'Nomor         : ' . $data->delivery_order_number;
     //    $nomor = substr_replace(str_repeat(' ',60), $nomor, 0,strlen($nomor));
        
     //    $pekerjaan =      	'Pekerjaan     : ' . $data->pekerjaan;
     //    $pekerjaan = substr_replace(str_repeat(' ',60), $pekerjaan, 0,strlen($pekerjaan));
        
     //    $tanggal =        'Tanggal Order : ' . $data->order_date_formatted;
     //    $tanggal = substr_replace(str_repeat(' ',60), $tanggal, 0,strlen($tanggal));
        
     //    $alamat =         'Alamat        : ' . $data->alamat_pekerjaan;
     //    $alamat = substr_replace(str_repeat(' ',60), $alamat, 0,strlen($alamat));
        
     //    $no_kendaraan =   'No. Kendaraan : ' . $data->nopol;
     //    $no_kendaraan = substr_replace(str_repeat(' ',60), $no_kendaraan, 0,strlen($no_kendaraan));

     //    $Data .= $condensed1;
     //    $Data .= $left;

     //    $space_len = strlen($data->customer) - strlen($data->customer);
     //    $spaces_1 = $space_len > 0 ? 0 : abs($space_len);
     //    $spaces_2 = $space_len > 0 ? abs($space_len) : 0;

     //    $Data .= $kepada . $nomor .$LF;
     //    $Data .= $pekerjaan . $tanggal .$LF;
     //    $Data .= $alamat . $no_kendaraan .$LF;

     //    // TABLE HEADER
     //    $header_border = str_repeat('-', $max_line);
     //    $header_space = str_repeat(' ', $max_line);
     //    $table_header = substr_replace($header_space, 'NO', 2,strlen('NO'));
     //    $table_header = substr_replace($table_header, 'MATERIAL', 7,strlen('MATERIAL'));
     //    $table_header = substr_replace($table_header, 'JUMLAH', -8,strlen('JUMLAH'));

     //    $Data .= $header_border . $LF;
     //    $Data .= $table_header . $LF;        
     //    $Data .= $header_border . $LF;

     //    // TABLE CONTENT
    	// $content_space = str_repeat(' ', $max_line);
    	// $content_row = substr_replace($content_space, 1, 2,1);
     //    $content_row = substr_replace($content_row, $data->material, 7,strlen($data->material));
     //    $content_row = substr_replace($content_row, 1, -2,1);

     //    $Data .= $content_row . $LF;

	   	
	   	// // TABLE SPACE
     //    $Data .= $LF.$LF.$LF.$LF.$LF.$LF.$LF.$LF;

     //    // LAST ROW LINE
     //    $Data .=  str_repeat('-', $max_line) . $LF;

     //    // FOOT NOTE
     //    $Data .= Appsetting('delivery_order_catatan_kaki') . $LF. $LF;

     //    // TERTANDA
     //    $penerima_space = str_repeat(' ', $max_line/3);
     //    $penerima_space_2 = str_repeat(' ', $max_line/3);
     //    $penerima_header = substr_replace($penerima_space,'Penerima', (strlen($penerima_space)-strlen('Penerima'))/2,strlen('Penerima') );
     //    $penerima = substr_replace($penerima_space_2,'____________________', (strlen($penerima_space_2)-strlen('____________________'))/2,strlen('____________________') );

     //    $pengirim_space_1 = str_repeat(' ', $max_line/3);
     //    $pengirim_space_2 = str_repeat(' ', $max_line/3);
     //    $pengirim_header = substr_replace($pengirim_space_1,'Pengirim', (strlen($pengirim_space_1)-strlen('Pengirim'))/2,strlen('Pengirim') );
     //    $pengirim = substr_replace($pengirim_space_2,$data->karyawan, (strlen($pengirim_space_2)-strlen($data->karyawan))/2,strlen($data->karyawan) );

     //    $admin_space_1 = str_repeat(' ', $max_line/3);
     //    $admin_space_2 = str_repeat(' ', $max_line/3);
     //    $admin_header = substr_replace($admin_space_1,Appsetting('delivery_order_slip_tertanda'), (strlen($admin_space_1)-strlen(Appsetting('delivery_order_slip_tertanda')))/2,strlen(Appsetting('delivery_order_slip_tertanda')));
     //    $user = \DB::table('users')->find($data->user_id)->name;
     //    $admin = substr_replace($admin_space_2,$user, (strlen($admin_space_2)-strlen($user))/2,strlen($user));

     //    $Data .= $penerima_header . $pengirim_header . $admin_header . $LF . $LF. $LF. $LF; 
     //    $Data .= $penerima . $pengirim . $admin ; 

     //    // set cutting edge
     //    $Data.= $LF . $LF . $LF. $LF. $LF. $LF. $LF. $LF. $LF. $LF. $LF. $LF. $LF. $LF. $LF. $LF;

        fwrite($handle, $Data);
        fclose($handle);
        // copy($file, Appsetting('printer_address'));  # Lakukan cetak
        copy($file, '//localhost/LX-310');  # Lakukan cetak
        unlink($file);
	}

	// Cetak PDF Delivery Order
	public function doPdf($id){
		$data = \DB::table('view_delivery_order')->find($id);


		// $doPdf = new MyPdf('L','mm',array(210,148.5));
		$doPdf = new MyPdf('P','mm','A4');
		$doPdf->SetAutoPageBreak(false,0);
		$doPdf->AddPage();
		GeneratePdfHeader($doPdf,'SURAT JALAN',$data->delivery_order_number);

		// defined
		$page_content_width = $doPdf->GetPageWidth()-16;

		// KONTENT HEADER
		$doPdf->Ln(8);
		$doPdf->SetX(8);
		$doPdf->SetTextColor(0,0,0);

		// Kepada
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(20,5,'Kepada',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(0,5,$data->customer,0,0,false);


		// nomor do
		$doPdf->SetX($doPdf->GetPageWidth()/2);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(22,5,'Nomor',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,$data->delivery_order_number,0,2,false);

		// Pekerjaan
		$doPdf->SetX(8);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(20,5,'Pekerjaan',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,$data->pekerjaan,0,0,false);

		// Tanggal
		$doPdf->SetX($doPdf->GetPageWidth()/2);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(22,5,'Tanggal',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,$data->order_date_formatted,0,2,false);

		// Alamat Pekerjaan
		$doPdf->SetX(8);
		$y = $doPdf->GetY();
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(20,5,'Alamat',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$alamat = ($data->alamat_pekerjaan != '' ? $data->alamat_pekerjaan : '') 
					. ($data->desa != '' ?  ', ' . $data->desa : '') 
					. ($data->kecamatan !='' ?  ', ' . $data->kecamatan : '' )
					. ($data->kabupaten != '' ?  ', ' . $data->kabupaten : '' )
					. ($data->provinsi != '' ?  ', '. $data->provinsi : '') ;
		$doPdf->MultiCell(20,5,$alamat,0,'L',false);

		// Driver
		$doPdf->SetXY($doPdf->GetPageWidth()/2,$y);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(22,5,'No. Kendaraan',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,$data->nopol,0,2,false);

		// TABLE HEADER
		$separator_width = 1;
		$table_width = $page_content_width - (2*$separator_width); 
		$col_no = 5/100*$table_width;
		$col_material = 75/100*$table_width;
		$col_qty = 20/100*$table_width;
		$header_height = 8;
		$table_content_height = 8;

		$doPdf->SetFillColor(0,0,0);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->SetTextColor(255,255,255);
		$doPdf->Ln(2);
		$doPdf->SetX(8);

		$doPdf->Cell($col_no,$header_height,'NO',0,0,'C',true);
		$doPdf->Cell($separator_width,$header_height,null,0,0,'C',false);

		$doPdf->SetFillColor(0,128,128);
		$doPdf->Cell($col_material,$header_height,'MATERIAL',0,0,'C',true);
		$doPdf->Cell($separator_width,$header_height,null,0,0,'C',false);
		
		$doPdf->SetFillColor(0,0,0);
		$doPdf->Cell($col_qty,$header_height,'QUANTITY',0,0,'C',true);
		$doPdf->Cell($separator_width,$header_height,null,0,2,'C',false);

		// // LINE HEADER
		// $doPdf->SetXY(8+$col_no+$separator_width);
		$doPdf->SetFillColor(0,128,128);
		// $doPdf->Cell($col_material,1,null,0,2,'C',true);
		$doPdf->SetXY(8,$doPdf->GetY()+1);
		$doPdf->Cell($page_content_width,0.7,null,0,2,'C',true);

		// TABLE CONTENT
		$doPdf->SetTextColor(0,0,0);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->SetX(8);
		$doPdf->Cell($col_no,$table_content_height,'1',0,0,'C',false);
		$doPdf->Cell($separator_width,$table_content_height,null,0,0,'C',false);

		$doPdf->Cell($col_material,$table_content_height,$data->material,0,0,'L',false);
		$doPdf->Cell($separator_width,$table_content_height,null,0,0,'C',false);

		$doPdf->Cell($col_qty,$table_content_height,'1',0,2,'C',false);

		// LAST ROW LINE
		$doPdf->Ln(30);
		$doPdf->SetX(8);
		$doPdf->Cell($page_content_width,0.5,null,0,2,'C',true);

		// footnote
		$doPdf->Ln(2);
		$doPdf->SetX(8);
		$doPdf->SetFont('Arial','I',8);
		$doPdf->Cell(0,4,Appsetting('delivery_order_catatan_kaki'),0,2,false);

		// TERTANDA
		$tertanda_width = ($page_content_width-2) / 3;
		$tertanda_height = 20;

		$doPdf->Ln(5);
		$doPdf->SetTextColor(0,128,128);
		$doPdf->SetDrawColor(0,128,128);
		$doPdf->SetLineWidth(0.25);
		$doPdf->SetFont('Arial','B',8);

		$doPdf->SetX(8);
		$admin_str = Appsetting('delivery_order_slip_tertanda');
		$user = \DB::table('users')->find($data->user_id);

		$doPdf->Cell($tertanda_width,7,'PENERIMA',1,0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);

		$doPdf->Cell($tertanda_width,7,'PENGIRIM',1,0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);

		$doPdf->Cell($tertanda_width,7,strtoupper($admin_str),1,2,'C',false);

		// $doPdf->Ln(0);

		$doPdf->SetX(8);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell($tertanda_width,$tertanda_height,null,'LR',0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);
		$doPdf->Cell($tertanda_width,$tertanda_height,null,'LR',0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);
		$doPdf->Cell($tertanda_width,$tertanda_height,null,'LR',2,'C',false);

		// bottom tertanda
		$doPdf->SetX(8);
		$doPdf->Cell($tertanda_width,5,null,'LRB',0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);
		$doPdf->Cell($tertanda_width,5,strtoupper($data->karyawan),'LRB',0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);
		$doPdf->Cell($tertanda_width,5,strtoupper($user->name),'LRB',0,'C',false);
		
		// CUT LINE
		$doPdf->Ln(11.5);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->SetX(0);
		$doPdf->Cell(0,5,str_repeat(' - ',200),0,0,'C',false);		

		// OUTPUT PDF
		$doPdf->Output('I','DeliveryOrder_'.date('dmY_hms').'.pdf');
		exit;
	}

	// Cetak PDF Delivery Order & Copy
	public function doPdfCopy($id){
		$data = \DB::table('view_delivery_order')->find($id);

		// $doPdf = new MyPdf('L','mm',array(210,148.5));
		$doPdf = new MyPdf('P','mm','A4');
		$doPdf->SetAutoPageBreak(true,8);
		$doPdf->AddPage();
		GeneratePdfHeader($doPdf,'SURAT JALAN',$data->delivery_order_number);

		$this->doPdfCopyContent($doPdf,$data);

		// CUT LINE
		$doPdf->Ln(11.5);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->SetX(0);
		$doPdf->Cell(0,5,str_repeat(' - ',200),0,0,'C',false);	

		// copy 1
		$doPdf->Ln(10);
		GeneratePdfHeader($doPdf,'SURAT JALAN [COPY 1]',$data->delivery_order_number);
		$this->doPdfCopyContent($doPdf,$data);

		if(Appsetting('delivery_order_slip_copy_number') == 2){
			$doPdf->AddPage();	
			GeneratePdfHeader($doPdf,'SURAT JALAN [COPY 2]',$data->delivery_order_number);
			$this->doPdfCopyContent($doPdf,$data);

			// CUT LINE
			$doPdf->Ln(11.5);
			$doPdf->SetFont('Arial',null,8);
			$doPdf->SetX(0);
			$doPdf->Cell(0,5,str_repeat(' - ',200),0,0,'C',false);	
		}

		if(Appsetting('delivery_order_slip_copy_number') == 3){
			$doPdf->AddPage();	
			GeneratePdfHeader($doPdf,'SURAT JALAN [COPY 2]',$data->delivery_order_number);
			$this->doPdfCopyContent($doPdf,$data);

			// CUT LINE
			$doPdf->Ln(11.5);
			$doPdf->SetFont('Arial',null,8);
			$doPdf->SetX(0);
			$doPdf->Cell(0,5,str_repeat(' - ',200),0,0,'C',false);	

			// copy 3
			$doPdf->Ln(10);
			GeneratePdfHeader($doPdf,'SURAT JALAN [COPY 3]',$data->delivery_order_number);
			$this->doPdfCopyContent($doPdf,$data);
		}

		if(Appsetting('delivery_order_slip_copy_number') == 4){
			$doPdf->AddPage();	
			GeneratePdfHeader($doPdf,'SURAT JALAN [COPY 2]',$data->delivery_order_number);
			$this->doPdfCopyContent($doPdf,$data);

			// CUT LINE
			$doPdf->Ln(11.5);
			$doPdf->SetFont('Arial',null,8);
			$doPdf->SetX(0);
			$doPdf->Cell(0,5,str_repeat(' - ',200),0,0,'C',false);	

			// copy 3
			$doPdf->Ln(10);
			GeneratePdfHeader($doPdf,'SURAT JALAN [COPY 3]',$data->delivery_order_number);
			$this->doPdfCopyContent($doPdf,$data);

			// copy 4
			$doPdf->AddPage();	
			GeneratePdfHeader($doPdf,'SURAT JALAN [COPY 4]',$data->delivery_order_number);
			$this->doPdfCopyContent($doPdf,$data);
		}

		
		// OUTPUT PDF
		$doPdf->Output('I','DeliveryOrder_'.date('dmY_hms').'.pdf');
		exit;
	}

	private function doPdfCopyContent(&$doPdf,$data){
		// defined
		$page_content_width = $doPdf->GetPageWidth()-16;

		// KONTENT HEADER
		$doPdf->Ln(8);
		$doPdf->SetX(8);
		$doPdf->SetTextColor(0,0,0);

		// Kepada
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(20,5,'Kepada',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(0,5,$data->customer,0,0,false);


		// nomor do
		$doPdf->SetX($doPdf->GetPageWidth()/2);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(22,5,'Nomor',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,$data->delivery_order_number,0,2,false);

		// Pekerjaan
		$doPdf->SetX(8);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(20,5,'Pekerjaan',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,$data->pekerjaan,0,0,false);

		// Tanggal
		$doPdf->SetX($doPdf->GetPageWidth()/2);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(22,5,'Tanggal',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,$data->order_date_formatted,0,2,false);

		// Alamat Pekerjaan
		$doPdf->SetX(8);
		$y = $doPdf->GetY();
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(20,5,'Alamat',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$alamat = ($data->alamat_pekerjaan != '' ? $data->alamat_pekerjaan : '') 
					. ($data->desa != '' ?  ', ' . $data->desa : '') 
					. ($data->kecamatan !='' ?  ', ' . $data->kecamatan : '' )
					. ($data->kabupaten != '' ?  ', ' . $data->kabupaten : '' )
					. ($data->provinsi != '' ?  ', '. $data->provinsi : '') ;
		$doPdf->MultiCell(20,5,$alamat,0,'L',false);

		// Driver
		$doPdf->SetXY($doPdf->GetPageWidth()/2,$y);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell(22,5,'No. Kendaraan',0,0,false);
		$doPdf->Cell(2,5,':',0,0,false);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->Cell(20,5,$data->nopol,0,2,false);

		// TABLE HEADER
		$separator_width = 1;
		$table_width = $page_content_width - (2*$separator_width); 
		$col_no = 5/100*$table_width;
		$col_material = 75/100*$table_width;
		$col_qty = 20/100*$table_width;
		$header_height = 8;
		$table_content_height = 8;

		$doPdf->SetFillColor(0,0,0);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->SetTextColor(255,255,255);
		$doPdf->Ln(2);
		$doPdf->SetX(8);

		$doPdf->Cell($col_no,$header_height,'NO',0,0,'C',true);
		$doPdf->Cell($separator_width,$header_height,null,0,0,'C',false);

		$doPdf->SetFillColor(0,128,128);
		$doPdf->Cell($col_material,$header_height,'MATERIAL',0,0,'C',true);
		$doPdf->Cell($separator_width,$header_height,null,0,0,'C',false);
		
		$doPdf->SetFillColor(0,0,0);
		$doPdf->Cell($col_qty,$header_height,'QUANTITY',0,0,'C',true);
		$doPdf->Cell($separator_width,$header_height,null,0,2,'C',false);

		// // LINE HEADER
		// $doPdf->SetXY(8+$col_no+$separator_width);
		$doPdf->SetFillColor(0,128,128);
		// $doPdf->Cell($col_material,1,null,0,2,'C',true);
		$doPdf->SetXY(8,$doPdf->GetY()+1);
		$doPdf->Cell($page_content_width,0.7,null,0,2,'C',true);

		// TABLE CONTENT
		$doPdf->SetTextColor(0,0,0);
		$doPdf->SetFont('Arial',null,8);
		$doPdf->SetX(8);
		$doPdf->Cell($col_no,$table_content_height,'1',0,0,'C',false);
		$doPdf->Cell($separator_width,$table_content_height,null,0,0,'C',false);

		$doPdf->Cell($col_material,$table_content_height,$data->material,0,0,'L',false);
		$doPdf->Cell($separator_width,$table_content_height,null,0,0,'C',false);

		$doPdf->Cell($col_qty,$table_content_height,'1',0,2,'C',false);

		// LAST ROW LINE
		$doPdf->Ln(30);
		$doPdf->SetX(8);
		$doPdf->Cell($page_content_width,0.5,null,0,2,'C',true);

		// footnote
		$doPdf->Ln(2);
		$doPdf->SetX(8);
		$doPdf->SetFont('Arial','I',8);
		$doPdf->Cell(0,4,Appsetting('delivery_order_catatan_kaki'),0,2,false);

		// TERTANDA
		$tertanda_width = ($page_content_width-2) / 3;
		$tertanda_height = 20;

		$doPdf->Ln(3);
		$doPdf->SetTextColor(0,128,128);
		$doPdf->SetDrawColor(0,128,128);
		$doPdf->SetLineWidth(0.25);
		$doPdf->SetFont('Arial','B',8);

		$doPdf->SetX(8);
		$admin_str = Appsetting('delivery_order_slip_tertanda');
		$user = \DB::table('users')->find($data->user_id);

		$doPdf->Cell($tertanda_width,7,'PENERIMA',1,0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);

		$doPdf->Cell($tertanda_width,7,'PENGIRIM',1,0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);

		$doPdf->Cell($tertanda_width,7,strtoupper($admin_str),1,2,'C',false);

		// $doPdf->Ln(0);

		$doPdf->SetX(8);
		$doPdf->SetFont('Arial','B',8);
		$doPdf->Cell($tertanda_width,$tertanda_height,null,'LR',0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);
		$doPdf->Cell($tertanda_width,$tertanda_height,null,'LR',0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);
		$doPdf->Cell($tertanda_width,$tertanda_height,null,'LR',2,'C',false);

		// bottom tertanda
		$doPdf->SetX(8);
		$doPdf->Cell($tertanda_width,5,null,'LRB',0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);
		$doPdf->Cell($tertanda_width,5,strtoupper($data->karyawan),'LRB',0,'C',false);
		$doPdf->Cell(1,7,null,0,0,'C',false);
		$doPdf->Cell($tertanda_width,5,strtoupper($user->name),'LRB',0,'C',false);	

	}

}
