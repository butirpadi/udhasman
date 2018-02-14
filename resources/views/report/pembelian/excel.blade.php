<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<table class="table-product" style="font-size:10pt;width: 100%;font-family: arial;"  >
			<thead>
				<tr>
					<th colspan="8" style="text-align: center;" >REKAPITULASI PEMBELIAN</th>
				</tr>
				<tr  >
					<th colspan="8" style="text-align: center;" >Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</th>
				</tr>
				<tr  >
					<th style="width: 10%;border:1px solid #000;text-align: center;" >#</th>
					<th style="width: 10%;border:1px solid #000;text-align: center;" >TANGGAL</th>
					<th style="width: 10%;border:1px solid #000;text-align: center;" >NOTA</th>
					<th style="width: 33%;border:1px solid #000;text-align: center;" >PRODUK</th>
					<th colspan="2" style="width: 9%;border:1px solid #000;text-align: center;" >QTY</th>
					<th style="width: 15%;border:1px solid #000;text-align: center;" >HARGA</th>
					<th style="width: 15%;border:1px solid #000;text-align: center;" >JUMLAH</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pembelian as $pb)
			    	<tr>
			    	@if($tipe_report == 'detail')
			    		<td colspan="8" style="border:1px solid #000;">
			    			<strong>Supplier : {{$pb->supplier}}</strong>
			    		</td>
			    	@else 
			    		<td colspan="6" style="border:1px solid #000;" >
			    			<strong>Supplier : {{$pb->supplier}}</strong>
			    		</td>
			    		<td colspan="2" style="text-align: right;border:1px solid #000;" data-format="0,0.00" >{{$pb->total}}</td>
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
									<td style="border:1px solid #000;">{{$pr->nama_product}}</td>
									<td data-format="0" style="text-align: right;border:1px solid #000;" >{{$pr->qty}}</td>
									<td style="border:1px solid #000;">{{$pr->satuan}}</td>
									<td data-format="0,0.00" style="text-align: right;border:1px solid #000;" >{{$pr->unit_price}}</td>
									<td data-format="0,0.00" style="text-align: right;border:1px solid #000;" >{{$pr->unit_price*$pr->qty}}</td>
								</tr>
								<?php $total_per_supplier+=$pr->unit_price*$pr->qty; ?>
								<?php $first=false; ?>
							@endforeach
						@endforeach
						<tr>
							<td colspan="6" style="text-align: right;border:1px solid #000;">
								<strong>Total {{$pb->supplier}} </strong>
							</td>
							<td data-format="0,0.00" colspan="2" style="text-align: right;border:1px solid #000;font-weight: bold;" >{{$total_per_supplier}}</td>
						</tr>
					@endif
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6" style="text-align: center;border:1px solid #000;">
						<strong>TOTAL</strong>
					</td>
					<td colspan="2" style="text-align: right;border:1px solid #000;font-weight: bold;" data-format="0,0.00" >{{$sum_total}}</td>
				</tr>
			</tfoot>
		</table>		
	</body>
</html>