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
		$customer = \DB::table('view_customer')->get();
		$select_customer = [];
		foreach($customer as $dt){
			$select_customer[$dt->id] = $dt->nama;
		}

        $material = \DB::table('material')->get();
        $select_material = [];
        foreach($material as $dt){
            $select_material[$dt->id] = $dt->nama;
        }

		return view('report.pengiriman.index',[
            'select_customer' => $select_customer,
			'select_material' => $select_material
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
                            ->groupBy('customer_id','pekerjaan_id','material_id','kalkulasi')
                            ->get();

            $sum_total = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->whereRaw($req->customer == '' ? 'true' : 'customer_id = ' . $req->customer)
                            ->whereRaw($req->pekerjaan == '' ? 'true' : 'pekerjaan_id = ' . $req->pekerjaan)
                            ->whereRaw($req->material == '' ? 'true' : 'material_id = ' . $req->material)
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
                            ->get();
            $grand_total = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->whereRaw($req->customer == '' ? 'true' : 'customer_id = ' . $req->customer)
                            ->whereRaw($req->pekerjaan == '' ? 'true' : 'pekerjaan_id = ' . $req->pekerjaan)
                            ->whereRaw($req->material == '' ? 'true' : 'material_id = ' . $req->material)
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
                            ->orderBy('customer','asc')
                            ->groupBy('customer_id')
                            ->get();
            
        }elseif($req->group_by == 'material'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->orderBy('customer','asc')
                            ->groupBy('material_id')
                            ->get();
            
        }elseif($req->group_by == 'lokasi'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->orderBy('customer','asc')
                            ->groupBy('lokasi_galian_id')
                            ->get();
            
        }elseif($req->group_by == 'driver'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->orderBy('customer','asc')
                            ->groupBy('karyawan_id')
                            ->get();
            
        }elseif($req->group_by == 'pekerjaan'){
            $pengiriman = \DB::table('view_new_pengiriman')
                            ->select('customer','material','lokasi_galian','karyawan','pekerjaan',\DB::raw('sum(qty) as sum_qty'),\DB::raw('sum(volume) as sum_vol'),\DB::raw('sum(netto) as sum_net'),\DB::raw('sum(harga_total) as sum_total'))
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->orderBy('customer','asc')
                            ->groupBy('pekerjaan_id')
                            ->get();
            
        }


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

        //// SHOW HTML
        // return $this->groupReportHtml([
        //         'pengiriman' => $pengiriman,
        //         'tanggal_awal' => $awal,
        //         'tanggal_akhir' => $akhir,
        //         'group_by' => $req->group_by,
        //         'dicetak'=>date('d-m-Y H:i:s')
        //     ]);

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

        if($req->group_by == 'customer'){
            $pengiriman_by_group = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->orderBy('customer','asc')
                            ->groupBy('customer_id')
                            ->get();

            // get detail
            foreach($pengiriman_by_group as $dt){
                $dt->detail = \DB::table('view_new_pengiriman')
                                ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                                ->where('customer_id',$dt->customer_id)
                                ->orderBy('order_date','asc')
                                ->orderBy('customer','asc')
                                ->orderBy('material','asc')
                                ->orderBy('karyawan','asc')
                                ->get();
            }
            
        }elseif($req->group_by == 'material'){
            $pengiriman_by_group = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->orderBy('customer','asc')
                            ->groupBy('material_id')
                            ->get();

            // get detail
            foreach($pengiriman_by_group as $dt){
                $dt->detail = \DB::table('view_new_pengiriman')
                                ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                                ->where('material_id',$dt->material_id)
                                ->orderBy('order_date','asc')
                                ->orderBy('customer','asc')
                                ->orderBy('material','asc')
                                ->orderBy('karyawan','asc')
                                ->get();
            }
            
        }elseif($req->group_by == 'lokasi'){
            $pengiriman_by_group = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->orderBy('customer','asc')
                            ->groupBy('lokasi_galian_id')
                            ->get();

            // get detail
            foreach($pengiriman_by_group as $dt){
                $dt->detail = \DB::table('view_new_pengiriman')
                                ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                                ->where('lokasi_galian_id',$dt->lokasi_galian_id)
                                ->orderBy('order_date','asc')
                                ->orderBy('customer','asc')
                                ->orderBy('material','asc')
                                ->orderBy('karyawan','asc')
                                ->get();
            }
            
        }elseif($req->group_by == 'driver'){
            $pengiriman_by_group = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->orderBy('customer','asc')
                            ->groupBy('karyawan_id')
                            ->get();

            // get detail
            foreach($pengiriman_by_group as $dt){
                $dt->detail = \DB::table('view_new_pengiriman')
                                ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                                ->where('karyawan_id',$dt->karyawan_id)
                                ->orderBy('order_date','asc')
                                ->orderBy('customer','asc')
                                ->orderBy('material','asc')
                                ->orderBy('karyawan','asc')
                                ->get();
            }
            
        }elseif($req->group_by == 'pekerjaan'){
            $pengiriman_by_group = \DB::table('view_new_pengiriman')
                            ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                            ->orderBy('customer','asc')
                            ->groupBy('pekerjaan_id')
                            ->get();

            // get detail
            foreach($pengiriman_by_group as $dt){
                $dt->detail = \DB::table('view_new_pengiriman')
                                ->whereBetween('order_date',[$tgl_awal_str,$tgl_akhir_str])
                                ->where('pekerjaan_id',$dt->pekerjaan_id)
                                ->orderBy('order_date','asc')
                                ->orderBy('customer','asc')
                                ->orderBy('material','asc')
                                ->orderBy('karyawan','asc')
                                ->get();
            }
            
        }
        

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


}
