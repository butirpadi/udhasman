<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
			<i style="position: absolute;left:0;" >LAPORAN OPERASIONAL ALAT BERAT</i>
		    Page <span class="pagenum"></span>
			<i style="position: absolute;right:0;" >Dicetak pada {{date('d-m-Y H:i:s')}}</i>
		</div>

		<div style="text-align: center;" >
			<h4 style="margin:0;padding:0;">LAPORAN OPERASIOANL ALAT</h4>
			<p style="font-size: 10pt;margin:0;padding:0;"><strong>Periode : </strong>{{$tanggal_awal . ' / ' . $tanggal_akhir}}</p>
			<br/>
		</div>

		<div class="content" >
			<?php $grand_total=0; ?>
			<table class="table-product" style="font-size:10pt;width: 100%;font-family: arial;"  >
				<thead>
					<tr>
						<th style="width: 12%;border:1px solid #000;text-align: center;" >#</th>
						<th style="width: 10%;border:1px solid #000;text-align: center;" >TANGGAL</th>
						@if($group_by != 'alat_id')
							<th style="width: 16%;border:1px solid #000;text-align: center;" >ALAT</th>
						@endif
						@if($group_by != 'lokasi_galian_id')
							<th style="width: 16%;border:1px solid #000;text-align: center;" >LOKASI GALIAN</th>
						@endif
						@if($group_by != 'pengawas_id')
							<th style="width: 16%;border:1px solid #000;text-align: center;" >PENGAWAS</th>
						@endif
						@if($group_by != 'operator_id')
							<th style="width: 16%;border:1px solid #000;text-align: center;" >OPERATOR</th>
						@endif
						<th style="width: 10%;border:1px solid #000;text-align: center;" >SOLAR</th>
						<th style="width: 10%;border:1px solid #000;text-align: center;" >OLI</th>
						<th style="width: 10%;border:1px solid #000;text-align: center;" >TOTAL<br/>JAM KERJA</th>
					</tr>
				</thead>
				<tbody>
					<?php $grand_solar = 0; ?>
					<?php $grand_oli = 0; ?>
					<?php $grand_jam_kerja = 0; ?>

					@foreach($data_group as $pg)
						@if($group_by == 'alat_id')
							<?php $subtitle = 'Alat';?>
							<?php $subtitle_val = $pg->kode_alat . ' - ' . $pg->alat;?>
						@elseif($group_by == 'lokasi_galian_id')
							<?php $subtitle ='Lokasi Galian';?>
							<?php $subtitle_val =$pg->lokasi_galian;?>
						@elseif($group_by == 'pengawas_id')
							<?php $subtitle ='Pengawas';?>
							<?php $subtitle_val =$pg->pengawas;?>
						@elseif($group_by == 'operator_id')
							<?php $subtitle ='Operator';?>
							<?php $subtitle_val =$pg->operator;?>
						@endif
						<tr>
							@if($tipe_report == 'detail')
								<td colspan="8" style="border:1px solid #000;background-color: whitesmoke;" >
									<strong>
										{{$subtitle}} : {{$subtitle_val}}
									</strong>
								</td>
							@else
								<td colspan="5" style="border:1px solid #000;" >
									{{$subtitle}} : {{$subtitle_val}}
								</td>
								<td style="text-align: right;border:1px solid #000;" >
									{{number_format($pg->sum_solar),2,'.',','}}	
								</td>
								<td style="text-align: right;border:1px solid #000;" >
									{{number_format($pg->sum_oli),2,'.',','}}	
								</td>
								<td style="text-align: right;border:1px solid #000;" >
									{{number_format($pg->sum_jam_kerja),2,'.',','}}	
								</td>
							@endif
						</tr>
						<?php $sum_solar = 0; ?>
						<?php $sum_oli = 0; ?>
						<?php $sum_jam_kerja = 0; ?>
						
						@if(isset($pg->detail))
							@foreach($pg->detail as $dt)
							<tr>
								<td style="text-align: center;border:1px solid #000;" >
									{{$dt->ref}}
								</td>
								<td style="text-align: center;border:1px solid #000;" >
									{{$dt->tanggal_format}}
								</td>						
								@if($group_by != 'alat_id')
									<td style="border:1px solid #000;">{{$dt->alat}}</td>
								@endif
								@if($group_by != 'lokasi_galian_id')
									<td style="border:1px solid #000;">{{$dt->lokasi_galian}}</td>
								@endif
								@if($group_by != 'pengawas_id')
									<td style="border:1px solid #000;">{{$dt->pengawas}}</td>
								@endif
								@if($group_by != 'operator_id')
									<td style="border:1px solid #000;">{{$dt->operator}}</td>
								@endif
								<td style="text-align: right;border:1px solid #000;" >
									{{$dt->solar}}
								</td>
								<td style="text-align: right;border:1px solid #000;" >
									{{$dt->oli}}
								</td>
								<td style="text-align: right;border:1px solid #000;" >
									{{$dt->jam_kerja}}
								</td>
							</tr>
								<?php $sum_solar+=$dt->solar; ?>
								<?php $sum_oli+=$dt->oli; ?>
								<?php $sum_jam_kerja+=$dt->jam_kerja; ?>
							@endforeach
							<tr>
								<td style="text-align: right;border:1px solid #000;" colspan="5" ><strong>TOTAL {{$subtitle_val}}</strong>
								</td>
								<td style="text-align: right;border:1px solid #000;" >
									<strong>{{number_format($sum_solar,2,'.',',')}}</strong>
								</td>
								<td style="text-align: right;border:1px solid #000;" >
									<strong>{{number_format($sum_oli,2,'.',',')}}</strong>
								</td>
								<td style="text-align: right;border:1px solid #000;" >
									<strong>{{number_format($sum_jam_kerja,2,'.',',')}}</strong>
								</td>
							</tr>
						@endif

						@if($tipe_report == 'detail')
							<?php $grand_solar += $sum_solar; ?>					
							<?php $grand_oli += $sum_oli; ?>					
							<?php $grand_jam_kerja += $sum_jam_kerja; ?>					
						@else
							<?php $grand_solar += $pg->sum_solar; ?>					
							<?php $grand_oli += $pg->sum_oli; ?>					
							<?php $grand_jam_kerja += $pg->sum_jam_kerja; ?>
						@endif
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" style="text-align: center;border:1px solid #000;height: 30px;vertical-align: middle;" >
							<strong>TOTAL</strong>
						</td>
						<td style="text-align: right;border:1px solid #000;vertical-align: middle;" >
							<strong>{{number_format($grand_solar,2,'.',',')}}</strong>
						</td>
						<td style="text-align: right;border:1px solid #000;vertical-align: middle;" >
							<strong>{{number_format($grand_oli,2,'.',',')}}</strong>
						</td>
						<td style="text-align: right;border:1px solid #000;vertical-align: middle;" >
							<strong>{{number_format($grand_jam_kerja,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>

				
	</body>
</html>