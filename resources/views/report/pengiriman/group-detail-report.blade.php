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

	<div style="text-align: center;margin-bottom: 10mm;" >
		<h4 style="margin:0;padding:0;font-size: 11pt;">REKAPITULASI PENGIRIMAN MATERIAL</h4>
		<p style="font-size: 10pt;margin:0;padding:0;">Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</p>
	</div>

	<div class="content" style="font-size: 10pt;" >
		<?php $grand_total=0; ?>
		<table class="table-product" style="font-size:10pt;width: 100%;"  >
				<thead>
					<tr>
						<th style="width: 11%;" >#</th>
						<th style="width: 10%;" >TANGGAL</th>

						@if($group_by!='customer')
						<th style="width: 11%;" >CUSTOMER</th>
						@endif
						@if($group_by!='pekerjaan')
						<th style="width: 11%;" >PEKERJAAN</th>
						@endif
						@if($group_by!='material')
						<th style="width: 11%;" >MATERIAL</th>
						@endif
						@if($group_by!='lokasi')
						<th style="width: 11%;" >LOKASI<br/>GALIAN</th>
						@endif
						@if($group_by!='driver')
						<th style="width: 11%;" >DRIVER<br/>NOPOL</th>
						@endif

						<th style="width: 5%;" >RIT</th>
						<th style="width: 5%;" >VOL</th>
						<th style="width: 5%;" >NET</th>
						<th style="width: 10%;" >HARGA<br/>SATUAN</th>
						<th style="width: 10%;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pengiriman_by_group as $pg)
						@if($group_by == 'customer')
							<?php $subtitle = 'Customer';?>
							<?php $subtitle_val = $pg->customer;?>
						@elseif($group_by == 'pekerjaan')
							<?php $subtitle ='Pekerjaan';?>
							<?php $subtitle_val =$pg->pekerjaan;?>
						@elseif($group_by == 'material')
							<?php $subtitle ='Material';?>
							<?php $subtitle_val =$pg->material;?>
						@elseif($group_by == 'lokasi')
							<?php $subtitle ='Lokasi Galian';?>
							<?php $subtitle_val =$pg->lokasi_galian;?>
						@elseif($group_by == 'driver')
							<?php $subtitle ='Driver';?>
							<?php $subtitle_val =$pg->karyawan . ' / ' . $pg->nopol;?>
						@endif
						<tr>
							<td colspan="11" >
								<strong>
									{{$subtitle}} : 
								</strong>
								{{$subtitle_val}}
							</td>
						</tr>
						
						<?php $sum_rit=0; ?>
						<?php $sum_vol=0; ?>
						<?php $sum_net=0; ?>
						<?php $sum_total=0; ?>
						@foreach($pg->detail as $dt)
						<tr>
							<td style="text-align: center;" >
								{{$dt->name}}
							</td>
							<td style="text-align: center;" >
								{{$dt->order_date_format}}
							</td>
							@if($group_by!='customer')
								<td>{{$dt->customer}}</td>
							@endif
							@if($group_by!='pekerjaan')
								<td>{{$dt->pekerjaan}}</td>
							@endif
							@if($group_by!='material')
								<td>{{$dt->material}}</td>
							@endif
							@if($group_by!='lokasi')
								<td>{{$dt->lokasi_galian}}</td>
							@endif
							@if($group_by!='driver')
								<td>{{$dt->karyawan}} <br/> {{$dt->nopol}}</td>
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
						<tr>
							<td colspan="6" style="text-align: right;" >
								<strong>Total {{$subtitle_val}}</strong>
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
						</tr>
						<?php $grand_total += $sum_total; ?>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="9" style="text-align: center;" >
							<strong>TOTAL</strong>
						</td>
						<td colspan="2" style="text-align: right;" >
							<strong>{{number_format($grand_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>

		<!-- Looping for group category -->
		


		
				
	</div>
	
</body>
</html>