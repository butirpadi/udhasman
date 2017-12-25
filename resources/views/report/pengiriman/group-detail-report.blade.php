<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>.:: Rekapitulasi Pengiriman Material ::.</title>
	<style>
		body {  
		    font-family: 'Arial'  
		}
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

	<div style="text-align: center;margin-bottom: 10mm;" >
		<h4 style="margin:0;padding:0;font-size: 11pt;">REKAPITULASI PENGIRIMAN MATERIAL</h4>
		<p style="font-size: 10pt;margin:0;padding:0;"><strong>Periode : </strong>{{$tanggal_awal . ' / ' . $tanggal_akhir}}</p>
	</div>

	<div class="content" style="font-size: 10pt;" >
		<!-- Looping for group category -->
		@foreach($pengiriman_by_group as $pg)
			<div style="margin-bottom: 1mm;" >
				<strong>
					@if($group_by == 'customer')
						{{'CUSTOMER'}}
					@elseif($group_by == 'material')
						{{'MATERIAL'}}
					@elseif($group_by == 'lokasi')
						{{'LOKASI GALIAN'}}
					@elseif($group_by == 'driver')
						{{'DRIVER'}}
					@endif
					 : 
				</strong>
				@if($group_by == 'customer')
					{{$pg->customer}}
				@elseif($group_by == 'material')
					{{$pg->material}}
				@elseif($group_by == 'lokasi')
					{{$pg->lokasi_galian}}
				@elseif($group_by == 'driver')
					{{$pg->karyawan}}
				@endif
			</div>
			<br/>

			<table class="table-product" style="font-size:10pt;width: 100%;"  >
				<thead>
					<tr>
						<th>TANGGAL</th>
						@if($group_by!='customer')
						<th>CUSTOMER</th>
						@endif
						@if($group_by!='material')
						<th>MATERIAL</th>
						@endif
						@if($group_by!='lokasi')
						<th>LOKASI GALIAN</th>
						@endif
						@if($group_by!='driver')
						<th>DRIVER</th>
						@endif
						<th style="" >RIT</th>
						<th style="" >VOL</th>
						<th style="" >NET</th>
						<th style="" >HARGA SATUAN</th>
						<th style="" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					<?php $sum_rit=0; ?>
					<?php $sum_vol=0; ?>
					<?php $sum_net=0; ?>
					<?php $sum_total=0; ?>
					@foreach($pg->detail as $dt)
					<tr>
						<td style="text-align: center;" >
							{{$dt->order_date_format}}
						</td>
						@if($group_by!='customer')
							<td>{{$dt->customer}}</td>
						@endif
						@if($group_by!='material')
							<td>{{$dt->material}}</td>
						@endif
						@if($group_by!='lokasi')
							<td>{{$dt->lokasi_galian}}</td>
						@endif
						@if($group_by!='driver')
							<td>{{$dt->karyawan}}</td>
						@endif
						<td style="text-align: right;" >
							{{$dt->qty}}
							<?php $sum_rit+=$dt->qty; ?>
						</td>
						<td style="text-align: right;" >
							{{$dt->volume}}
							<?php $sum_vol+=$dt->volume; ?>
						</td>
						<td style="text-align: right;" >
							{{$dt->netto}}
							<?php $sum_net+=$dt->netto; ?>
						</td>
						<td style="text-align: right;" >
							{{number_format($dt->harga_satuan,2,'.',',')}}
						</td>
						<td style="text-align: right;" >
							{{number_format($dt->harga_total,2,'.',',')}}
							<?php $sum_total+=$dt->harga_total; ?>
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<td colspan="4" style="text-align: center;" >
						<strong>TOTAL</strong>
					</td>
					<td style="text-align: right;" >
						<strong>{{$sum_rit}}</strong>
					</td>
					<td style="text-align: right;" >
						<strong>{{$sum_vol}}</strong>
					</td>
					<td style="text-align: right;" >
						<strong>{{$sum_net}}</strong>
					</td>
					<td colspan="2" style="text-align: right;" >
						<strong>{{number_format($sum_total,2,'.',',')}}</strong>
					</td>
				</tfoot>
			</table>
		@endforeach


		
				
	</div>
	
</body>
</html>