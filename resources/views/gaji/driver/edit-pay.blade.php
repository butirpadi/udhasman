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
				<label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->payroll_number}}</h3></label>
				<label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
				<a class="btn btn-arrow-right pull-right disabled {{$data->state == 'paid' ? 'bg-blue' : 'bg-gray'}}" >Paid</a>
				<label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
				<a class="btn btn-arrow-right pull-right disabled {{$data->state == 'open' ? 'bg-blue' : 'bg-gray'}}" >Open</a>
				<label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
				<a class="btn btn-arrow-right pull-right disabled {{$data->state == 'draft' ? 'bg-blue' : 'bg-gray'}}" >Draft</a>
			</div>
			<div class="box-body">
		    	<div class="row" >
		    		<div class="col-xs-6" >
		    			<div class="form-group" >
		    				<label>Tanggal Pembayaran</label>
		    				<input type="text" readonly  name="periode_pembayaran" class="form-control" value="{{$data->payment_date_formatted}}" />
		    				<input type="hidden" name="tanggal_awal" value="{{$tanggal_awal}}" />
		    				<input type="hidden" name="tanggal_akhir" value="{{$tanggal_akhir}}" />
		    			</div>
		    			<div class="form-group" >
		    				<label>Nama</label>
		    				<input type="text" readonly  name="nama" class="form-control" value="{{$data->karyawan}}" />
		    			</div>
		    		</div>
		    		<div class="col-xs-6" >
		    			<div class="form-group" >
		    				<label>Kode Karyawan</label>
		    				<input type="text" readonly  name="kode" class="form-control" value="{{$data->kode_karyawan}}" />
		    			</div>
		    		</div>

		    		<div class="col-xs-12 hide" >
		    			<h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>DATA PENGIRIMAN</strong></h4>

		    			<table class="table table-bordered table-condensed" id="table-data" >
		    				<thead>
		    					<tr>
		    						<th>MATERIAL</th>
		    						<th>PEKERJAAN</th>
		    						<th>RIT</th>
		    						<th>VOL</th>
		    						<th>NET</th>
		    						<th class="col-xs-2" >HARGA</th>
		    						<th class="col-xs-2" >JUMLAH</th>
		    					</tr>
		    				</thead>
		    				<tbody>
		    					
		    				</tbody>
		    			</table>
		    		</div>
		    	</div>
		    </div>
		    <div class="box-footer" >
		    	@if($data->state == 'draft')
		    		<a class="btn btn-primary" id="btn-calculate" data-paymentid="{{$data->id}}" data-karyawanid="{{$data->karyawan_id}}" data-tanggal="{{$data->payment_date_formatted}}" ><i class="fa fa-hourglass-half" ></i> Calculate</a>
		    	@endif

		    	<!-- <a class="btn btn-success" href="payroll/payroll-staff/validate-pay/{{$data->id}}" ><i class="fa fa-check" ></i> Validate</a>
		    	<a class="btn btn-primary" id="btn-save-payroll" ><i class="fa fa-save" ></i> Save</a>
		    	<a class="btn btn-danger" href="payroll/payroll-staff/show-payroll-table/{{$data->payment_date_formatted}}" ><i class="fa fa-close" ></i> Close</a>
		    	<a id="btn-reset" class="btn btn-danger pull-right"><i class="fa fa-refresh" ></i> Reset</a> -->
		    </div>
		</div>
	</section>
</div>

@append

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

	$('#btn-calculate').click(function(){
		var karyawan_id = $(this).data('karyawanid');
		var tanggal_awal = $('input[name=tanggal_awal]').val();
		var tanggal_akhir = $('input[name=tanggal_akhir]').val();
		var payment_id = $(this).data('paymentid');
		var url = 'gaji/driver/get-pengiriman/' + karyawan_id + '/' + tanggal_awal + '/' + tanggal_akhir;
		// alert(url);
		var table = $('#table-data');
		$.getJSON(url,function(res){
			if(!table.parent().is(":visible")){
				table.parent().hide();
				table.parent().removeClass('hide');
				table.parent().slideDown();		

				// add item
				$.each(res,function(i,data){
					table.append($('<tr>')
									.append($('<td>').text(data.material))
									.append($('<td>').text(data.pekerjaan))
									.append($('<td>').css('text-align','right').text(data.order_date + ' - ' + data.sum_rit))
									.append($('<td>').css('text-align','right').text(data.sum_vol))
									.append($('<td>').css('text-align','right').text(data.sum_net))
									.append($('<td>').append($('<input>').addClass('form-control')))
									.append($('<td>').append($('<input>').addClass('form-control')))
								);
				});		
			}
		});
	});

})(jQuery);
</script>
@append

