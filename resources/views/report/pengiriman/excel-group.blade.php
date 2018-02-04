<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
		<table class="table-product" style="font-size:10pt;width: 100%;"  >
				<thead>
					<tr>
						<th colspan="5" style="text-align: center;" >REKAPITULASI PENGIRIMAN MATERIAL</th>
					</tr>
					<tr  >
						<th colspan="5" style="text-align: center;" >Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</th>
					</tr>
					<tr>
						<th style="width: 50%;border:1px solid #000;text-align: center;" >
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
						<th style="width: 10%;border:1px solid #000;text-align: center;" >RIT</th>
						<th style="width: 10%;border:1px solid #000;text-align: center;" >VOL</th>
						<th style="width: 10%;border:1px solid #000;text-align: center;" >NET</th>
						<th style="width: 20%;border:1px solid #000;text-align: center;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					<?php $grand_total=0; ?>
					<?php $grand_rit=0; ?>
					<?php $grand_vol=0; ?>
					<?php $grand_net=0; ?>
					@foreach($pengiriman as $dt)
					<tr>
						<td style="border:1px solid #000;" >
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
						<td style="text-align:right;border:1px solid #000;" >
							{{$dt->sum_qty}}
							<?php $grand_rit+=$dt->sum_qty; ?>
						</td>
						<td style="text-align:right;border:1px solid #000;" >
							{{$dt->sum_vol}}
							<?php $grand_vol+=$dt->sum_vol; ?>
						</td>
						<td style="text-align:right;border:1px solid #000;" >
							{{$dt->sum_net}}
							<?php $grand_net+=$dt->sum_net; ?>
						</td>
						<td style="text-align:right;border:1px solid #000;" >
							{{number_format($dt->sum_total,2,'.',',')}}
							<?php $grand_total+=$dt->sum_total; ?>
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td style="text-align: center;border:1px solid #000;" >
							<strong>TOTAL</strong>
						</td>
						<td style="text-align: right;border:1px solid #000;" >
							<strong>{{$grand_rit}}</strong>
						</td>
						<td style="text-align: right;border:1px solid #000;" >
							<strong>{{$grand_vol}}</strong>
						</td>
						<td style="text-align: right;border:1px solid #000;" >
							<strong>{{$grand_net}}</strong>
						</td>
						<td style="text-align: right;border:1px solid #000;" >
							<strong>{{number_format($grand_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
				
</body>
</html>