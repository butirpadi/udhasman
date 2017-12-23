<page footer="page" backtop="20mm" backleft="10mm" backright="10mm" backbottom="10mm" > 
    <page_header  > 
    	<style>
		    table.table-product,table.table-total {
		        border-collapse: collapse;
		      }
		    table.table-product th, table.table-product td {
		        border: 1px solid black;
		        /*padding: 10px;*/
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
		</style> 
    	<h4 style="margin: 0;padding: 0;margin-top:10mm;text-align: center;" >REKAPITULASI PENGIRIMAN MATERIAL</h4>
		<p style="text-align: center;margin:0;padding:0;" >Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</p
>    </page_header> 
    <page_footer> 
         <i style="font-size: 10px;" >Dicetak pada {{$dicetak}}</i>
    </page_footer> 
    
    <br/>
    <div style="width: 100%;" >
    	<table class="table-product" style="font-size:12px;width: inherit;"   >
			<thead>
				<tr>
					<th style="width: 25%;" >CUSTOMER</th>
					<th style="width: 18%;" >PEKERJAAN</th>
					<th style="width: 12%;" >MATERIAL</th>
					<th style="width: 10%;" >RIT</th>
					<th style="width: 10%;" >VOL</th>
					<th style="width: 10%;" >NET</th>
					<th style="width: 15%;" >JUMLAH</th>
				</tr>
			</thead>
			<tbody>
				<?php $sum_rit=0; ?>
				<?php $sum_vol=0; ?>
				<?php $sum_net=0; ?>
				@foreach($pengiriman as $dt)
					<tr>
						<td  >
							{{$dt->customer}}
						</td>
						<td  >
							{{$dt->pekerjaan}}
						</td>
						<td  >
							{{$dt->material}}
						</td>
						<td align="right" >
							{{$dt->rit}}
							<?php $sum_rit+=$dt->rit; ?>
						</td>
						<td align="right" >
							{{$dt->vol}}
							<?php $sum_vol+=$dt->vol; ?>
						</td>
						<td align="right" >
							{{$dt->net}}
							<?php $sum_net+=$dt->net; ?>
						</td>
						<td align="right" >
							{{number_format($dt->harga_total,2,'.',',')}}
						</td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" align="center" >
						<strong>TOTAL</strong>
					</td>
					<td align="right" >
						{{$sum_rit}}
					</td>
					<td align="right" >
						{{$sum_vol}}
					</td>
					<td align="right" >
						{{$sum_net}}
					</td>
					<td align="right" >
						<strong>{{number_format($sum_total,2,'.',',')}}</strong>
					</td>
				</tr>
			</tfoot>
		</table>
    </div>
	


	<br/>
    	<table class="table-total" >
    		<tbody>
    			<tr>
    				<td 0;" >
    					<table class="table-total" style="width: 100%;" >
							<tbody>
								<tr>
									<td style="width: 20%;" ></td>
									<td style="width: 35%;" ></td>
									<td style="width: 10%;border-top: solid thin black;vertical-align: top;" align="left" >
										<strong>TOTAL</strong>
									</td>
									<td style="width: 35%;border-top: solid thin black;" align="right" >
										<strong>{{number_format($sum_total,2,'.',',')}}</strong>
									</td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td align="center" >
										<br/>
										<br/>
										<span style="text-align: center;" >Dibuat,</span>
										<br/>
										<br/>
										<br/>
										<br/>
										<br/>
										<br/>
										<strong style="text-align: center;" >(____________________________)</strong>
									</td>
								</tr>
							</tbody>
						</table>
    				</td>
    			</tr>
    		</tbody>
    	</table>
</page> 

