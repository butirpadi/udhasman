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
        <a href="pengiriman" >Pengiriman</a> <i class="fa fa-angle-double-right" ></i> {{$data_pengiriman->kode_pengiriman}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data_pengiriman->kode_pengiriman}}</h3></label>
            
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >DONE</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >VALIDATED</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled {{$data_pengiriman->status == 'OPEN' ? 'bg-blue' : 'bg-gray'}}" >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled {{$data_pengiriman->status == 'DRAFT' ? 'bg-blue' : 'bg-gray'}}" >DRAFT</a>
        </div>
        <form action="pengiriman/update" method="POST">
            <div class="box-body">
                <input type="hidden" name="pengiriman_id" value="{{$data_pengiriman->id}}">
                
                <table class="table table-condensed no-border table-master-header" >
                    <tbody>
                        <tr>
                            <td class="col-lg-2">
                                <label>Customer</label>
                            </td>
                            <td class="col-lg-4" >
                                {{$data_pengiriman->nama_customer}}
                            </td>
                            <td class="col-lg-2" >
                                <label>Tanggal Order</label>
                            </td>
                            <td class="col-lg-4" id="label-tanggal-order" >
                                {{$data_pengiriman->penjualan->tanggal_format}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Pekerjaan</label>
                            </td>
                            <td>
                            {{$data_pengiriman->nama_pekerjaan}}
                            </td>
                            <td class="col-lg-2" >
                                <label>Tanggal Pengiriman</label>
                            </td>
                            <td class="col-lg-4" >
                                <input required type="text" name="tanggal" class="form-control input-tanggal">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Alamat</label>
                            </td>
                            <td style="vertical-align: top;" >
                                {{
                                    $data_pengiriman->pekerjaan->alamat 
                                    . ($data_pengiriman->pekerjaan->desa != '' ? ', ' . $data_pengiriman->pekerjaan->desa :'') 
                                    . ($data_pengiriman->pekerjaan->kecamatan != '' ? ', ' . $data_pengiriman->pekerjaan->kecamatan :'') 
                                    . ($data_pengiriman->pekerjaan->kabupaten != '' ? ', ' . $data_pengiriman->pekerjaan->kabupaten :'') 
                                    . ($data_pengiriman->pekerjaan->provinsi != '' ? ', ' . $data_pengiriman->pekerjaan->provinsi :'') 
                                    }}
                            </td>
                            <td>
                                <label>Lokasi Galian</label>
                            </td>
                            <td>
                                {!! Form::select('lokasi_galian',$select_lokasi_galian,null,['class'=>'form-control','required']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <label>Driver</label>
                            </td>
                            <td>
                                {!! Form::select('driver',$select_driver,null,['class'=>'form-control','required']) !!}
                            </td>
                        </tr>
                    </tbody>
                </table>

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
                        <tr  id="row-add-product"  >
                            <td class="text-right" >1</td>
                            <td>
                                {{$data_pengiriman->material}}
                            </td>
                            <td class="text-center" >
                                1
                            </td>
                        </tr>                   
                        
                    </tbody>
                </table>

            </div><!-- /.box-body -->
            <div class="box-footer" >
                <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
                <a class="btn btn-danger" id="btn-cancel-save" href="pengiriman" ><i class="fa fa-close" ></i> Close</a>
                {{-- <a class="btn btn-success pull-right"  id="btn-validate" href="pengiriman/validate/{{$data_pengiriman->id}}" ><i class="fa fa-check" ></i> Validate</a> --}}
            </div>        
        </form>

        

    </div><!-- /.box -->

<!-- /.modal -->
</div>

</section><!-- /.content -->


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
        autoclose: true,
        startDate: $('#label-tanggal-order').text()
    });
    // END OF SET DATEPICKER

    //Initialize Select2 Elements
    $("select[name=lokasi_galian]").val([]);
    $("select[name=lokasi_galian]").select2();
    $("select[name=driver]").val([]);
    $("select[name=driver]").select2();


})(jQuery);
</script>
@append