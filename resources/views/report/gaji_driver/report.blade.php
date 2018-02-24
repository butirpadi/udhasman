<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
		<table class="table-product" style="font-size:10pt;width: 100%;"  >
			<thead>
				<tr>
				@if($tipe_report == 'summary')
					<th colspan="3"  style="border:0.5px solid #000;text-align: center;" >DRIVER</th>
					<th style="border:0.5px solid #000;text-align: center;" >JUMLAH</th>
					<th style="border:0.5px solid #000;text-align: center;" >AMOUNT DUE</th>
				@else
					<th style="border:0.5px solid #000;text-align: center;" >#</th>
					<th style="border:0.5px solid #000;text-align: center;" >TANGGAL GAJI</th>
					<th style="border:0.5px solid #000;text-align: center;" >PERIODE</th>
					<th style="border:0.5px solid #000;text-align: center;" >JUMLAH</th>
					<th style="border:0.5px solid #000;text-align: center;" >AMOUNT DUE</th>
				@endif
				</tr>
			</thead>
			<tbody>
				@foreach($data as $pg)
					@if($tipe_report == 'summary')
						<tr>
							<td colspan="3" style="border:0.5px solid #000;" >
								<strong>
									{{$pg->kode_partner .' - ' . $pg->partner }}
								</strong>
							</td>
							<td style="text-align: right;" >
								{{number_format($pg->gaji_nett,2,'.',',')}}
							</td>
							<td style="text-align: right;" >
								{{number_format($pg->amount_due,2,'.',',')}}
							</td>
						</tr>
					@else
						<tr>
						<td colspan="5" style="border:0.5px solid #000;" >
							<strong>
								Driver : {{$pg->kode_partner .' - ' . $pg->partner }}
							</strong>
						</td>
					</tr> 
					@endif
					@if($tipe_report == 'detail')
						<?php $sum_partner_jumlah = 0; ?>
						<?php $sum_partner_amount_due = 0; ?>
						@foreach($pg->details as $dt)
						<tr>
							<td style="text-align: center;border:0.5px solid #000;" >
								{{$dt->name}}
							</td>
							<td style="text-align: center;border:0.5px solid #000;" >
								{{$dt->tanggal_format}}
							</td>	
							<td style="text-align: center;border:0.5px solid #000;" >
								{{$dt->tanggal_awal_format . ' / ' . $dt->tanggal_akhir_format}}
							</td>						
							<td style="text-align: right;border:0.5px solid #000;" >
								{{number_format($dt->gaji_nett,2,'.',',')}}
							</td>
							<td style="text-align: right;border:0.5px solid #000;" >
								{{number_format($dt->amount_due,2,'.',',')}}
							</td>
						</tr>
						<?php $sum_partner_amount_due+= $dt->amount_due; ?>
						<?php $sum_partner_jumlah+= $dt->gaji_nett; ?>
						@endforeach
						<tr>
							<td style="text-align: right;border:0.5px solid #000;" colspan="3" ><strong>TOTAL {{$pg->partner}}</strong>
							</td>
							<td style="text-align: right;border:0.5px solid #000;" >
								<strong>{{number_format($sum_partner_jumlah,2,'.',',')}}</strong>
							</td>
							<td style="text-align: right;border:0.5px solid #000;" >
								<strong>{{number_format($sum_partner_amount_due,2,'.',',')}}</strong>
							</td>
						</tr>
					@endif
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" style="text-align: center;border:0.5px solid #000;" >
						<strong>TOTAL</strong>
					</td>
					<td style="text-align: right;border:0.5px solid #000;" >
						<strong>{{number_format($sum_gaji_nett,2,'.',',')}}</strong>
					</td>
					<td style="text-align: right;border:0.5px solid #000;" >
						<strong>{{number_format($sum_amount_due,2,'.',',')}}</strong>
					</td>
				</tr>
			</tfoot>
		</table>		
	</body>
</html>