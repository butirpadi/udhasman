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
			<i style="position: absolute;left:0;" >LAPORAN PIUTANG</i>
		    Page <span class="pagenum"></span>
			<i style="position: absolute;right:0;" >Dicetak pada {{date('d-m-Y H:i:s')}}</i>
		</div>

		<div style="text-align: center;" >
			<h4 style="margin:0;padding:0;">LAPORAN PIUTANG</h4>
			<p style="font-size: 10pt;margin:0;padding:0;"><strong>Periode : </strong>{{$tanggal_awal . ' / ' . $tanggal_akhir}}</p>
			<br/>
		</div>

		<div class="content" >
			<?php $grand_total=0; ?>
		<table class="table-product" style="font-size:10pt;width: 100%;font-family: arial;"  >
			<thead>
				<tr  >
					@if($group_by != 'partner_id')
					<th style="width: 30%;border:1px solid #000;text-align: center;" >PARTNER</th>
					@endif
					<th style="width: 10%;border:1px solid #000;text-align: center;" >#</th>
					<th style="width: 10%;border:1px solid #000;text-align: center;" >TANGGAL</th>
					<th style="width: 10%;border:1px solid #000;text-align: center;" >SOURCE</th>
					<th style="width: 20%;border:1px solid #000;text-align: center;" >JUMLAH</th>
					<th style="width: 20%;border:1px solid #000;text-align: center;" >AMOUNT DUE</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data_group as $pg)
					@if($group_by == 'partner_id')
						<?php $subtitle = 'Partner';?>
						<?php $subtitle_val = $pg->partner;?>
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
						@if($tipe_report == 'detail')
							<td colspan="5" style="border:1px solid #000;background-color: whitesmoke;" >
								<strong>
									{{$subtitle}} : {{$subtitle_val}}
								</strong>
							</td>
						@else
							<td colspan="3" style="border:1px solid #000;" >
								{{$subtitle}} : {{$subtitle_val}}
							</td>
							<td style="text-align: right;border:1px solid #000;" >
								{{number_format($pg->sum_jumlah,2,'.',',')}}
							</td>
							<td style="text-align: right;border:1px solid #000;" >
								{{number_format($pg->sum_amount_due,2,'.',',')}}
							</td>
						@endif
					</tr>
					<?php $sum_jumlah_per_partner = 0; ?>
					<?php $sum_amount_per_partner = 0; ?>
					@if(isset($pg->detail))
						@foreach($pg->detail as $dt)
						<tr>
							@if($group_by != 'partner_id')
								<td style="border:1px solid #000;">{{$dt->Partner}}</td>
							@endif
							<td style="text-align: center;border:1px solid #000;" >
								{{$dt->name}}
							</td>
							<td style="text-align: center;border:1px solid #000;" >
								{{$dt->tanggal_format}}
							</td>						
							<td style="text-align: center;border:1px solid #000;" >
								{{$dt->source}}
							</td>
							<td style="text-align: right;border:1px solid #000;" >
								{{number_format($dt->jumlah,2,'.',',')}}
							</td>
							<td style="text-align: right;border:1px solid #000;" >
								{{number_format($dt->amount_due,2,'.',',')}}
							</td>
						</tr>
							<?php $sum_jumlah_per_partner += $dt->jumlah; ?>
							<?php $sum_amount_per_partner += $dt->amount_due; ?>
						@endforeach
						<tr>
							<td style="text-align: right;border:1px solid #000;" colspan="3" ><strong>TOTAL {{$subtitle_val}}</strong>
							</td>
							<td style="text-align: right;border:1px solid #000;" >
								<strong>{{number_format($sum_jumlah_per_partner,2,'.',',')}}</strong>
							</td>
							<td style="text-align: right;border:1px solid #000;" >
								<strong>{{number_format($sum_amount_per_partner,2,'.',',')}}</strong>
							</td>
						</tr>
					@endif

					
				@endforeach
			</tbody>
			<tfoot>
				<tr >
					<td colspan="3" style="text-align: center;border:1px solid #000;height: 30px;vertical-align: middle;" >
						<strong>TOTAL</strong>
					</td>
					<td style="text-align: right;border:1px solid #000;vertical-align: middle;" >
						<strong>{{number_format($sum_jumlah,2,'.',',')}}</strong>
					</td>
					<td style="text-align: right;border:1px solid #000;vertical-align: middle;" >
						<strong>{{number_format($sum_amount_due,2,'.',',')}}</strong>
					</td>
				</tr>
			</tfoot>
		</table>
		</div>

				
	</body>
</html>