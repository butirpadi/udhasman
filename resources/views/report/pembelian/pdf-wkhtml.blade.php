<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>.:: Laporan Pembelian ::.</title>
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

		<div style="text-align: center;" >
			<h4 style="margin:0;padding:0;">LAPORAN PEMBELIAN</h4>
			<p style="margin:0;padding:0;"><strong>Periode : </strong>{{$tanggal_awal . ' / ' . $tanggal_akhir}}</p>
			<br/>
		</div>

		<div class="content" >
			<table class="table-product" style="width: 100%;"   >
			<thead>
				<tr>
					<th style="width:10%;" >REF#</th>
					<th style="width:11%;" >TANGGAL</th>
					<th style="width:10%;" >NOMOR<br/>NOTA</th>
					<th style="width:30%;" >PRODUCT</th>
					<th style="width:9%;" colspan="2"  >QTY</th>
					<th style="width:15%;" >HARGA<br/>SATUAN</th>
					<th style="width:15%;" >JUMLAH</th>
				</tr>
			</thead>
			<tbody >
		    	@foreach($pembelian as $pb)
			    	<tr>
			    	@if($tipe_report == 'detail')
			    		<td colspan="8" style="background-color: azure;" >
			    			<strong>Supplier : {{$pb->supplier}}</strong>
			    		</td>
			    	@else 
			    		<td colspan="7" style="background-color: azure;" >
			    			<strong>Supplier : {{$pb->supplier}}</strong>
			    		</td>
			    		<td style="text-align: right;background-color: azure;" >
			    			{{number_format($pb->total,2,'.',',')}}
			    		</td>
			    	@endif
			    	</tr>
			    	@if($tipe_report == 'detail')
						<?php $total_per_supplier=0; ?>
						@foreach($pb->reports as $rp)
							<tr>
								<td style="text-align: center;" >
									{{$rp->ref}}
								</td>
								<td style="text-align: center;" >
									{{$rp->tanggal_format}}
								</td>
								<td style="text-align: center;" >
									{{$rp->supplier_ref}}
								</td>
								<td>
									@foreach($rp->products as $pr)
										<p style="margin:0;padding: 2px;" >{{$pr->nama_product}}</p>
									@endforeach
								</td>
								<td style="text-align: right;" >
									@foreach($rp->products as $pr)
										<p style="margin:0;padding: 2px;" >{{$pr->qty}}</p>
									@endforeach
								</td>
								<td >
									@foreach($rp->products as $pr)
										<p style="margin:0;padding: 2px;" >{{$pr->satuan}}</p>
									@endforeach
								</td>
								<td style="text-align: right;" >
									@foreach($rp->products as $pr)
										<p style="margin:0;padding: 2px;" >{{number_format($pr->unit_price,2,'.',',')}}</p>
									@endforeach
								</td>
								<td style="text-align: right;" >
									@foreach($rp->products as $pr)
										<p style="margin:0;padding: 2px;" >{{number_format($pr->unit_price*$pr->qty,2,'.',',')}}</p>
										<?php $total_per_supplier+=$pr->unit_price*$pr->qty; ?>
									@endforeach
								</td>
							</tr>
						@endforeach
						<tr>
							<td colspan="6" style="text-align: right;">
								<strong>Total {{$pb->supplier}} </strong>
							</td>
							<td colspan="2" style="text-align: right;" >
								<strong>{{number_format($total_per_supplier,2,'.',',')}}</strong>
							</td>
						</tr>
					@endif
		    	@endforeach
		    	<tr  >
		    		<td colspan="6" style="text-align: center;">
		    			<strong>TOTAL</strong>
		    		</td>
		    		<td colspan="2" style="text-align: right;" >
		    			<strong>{{number_format($sum_total,2,'.',',')}}</strong>
		    		</td>
		    	</tr>
		    </tbody>
		</table>
		</div>

				
	</body>
</html>