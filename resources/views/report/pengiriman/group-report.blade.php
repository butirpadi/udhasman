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

	<div class="content" >
		<table class="table-product" style="font-size:10pt;width: 100%;"  >
				<thead>
					<tr>
						<th style="width: 50%;" >
							@if($group_by == 'customer')
								{{'CUSTOMER'}}
							@elseif($group_by == 'material')
								{{'MATERIAL'}}
							@elseif($group_by == 'lokasi')
								{{'LOKASI GALIAN'}}
							@elseif($group_by == 'driver')
								{{'DRIVER'}}
							@elseif($group_by == 'pekerjaan')
								{{'PEKERJAAN'}}
							@endif
						</th>
						<th style="width: 10%;" >RIT</th>
						<th style="width: 10%;" >VOL</th>
						<th style="width: 10%;" >NET</th>
						<th style="width: 20%;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					<?php $grand_total=0; ?>
					<?php $grand_rit=0; ?>
					<?php $grand_vol=0; ?>
					<?php $grand_net=0; ?>
					@foreach($pengiriman as $dt)
					<tr>
						<td>
							@if($group_by == 'customer')
								{{$dt->customer}}
							@elseif($group_by == 'material')
								{{$dt->material}}
							@elseif($group_by == 'lokasi')
								{{$dt->lokasi_galian}}
							@elseif($group_by == 'driver')
								{{$dt->karyawan}}
							@elseif($group_by == 'pekerjaan')
								{{$dt->pekerjaan}}
							@endif
						</td>
						<td style="text-align:right;" >
							{{$dt->sum_qty}}
							<?php $grand_rit+=$dt->sum_qty; ?>
						</td>
						<td style="text-align:right;" >
							{{$dt->sum_vol}}
							<?php $grand_vol+=$dt->sum_vol; ?>
						</td>
						<td style="text-align:right;" >
							{{$dt->sum_net}}
							<?php $grand_net+=$dt->sum_net; ?>
						</td>
						<td style="text-align:right;" >
							{{number_format($dt->sum_total,2,'.',',')}}
							<?php $grand_total+=$dt->sum_total; ?>
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td style="text-align: center;" >
							<strong>TOTAL</strong>
						</td>
						<td style="text-align: right;" >
							<strong>{{$grand_rit}}</strong>
						</td>
						<td style="text-align: right;" >
							<strong>{{$grand_vol}}</strong>
						</td>
						<td style="text-align: right;" >
							<strong>{{$grand_net}}</strong>
						</td>
						<td style="text-align: right;" >
							<strong>{{number_format($grand_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
				
	</div>
	
</body>
</html>