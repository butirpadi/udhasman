<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style>
		    table.table-product, table.table-total {
		        border-collapse: collapse;
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

		    .background {
			  width: 100px;
			  height: 50px;
			  padding: 0;
			  margin: 0;
			}

			.line {
			  width: 112px;
			  height: 47px;
			  border-bottom: 1px solid red;
			  transform: translateY(-20px) translateX(5px) rotate(27deg);
			  position: absolute;
			  z-index: -1;
			}

			.background>div {
			  position: relative;
			  height: 100%;
			  width: 100%;
			  top: 0;
			  left: 0;
			}

			.bottom {
			  position: absolute;
			  bottom: 1px;
			  left: 1px;
			}

			.top {
			  position: absolute;
			  top: 1px;
			  right: 1px;
			}
	      	
	      	.cell-box{
	      		width: 25px!important;
	      	}
		</style> 
	</head>
	<body>
		<table class="table-product" style="font-size:8pt;font-family: arial;width: 100%;"  >
			<thead>
				<tr>
					<th rowspan="2" style="border:1px solid #000;" >KODE</th>
					<th rowspan="2" style="border:1px solid #000;" >NAMA</th>
					@if($tipe_report == 'detail')
						@foreach($data_group as $dt)
							<th  colspan="{{cal_days_in_month(CAL_GREGORIAN,$dt->bulan_idx,$dt->year)}}" style="border:1px solid #000;text-align: center;width: 100px;" >{{$dt->bulan}}</th>
						@endforeach
					@endif
					<th colspan="2" style="border:1px solid #000;text-align: center;" >HADIR</th>				
					<th colspan="2" style="border:1px solid #000;text-align: center;" >ALPHA</th>				
				</tr>
				<tr>
					@if($tipe_report == 'detail')
						@foreach($data_group as $dt)
							@for($i=1;$i <= cal_days_in_month(CAL_GREGORIAN,$dt->bulan_idx,$dt->year);$i++)
								<th style="border:1px solid #000;text-align: center;" >{{$i}}</th>
							@endfor
						@endforeach
					@endif
					<th>P</th>
					<th>S</th>
					<th>P</th>
					<th>S</th>
				</tr>
			</thead>
			<tbody>
				@foreach($karyawan as $kary)
					<tr >
						<td style="text-align: center;" >{{$kary->kode}}</td>
						<td>{{$kary->nama}}</td>
						<?php $siang = 0;?>
						<?php $pagi = 0;?>
						<?php $alpha = 0;?>
						<?php $alpha_pagi = 0;?>
						<?php $alpha_siang = 0;?>
						@foreach($data_group as $dt)
							@for($i=1;$i <= cal_days_in_month(CAL_GREGORIAN,$dt->bulan_idx,$dt->year);$i++)
								<?php $bg='background-color:white;color:black;'; ?>
								<?php $bg_cont=''; ?>
								@foreach($kary->presensi as $pres)
									@if($pres->bulan == $dt->bulan && $pres->day == $i)
										@if($pres->pagi == 'Y' && $pres->siang == 'Y')
											<?php $bg='background-color:green;color:white;'; ?>
											<?php $bg_cont='F'; ?>
										@elseif($pres->pagi == 'Y' && $pres->siang == 'N')
											<?php $bg='background-color:orangered;color:white;'; ?>
											<?php $bg_cont='P'; ?>
										@elseif($pres->pagi == 'N' && $pres->siang == 'Y')
											<?php $bg='background-color:yellow;color:darkred;'; ?>
											<?php $bg_cont='S'; ?>
										@else
											<?php $bg_cont='A'; ?>
										@endif

										@if($pres->siang == 'Y')
											<?php $siang += 1; ?>
										@endif
										@if($pres->pagi == 'Y')
											<?php $pagi += 1; ?>
										@endif
										@if($pres->pagi == 'N')
											<?php $alpha_pagi += 1; ?>
										@endif
										@if($pres->siang == 'N')
											<?php $alpha_siang += 1; ?>
										@endif
									@endif
								@endforeach	
								@if($tipe_report == 'detail')
								<td class="cell-box" style="text-align: center;{{$bg}}" >
									{{$bg_cont}}
								</td>
								@endif
							@endfor
						@endforeach		
						<td style="text-align: right;" >{{$pagi}}</td>
						<td style="text-align: right;">{{$siang}}</td>
						<td style="text-align: right;">{{$alpha_pagi}}</td>
						<td style="text-align: right;">{{$alpha_siang}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		@if($tipe_report == 'detail')
		<br/>
		<table>
			<tbody>
				<tr>
					<td style="width: 20px;height: 20px;background-color: green;text-align: center;color: white;" >F</td>
					<td style="padding-left: 10px;" >Hadir Pagi & Siang</td>
					<td style="width: 20px;" ></td>
					<td style="width: 20px;height: 20px;background-color: orangered;text-align: center;color: white;" >P</td>
					<td style="padding-left: 10px;" >Hadir Pagi</td>
				</tr>
				<tr>
					<td colspan="4" style="height: 5px;" ></td>
				</tr>
				<tr>
					<td style="width: 20px;height: 20px;background-color: yellow;text-align: center;color: darkred;" >S</td>
					<td style="padding-left: 10px;" >Hadir Siang</td>
					<td style="width: 20px;" ></td>
					<td style="width: 20px;height: 20px;background-color: white;text-align: center;" >A</td>
					<td style="padding-left: 10px;" >Alpha</td>
				</tr>
			</tbody>
		</table>
		@endif
	</body>
</html>