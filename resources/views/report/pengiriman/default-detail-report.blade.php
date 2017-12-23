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
</style> 

<page orientation="L" format="A4" backtop="15mm" backleft="10mm" backbottom="10mm" backright="10mm" footer="page" > 
    <page_header > 
        <h4 style="margin:0;padding:0;text-align: center;" >Rekapitulasi Pengiriman Material</h4>
		<p style="text-align: center;margin:0;padding:0;" >Periode : {{$tanggal_awal . ' / ' . $tanggal_akhir}}</p
>    </page_header> 
    <page_footer> 
         <i style="font-size: 10px;" >Dicetak pada {{$dicetak}}</i>
    </page_footer>    

    	@foreach($pengiriman as $pg)
    		<table>
    			<tbody>
    				<tr>
    					<td>
    						<table style="font-size: 12px;">
				    			<tbody>
				    				<tr>
				    					<td>
				    						<strong>Customer</strong>
				    					</td>
				    					<td>:</td>
				    					<td>
				    						{{$pg->customer}}
				    					</td>
				    				</tr>
				    				<tr>
				    					<td>
				    						<strong>Pekerjaan</strong>
				    					</td>
				    					<td>:</td>
				    					<td>
				    						{{$pg->pekerjaan}}
				    					</td>
				    				</tr>
				    				<tr>
				    					<td>
				    						<strong>Material</strong>
				    					</td>
				    					<td>:</td>
				    					<td>
				    						{{$pg->material}}
				    					</td>
				    				</tr>
				    			</tbody>
				    		</table>
    					</td>
    				</tr>
    			</tbody>
    		</table>
    		
    		<br/>

    		@if($pg->kalkulasi == 'kubik')
    		<?php $sum_vol=0; ?>
    		<?php $sum_total=0; ?>
    		<table class="table-product" style="font-size:12px;"  >
				<thead>
					<tr>
						<th style="width: 10%;" >REF#</th>
						<th style="width: 8%;" >TANGGAL</th>
						<th style="width: 20%;" >DRIVER</th>
						<th style="width: 10%;" >NOPOL</th>
						<th style="width: 7%;" >P</th>
						<th style="width: 7%;" >L</th>
						<th style="width: 7%;" >T</th>
						<th style="width: 7%;" >VOL</th>
						<th style="width: 12%;" >HARGA SATUAN</th>
						<th style="width: 12%;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pg->detail as $dt)
					<tr>
						<td align="center" >
							{{$dt->name}}
						</td>
						<td align="center" >
							{{$dt->order_date_format}}
						</td>
						<td>
							{{$dt->karyawan}}
						</td>
						<td align="center" >
							{{$dt->nopol}}
						</td>
						<td align="right" >
							{{$dt->panjang}}
						</td>
						<td align="right" >
							{{$dt->lebar}}
						</td>
						<td align="right" >
							{{$dt->tinggi}}
						</td>
						<td align="right" >
							{{$dt->volume}}
							<?php $sum_vol+=$dt->volume ?>
						</td>
						<td align="right" >
							{{number_format($dt->harga_satuan,2,'.',',')}}
						</td>
						<td align="right" >
							{{number_format($dt->harga_total,2,'.',',')}}
							<?php $sum_total+=$dt->harga_total ?>
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="7" align="center" >
							<strong>TOTAL</strong>
						</td>
						<td align="right" >
							<strong>{{$sum_vol}}</strong>
						</td>
						<td colspan="2" align="right" >
							<strong>{{number_format($sum_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
			@endif


			@if($pg->kalkulasi == 'ton')
			<?php $sum_net = 0; ?>
			<?php $sum_total = 0; ?>
    		<table class="table-product" style="font-size:12px;"  >
				<thead>
					<tr>
						<th style="width: 10%;" >REF#</th>
						<th style="width: 8%;" >TANGGAL</th>
						<th style="width: 27%;" >DRIVER</th>
						<th style="width: 10%;" >NOPOL</th>
						<th style="width: 7%;" >G</th>
						<th style="width: 7%;" >T</th>
						<th style="width: 7%;" >NET</th>
						<th style="width: 12%;" >HARGA SATUAN</th>
						<th style="width: 12%;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pg->detail as $dt)
					<tr>
						<td align="center" >
							{{$dt->name}}
						</td>
						<td align="center" >
							{{$dt->order_date_format}}
						</td>
						<td>
							{{$dt->karyawan}}
						</td>
						<td align="center" >
							{{$dt->nopol}}
						</td>
						<td align="right" >
							{{$dt->gross}}
						</td>
						<td align="right" >
							{{$dt->tare}}
						</td>
						<td align="right" >
							{{$dt->netto}}
							<?php $sum_net+=$dt->netto; ?>
						</td>
						<td align="right" >
							{{number_format($dt->harga_satuan,2,'.',',')}}
						</td>
						<td align="right" >
							{{number_format($dt->harga_total,2,'.',',')}}
							<?php $sum_total+=$dt->harga_total; ?>
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6" align="center" >
							<strong>TOTAL</strong>
						</td>
						<td align="right" >
							<strong>{{$sum_net}}</strong>
						</td>
						<td colspan="2" align="right" >
							<strong>{{number_format($sum_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
			@endif

			@if($pg->kalkulasi == 'rit')
			<?php $sum_rit = 0; ?>
			<?php $sum_total = 0; ?>
    		<table class="table-product" style="font-size:12px;"  >
				<thead>
					<tr>
						<th style="width: 10%;" >REF#</th>
						<th style="width: 10%;" >TANGGAL</th>
						<th style="width: 35%;" >DRIVER</th>
						<th style="width: 10%;" >NOPOL</th>
						<th style="width: 5%;" >RIT</th>
						<th style="width: 15%;" >HARGA SATUAN</th>
						<th style="width: 15%;" >JUMLAH</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pg->detail as $dt)
					<tr>
						<td align="center" >
							{{$dt->name}}
						</td>
						<td align="center">
							{{$dt->order_date_format}}
						</td>
						<td>
							{{$dt->karyawan}}
						</td>
						<td align="center" >
							{{$dt->nopol}}
						</td>
						<td align="right" >
							{{$dt->qty}}
							<?php $sum_rit+=$dt->qty; ?>
						</td>
						<td align="right" >
							{{number_format($dt->harga_satuan,2,'.',',')}}
						</td>
						<td align="right" >
							{{number_format($dt->harga_total,2,'.',',')}}
							<?php $sum_total+=$dt->harga_total; ?>
						</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" align="center" >
							<strong>TOTAL</strong>
						</td>
						<td align="right" >
							<strong>{{$sum_rit}}</strong>
						</td>
						<td colspan="2" align="right" >
							<strong>{{number_format($sum_total,2,'.',',')}}</strong>
						</td>
					</tr>
				</tfoot>
			</table>
			@endif
    	@endforeach
    

    	<br/>
    	<table class="table-total" >
    		<tbody>
    			<tr>
    				<td  >
    					<table class="table-total">
							<tbody>
								<tr>
									<td style="width: 25%;" ></td>
									<td style="width: 35%;" ></td>
									<td style="width: 10%;border-top: solid thin black;vertical-align: top;" align="left" >
										<strong>TOTAL</strong>
									</td>
									<td style="width: 30%;border-top: solid thin black;" align="right" >
										<strong>{{number_format($grand_total,2,'.',',')}}</strong>
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

