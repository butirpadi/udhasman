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
        <a href="finance/piutang" >Piutang</a> <i class="fa fa-angle-double-right" ></i> Receive Payment
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <form role="form" method="POST" action="finance/payment/update" >
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn  btn-arrow-right pull-right disabled bg-gray" >PAID</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled bg-gray" >OPEN</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled bg-gray" >DRAFT</a>
            </div>
            <div class="box-body">
                    <div class="box-body">
                        <div class="row" >
                            <div class="col-xs-6" >
                                <div class="form-group">
                                    <label >Payment Type</label>
                                    <select name="type" class="form-control">
                                        <option value="so" >Pembayaran Pengiriman Material</option>
                                        <option value="pk" >Pembayaran Piutang Karyawan</option>
                                        <option value="pl" >Pembayaran Piutang Lain</option>
                                    </select> 
                                </div>  
                                <div class="form-group">
                                    
                                </div>
                                <div class="form-group"  >
                                    <label >Karyawan</label>
                                   {!! Form::select('karyawan',$karyawan,null,['class'=>'form-control init-data']) !!}                                    
                                </div>
                                <div class="form-group" >
                                    <label >Customer</label>
                                   {!! Form::select('customer',$customer,null,['class'=>'form-control init-data']) !!}                                    

                                </div>
                                <div class="form-group" >
                                    <label >Penyetor</label>
                                   <input type="text" name="penyetor" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-xs-6 " >
                                <div class="form-group" id="input-lain" >
                                    <label >Memo</label>
                                    <input type="memo" class="form-control" max="250" />
                                </div>
                                <div class="form-group">
                                    <label >Jumlah</label>
                                    <input type="text" name="jumlah" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

            </div><!-- /.box-body -->
            <div class="box-footer" >
                <button type="submit" class="btn btn-primary " id="btn-save"  ><i class="fa fa-save" ></i> Save</button>
                
                
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

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });

    $('input[name=jumlah]').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });    

    // select 2
    $("select[name=karyawan]").select2();
    $("select[name=karyawan]").select2('val',[]);
    $("select[name=customer]").select2();
    $("select[name=customer]").select2('val',[]);

})(jQuery);
</script>
@append
