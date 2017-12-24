@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/dist/css/select2.min.css">
<style>
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #FFE291; }
    .autocomplete-suggestions strong { font-weight: normal; color: red; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

    .table-row-mid > tbody > tr > td {
        vertical-align:middle;
    }

    input.input-clear {
        display: block; 
        padding: 0; 
        margin: 0; 
        border: 0; 
        width: 100%;
        background-color:#EEF0F0;
        float:right;
        padding-right: 5px;
        padding-left: 5px;
    }

    span.select2-selection.select2-selection--single.select-clear {
        outline: none;
        border: none;
        /*padding: 0; 
        margin: 0; 
        border: 0; */
        /*width: 100%;*/
        background-color:#EEF0F0;
        /*float:right;*/
        padding-right: 5px;
        padding-left: 5px;
        height: 30px;
    }
    span.select2-selection.select2-selection--single.select-clear .select2-selection__arrow{
        visibility: hidden;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="penjualan" >Pesanan Penjualan</a> <i class="fa fa-angle-double-right" ></i> {{$data_master->order_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data_master->order_number}}</h3></label>
            
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-red" >CANCELED</a>

            
        <div class="box-body">
            <input type="hidden" name="penjualan_id" value="{{$data_master->id}}">
            <div class="row" >
                <div class="col-xs-9" >
                    <table class="table table-condensed no-border table-master-header" >
                        <tbody>
                            <tr>
                                <td class="col-lg-2">
                                    <label>Customer</label>
                                </td>
                                <td class="col-lg-4" >
                                    {{$data_master->nama_customer}}
                                </td>
                                <td class="col-lg-2" >
                                    <label>Tanggal Order</label>
                                </td>
                                <td class="col-lg-4" >
                                    {{$data_master->tanggal_format}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Pekerjaan</label>
                                </td>
                                <td>
                                    {{$data_master->nama_pekerjaan}}

                                </td>
                                <td>
                                    <label>Alamat</label>
                                </td>
                                <td>
                                     {{
                                        $data_master->pekerjaan->alamat 
                                        . ($data_master->pekerjaan->desa != '' ? ', ' . $data_master->pekerjaan->desa :'') 
                                        . ($data_master->pekerjaan->kecamatan != '' ? ', ' . $data_master->pekerjaan->kecamatan :'') 
                                        . ($data_master->pekerjaan->kabupaten != '' ? ', ' . $data_master->pekerjaan->kabupaten :'') 
                                        . ($data_master->pekerjaan->provinsi != '' ? ', ' . $data_master->pekerjaan->provinsi :'') 
                                        }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-3" >
                </div>
            </div>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th  >MATERIAL</th>
                        <th class="col-lg-1" >QUANTITY</th>
                    </tr>
                </thead>
                <tbody>                    
                    <?php $rownum=1; ?>
                    @foreach($data_master->detail as $dt)
                        <tr class="row-product"  >
                            <td class="text-right" >{{$rownum++}}</td>
                            <td>
                                {{$dt->nama_material}}
                            </td>
                            <td class="text-right" >
                                {{$dt->qty}}
                            </td>
                        </tr>
                    @endforeach                 
                    
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" id="btn-cancel-save" href="penjualan" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div><!-- /.box -->

   
<!-- /.modal -->
</div>

</section><!-- /.content -->

<div class="hide" >
    {{-- data element untuk di cloning --}}
    <div id="select-material-for-clone" >
        {!! Form::select('material',$selectMaterial,null,['class'=>'form-control input-sm','style'=>'width:100%;']) !!}
    </div>
</div>

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/select2/dist/js/select2.full.min.js"></script>

<script type="text/javascript">
(function ($) {
    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    $('#btn-cancel-penjualan').click(function(){
        if(confirm('Anda akan membatalkan transaksi ini?')){
            var penjualan_id = $('input[name=penjualan_id]').val();
            location.href = 'penjualan/cancel-penjualan/'+ penjualan_id;
        }

        return false;
    });

  
})(jQuery);
</script>
@append