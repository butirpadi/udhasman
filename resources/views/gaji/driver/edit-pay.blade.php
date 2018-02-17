@extends('layouts.master')

@section('styles')
	<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="plugins/select2/dist/css/select2.min.css">
	<style>
	    table.table tr td{
	    	padding:5px;
	    }
	</style>
@append

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
	    <h1>
	        <a href="gaji/driver" >Payroll Driver</a>
	        <i class="fa fa-angle-double-right" ></i>
	        <a href="gaji/driver/show-payroll-table/{{$data->payment_date_formatted}}" >{{$data->payment_date_formatted}}</a>
	        <i class="fa fa-angle-double-right" ></i>
	        {{$data->payroll_number}}
	    </h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="box box-solid">
			<div class="box-header with-border"  >
				<label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->payroll_number != '' ? $data->payroll_number : 'Draft'}}</h3></label>
				<label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
				<a class="btn btn-arrow-right pull-right disabled {{$data->state == 'paid' ? 'bg-blue' : 'bg-gray'}}" >Paid</a>
				<label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
				<a class="btn btn-arrow-right pull-right disabled {{$data->state == 'open' ? 'bg-blue' : 'bg-gray'}}" >Open</a>
				<label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
				<a class="btn btn-arrow-right pull-right disabled {{$data->state == 'draft' ? 'bg-blue' : 'bg-gray'}}" >Draft</a>
			</div>
			<div class="box-body">
				<input type="hidden" name="pay_id" value="{{$data->id}}">
		    	<div class="row" >
		    		<div class="col-xs-6" >
		    			<div class="form-group" >
		    				<label>Tanggal Gaji</label>
		    				<input type="text" readonly  name="periode_pembayaran" class="form-control" value="{{$data->payment_date_formatted}}" />
		    				<input type="hidden" name="tanggal_awal" value="{{$tanggal_awal}}" />
		    				<input type="hidden" name="tanggal_akhir" value="{{$tanggal_akhir}}" />
		    			</div>
		    			
		    		</div>
		    		<div class="col-xs-6" >
		    			<div class="form-group" >
		    				<label>Driver</label>
		    				<input type="text" readonly  name="nama" class="form-control" value="{{$data->kode_karyawan . ' - ' . $data->karyawan}}" />
		    			</div>
		    			<div class="form-group hide" >
		    				<label>Kode Karyawan</label>
		    				<input type="text" readonly  name="kode" class="form-control" value="{{$data->kode_karyawan}}" />
		    			</div>
		    		</div>

		    		<div class="col-xs-12 {{$data->state =='draft' ? 'hide':''}}" >
		    			<h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>DATA PENGIRIMAN</strong></h4>

		    			<table class="table table-bordered table-condensed" id="table-data" >
		    				<thead>
		    					<tr>
		    						<th>MATERIAL</th>
		    						<th>PEKERJAAN</th>
		    						<th class="hide" >KALKULASI</th>
		    						<th>RIT</th>
		    						<th>VOL</th>
		    						<th>NET</th>
		    						<th class="col-xs-2" >HARGA</th>
		    						<th class="col-xs-2" >JUMLAH</th>
		    					</tr>
		    				</thead>
		    				<tbody>
		    					@foreach($data->detail as $dt)
		    						<tr>
		    							<td>{{$dt->material}}</td>
		    							<td>{{$dt->pekerjaan}}</td>
		    							<td class="col-kal hide" >{{$dt->kalkulasi}}</td>
		    							<td class="col-rit" align="right" >{{$dt->qty}}</td>
		    							<td class="col-vol"  align="right" >{{$dt->volume}}</td>
		    							<td class="col-net" align="right" >{{$dt->netto}}</td>
		    							<td  >
		    								<input type="text" class="form-control input-harga text-right input-sm no-border" style="background-color: azure;" value="{{$dt->harga_satuan}}" data-payrolldetailid="{{$dt->id}}" >
		    							</td>
		    							<td class="col-jumlah text-right" >{{$dt->jumlah}}</td>
		    						</tr>
		    					@endforeach
		    				</tbody>
		    			</table>
		    		</div>

		    		<div class="col-xs-4 pull-right {{$data->state =='draft' ? 'hide':''}}" >
		    			<table class="table table-condensed" >
		    				<tbody>
		    					<tr>
		    						<td class="text-bold" >Jumlah</td>
		    						<td class="text-bold" >:</td>
		    						<td class="uang text-right text-bold">{{$data->total}}</td>
		    					</tr>
		    					<tr>
		    						<td class="text-bold" >Sisa Bayaran</td>
		    						<td class="text-bold" >:</td>
		    						<td class=""><input type="text" name="sisa_bayaran" class="form-control uang text-right text-bold no-border" style="border-bottom: solid 1px darkgrey!important;" value="{{$data->sisa_bayaran}}" ></td>
		    					</tr>
		    					<tr>
		    						<td class="text-bold" >DP</td>
		    						<td class="text-bold" >:</td>
		    						<td class=""><input type="text" name="dp" class="form-control uang text-right text-bold no-border" style="border-bottom: solid 1px darkgrey!important;" value="{{$data->sisa_bayaran}}" ></td>
		    					</tr>
		    					<tr>
		    						<td class="text-bold" >Potongan Bahan</td>
		    						<td class="text-bold" >:</td>
		    						<td class=""><input type="text" name="potongan_bahan" class="form-control uang text-right text-bold no-border" style="border-bottom: solid 1px darkgrey!important;" value="{{$data->sisa_bayaran}}" ></td>
		    					</tr>
		    					<tr>
		    						<td class="text-bold" >Potongan Bon</td>
		    						<td class="text-bold" >:</td>
		    						<td class=""><input type="text" name="potongan_bon" class="form-control uang text-right text-bold no-border" style="border-bottom: solid 1px darkgrey!important;" value="{{$data->sisa_bayaran}}" ></td>
		    					</tr>
		    				</tbody>
		    			</table>
		    		</div>

		    	</div>
		    </div>
		    <div class="box-footer" >
		    	@if($data->state == 'draft')
		    		<a class="btn btn-primary" id="btn-confirm" data-paymentid="{{$data->id}}" data-karyawanid="{{$data->karyawan_id}}" data-tanggal="{{$data->payment_date_formatted}}" ><i class="fa fa-check" ></i> Confirm</a>
		    	@elseif($data->state == 'open')
		    		<a class="btn btn-primary" id="btn-save" data-paymentid="{{$data->id}}" data-karyawanid="{{$data->karyawan_id}}" data-tanggal="{{$data->payment_date_formatted}}" ><i class="fa fa-save" ></i> Save</a>		    	
		    	@endif
		    	<a class="btn btn-danger" href="gaji/driver/show-payroll-table/{{$data->payment_date_formatted}}" ><i class="fa fa-close" ></i> Close</a>

		    	<!-- <a class="btn btn-success" href="payroll/payroll-staff/validate-pay/{{$data->id}}" ><i class="fa fa-check" ></i> Validate</a>
		    	<a class="btn btn-primary" id="btn-save-payroll" ><i class="fa fa-save" ></i> Save</a>
		    	<a class="btn btn-danger" href="payroll/payroll-staff/show-payroll-table/{{$data->payment_date_formatted}}" ><i class="fa fa-close" ></i> Close</a>
		    	<a id="btn-reset" class="btn btn-danger pull-right"><i class="fa fa-refresh" ></i> Reset</a> -->
		    </div>
		</div>
	</section>

	<div id="detail-form" ></div>

@append

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

	function formatNumeric(){
		// format autonumeric
		$('.input-harga, .col-jumlah, .uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });
	}

	formatNumeric();

	$('#btn-confirm').click(function(){
		var pay_id = $('input[name=pay_id]').val();
		var karyawan_id = $(this).data('karyawanid');
		var tanggal_awal = $('input[name=tanggal_awal]').val();
		var tanggal_akhir = $('input[name=tanggal_akhir]').val();
		var payment_id = $(this).data('paymentid');
		var url = 'gaji/driver/get-pengiriman/' + karyawan_id + '/' + tanggal_awal + '/' + tanggal_akhir + '/' + pay_id;
		// window.open(url,'_blank');
		var table = $('#table-data');
		$.getJSON(url,function(res){
			if(!table.parent().is(":visible")){
				table.parent().hide();
				table.parent().removeClass('hide');
				// table.parent().slideDown();		

				// // add item
				// $.each(res,function(i,data){
				// 	table.append($('<tr>')
				// 					.append($('<td>').text(data.material))
				// 					.append($('<td>').text(data.pekerjaan))
				// 					.append($('<td>').addClass('col-kal').text(data.kalkulasi))
				// 					.append($('<td>').addClass('col-rit').css('text-align','right').text(data.sum_rit))
				// 					.append($('<td>').addClass('col-vol').css('text-align','right').text(data.sum_vol))
				// 					.append($('<td>').addClass('col-net').css('text-align','right').text(data.sum_net))
				// 					.append($('<td>').append($('<input>').addClass('form-control input-harga text-right')))
				// 					.append($('<td>').addClass('col-jumlah text-right'))
				// 				);
				// });

				// formatNumeric();

				// hide tombol calculate
	        	$('#btn-confirm').hide();

	        	location.reload();

			}
		});
	});

	// calculate jumlah
	$(document).on('keyup','.input-harga',function(){
		var row = $(this).parent().parent();
		var kalkulasi = row.find('.col-kal').text().trim();
		var harga = Number($(this).autoNumeric('get'));
		var jumlah = 0;
		var qty = Number(kalkulasi == 'rit' ? row.find('.col-rit').text() : (kalkulasi == 'kubik' ? row.find('.col-vol').text() : (kalkulasi == 'ton' ? row.find('.col-net').text() : 0 ) ));
		var jumlah = qty * harga;
		row.find('.col-jumlah').autoNumeric('set',jumlah);

	});

	$('#btn-save').click(function(){
		jsonData = [];
		$('.input-harga').each(function(i,item){
			item = {};
			item['payroll_driver_detail_id'] = $(this).data('payrolldetailid');
			item['harga_satuan'] = $(this).autoNumeric('get') == '' ? 0 : $(this).autoNumeric('get');
			jsonData.push(item);
		});

		// $.post('gaji/driver/save-pay',function(){

		// });
		var form = $('<form>').attr('method','POST').attr('action','gaji/driver/save-pay').append($('<input>').attr('type','text').attr('name','pay_detail').val(JSON.stringify(jsonData)));
		$('#detail-form').append(form);
		form.submit();

	});

})(jQuery);
</script>
@append

