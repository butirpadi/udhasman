@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/select2.min.css">

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="pengiriman" >Pengiriman</a> <i class="fa fa-angle-double-right" ></i> 
        Batch Edit
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <h3 style="margin:0;padding:0;" >{{$data_penjualan->order_number}}</h3>
        </div>
        <div class="box-body">
            <input type="hidden" name="penjualan_id" value="{{$data_penjualan->id}}"> 
            <input type="hidden" name="has_saved_count" value="{{$has_saved_count}}"> 
            <input type="hidden" name="data_pengiriman" value="{{json_encode($data_pengiriman)}}"> 

            <table class="table table-condensed no-border table-master-header"  >
                <tbody>
                    <tr>
                        <td class="col-xs-2" >
                            <label>Customer</label> 
                        </td>
                        <td class="col-xs-4" >
                            {{$data_penjualan->nama_customer}}
                        </td>
                        <td class="col-xs-2" >
                            <label>Tanggal Order</label>
                        </td>
                        <td class="col-xs-4" id="label-tanggal-order" >
                            {{$data_penjualan->tanggal_format}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Pekerjaan</label>
                        </td>
                        <td>
                            {{$data_penjualan->nama_pekerjaan}}
                        </td>
                        <td>
                            <label>Alamat</label>
                        </td>
                        <td>
                            {{
                                $data_penjualan->pekerjaan->alamat 
                                . ($data_penjualan->pekerjaan->desa != '' ? ', ' . $data_penjualan->pekerjaan->desa :'') 
                                . ($data_penjualan->pekerjaan->kecamatan != '' ? ', ' . $data_penjualan->pekerjaan->kecamatan :'') 
                                . ($data_penjualan->pekerjaan->kabupaten != '' ? ', ' . $data_penjualan->pekerjaan->kabupaten :'') 
                                . ($data_penjualan->pekerjaan->provinsi != '' ? ', ' . $data_penjualan->pekerjaan->provinsi :'') 
                                }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <br/>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table class="table table-bordered table-condensed " id="table-data-pengiriman" >
                <thead>
                    <tr>
                        <th style="width: 75px;" class="text-center" >NO</th>
                        <th class="text-center col-xs-2" >Nomor<br/>Pengiriman</th>
                        <th class="text-center col-xs-2">Material</th>
                        <th class="text-center">Tanggal<br/>Pengiriman</th>
                        <th class="text-center col-xs-3">Lokasi<br/>Galian</th>
                        <th class="text-center col-xs-3">Driver</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    @foreach($data_pengiriman as $dt)
                    <tr  data-id="{{$dt->id}}" class="data-row-pengiriman">
                        <td class="text-center" >
                            {{$rownum++}}
                        </td>
                        <td class="text-center" >
                            {{$dt->kode_pengiriman}}
                        </td>
                        <td class="text-center" >
                            {{$dt->material}}
                        </td>
                        <td class="text-center col-xs-2 " >
                            <input type="text" class="form-control input-tanggal" name="tanggal_pengiriman" >
                        </td>
                        <td  >
                            {!! Form::select('lokasi_galian',$select_lokasi_galian,null,['class'=>'form-control select2','style'=>'width:100%']) !!}
                        </td>
                        <td class="text-left" >
                            {!! Form::select('driver',$select_driver,null,['class'=>'form-control select2','style'=>'width:100%']) !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <button class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <button class="btn btn-success" id="btn-save-validate" ><i class="fa fa-check" ></i> Save & Validate</button>
            <a class="btn btn-danger" href="pengiriman" ><i class="fa fa-close" ></i> Close</a>

            <div class="btn-group pull-right">
                <button type="button" class="btn bg-purple dropdown-toggle " data-toggle="dropdown">
                    <i class="fa fa-copy" ></i> Copy Data
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a id="btn-copy-tanggal" href="#" ><i class="fa fa-angle-double-right" ></i> Copy Tanggal</a></li>
                    <li><a id="btn-copy-lokasi" href="#" ><i class="fa fa-angle-double-right" ></i> Copy Lokasi Galian</a></li>
                </ul>
            </div>
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

<script type="text/javascript">
(function ($) {

    // format select2
    // if($('input[name=has_saved_count]').val() == 0 || $('input[name=has_saved_count]').val() == ""){
    //     $(".select2").val([]);
    // }else{
    //     $(".select2").val([]);
    //     loadDataPengiriman();
    // }
    $(".select2").val([]);
    loadDataPengiriman();


    function loadDataPengiriman(){
        // alert('load data pengiriman');
        var dt_pengiriman  = $('input[name=data_pengiriman]').val();
        var dt_pengiriman_obj  = JSON.parse(dt_pengiriman);

        $.each(dt_pengiriman_obj,function(){
            // cek per row table
            var dt_obj = $(this)[0];
            $('.data-row-pengiriman').each(function(){
                var aRow = $(this);

                // var tanggal = aRow.find('input[name=tanggal_pengiriman]').val();
                // var lokasi_galian = aRow.find('select[name=lokasi_galian]').val();
                // var driver = aRow.find('select[name=driver]').val();

                if(aRow.data('id') == dt_obj.id){
                    // data ditemukan
                    aRow.find('input[name=tanggal_pengiriman]').val(dt_obj.tanggal_format);
                    aRow.find('select[name=lokasi_galian]').val(dt_obj.lokasi_galian_id);
                    aRow.find('select[name=driver]').val(dt_obj.karyawan_id);

                    // cek jika status nya validated maka readonly biar tidak dapat di rubah
                    if (dt_obj.status == 'VALIDATED' ){
                        // SET READONLY
                        aRow.find('input[name=tanggal_pengiriman]').attr('disabled','disabled');
                        aRow.find('select[name=lokasi_galian]').attr('disabled','disabled');
                        aRow.find('select[name=driver]').attr('disabled','disabled');                        
                    }else{
                        aRow.find('select[name=lokasi_galian]').select2();
                        aRow.find('select[name=driver]').select2();
                    }
                }

            });
        });
    }
    

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: $('#label-tanggal-order').text()
    });


    // BATCH EDIT
    $('#btn-batch-edit').click(function(){
        $('select[name=nomor_penjualan]').val([]);
        $('#b-batch-edit').modal('show');

        return false;
    });

    // SAVE DATA PENGIRIMAN
    var data_master = {"penjualan_id":$('input[name=penjualan_id]').val()};
    var data_detail = {"pengiriman":[]};

    function setDataPengiriman(){
        $('.data-row-pengiriman').each(function(){
            var aRow = $(this);

            var tanggal = aRow.find('input[name=tanggal_pengiriman]').val();
            var lokasi_galian = aRow.find('select[name=lokasi_galian]').val();
            var driver = aRow.find('select[name=driver]').val();

            if(tanggal != "" && lokasi_galian != "" && driver != ""){
                var detail = {
                                "pengiriman_id": aRow.data('id'),
                                "tanggal": tanggal,
                                "lokasi_galian": lokasi_galian,
                                "driver":  driver
                            };
                data_detail.pengiriman.push(detail);                
            }

        });
    }

    $('#btn-save').click(function(){
        setDataPengiriman();

        if(data_detail.pengiriman.length > 0){
            // submit form
            var newform = $('<form>').attr('method','POST').attr('action','pengiriman/batch-edit-save');
            newform.append($('<input>').attr('type','hidden').attr('name','data_master').val(JSON.stringify(data_master)));
            newform.append($('<input>').attr('type','hidden').attr('name','data_detail').val(JSON.stringify(data_detail)));
            $('body').append(newform);
            newform.submit();
            
        }else{
            alert('Lengkapi data yang kosong.');
        }

        // return false;

    });

    $('#btn-save-validate').click(function(){
        setDataPengiriman();

        if(data_detail.pengiriman.length > 0){
            // submit form
            var newform = $('<form>').attr('method','POST').attr('action','pengiriman/batch-edit-save-validate');
            newform.append($('<input>').attr('type','hidden').attr('name','data_master').val(JSON.stringify(data_master)));
            newform.append($('<input>').attr('type','hidden').attr('name','data_detail').val(JSON.stringify(data_detail)));
            $('body').append(newform);
            newform.submit();
        }else{
            alert('Lengkapi data yang kosong.');
        }

        return false;
    });


    // copy tanggal dari row pertama
    $('#btn-copy-tanggal').click(function(){
        var tanggal = $('#table-data-pengiriman tbody tr:first').find('input[name=tanggal_pengiriman]').val();
        $('input[name=tanggal_pengiriman]').val(tanggal);
        return false;
    });

    // copy lokasi dari row pertama
    $('#btn-copy-lokasi').click(function(){
        // alert('click');
        var lokasi = $('#table-data-pengiriman tbody tr:first').find('select[name=lokasi_galian]').val();
        // alert(lokasi);
        $('select[name=lokasi_galian]').val(lokasi).trigger('change');
        return false;
    });


})(jQuery);
</script>
@append