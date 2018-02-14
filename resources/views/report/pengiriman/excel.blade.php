<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<?php $grand_total=0; ?>
		<table class="table-product" style="font-size:10pt;width: 100%;font-family: arial;"  >
			<thead>
				<tr>
					<th colspan="11" style="text-align: center;" >REKAPITULASI PENGIRIMAN MATERIAL</th>
				</tr>
				<tr  >
					<th colspan="11" style="text-align: center;" >Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</th>
				</tr>
				<tr  >
					<th colspan="11" style="text-align: center;" ></th>
				</tr>
				<tr  >
					<th style="width: 11%;border:1px solid #000;text-align: center;" >#</th>
					<th style="width: 10%;border:1px solid #000;text-align: center;" >TANGGAL</th>

					@if($group_by!='customer')
					<th style="width: 11%;border:1px solid #000;text-align: center;" >CUSTOMER</th>
					@endif
					@if($group_by!='pekerjaan')
					<th style="width: 11%;border:1px solid #000;text-align: center;" >PEKERJAAN</th>
					@endif
					@if($group_by!='material')
					<th style="width: 11%;border:1px solid #000;text-align: center;" >MATERIAL</th>
					@endif
					@if($group_by!='lokasi')
					<th style="width: 11%;border:1px solid #000;text-align: center;" >LOKASI GALIAN</th>
					@endif
					@if($group_by!='driver')
					<th style="width: 11%;border:1px solid #000;text-align: center;" >DRIVER NOPOL</th>
					@endif

					<th style="width: 5%;border:1px solid #000;text-align: center;" >RIT</th>
					<th style="width: 5%;border:1px solid #000;text-align: center;" >VOL</th>
					<th style="width: 5%;border:1px solid #000;text-align: center;" >NET</th>
					<th style="width: 10%;border:1px solid #000;text-align: center;" >HARGA SATUAN</th>
					<th style="width: 10%;border:1px solid #000;text-align: center;" >JUMLAH</th>
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
						<td colspan="11" style="border:1px solid #000;" >
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
						<td style="text-align: center;border:1px solid #000;" >
							{{$dt->name}}
						</td>
						<td style="text-align: center;border:1px solid #000;" >
							{{$dt->order_date_format}}
						</td>
						@if($group_by!='customer')
							<td style="border:1px solid #000;">{{$dt->customer}}</td>
						@endif
						@if($group_by!='pekerjaan')
							<td style="border:1px solid #000;">{{$dt->pekerjaan}}</td>
						@endif
						@if($group_by!='material')
							<td style="border:1px solid #000;">{{$dt->material}}</td>
						@endif
						@if($group_by!='lokasi')
							<td style="border:1px solid #000;">{{$dt->lokasi_galian}}</td>
						@endif
						@if($group_by!='driver')
							<td style="border:1px solid #000;" >{{$dt->karyawan}} - {{$dt->nopol}}</td>
						@endif
						<td data-format="0.00" style="text-align: right;border:1px solid #000;" >
							{{$dt->qty}}
							<?php $sum_rit+=$dt->qty; ?>
						</td>
						<td data-format="0.00" style="text-align: right;border:1px solid #000;" >
							{{$dt->volume}}
							<?php $sum_vol+=$dt->volume; ?>
						</td>
						<td data-format="0.00" style="text-align: right;border:1px solid #000;" >
							{{$dt->netto}}
							<?php $sum_net+=$dt->netto; ?>
						</td>
						<td data-format="0,0.00" style="text-align: right;border:1px solid #000;" >
							{{$dt->harga_satuan}}
						</td>
						<td data-format="0,0.00" style="text-align: right;border:1px solid #000;" >
							{{$dt->harga_total}}
							<?php $sum_total+=$dt->harga_total; ?>
						</td>
					</tr>
					@endforeach
					<tr>
						<td colspan="6" style="text-align: right;border:1px solid #000;font-weight: bold;" >Total {{$subtitle_val}}</td>
						<td style="text-align: right;border:1px solid #000;font-weight: bold;" data-format="0.00" >{{$sum_rit}}</td>
						<td style="text-align: right;border:1px solid #000;font-weight: bold;" data-format="0.00" >{{$sum_vol}}</td>
						<td style="text-align: right;border:1px solid #000;font-weight: bold;" data-format="0.00" >{{$sum_net}}</td>
						<td colspan="2" style="text-align: right;border:1px solid #000;font-weight: bold;" data-format="0,0.00" >{{$sum_total}}</td>
					</tr>
					<?php $grand_total += $sum_total; ?>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="9" style="text-align: center;border:1px solid #000;font-weight: bold;" >TOTAL</td>
					<td colspan="2" style="text-align: right;border:1px solid #000;font-weight: bold;" data-format="0,0.00" >{{$grand_total}}
					</td>
				</tr>
			</tfoot>
		</table>		
	</body>
</html>