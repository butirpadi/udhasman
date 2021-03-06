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
        <a href="finance/piutang" >Piutang</a> <i class="fa fa-angle-double-right" ></i> 
        <a href="finance/piutang/edit/{{$data->id}}" >{{$data->name}}</a> <i class="fa fa-angle-double-right" ></i> 
        Register Payment
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <form name="form_payment" role="form" method="POST" action="finance/piutang/add-pay" >
            <input type="hidden" name="original_id" value="{{$data->id}}">
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >Register Payment</h3></label>
            </div>
            <div class="box-body">
                    <input type="hidden" name="pembelian_id" value="{{$data->id}}">
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Jumlah Tagihan</label>
                                <input type="text" name="jumlah_tagihan" class="form-control text-right" value="{{$data->amount_due}}" readonly/>
                                <input type="hidden" name="piutang_id" value="{{$data->id}}" />
                            </div>  
                            <div class="form-group">
                                <label >Jumlah Bayar</label>
                                <input type="text" name="jumlah_bayar" class="form-control text-right" value="{{$data->amount_due}}" required >    
                            </div>  
                        </div>
                        <div class="col-xs-6" >
                            <div class="form-group" >
                                <label>Tanggal</label>
                                <input type="text" name="tanggal" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required >
                            </div>
                            <div class="form-group" >
                                <label>Sisa Tagihan</label>
                                <input type="text" name="sisa" class="form-control text-right" readonly value="0" />
                            </div>
                        </div>
                    </div>
            </div><!-- /.box-body -->
            <div class="box-footer" >
                <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button> 
                <a class="btn btn-danger" id="btn-cancel-save" href="finance/piutang/edit/{{$data->id}}" ><i class="fa fa-close" ></i> Close</a>
                
            </div>
        </form>         
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
    $('input[name=jumlah_tagihan],input[name=jumlah_bayar],input[name=sisa]').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });

    // $('.uang').each(function(){
    //     $(this).autoNumeric('set',$(this).autoNumeric('get'));
    // });

    $('input[name=jumlah_bayar]').keyup(function(){
        $('input[name=sisa]').autoNumeric('set',$('input[name=jumlah_tagihan]').autoNumeric('get')-$('input[name=jumlah_bayar]').autoNumeric('get'));
    });

    $('form[name=form_payment]').submit(function(){
        if(Number($('input[name=jumlah_bayar]').autoNumeric('get')) > Number($('input[name=jumlah_tagihan]').autoNumeric('get'))){
            alert('Jumlah bayar melebihi jumlah tagihan!');
            return false;
        }
    });


})(jQuery);
</script>
@append
