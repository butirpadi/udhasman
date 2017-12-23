<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>.:: Rekapitulasi Pengiriman Material ::.</title>
	<style>
	    table.table-product, table.table-total {
	        border-collapse: collapse;
	      }
	     table.table-product{
	     	margin-bottom: 10mm;
	     }
	    table.table-product th, table.table-product td {
	        border: 1px solid black;
	        padding: 0px;
	        text-align: left;
	      }
	    table.table-product th{
	    	padding-top: 10px;
	    	padding-bottom: 10px;
	    	text-align: center;
	    }
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
		    font-size: 10px;
		}
		.pagenum:before {
		    content: counter(page);
		}
	</style> 
</head>
<body>
	<div class="footer">
		<i style="position: absolute;left:0;" >REKAPITULASI PENGIRIMAN MATERIAL</i>
	    Page <span class="pagenum"></span>
		<i style="position: absolute;right:0;" >Dicetak pada {{$dicetak}}</i>
	</div>

	<div style="text-align: center;" >
		<h4 style="margin:0;padding:0;">REKAPITULASI PENGIRIMAN MATERIAL</h4>
		<p style="font-size: 10pt;margin:0;padding:0;"><strong>Periode : </strong>{{$tanggal_awal . ' / ' . $tanggal_akhir}}</p>
	</div>

	<div class="content" >
		@foreach($pengiriman as $pg)
			<table style="font-size: 10pt;">
				<tbody>
					<tr>
						<td>
							<strong>Customer</strong>
						</td>
						<td>:</td>
						<td>
							{{$pg->customer}}
						</td>
					</tr>
					<tr>
						<td>
							<strong>Pekerjaan</strong>
						</td>
						<td>:</td>
						<td>
							{{$pg->pekerjaan}}
						</td>
					</tr>
					<tr>
						<td>
							<strong>Material</strong>
						</td>
						<td>:</td>
						<td>
							{{$pg->material}}
						</td>
					</tr>
				</tbody>
			</table>

			@if($pg->kalkulasi == 'kubik')
			<?php $sum_vol=0; ?>
			<?php $sum_total=0; ?>
			<table class="table-product" style="font-size:10pt;width: 100%;"  >
				<thead>
					<tr>
						<th style="width: 10%;" >REF#</th>
						<th style="width: 8%;" >TANGGAL</th>
						<th style="width: 20%;" >DRIVER</th>
						<th style="width: 10%;" >NOPOL</th>
						<th style="width: 7%;" >P</th>
						<th style="width: 7%;" >L</th>
						<th style="width: 7%;" >T</th>
						<th style="width: 7%;" >VOL</th>
						<th style="width: 12%;" >HARGA SATUAN</th>
						<th style="width: 12%;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pg->detail as $dt)
					<tr>
						<td style="text-align:center;" >
							{{$dt->name}}
						</td>
						<td style="text-align:center;" >
							{{$dt->order_date_format}}
						</td>
						<td>
							{{$dt->karyawan}}
						</td>
						<td style="text-align:center;" >
							{{$dt->nopol}}
						</td>
						<td style="text-align:right;" >
							{{$dt->panjang}}
						</td>
						<td style="text-align:right;" >
							{{$dt->lebar}}
						</td>
						<td style="text-align:right;" >
							{{$dt->tinggi}}
						</td>
						<td style="text-align:right;" >
							{{$dt->volume}}
							<?php $sum_vol+=$dt->volume ?>
						</td>
						<td style="text-align:right;" >
							{{number_format($dt->harga_satuan,2,'.',',')}}
						</td>
						<td style="text-align:right;" >
							{{number_format($dt->harga_total,2,'.',',')}}
							<?php $sum_total+=$dt->harga_total ?>
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="7" style="text-align:center;" >
							<strong>TOTAL</strong>
						</td>
						<td style="text-align:right;" >
							<strong>{{$sum_vol}}</strong>
						</td>
						<td colspan="2" style="text-align:right;" >
							<strong>{{number_format($sum_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
			@endif


			@if($pg->kalkulasi == 'ton')
			<?php $sum_net = 0; ?>
			<?php $sum_total = 0; ?>
			<table class="table-product" style="font-size:10pt;width: 100%;"  >
				<thead>
					<tr>
						<th style="width: 10%;" >REF#</th>
						<th style="width: 8%;" >TANGGAL</th>
						<th style="width: 27%;" >DRIVER</th>
						<th style="width: 10%;" >NOPOL</th>
						<th style="width: 7%;" >G</th>
						<th style="width: 7%;" >T</th>
						<th style="width: 7%;" >NET</th>
						<th style="width: 12%;" >HARGA SATUAN</th>
						<th style="width: 12%;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pg->detail as $dt)
					<tr>
						<td style="text-align:center;" >
							{{$dt->name}}
						</td>
						<td style="text-align:center;" >
							{{$dt->order_date_format}}
						</td>
						<td>
							{{$dt->karyawan}}
						</td>
						<td style="text-align:center;" >
							{{$dt->nopol}}
						</td>
						<td style="text-align:right;" >
							{{$dt->gross}}
						</td>
						<td style="text-align:right;" >
							{{$dt->tare}}
						</td>
						<td style="text-align:right;" >
							{{$dt->netto}}
							<?php $sum_net+=$dt->netto; ?>
						</td>
						<td style="text-align:right;" >
							{{number_format($dt->harga_satuan,2,'.',',')}}
						</td>
						<td style="text-align:right;" >
							{{number_format($dt->harga_total,2,'.',',')}}
							<?php $sum_total+=$dt->harga_total; ?>
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6" style="text-align:center;" >
							<strong>TOTAL</strong>
						</td>
						<td style="text-align:right;" >
							<strong>{{$sum_net}}</strong>
						</td>
						<td colspan="2" style="text-align:right;" >
							<strong>{{number_format($sum_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
			@endif

			@if($pg->kalkulasi == 'rit')
			<?php $sum_rit = 0; ?>
			<?php $sum_total = 0; ?>
			<table class="table-product" style="font-size:10pt;width: 100%;"  >
				<thead>
					<tr>
						<th style="width: 10%;" >REF#</th>
						<th style="width: 10%;" >TANGGAL</th>
						<th style="width: 35%;" >DRIVER</th>
						<th style="width: 10%;" >NOPOL</th>
						<th style="width: 5%;" >RIT</th>
						<th style="width: 15%;" >HARGA SATUAN</th>
						<th style="width: 15%;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pg->detail as $dt)
					<tr>
						<td style="text-align:center;" >
							{{$dt->name}}
						</td>
						<td style="text-align:center;">
							{{$dt->order_date_format}}
						</td>
						<td>
							{{$dt->karyawan}}
						</td>
						<td style="text-align:center;" >
							{{$dt->nopol}}
						</td>
						<td style="text-align:right;" >
							{{$dt->qty}}
							<?php $sum_rit+=$dt->qty; ?>
						</td>
						<td style="text-align:right;" >
							{{number_format($dt->harga_satuan,2,'.',',')}}
						</td>
						<td style="text-align:right;" >
							{{number_format($dt->harga_total,2,'.',',')}}
							<?php $sum_total+=$dt->harga_total; ?>
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" style="text-align:center;" >
							<strong>TOTAL</strong>
						</td>
						<td style="text-align:right;" >
							<strong>{{$sum_rit}}</strong>
						</td>
						<td colspan="2" style="text-align:right;" >
							<strong>{{number_format($sum_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
			@endif
		@endforeach    

		<table class="table-total" style="width: 100%;"  >
			<tbody>
				<tr>
					<td style="width: 25%;" ></td>
					<td style="width: 35%;" ></td>
					<td style="width: 10%;border-top: solid thin black;vertical-align: top;" align="left" >
						<strong>TOTAL</strong>
					</td>
					<td style="width: 30%;border-top: solid thin black;text-align:right;" >
						<strong>{{number_format($grand_total,2,'.',',')}}</strong>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td style="text-align:center;" >
						<br/>
						<span style="text-align: center;" >Dibuat,</span>
						<br/>
						<br/>
						<br/>
						<br/>
						<strong style="text-align: center;" >(____________________)</strong>
					</td>
				</tr>
			</tbody>
		</table>
	    	
		
	</div>
	
</body>
</html>