<style>

    table.table-product {
        border-collapse: collapse;
    }
    table.table-product th, table.table-product td {
        border: 1px solid black;
        padding: 10px;
        text-align: left;
    }
    table.table-product th{
    	text-align: center;
    }
    table.table-product td {
        padding: 5px;
        vertical-align: top;
    }
</style> 

<page orientation="L" format="A4" backtop="20mm" backleft="10mm" backbottom="10mm" backright="10mm" footer="page" > 
    <div style="text-align: center;" >
		<h4 style="margin:0;padding:0;">LAPORAN PEMBELIAN</h4>
		<p style="font-size: 10pt;margin:0;padding:0;"><strong>Periode : </strong>{{$tanggal_awal . ' / ' . $tanggal_akhir}}</p>
		<br/>
	</div>     
    
	<table class="table-product" style="font-size:12px;width: 100%;"   >
		<thead>
			<tr>
				<th style="width:10%;" >REF#</th>
				<th style="width:8%;" >TANGGAL</th>
				<th style="width:10%;" >NOMOR<br/>NOTA</th>
				<th style="width:33%;" >PRODUCT</th>
				<th style="width:9%;" colspan="2"  >QTY</th>
				<th style="width:15%;" >HARGA<br/>SATUAN</th>
				<th style="width:15%;" >JUMLAH</th>
			</tr>
		</thead>
		<tbody >
	    	@foreach($pembelian as $pb)
		    	<tr>
		    	@if($tipe_report == 'detail')
		    		<td colspan="8" >
		    			<strong>Supplier : {{$pb->supplier}}</strong>
		    		</td>
		    	@else 
		    		<td colspan="7" >
		    			<strong>Supplier : {{$pb->supplier}}</strong>
		    		</td>
		    		<td style="text-align: right;" >
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
	    	<tr style="font-size: 16px;" >
	    		<td colspan="6" style="text-align: center;">
	    			<strong>TOTAL</strong>
	    		</td>
	    		<td colspan="2" style="text-align: right;" >
	    			<strong>{{number_format($sum_total,2,'.',',')}}</strong>
	    		</td>
	    	</tr>
	    </tbody>
	</table>

</page> 

