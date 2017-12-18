@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/select2.min.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="plugins/iCheck/all.css">

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="tagihan-customer" >Data Tagihan Customer</a> <i class="fa fa-angle-double-right" ></i> 
        Batch Edit
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <h3 style="margin:0;padding:0;" >{{$customer->nama}}</h3>
        </div>
        <div class="box-body">
            <input type="hidden" name="customer_id" value="{{$customer->id}}"> 
            <input type="hidden" name="has_saved_count" value="0"> 
            <input type="hidden" name="pekerjaan_id" value="{{$pekerjaan->id}}"> 

            <table class="table table-condensed no-border table-master-header" >
                <tbody>
                    <tr>
                        <td class="col-xs-2" >
                            <label>Customer</label> 
                        </td>
                        <td class="col-xs-4" >
                            {{$customer->nama}}
                        </td>
                        <td class="col-xs-2" >
                            <label>Pekerjaan</label>
                        </td>
                        <td class="col-xs-4" id="label-tanggal-order" >
                            {{$pekerjaan->nama}}
                        </td>
                    </tr>
                </tbody>
            </table>
            <br/>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table class="table table-bordered table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th>Nomor<br/>Penjualan</th>
                        <th>Tanggal<br/>Order</th>
                        
                        <th>Nomor<br/>Pengiriman</th>
                        <th>Tanggal<br/>Pengiriman</th>
                        <th>Material</th>
                        {{-- <th>Kalkulasi</th> --}}
                        <th>Total</th>
                        <th>Status</th>
                        <th><label>PEMBAYARAN<br/><input type="checkbox" name="checkall" class="minimal"></label></th>
                        <th class="col-xs-2" >TANGGAL<br/>PEMBAYARAN</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data_tagihan as $dt)
                    <tr  data-id="{{$dt->id}}">
                        <td class="text-center" >
                            {{$dt->order_number}}
                        </td>
                        <td class="text-center" >
                            {{$dt->tanggal_order_format}}
                        </td>
                        
                        <td class="text-center" >
                            {{$dt->kode_pengiriman}}
                        </td>
                        <td class="text-center" >
                            {{$dt->tanggal_pengiriman_format}}
                        </td>
                        <td class="text-center" >
                            {{$dt->nama_material}}
                        </td>
                        <td class="text-right uang" >
                            {{$dt->total}}
                        </td>
                        <td class="text-center" >
                             @if($dt->status == 'DRAFT')
                                <label class="label label-default" >DRAFT</label>
                            @elseif($dt->status == 'OPEN')
                                <label class="label label-warning" >OPEN</label>
                            @elseif($dt->status =='PAID')
                                <label class="label label-success" >PAID</label>
                            @elseif($dt->status =='CANCELED')
                                <label class="label label-danger" >CANCELED</label>
                            @endif
                        </td>
                        <td class="text-center" >
                            <input type="checkbox" name="bayar" class="minimal" >
                        </td>
                        <td>
                            
                        </td>
                        <td class="text-center" >
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            @if(count($data_tagihan) > 0)
                <button class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            @endif
            {{-- <button class="btn btn-success" id="btn-save-validate" ><i class="fa fa-check" ></i> Save & Validate</button> --}}
            <a class="btn btn-danger" href="tagihan-customer" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/select2/select2.full.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>

<script type="text/javascript">
(function ($) {

    // SET AUTONUMERIC
    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
        });

    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
    });

    // check all
    $('input[name=checkall]').on('ifChecked', function(event){
      $('input[name=bayar]').iCheck('check');
    });
    $('input[name=checkall]').on('ifUnchecked', function(event){
      $('input[name=bayar]').iCheck('uncheck');
    });

    // TAMPILKAN INPUT TANGGAL PEMBAYARAN
    $('input[name=bayar]').on('ifChecked',function(event){
        var colBayar = $(this).parent().parent().next();
        var tanggal_kirim = $(this).parent().parent().prev().prev().prev().prev();
        colBayar.empty();
        colBayar.append($('<input>').attr('type','text').attr('name','tanggal_bayar').attr('required','required').addClass('form-control input-tanggal'));


        $('.input-tanggal').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            startDate: tanggal_kirim.text()
        });
    });

    $('input[name=bayar]').on('ifUnchecked',function(event){
        var colBayar = $(this).parent().parent().next();
        colBayar.empty();
    });

    // SAVE PEBAYARAN
    $('#btn-save').click(function(){
        // alert($('input[name=bayar]').length + '  ' + $('input[name=bayar]:checked').length);
        // if($('input[name=bayar]').length == $('input[name=bayar]:checked').length){
        //     alert('sama');
        // }else{
        //     alert('tidak asama');
        // };
        var data_tagihan = [];
        $('input[name=bayar]:checked').each(function(){
            var aRow = $(this).parent().parent().parent();
            var tanggal = $(this).parent().parent().next().children('input').val();

            var newtagihan = {
                                "tagihan_id":aRow.data('id'),
                                "tanggal":tanggal,
                            }; 

            data_tagihan.push(newtagihan);
        });

        alert(JSON.stringify(data_tagihan));

    });





})(jQuery);
</script>
@append