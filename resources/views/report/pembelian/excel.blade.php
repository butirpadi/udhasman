<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<div class="content" >
			<table style="font-size:12px;width: 100%;"   >
			<thead>
				<tr>
					<th colspan="8" style="text-align: center;border:none;" ><strong>LAPORAN PEMBELIAN</strong></th>
				</tr>
				<tr>
					<th colspan="8" style="text-align: center;border:none;" ><strong>Periode : </strong>{{$tanggal_awal . ' / ' . $tanggal_akhir}}</th>
				</tr>
				<tr>
					<th colspan="8" ></th>
				</tr>
				<tr>
					<th style="width:10%;border:1px solid #000;text-align: center;" >REF#</th>
					<th style="width:100px;border:1px solid #000;text-align: center;" >TANGGAL</th>
					<th style="width:10%;border:1px solid #000;text-align: center;" >NOMOR<br/>NOTA</th>
					<th style="width:33%;border:1px solid #000;text-align: center;" >PRODUCT</th>
					<th style="width:9%;border:1px solid #000;text-align: center;" colspan="2"  >QTY</th>
					<th style="width:15%;border:1px solid #000;text-align: center;" >HARGA<br/>SATUAN</th>
					<th style="width:15%;border:1px solid #000;text-align: center;" >JUMLAH</th>
				</tr>
			</thead>
			<tbody >
		    	@foreach($pembelian as $pb)
			    	<tr>
			    	@if($tipe_report == 'detail')
			    		<td colspan="8" style="border:1px solid #000;">
			    			<strong>Supplier : {{$pb->supplier}}</strong>
			    		</td>
			    	@else 
			    		<td colspan="7" style="border:1px solid #000;" >
			    			<strong>Supplier : {{$pb->supplier}}</strong>
			    		</td>
			    		<td style="text-align: right;border:1px solid #000;" >
			    			{{number_format($pb->total,2,'.',',')}}
			    		</td>
			    	@endif
			    	</tr>
			    	@if($tipe_report == 'detail')
						<?php $total_per_supplier=0; ?>
						@foreach($pb->po as $rp)
							<?php $first = true; ?>
							@foreach($rp->products as $pr)
								<tr>
									<td style="text-align: center;border:1px solid #000;" >
										@if($first)
											{{$rp->ref}}
										@endif
									</td>
									<td style="text-align: center;border:1px solid #000;" >
										@if($first)
											{{$rp->tanggal_format}}
										@endif
									</td>
									<td style="text-align: center;border:1px solid #000;" >
										@if($first)
											{{$rp->supplier_ref}}
										@endif
									</td>
									<td style="border:1px solid #000;">
										<p style="margin:0;padding: 2px;" >{{$pr->nama_product}}</p>
										
									</td>
									<td style="text-align: right;border:1px solid #000;" >
										<p style="margin:0;padding: 2px;" >{{$pr->qty}}</p>
									</td>
									<td style="border:1px solid #000;">
										<p style="margin:0;padding: 2px;" >{{$pr->satuan}}</p>
									</td>
									<td style="text-align: right;border:1px solid #000;" >
										<p style="margin:0;padding: 2px;" >{{number_format($pr->unit_price,2,'.',',')}}</p>
									</td>
									<td style="text-align: right;border:1px solid #000;" >
										<p style="margin:0;padding: 2px;" >{{number_format($pr->unit_price*$pr->qty,2,'.',',')}}</p>
										<?php $total_per_supplier+=$pr->unit_price*$pr->qty; ?>
									</td>
								</tr>
								<?php $first=false; ?>
							@endforeach
						@endforeach
						<tr>
							<td colspan="6" style="text-align: right;border:1px solid #000;">
								<strong>Total {{$pb->supplier}} </strong>
							</td>
							<td colspan="2" style="text-align: right;border:1px solid #000;" >
								<strong>{{number_format($total_per_supplier,2,'.',',')}}</strong>
							</td>
						</tr>
					@endif
		    	@endforeach
		    	<tr style="font-size: 16px;" >
		    		<td colspan="6" style="text-align: center;border:1px solid #000;">
		    			<strong>TOTAL</strong>
		    		</td>
		    		<td colspan="2" style="text-align: right;border:1px solid #000;" >
		    			<strong>{{number_format($sum_total,2,'.',',')}}</strong>
		    		</td>
		    	</tr>
		    </tbody>
		</table>
		</div>

				
	</body>
</html>