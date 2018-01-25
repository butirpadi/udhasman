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
        <a href="finance/payment" >Payment</a> <i class="fa fa-angle-double-right" ></i> New
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <form role="form" method="POST" action="finance/payment/insert" >
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn  btn-arrow-right pull-right disabled bg-gray" >RECONCILED</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled bg-gray" >POSTED</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled bg-blue" >DRAFT</a>
            </div>
            <div class="box-body">
                    <div class="box-body">
                        <div class="row" >
                            <div class="col-xs-6" >
                                <div class="form-group">
                                    <label >Tanggal</label>
                                    <input type="text" name="tanggal" class="form-control input-tanggal" value="{{date('d-m-Y')}}" required>
                                </div>
                                <div class="form-group " id="input-customer" >
                                    <label >Partner</label>
                                   {!! Form::select('partner',$partner,null,['class'=>'form-control','required']) !!}                                    
                                </div>
                                <div class="form-group" >
                                    <label>Amount Due</label>
                                    <input type="text" name="amount_due" class="form-control" value="0" readonly>
                                </div>
                                <div class="form-group hide" id="input-lain" >
                                    <label >Penyetor</label>
                                   <input type="text" name="penyetor" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-xs-6 " >
                                <div class="form-group"  >
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
                <a class="btn btn-danger" href="finance/payment" ><i class="fa fa-close" ></i> Close</a>
                
                
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

    $('input[name=amount_due]').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });    

    // select 2
    // $("select[name=karyawan]").select2();
    // $("select[name=karyawan]").select2('val',[]);
    // $("select[name=customer]").select2();
    // $("select[name=customer]").select2('val',[]);
    $("select[name=partner]").select2();
    $("select[name=partner]").select2('val',[]);

    // $('select[name=type]').change(function(){
    //     val = $(this).val();
    //     if(val == 'so'){
    //         $('#input-lain').hide();
    //         $('#input-karyawan').hide();
    //         $('#input-customer').removeClass('hide');
    //         $('#input-customer').hide();
    //         $('#input-customer').fadeIn();

    //         $('select[name=customer]').select2('val',[]);
    //         $('select[name=customer]').attr('required','required');
    //         $('select[name=karyawan]').removeAttr('required');
    //         $('input[name=penyetor]').removeAttr('required');
    //     }else if(val == 'pl'){
    //         $('#input-customer').hide();
    //         $('#input-karyawan').hide();
    //         $('#input-lain').removeClass('hide');
    //         $('#input-lain').hide();
    //         $('#input-lain').fadeIn();

    //         $('input[name=penyetor]').attr('required','required');
    //         $('select[name=karyawan]').removeAttr('required');
    //         $('select[name=customer]').removeAttr('required');
    //     }else if(val == 'pk'){
    //         $('#input-customer').hide();
    //         $('#input-lain').hide();
    //         $('#input-karyawan').removeClass('hide');
    //         $('#input-karyawan').hide();
    //         $('#input-karyawan').fadeIn();
            
    //         $('select[name=karyawan]').select2('destroy');
    //         $('select[name=karyawan]').select2();
    //         $('select[name=karyawan]').select2('val',[]);

    //         $('select[name=karyawan]').attr('required','required');
    //         $('input[name=penyetor]').removeAttr('required');
    //         $('select[name=customer]').removeAttr('required');
            
    //     }
    // });

    // $('select[name=customer]').change(function(){
    //     // get amount due
    //     var customer_id = $(this).val();
    //     $.get('finance/payment/get-amount-due/'+customer_id,function(res){
    //         $('input[name=amount_due]').autoNumeric('set',res);
    //     });
    // });

    $('select[name=partner]').change(function(){
        // get amount due
        var partner_id = $(this).val();
        $.get('finance/payment/get-amount-due/'+partner_id,function(res){
            $('input[name=amount_due]').autoNumeric('set',res);
        });
    });

})(jQuery);
</script>
@append
