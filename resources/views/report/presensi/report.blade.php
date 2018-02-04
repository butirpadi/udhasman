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
	      		width: 20px!important;
	      		
	      	}
		</style> 
	</head>
	<body>
		<table class="table-product" style="font-size:8pt;font-family: arial;width: 100%;"  >
			<thead>
				<tr>
					<th rowspan="2" style="border:1px solid #000;" >KODE</th>
					<th rowspan="2" style="border:1px solid #000;" >NAMA</th>
					@foreach($data_group as $dt)
						<th  colspan="{{cal_days_in_month(CAL_GREGORIAN,$dt->bulan_idx,$dt->year)}}" style="border:1px solid #000;text-align: center;width: 100px;" >{{$dt->bulan}}</th>
					@endforeach
					<th colspan="2" style="border:1px solid #000;text-align: center;" >HADIR</th>				
					<th rowspan="2" style="border:1px solid #000;text-align: center;" >ALPHA</th>				
				</tr>
				<tr>
					@foreach($data_group as $dt)
						@for($i=1;$i <= cal_days_in_month(CAL_GREGORIAN,$dt->bulan_idx,$dt->year);$i++)
							<th style="border:1px solid #000;text-align: center;" >{{$i}}</th>
						@endfor
					@endforeach
					<th>P</th>
					<th>S</th>
				</tr>
			</thead>
			<tbody>
				@foreach($karyawan as $kary)
					<tr >
						<td>{{$kary->kode}}</td>
						<td>{{$kary->nama}}</td>
						<?php $siang = 0;?>
						<?php $pagi = 0;?>
						<?php $alpha = 0;?>
						@foreach($data_group as $dt)
							@for($i=1;$i <= cal_days_in_month(CAL_GREGORIAN,$dt->bulan_idx,$dt->year);$i++)
								<?php $bg=url('img/presensi_null.png'); ?>
								@foreach($kary->presensi as $pres)
									@if($pres->bulan == $dt->bulan && $pres->day == $i)
										@if($pres->pagi == 'Y' && $pres->siang == 'Y')
											<?php $bg=url('img/presensi_full.png'); ?>
										@elseif($pres->pagi == 'Y' && $pres->siang == 'N')
											<?php $bg=url('img/presensi_pagi.png'); ?>
										@elseif($pres->pagi == 'N' && $pres->siang == 'Y')
											<?php $bg=url('img/presensi_siang.png'); ?>
										@endif

										@if($pres->siang == 'Y')
											<?php $siang += 1; ?>
										@endif
										@if($pres->pagi == 'Y')
											<?php $pagi += 1; ?>
										@endif
										@if($pres->pagi == 'N' && $pres->siang == 'N')
											<?php $alpha += 1; ?>
										@endif
									@endif
								@endforeach	
								<td class="cell-box" style="background-position: center;background-image:url({{$bg}}); background-repeat: no-repeat;" >
									
								</td>
							@endfor
						@endforeach		
						<td>{{$pagi}}</td>
						<td>{{$siang}}</td>
						<td>{{$alpha}}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>