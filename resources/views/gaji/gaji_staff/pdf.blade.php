<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>SLIP GAJI</title>
	<style>
		body{			
			font-size: 13px;
		}
		table{
			font-size: inherit;
		}
	    table.table-product, table.table-total {
	        border-collapse: collapse;
	      }
	    table.table-product{
	     	margin-bottom: 10mm;
	     }
	    table.table-product thead, table.table-product tbody{
	    	border:0.5px solid black;
	    }
	    table.table-product th{
	    	padding: 2px;
	    }
	    table.table-product td {
	        /*border:0.5px solid black;*/
	        padding: 0px;
	        text-align: left;
	      }
	    /*table.table-product th{
	    	padding-top: 10px;
	    	padding-bottom: 10px;
	    	text-align: center;
	    }*/
	    table.table-product td {
	        padding: 5px;
	        vertical-align: top;
	    }
      	.header,
		.footer {
		    width: 100%;
		    text-align: center;
		    position: fixed;
		}
		.header {
		    top: 0px;
		}
		.footer {
		    bottom: 0px;
		}
		.pagenum:before {
		    content: counter(page);
		}
		
		table.table-detail tr > td, table.table-detail tr > th {
			padding: 0;
		}
	</style> 
</head>
<body>
	<div class="content"  >
		<table class="table-product" style="width: 100%;" >
			<thead>
				<tr>
					<th style="width:50%;padding-left: 5px;" align="left" colspan="3" rowspan="2"  >
						{{Appsetting('company_name')}}<br/>
						SLIP GAJI - {{$data->name . ($data->dp == 'Y' ? '-'.Appsetting('dp_pay_prefix') . $payment->order : '' ) }}
					</th>
					<th style="width: 18%;" align="left" >Kode Karyawan</th>
					<th style="width: 2%;" >:</th>
					<th style="width: 30%;" align="left" >{{$data->kode_partner}}</th>
				</tr>
				<tr>
					<th style="width: 18%;" align="left" >Nama</th>
					<th style="width: 2%;" >:</th>
					<th style="width: 30%;" align="left" >{{$data->partner}}</th>
				</tr>
				<tr>
					<th style="width: 18%;padding-left: 5px;" align="left" >Periode</th>
					<th style="width: 2%;" >:</th>
					<th style="width: 30%;" align="left" >{{$data->tanggal_awal_format . ' s/d ' . $data->tanggal_akhir_format}}</th>
					<th style="width: 18%;" align="left" >Jabatan</th>
					<th style="width: 2%;" >:</th>
					<th style="width: 30%;" align="left" >Staff</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="4" style="border-right: 0.5px solid darkgrey;" ><b>Penerimaan (+)</b></td>
					<td colspan="2"  ><b>Potongan (-)</b></td>
				</tr>
				<tr>
					<td colspan="4" style="border-right: 0.5px solid darkgrey;" >
						<table style="width: 100%;" class="table-detail" >
							<tbody>
								<tr>
									<td>Gaji Pokok Harian</td>
									<td style="text-align: right;" >{{number_format($data->gaji_pokok,2,'.',',')}}</td>
								</tr>
								<tr>
									<td>Jumlah Kehadiran</td>
									<td style="text-align: right;" >{{($data->masuk_pagi + $data->masuk_siang)/2}}</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td colspan="2" >
						<table class="table-detail" style="width: 100%;"  >
							<tbody>
								<tr>
									<td></td>
									<td style="text-align: right;" >
										
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr style="border-top: 0.5px dashed darkgrey;"  >
					<td colspan="4" style="border-right: 0.5px solid darkgrey;" ><b>Total Penerimaan <span style="float: right;" >{{number_format($data->jumlah,2,'.',',')}}</span></b></td>
					<td colspan="2" ><b>Total Potongan <span style="float: right;" >0</span></b></td>
				</tr>
				<tr style="border-top: 0.5px solid darkgrey;" >
					<td colspan="6" vertical-align: middle;" >NETT GAJI<span style="float: right;" >{{number_format($data->gaji_nett,2,'.',',')}}</span></td>
				</tr>
				@if(isset($paymentBefore))
				@foreach($paymentBefore as $pay)
				<tr >
					<td colspan="6"  style="vertical-align: middle;padding-top: 0;padding-bottom: 0;" ><i>Dibayar pada {{$pay->tanggal_format}} <span style="float: right;" >{{number_format($pay->jumlah,2,'.',',')}}</span></i></td>
				</tr>
				@endforeach
				@endif
				<tr >
					<td style="padding-top: 20px;" colspan="6"  vertical-align: middle;" ><b>GAJI DITERIMA<span style="float: right;" >{{number_format($payment->jumlah,2,'.',',')}}</span></b></td>
				</tr>
				<tr >
					<td colspan="6"  vertical-align: middle;" style="border-bottom: 0.5px solid darkgrey;border-top: 0.5px solid darkgrey;" ><i>AMOUNT DUE<span style="float: right;" >{{number_format($amount_due,2,'.',',')}}</span></i></td>
				</tr>
				<tr style="height: 30px;" >
					<td colspan="3" ></td>
					<td colspan="3" style="text-align: right;" >Tanggal Cetak : {{date('d-m-Y')}}</td>
				</tr>
				<tr>
					<td colspan="3" style="text-align: center;" >
						Diserahkan oleh,
						<br/>
						<br/>
						<br/>
						<br/>
						(_______________________)
						<br/>
					</td>
					<td colspan="3" style="text-align: center;" >
						Diterima oleh,
						<br/>
						<br/>
						<br/>
						<br/>
						<b>{{$data->partner}}</b>
						<br/>
						<br/>
					</td>
				</tr>
			</tbody>
		</table>	

		<hr style="border-top: 1px dashed #8c8b8b;" >
		<br />
		<br/>

		<table class="table-product" style="width: 100%;" >
			<thead>
				<tr>
					<th style="width:50%;padding-left: 5px;" align="left" colspan="3" rowspan="2"  >
						{{Appsetting('company_name')}}<br/>
						SLIP GAJI - {{$data->name . ($data->dp == 'Y' ? '-'.Appsetting('dp_pay_prefix') . $payment->order : '' ) }}
					</th>
					<th style="width: 18%;" align="left" >Kode Karyawan</th>
					<th style="width: 2%;" >:</th>
					<th style="width: 30%;" align="left" >{{$data->kode_partner}}</th>
				</tr>
				<tr>
					<th style="width: 18%;" align="left" >Nama</th>
					<th style="width: 2%;" >:</th>
					<th style="width: 30%;" align="left" >{{$data->partner}}</th>
				</tr>
				<tr>
					<th style="width: 18%;padding-left: 5px;" align="left" >Periode</th>
					<th style="width: 2%;" >:</th>
					<th style="width: 30%;" align="left" >{{$data->tanggal_awal_format . ' s/d ' . $data->tanggal_akhir_format}}</th>
					<th style="width: 18%;" align="left" >Jabatan</th>
					<th style="width: 2%;" >:</th>
					<th style="width: 30%;" align="left" >Staff</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="4" style="border-right: 0.5px solid darkgrey;" ><b>Penerimaan (+)</b></td>
					<td colspan="2"  ><b>Potongan (-)</b></td>
				</tr>
				<tr>
					<td colspan="4" style="border-right: 0.5px solid darkgrey;" >
						<table style="width: 100%;" class="table-detail" >
							<tbody>
								<tr>
									<td>Gaji Pokok Harian</td>
									<td style="text-align: right;" >{{number_format($data->gaji_pokok,2,'.',',')}}</td>
								</tr>
								<tr>
									<td>Jumlah Kehadiran</td>
									<td style="text-align: right;" >{{($data->masuk_pagi + $data->masuk_siang)/2}}</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td colspan="2" >
						<table class="table-detail" style="width: 100%;"  >
							<tbody>
								<tr>
									<td></td>
									<td style="text-align: right;" >
										
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr style="border-top: 0.5px dashed darkgrey;"  >
					<td colspan="4" style="border-right: 0.5px solid darkgrey;" ><b>Total Penerimaan <span style="float: right;" >{{number_format($data->jumlah,2,'.',',')}}</span></b></td>
					<td colspan="2" ><b>Total Potongan <span style="float: right;" >0</span></b></td>
				</tr>
				<tr style="border-top: 0.5px solid darkgrey;" >
					<td colspan="6" vertical-align: middle;" >NETT GAJI<span style="float: right;" >{{number_format($data->gaji_nett,2,'.',',')}}</span></td>
				</tr>
				@if(isset($paymentBefore))
				@foreach($paymentBefore as $pay)
				<tr >
					<td colspan="6"  style="vertical-align: middle;padding-top: 0;padding-bottom: 0;" ><i>Dibayar pada {{$pay->tanggal_format}} <span style="float: right;" >{{number_format($pay->jumlah,2,'.',',')}}</span></i></td>
				</tr>
				@endforeach
				@endif
				<tr >
					<td style="padding-top: 20px;" colspan="6"  vertical-align: middle;" ><b>GAJI DITERIMA<span style="float: right;" >{{number_format($payment->jumlah,2,'.',',')}}</span></b></td>
				</tr>
				<tr >
					<td colspan="6"  vertical-align: middle;" style="border-bottom: 0.5px solid darkgrey;border-top: 0.5px solid darkgrey;" ><i>AMOUNT DUE<span style="float: right;" >{{number_format($amount_due,2,'.',',')}}</span></i></td>
				</tr>
				<tr style="height: 30px;" >
					<td colspan="3" ></td>
					<td colspan="3" style="text-align: right;" >Tanggal Cetak : {{date('d-m-Y')}}</td>
				</tr>
				<tr>
					<td colspan="3" style="text-align: center;" >
						Diserahkan oleh,
						<br/>
						<br/>
						<br/>
						<br/>
						(_______________________)
						<br/>
					</td>
					<td colspan="3" style="text-align: center;" >
						Diterima oleh,
						<br/>
						<br/>
						<br/>
						<br/>
						<b>{{$data->partner}}</b>
						<br/>
						<br/>
					</td>
				</tr>
			</tbody>
		</table>	
		
		
	</div>
	
</body>
</html>