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
        background-color:#EEF0F0;
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
        <a href="finance/hutang" >Hutang</a> <i class="fa fa-angle-double-right" ></i> 
        <a href="finance/hutang/edit/{{$data->hutang->id}}" >{{$data->hutang->name}}</a> <i class="fa fa-angle-double-right" ></i> 
        {{$data->ref}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->ref}}</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-blue" >DONE</a>

            <!-- <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label> -->
            <!-- <a class="btn btn-arrow-right pull-right disabled bg-blue" >VALIDATED</a> -->

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >DRAFT</a>
        </div>
        <div class="box-body">
            <input type="hidden" name="pembelian_id" value="{{$data->id}}">
            <div class="row" >
                <div class="col-xs-6" >
                    <div class="form-group">
                        <label >Supplier</label>
                        <input type="text" name="supplier" disabled value="{{$data->nama_supplier}}" class="form-control" >
                    </div>  
                    <div class="form-group">
                        <label >Nomor Nota</label>
                        <input type="text" name="supplier_ref" class="form-control" value="{{$data->supplier_ref}}" disabled>                        
                    </div>  
                </div>
                <div class="col-xs-6" >
                    <div class="form-group" >
                        <label>Tanggal</label>
                        <input type="text" name="tanggal" class="input-tanggal form-control" value="{{$data->tanggal_format}}" disabled>

                    </div>
                </div>
            </div>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>DETIL BARANG</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th>BARANG</th>
                        <th colspan="2" >JUMLAH</th>
                        <th class="col-lg-2" >UNIT PRICE</th>
                        {{-- <th class="col-lg-2" >S.U.P</th> --}}
                        <th class="col-lg-2" >SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    @foreach($data->detail as $dt)
                        <tr class="row-product"  >
                            <td class="text-right" >
                                {{$rownum++}}
                            </td>
                            <td>
                                {{$dt->nama_product}}
                            </td>
                            <td class="text-right" >
                                {{$dt->qty}}
                            </td>
                            <td class="label-satuan" >
                                {{$dt->satuan}}
                            </td>
                            <td class="text-right uang" >
                                {{$dt->unit_price}}
                            </td>
                            <td class="text-right uang" >
                                {{$dt->unit_price * $dt->qty}}
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>

            <div class="row" >
                <div class="col-lg-8" >
                </div>
                <div class="col-lg-4" >
                    <table class="table table-condensed" >
                        <tbody>
                            <tr>
                                <td class="text-right">
                                    <label>Subtotal :</label>
                                </td>
                                <td class="label-total-subtotal text-right uang" >
                                    {{$data->subtotal}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Disc :</label>
                                </td>
                                <td class="text-right uang" >
                                   {{$data->disc}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Total :
                                </td>
                                <td class="label-total text-right uang" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                    {{$data->total}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.box-body -->
        <div class="box-footer" >
            {{-- <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button> --}}
            <a class="btn btn-danger" id="btn-cancel-save" href="finance/hutang/edit/{{$data->hutang->id}}" ><i class="fa fa-close" ></i> Close</a>
            <!-- <a id="btn-cancel-pembelian" class="btn btn-warning pull-right" href="pembelian/cancel/{{$data->id}}" ><i class="fa fa-reply" ></i> Cancel Pembelian</a> -->
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

<!-- /.modal -->
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



<script type="text/javascript">
(function ($) {
    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });

    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

    // CANCEL PEMBELIAN
    $('#btn-cancel-pembelian').click(function(){
        if(confirm('Anda akan membatalan transaksi pembelian ini?')){

        }else{
            return false;
        }
    });

})(jQuery);
</script>
@append
