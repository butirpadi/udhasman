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

<page orientation="L" format="A4" backtop="20mm" backleft="10mm" backbottom="10mm" backright="10mm" > 
    <page_header > 
        <h4 style="margin:0;padding:0;margin-bottom:5px;text-align: center;" >Laporan Pengiriman Material</h4>
		<p style="text-align: center;margin:0;padding:0;" >Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</p
>    </page_header> 
    <page_footer> 
         [[page_cu]]/[[page_nb]]
    </page_footer> 
    
    
			<table class="table-product" style="font-size:10px;"  >
				<thead>
					<tr>
						<th style="width: 10%;" >REF#</th>
						<th style="width: 10%;" >TANGGAL</th>
						<th style="width: 20%;" >CUSTOMER</th>
						<th style="width: 10%;" >PEKERJAAN</th>
						<th style="width: 10%;" >MATERIAL</th>
						<th style="width: 15%;" >DRIVER</th>
						<th style="width: 10%;" >NOPOL</th>
						<th style="width: 5%;" >RIT</th>
						<th style="width: 5%;" >VOL</th>
						<th style="width: 5%;" >TON</th>
					</tr>
				</thead>
				<tbody >
					@foreach($pengiriman as $dt)
						<tr>
							<td align="center" >
								{{$dt->name}}
							</td>
							<td align="center" >
								{{$dt->order_date_format}}
							</td>
							<td >
								{{$dt->customer}}
							</td>
							<td>
								{{$dt->pekerjaan}}
							</td>
							<td>
								{{$dt->material}}
							</td>
							<td >
								{{$dt->karyawan }}
							</td>
							<td >
								{{$dt->nopol }}
							</td>
							<td align="right" >
								1
							</td>
							<td align="right" >
								@if($dt->kalkulasi == 'kubik')
									{{number_format($dt->volume,2)}}
								@else
								-
								@endif
							</td>
							<td align="right" >
								@if($dt->kalkulasi == 'ton')
									{{number_format($dt->volume,2)}}
								@else
									-
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
				<!-- <tfoot>
					<tr>
						<td colspan="5" align="center" >
							<strong>TOTAL</strong>
						</td>
						<td style="text-align: right;" >
							<strong>{{number_format($sum_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot> -->
			</table>
</page> 

