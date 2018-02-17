<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>.:: Rekapitulasi Pengiriman Material ::.</title>
	<style>
		body{
			/*font-family: 'open sans';*/
			
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
	    table.table-product th, table.table-product td {
	        border:0.5px solid black;
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
		}
		.pagenum:before {
		    content: counter(page);
		}
	</style> 
</head>
<body>
	<div style="text-align: center;margin-bottom: 10mm;" >
		<h4 style="margin:0;padding:0;">REKAPITULASI PENGIRIMAN MATERIAL</h4>
		<p style="margin:0;padding:0;">Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</p>
	</div>

	<div class="content"  >
		<?php $grand_total=0; ?>
		<table class="table-product" style="width: 100%;"  >
				<thead>
					<tr>
						<th  >#</th>
						<th  >TANGGAL</th>

						@if($group_by!='customer')
						<th  >CUSTOMER</th>
						@endif
						@if($group_by!='pekerjaan')
						<th  >PEKERJAAN</th>
						@endif
						@if($group_by!='material')
						<th  >MATERIAL</th>
						@endif
						@if($group_by!='lokasi')
						<th  >LOKASI<br/>GALIAN</th>
						@endif
						@if($group_by!='driver')
						<th  >DRIVER<br/>NOPOL</th>
						@endif

						<th  >RIT</th>
						<th  >VOL</th>
						<th  >NET</th>
						<th >HARGA<br/>SATUAN</th>
						<th >JUMLAH</th>
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
							<td colspan="11" style="background-color: azure;" >
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
					<tr>
						<td colspan="9" style="text-align: center;" >
							<strong>TOTAL</strong>
						</td>
						<td colspan="2" style="text-align: right;" >
							<strong>{{number_format($grand_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tbody>
			</table>

		<!-- Looping for group category -->
		


		
				
	</div>
	
</body>
</html>