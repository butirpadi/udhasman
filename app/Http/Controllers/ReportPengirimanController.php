<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;
use Dompdf\Dompdf;

class ReportPengirimanController extends Controller
{
	public function index(){
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

        $pekerjaan = \DB::table('pekerjaan')->get();
        $select_pekerjaan = [];
        foreach($pekerjaan as $dt){
            $select_pekerjaan[$dt->id] = $dt->nama;
        }

        $lokasi = \DB::table('lokasi_galian')->get();
        $select_lokasi = [];
        foreach($lokasi as $dt){
            $select_lokasi[$dt->id] = $dt->nama;
        }

        $driver = \DB::table('res_partner')
                    ->whereDriver('Y')
                    ->get();
        $select_driver = [];
        foreach($driver as $dt){
            $select_driver[$dt->id] = $dt->nama . ' - ' . \DB::table('armada')->find($dt->armada_id)->nopol;
        }

		return view('report.pengiriman.index',[
            'select_customer' => $select_customer,
            'select_material' => $select_material,
            'select_pekerjaan' => $select_pekerjaan,
            'select_lokasi_galian' => $select_lokasi,
			'select_driver' => $select_driver,
		]);
	}

	public function defaultReport(Request $req){
		// Generate Tanggal
        $awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $tgl_awal = new \DateTime();
        $tgl_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];


        if ($req->tipe_report == 'summary'){
            $html2pdf = new Html2Pdf('P', 'A4', 'en',false,'UTF-8',array(0,0,0,0));
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','pekerjaan','material',\DB::raw('sum(qty) as rit'),\DB::raw('sum(volume) as vol'),\DB::raw('sum(netto) as net'),\DB::raw('sum(harga_total) as harga_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->whereRaw($req->customer == '' ? 'true' : 'customer_id = ' . $req->customer)
                            ->whereRaw($req->pekerjaan == '' ? 'true' : 'pekerjaan_id = ' . $req->pekerjaan)
                            ->whereRaw($req->material == '' ? 'true' : 'material_id = ' . $req->material)
                            ->whereRaw("state in ('open','done')")
                            ->groupBy('customer_id','pekerjaan_id','material_id','kalkulasi')
                            ->get();

            $sum_total = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->whereRaw($req->customer == '' ? 'true' : 'customer_id = ' . $req->customer)
                            ->whereRaw($req->pekerjaan == '' ? 'true' : 'pekerjaan_id = ' . $req->pekerjaan)
                            ->whereRaw($req->material == '' ? 'true' : 'material_id = ' . $req->material)
                            ->whereRaw("state in ('open','done')")
                            ->sum('harga_total');

            $html2pdf->writeHTML($this->defaultSummaryReportHtml([
                 'tanggal_awal' => $awal,
                 'tanggal_akhir' => $akhir,
                 'pengiriman'=>$pengiriman,
                 'sum_total'=>$sum_total,
                 'dicetak'=>date('d-m-Y H:i:s')
             ]));
            $html2pdf->output();
            
        }else if($req->tipe_report == 'detail'){
            $html2pdf = new Html2Pdf('L', 'A4', 'en');
            
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->whereRaw($req->customer == '' ? 'true' : 'customer_id = ' . $req->customer)
                            ->whereRaw($req->pekerjaan == '' ? 'true' : 'pekerjaan_id = ' . $req->pekerjaan)
                            ->whereRaw($req->material == '' ? 'true' : 'material_id = ' . $req->material)
                            ->orderBy('customer','asc')
                            ->orderBy('pekerjaan','asc')
                            ->groupBy('customer_id','pekerjaan_id','material_id','kalkulasi')
                            ->whereRaw("state in ('open','done')")
                            ->get();
            $grand_total = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->whereRaw($req->customer == '' ? 'true' : 'customer_id = ' . $req->customer)
                            ->whereRaw($req->pekerjaan == '' ? 'true' : 'pekerjaan_id = ' . $req->pekerjaan)
                            ->whereRaw($req->material == '' ? 'true' : 'material_id = ' . $req->material)
                            ->whereRaw("state in ('open','done')")
                            ->sum('harga_total');

            foreach($pengiriman as $pg){
                $pg->detail = \DB::table('view_new_pengiriman')
                                ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                                ->where('customer_id',$pg->customer_id)
                                ->where('pekerjaan_id',$pg->pekerjaan_id)
                                ->where('material_id',$pg->material_id)
                                ->where('kalkulasi',$pg->kalkulasi)
                                ->get();
            }

            
            // $html2pdf->writeHTML($this->defaultDetailReportHtml([
            //      'tanggal_awal' => $awal,
            //      'tanggal_akhir' => $akhir,
            //      'pengiriman'=>$pengiriman,
            //      'grand_total'=>$grand_total,
            //      'dicetak'=>date('d-m-Y H:i:s')
            //  ]));
            // $html2pdf->output();

            $dompdf = new Dompdf();
            $dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->loadHtml($this->defaultDetailReportHtml([
                 'tanggal_awal' => $awal,
                 'tanggal_akhir' => $akhir,
                 'pengiriman'=>$pengiriman,
                 'grand_total'=>$grand_total,
                 'dicetak'=>date('d-m-Y H:i:s')
             ]));
            $dompdf->render();
            $dompdf->stream("ReportPengiriman.pdf", array("Attachment" => false));
            exit(0);
        }

     
	}

    public function defaultSummaryReportHtml($data){
        return view('report.pengiriman.default-summary-report',$data);
    }

    public function defaultDetailReportHtml($data){
        return view('report.pengiriman.default-detail-report',$data);
    }

	public function defaultReportHtml($data){
		return view('report.pengiriman.default-report',$data);
	}

    public function groupReportInline(Request $req){
        // Generate Tanggal
        $awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $tgl_awal = new \DateTime();
        $tgl_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        if($req->group_by == 'customer'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('customer_id')
                            ->get();
            
        }elseif($req->group_by == 'material'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('material_id')
                            ->get();
            
        }elseif($req->group_by == 'lokasi'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('lokasi_galian_id')
                            ->get();
            
        }elseif($req->group_by == 'driver'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('karyawan_id')
                            ->get();
            
        }elseif($req->group_by == 'pekerjaan'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('pekerjaan_id')
                            ->get();
            
        }

        // SHOW HTML
        return $this->groupReportHtml([
                'pengiriman' => $pengiriman,
                'tanggal_awal' => $awal,
                'tanggal_akhir' => $akhir,
                'group_by' => $req->group_by,
                'dicetak'=>date('d-m-Y H:i:s')
            ]);
    }

    public function groupReport(Request $req){
        // Generate Tanggal
        $awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $tgl_awal = new \DateTime();
        $tgl_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $dompdf = new Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->setPaper('A4', 'potrait');

        if($req->group_by == 'customer'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('customer_id')
                            ->get();
            
        }elseif($req->group_by == 'material'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('material_id')
                            ->get();
            
        }elseif($req->group_by == 'lokasi'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('lokasi_galian_id')
                            ->get();
            
        }elseif($req->group_by == 'driver'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('karyawan_id')
                            ->get();
            
        }elseif($req->group_by == 'pekerjaan'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('pekerjaan_id')
                            ->get();
            
        }

        // Show Report
        $dompdf->loadHtml($this->groupReportHtml([
                'pengiriman' => $pengiriman,
                'tanggal_awal' => $awal,
                'tanggal_akhir' => $akhir,
                'group_by' => $req->group_by,
                'dicetak'=>date('d-m-Y H:i:s')
            ]));

        $dompdf->render();
        $dompdf->stream("ReportPengiriman.pdf", array("Attachment" => false));
        exit(0);

    }

    public function groupReportExcel(Request $req){
        // Generate Tanggal
        $awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $tgl_awal = new \DateTime();
        $tgl_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        if($req->group_by == 'customer'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('customer_id')
                            ->get();
            
        }elseif($req->group_by == 'material'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('material_id')
                            ->get();
            
        }elseif($req->group_by == 'lokasi'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('lokasi_galian_id')
                            ->get();
            
        }elseif($req->group_by == 'driver'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('karyawan_id')
                            ->get();
            
        }elseif($req->group_by == 'pekerjaan'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            // ->whereRaw("state in ('open','done')")
                            ->orderBy('customer','asc')
                            ->groupBy('pekerjaan_id')
                            ->get();
            
        }

        // SHOW HTM
        // Generate Excell
        \Excel::create('Laporan_Summary_Pengiriman_Material_'.date('dmY_His'), function($excel) use($pengiriman,$awal,$akhir,$req)  {
            $excel->sheet('Report', function($sheet) use($pengiriman,$awal,$akhir,$req) {
                $sheet->loadView('report.pengiriman.excel-group',[
                    'pengiriman' => $pengiriman,
                    'tanggal_awal' => $awal,
                    'tanggal_akhir' => $akhir,
                    'group_by' => $req->group_by,
                    'dicetak'=>date('d-m-Y H:i:s')
                ]);
            });

        })->download('xlsx');
    }

    public function groupDetailReportInline(Request $req){
        // Generate Tanggal
        $awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $tgl_awal = new \DateTime();
        $tgl_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $groupby = $req->group_by == 'customer' ? 'customer_id' : ($req->group_by == 'pekerjaan' ? 'pekerjaan_id' : ($req->group_by == 'material' ? 'material_id' : ($req->group_by == 'lokasi' ? 'lokasi_galian_id' : ($req->group_by == 'driver' ? 'karyawan_id' : 'true') ) ) );

        $wheredetail = 'true';
        if($req->group_by == 'customer'){
            if($req->customer != ''){
                $wheredetail = 'customer_id = ' . $req->customer;
            }
        }else if($req->group_by == 'pekerjaan'){
            if($req->pekerjaan != ''){
                $wheredetail = 'pekerjaan_id = ' . $req->pekerjaan;
            }
        }else if($req->group_by == 'material'){
            if($req->material != ''){
                $wheredetail = 'material_id = ' . $req->material;
            }
        }else if($req->group_by == 'lokasi'){
            if($req->lokasi_galian != ''){
                $wheredetail = 'lokasi_galian_id = ' . $req->lokasi_galian;
            }
        }else if($req->group_by == 'driver'){
            if($req->driver != ''){
                $wheredetail = 'karyawan_id = ' . $req->driver;
            }
        }


        $pengiriman_by_group = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->whereRaw($wheredetail)
                            ->orderBy('customer','asc')
                            ->groupBy($groupby)
                            ->get();
        foreach($pengiriman_by_group as $dt){
            $whereraw = $req->group_by == 'customer' ? 'customer_id = ' . $dt->customer_id : ($req->group_by == 'pekerjaan' ? 'pekerjaan_id = ' . $dt->pekerjaan_id : ($req->group_by == 'material' ? 'material_id = ' . $dt->material_id : ($req->group_by == 'lokasi' ? 'lokasi_galian_id = ' . $dt->lokasi_galian_id : ($req->group_by == 'driver' ? 'karyawan_id = ' . $dt->karyawan_id : 'true') ) ) );
            $dt->detail = \DB::table('view_new_pengiriman')
                                ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                                ->whereRaw($whereraw)
                                ->orderBy('order_date','asc')
                                ->orderBy('customer','asc')
                                ->orderBy('material','asc')
                                ->orderBy('karyawan','asc')
                                ->get();
        }       

        // Show Html
        return $this->groupDetailReportHtml([
                'pengiriman_by_group' => $pengiriman_by_group,
                'tanggal_awal' => $awal,
                'tanggal_akhir' => $akhir,
                'group_by' => $req->group_by,
                'dicetak'=>date('d-m-Y H:i:s')
            ]);
    }

    public function groupDetailReport(Request $req){
        // Generate Tanggal
        $awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $tgl_awal = new \DateTime();
        $tgl_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $dompdf = new Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->setPaper('A4', 'landscape');

        $groupby = $req->group_by == 'customer' ? 'customer_id' : ($req->group_by == 'pekerjaan' ? 'pekerjaan_id' : ($req->group_by == 'material' ? 'material_id' : ($req->group_by == 'lokasi' ? 'lokasi_galian_id' : ($req->group_by == 'driver' ? 'karyawan_id' : 'true') ) ) );

        $wheredetail = 'true';
        if($req->group_by == 'customer'){
            if($req->customer != ''){
                $wheredetail = 'customer_id = ' . $req->customer;
            }
        }else if($req->group_by == 'pekerjaan'){
            if($req->pekerjaan != ''){
                $wheredetail = 'pekerjaan_id = ' . $req->pekerjaan;
            }
        }else if($req->group_by == 'material'){
            if($req->material != ''){
                $wheredetail = 'material_id = ' . $req->material;
            }
        }else if($req->group_by == 'lokasi'){
            if($req->lokasi_galian != ''){
                $wheredetail = 'lokasi_galian_id = ' . $req->lokasi_galian;
            }
        }else if($req->group_by == 'driver'){
            if($req->driver != ''){
                $wheredetail = 'karyawan_id = ' . $req->driver;
            }
        }


        $pengiriman_by_group = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->whereRaw($wheredetail)
                            ->orderBy('customer','asc')
                            ->groupBy($groupby)
                            ->get();
        foreach($pengiriman_by_group as $dt){
                $whereraw = $req->group_by == 'customer' ? 'customer_id = ' . $dt->customer_id : ($req->group_by == 'pekerjaan' ? 'pekerjaan_id = ' . $dt->pekerjaan_id : ($req->group_by == 'material' ? 'material_id = ' . $dt->material_id : ($req->group_by == 'lokasi' ? 'lokasi_galian_id = ' . $dt->lokasi_galian_id : ($req->group_by == 'driver' ? 'karyawan_id = ' . $dt->karyawan_id : 'true') ) ) );
                $dt->detail = \DB::table('view_new_pengiriman')
                                    ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                                    ->whereRaw($whereraw)
                                    ->orderBy('order_date','asc')
                                    ->orderBy('customer','asc')
                                    ->orderBy('material','asc')
                                    ->orderBy('karyawan','asc')
                                    ->get();
            }        

        //Show PDF Report
        $dompdf->loadHtml($this->groupDetailReportHtml([
                'pengiriman_by_group' => $pengiriman_by_group,
                'tanggal_awal' => $awal,
                'tanggal_akhir' => $akhir,
                'group_by' => $req->group_by,
                'dicetak'=>date('d-m-Y H:i:s')
            ]));
        $dompdf->render();
        $dompdf->stream("ReportPengiriman.pdf", array("Attachment" => false));
        exit(0);

       
    }

    public function groupDetailReportHtml($data){
        return view('report.pengiriman.group-detail-report',$data);
    }

    public function groupReportHtml($data){
        return view('report.pengiriman.group-report',$data);
    }

    public function testExel(){
        // generate excel
        \Excel::create('New file', function($excel)  {
            $excel->sheet('Report', function($sheet) {
                $sheet->loadView('report.pengiriman.test');
            });

        })->download('xlsx');;
        // return view('report.pengiriman.test');
    }

    public function groupDetailReportExcel(Request $req){
        // Generate Tanggal
        $awal = $req->tanggal_awal;
        $arr_tgl = explode('-',$awal);
        $tgl_awal = new \DateTime();
        $tgl_awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $akhir = $req->tanggal_akhir;
        $arr_tgl = explode('-',$akhir);
        $tgl_akhir = new \DateTime();
        $tgl_akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]); 
        $tgl_akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $groupby = $req->group_by == 'customer' ? 'customer_id' : ($req->group_by == 'pekerjaan' ? 'pekerjaan_id' : ($req->group_by == 'material' ? 'material_id' : ($req->group_by == 'lokasi' ? 'lokasi_galian_id' : ($req->group_by == 'driver' ? 'karyawan_id' : 'true') ) ) );

        $wheredetail = 'true';
        if($req->group_by == 'customer'){
            if($req->customer != ''){
                $wheredetail = 'customer_id = ' . $req->customer;
            }
        }else if($req->group_by == 'pekerjaan'){
            if($req->pekerjaan != ''){
                $wheredetail = 'pekerjaan_id = ' . $req->pekerjaan;
            }
        }else if($req->group_by == 'material'){
            if($req->material != ''){
                $wheredetail = 'material_id = ' . $req->material;
            }
        }else if($req->group_by == 'lokasi'){
            if($req->lokasi_galian != ''){
                $wheredetail = 'lokasi_galian_id = ' . $req->lokasi_galian;
            }
        }else if($req->group_by == 'driver'){
            if($req->driver != ''){
                $wheredetail = 'karyawan_id = ' . $req->driver;
            }
        }


        $pengiriman_by_group = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->whereRaw($wheredetail)
                            ->orderBy('customer','asc')
                            ->groupBy($groupby)
                            ->get();
        foreach($pengiriman_by_group as $dt){
                $whereraw = $req->group_by == 'customer' ? 'customer_id = ' . $dt->customer_id : ($req->group_by == 'pekerjaan' ? 'pekerjaan_id = ' . $dt->pekerjaan_id : ($req->group_by == 'material' ? 'material_id = ' . $dt->material_id : ($req->group_by == 'lokasi' ? 'lokasi_galian_id = ' . $dt->lokasi_galian_id : ($req->group_by == 'driver' ? 'karyawan_id = ' . $dt->karyawan_id : 'true') ) ) );
                $dt->detail = \DB::table('view_new_pengiriman')
                                    ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                                    ->whereRaw($whereraw)
                                    ->orderBy('order_date','asc')
                                    ->orderBy('customer','asc')
                                    ->orderBy('material','asc')
                                    ->orderBy('karyawan','asc')
                                    ->get();
            }       

        // Generate Excell
        \Excel::create('Laporan_Pengiriman_Material_'.date('dmY_His'), function($excel) use($pengiriman_by_group,$awal,$akhir,$req)  {
            $excel->sheet('Report', function($sheet) use($pengiriman_by_group,$awal,$akhir,$req) {
                $sheet->loadView('report.pengiriman.excel-detail',[
                    'pengiriman_by_group' => $pengiriman_by_group,
                    'tanggal_awal' => $awal,
                    'tanggal_akhir' => $akhir,
                    'group_by' => $req->group_by,
                    'dicetak'=>date('d-m-Y H:i:s')
                ]);
            });

        })->download('xlsx');
    }


}
