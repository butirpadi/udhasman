@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/dist/css/select2.min.css">
<link href="plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>

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

     /*clear border select2*/
     /*span.select2-selection.select2-selection--single {
        outline: none;
    }*/

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
        <a href="penjualan" >Pesanan Penjualan</a> <i class="fa fa-angle-double-right" ></i> Edit
    </h1>
</section>

{{-- DATA MASTER ID --}}
<input type="hidden" name="penjualan_id" value="{{$data_master->id}}">

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data_master->order_number}}</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >DONE</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-blue" >VALIDATED</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-gray" >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-gray" >DRAFT</a>
        </div>
        <div class="box-body">
            <table class="table table-condensed no-border table-master-header" >
                <tbody>
                    <tr>
                        <td class="col-lg-2" >
                            <label>Tanggal</label>
                        </td>
                        <td class="col-lg-4" >
                            {{$data_master->tanggal_format}}
                        </td>
                        <td class="col-lg-2">
                            <label>Direct Sales</label>
                        </td>
                        <td class="col-lg-4" >
                            <label class="label label-success" >Direct Sales</label>
                        </td>
                        
                    </tr>
                    <tr  >
                        <td >
                            <label>Customer</label>
                        </td>
                        <td >
                            {{$data_master->nama_customer}}
                        </td>

                        <td class="direct_sales_input" >
                            <label>No. Kendaraan</label>
                        </td>
                        <td class="direct_sales_input ">
                            {{$data_master->nopol_direct_sales}}
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
                        {{-- <th class="col-lg-1" >SATUAN</th> --}}
                        <th class="col-sm-1" >QUANTITY</th>
                        <th class="col-sm-2 direct_sales_input" >UNIT PRICE</th>
                        {{-- <th class="col-lg-2" >S.U.P</th> --}}
                        <th class="col-lg-2 direct_sales_input" >TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hide" id="row-add-product"  >
                        <td class="text-right" ></td>
                        <td>
                            <input autocomplete="off" type="text"  data-materialid="" data-kode="" class=" form-control input-product input-sm input-clear">
                        </td>
                        <td>
                            <input type="number" autocomplete="off" min="1" class="form-control text-right input-quantity input-sm input-clear">
                        </td>
                        <td class="direct_sales_input" >
                            <input type="text" class="uang form-control input-clear text-right input-harga-satuan-on-row uang" name="harga_satuan" >
                        </td>
                        <td class="direct_sales_input text-right uang" ></td>
                    </tr>
                    <?php $rownum=1; ?>
                    @foreach($data_master->detail as $dt)
                        <tr class="row-product"   >
                            <td class="text-right" >
                                {{$rownum++}}
                            </td>
                            <td>
                                {{$dt->nama_material}}
                            </td>
                            <td>
                                {{$dt->qty}}
                            </td>
                            <td class="direct_sales_input uang text-right" >
                                {{$dt->unit_price}}
                            </td>
                            <td class="direct_sales_input text-right uang" >
                                {{$dt->total}}
                            </td>
                        </tr>
                    @endforeach

                    
                    <tr class="direct_sales_input" >
                        <td colspan="4" class="text-right">
                            <label>TOTAL</label>
                        </td>
                        <td class=" text-right" >
                            <label class="label-total uang" >
                                {{$data_master->total}}
                            </label>
                        </td>
                    </tr>
                    
                    
                </tbody>
            </table>

            


        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="penjualan" id="btn-cancel-save" ><i class="fa fa-close" ></i> Close</a>

            <a class="pull-right btn btn-danger btn-xs" id="btn-cancel-order" ><i class="fa fa-reply" ></i> Cancel Order</a>
        </div>
    </div><!-- /.box -->

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
<!-- Select2 -->
<script src="plugins/select2/dist/js/select2.full.min.js"></script>
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>



<script type="text/javascript">
(function ($) {
    // UANG FORMAT
    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.99'
        });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

    // *************************************************************
    //   CANCEL ORDER, RESTRICTED FUNCTION
    // *************************************************************
    $('#btn-cancel-order').click(function(){
        if(confirm('Anda akan membatalkan transaksi ini? data yang telah dibatalkan tidak dapat dikembalikan.')){
            if(confirm('Anda yakin?')){
                var penjualan_id = $('input[name=penjualan_id]').val();
                location.href = 'penjualan/cancel-penjualan/'+ penjualan_id;
            }
        }
    });
    // *************************************************************
    //   END OF CANCEL ORDER, RESTRICTED FUNCTION
    // *************************************************************

  
})(jQuery);
</script>
@append