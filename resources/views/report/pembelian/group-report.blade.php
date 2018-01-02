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
    <page_header > 
        <h4 style="margin:0;padding:0;text-align: center;" >Laporan Pembelian</h4>
		<p style="text-align: center;margin:0;padding:0;" >Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</p
>    </page_header> 
    <page_footer  > 
         <i style="font-size: 10px;margin-left: 10mm;" >Laporan Pembelian {{$tanggal_awal . ' / ' . $tanggal_akhir}}</i>
    </page_footer> 
    
    	<table class="table-product" style="font-size:12px;"  >
			<thead>
				<tr>
					<th style="width:10%;" >REF#</th>
					<th style="width:8%;" >TANGGAL</th>
					<th style="width:10%;" >NOMOR<br/>NOTA</th>
					<th style="width:28%;" >PRODUCT</th>
					<th style="width:9%;" colspan="2"  >QTY</th>
					<th style="width:15%;" >HARGA<br/>SATUAN</th>
					<th style="width:15%;" >JUMLAH</th>
					<th style="width:5%;" >PAID</th>
				</tr>
			</thead>
			<tbody >
		    	@foreach($pembelian as $pb)
		    	<tr>
		    		<td colspan="9" >
		    			<strong>SUPPLIER : {{$pb->nama_supplier}}</strong>
		    		</td>
		    	</tr>
				<!-- <tr>
					<th style="width:10%;" >REF#</th>
					<th style="width:10%;" >TANGGAL</th>
					<th style="width:10%;" >NOMOR NOTA</th>
					<th style="width:40%;" >PRODUCT</th>
					<th style="width:10%;" >QTY</th>
					<th style="width:10%;" >HARGA SATUAN</th>
					<th style="width:10%;" >JUMLAH</th>
				</tr> -->
				<?php $total_per_supplier=0; ?>
				@foreach($pb->reports as $rp)
					<tr>
						<td align="center" >
							{{$rp->ref}}
						</td>
						<td align="center" >
							{{$rp->tanggal_format}}
						</td>
						<td align="center" >
							{{$rp->supplier_ref}}
						</td>
						<td>
							@foreach($rp->products as $pr)
								<p style="margin:0;padding: 2px;" >{{$pr->nama_product}}</p>
							@endforeach
						</td>
						<td align="right" >
							@foreach($rp->products as $pr)
								<p style="margin:0;padding: 2px;" >{{$pr->qty}}</p>
							@endforeach
						</td>
						<td >
							@foreach($rp->products as $pr)
								<p style="margin:0;padding: 2px;" >{{$pr->satuan}}</p>
							@endforeach
						</td>
						<td align="right" >
							@foreach($rp->products as $pr)
								<p style="margin:0;padding: 2px;" >{{number_format($pr->unit_price,2,'.',',')}}</p>
							@endforeach
						</td>
						<td align="right" >
							@foreach($rp->products as $pr)
								<p style="margin:0;padding: 2px;" >{{number_format($pr->unit_price*$pr->qty,2,'.',',')}}</p>
								<?php $total_per_supplier+=$pr->unit_price*$pr->qty; ?>
							@endforeach
						</td>
						<td align="center" valign="middle" >
							@if($rp->bill_state == 'P')
							PAID
							@else
							-
							@endif
						</td>
					</tr>
				@endforeach
				<tr>
					<td colspan="6" align="right" >
						<strong>TOTAL {{$pb->nama_supplier}} </strong>
					</td>
					<td align="right" colspan="2" >
						<strong>{{number_format($total_per_supplier,2,'.',',')}}</strong>
					</td>
					<td></td>
				</tr>
		    	@endforeach
		    	<tr style="font-size: 16px;" >
		    		<td colspan="6" align="center" >
		    			<strong>TOTAL</strong>
		    		</td>
		    		<td colspan="2" align="right" >
		    			<strong>{{number_format($sum_total,2,'.',',')}}</strong>
		    		</td>
		    		<td></td>
		    	</tr>
		    </tbody>
		</table>






</page> 

