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

<page orientation="P" format="A4" backtop="20mm" backleft="10mm" backbottom="10mm" backright="10mm" > 
    <page_header > 
        <h4 style="margin:0;padding:0;text-align: center;" >Laporan Pembelian</h4>
		<p style="text-align: center;margin:0;padding:0;" >Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</p
>    </page_header> 
    <page_footer> 
         [[page_cu]]/[[page_nb]]
    </page_footer> 
    
    
			<table class="table-product" >
				<thead>
					<tr>
						<th style="width: 15%;" >REF#</th>
						<th style="width: 15%;" >TANGGAL</th>
						<th style="width: 15%;" >NOMOR<br/>NOTA</th>
						<th style="width: 35%;" >SUPPLIER</th>
						<th style="width: 20%;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody style="font-size: 12px;" >
					@foreach($pembelian as $dt)
						<tr>
							<td align="center" >
								{{$dt->ref}}
							</td>
							<td align="center" >
								{{$dt->tanggal_format}}
							</td>
							<td align="center" >
								{{$dt->supplier_ref}}
							</td>
							<td>
								{{$dt->nama_supplier}}
							</td>
							<td align="right" >
								{{number_format($dt->total,2,'.',',')}}
							</td>
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" align="center" >
							<strong>TOTAL</strong>
						</td>
						<td style="text-align: right;" >
							<strong>{{number_format($sum_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
</page> 

