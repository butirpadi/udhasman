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
        <a href="finance/payment" >Payment</a> <i class="fa fa-angle-double-right" ></i> {{$data->name}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <form role="form" method="POST" action="finance/payment/update" >
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->name}}</h3></label>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn  btn-arrow-right pull-right disabled {{$data->state == 'rec' ? 'bg-blue':'bg-gray'}}" >RECONCILED</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled {{$data->state == 'post' ? 'bg-blue':'bg-gray'}}" >POSTED</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled {{$data->state == 'draft' ? 'bg-blue':'bg-gray'}}" >DRAFT</a>
            </div>
            <div class="box-body">
                    <div class="box-body">
                        <div class="row" >
                            <div class="col-xs-6" >
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" name="tanggal" class="form-control input-tanggal" value="{{$data->tanggal_format}}" {{$data->state == 'draft' ? '' :'readonly'}} >
                                    <input type="hidden" name="original_id" value="{{$data->id}}"  >
                                </div>  
                                <div class="form-group "  >
                                    <label >Partner</label>
                                    @if($data->state == 'draft')
                                        {!! Form::select('partner',$partner,$data->partner_id,['class'=>'form-control','required']) !!}                                    
                                    @else
                                        <input type="text" name="show_partner" value="{{$data->partner}}" class="form-control" readonly>
                                        <div class="hide" >
                                            {!! Form::select('partner',$partner,$data->partner_id,['class'=>'form-control','required']) !!}
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group {{$data->state != 'draft' ? 'hide' :''}} " >
                                    <label>Amount Due</label>
                                    <input type="text" name="amount_due" class="form-control" value="{{$sum_amount_due}}" readonly>
                                </div>
                                
                            </div>
                            <div class="col-xs-6 " >
                                <div class="form-group"  >
                                    <label >Memo</label>
                                    <input type="memo" class="form-control" max="250" {{$data->state == 'draft' ? '' : 'readonly'}} />
                                </div>
                                <div class="form-group">
                                    <label >Jumlah</label>
                                    <input type="text" name="jumlah" class="form-control" value="{{$data->jumlah}}" {{$data->state == 'draft' ? '' : 'readonly'}} >
                                </div>
                            </div>
                            @if($data->state != 'draft')
                            <div class="col-xs-6  "  >
                                <h4 class="page-header" style="font-size:14px;color:#3C8DBC;"><strong>DAFTAR TAGIHAN</strong></h4>

                                <div style="max-height: 200px;overflow-y: scroll;">
                                    <table class="table table-striped table-condensed table-responsive" id="table-tagihan" >
                                        <thead>
                                            <tr>
                                                <th style="width: 5px!important;" class="text-center" >#</th>
                                                <th class="text-center" >Ref#</th>
                                                <th class="text-center" >Tanggal</th>
                                                <th class="text-center" >Source</th>
                                                <th class="text-center" >Jumlah</th>
                                                <th class="text-center" >Amount Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($piutang as $dt)
                                                <tr id="row-{{$dt->id}}" >
                                                    <td >
                                                        <!-- <a href="#" class="btn-add-tagihan" data-piutang="{{json_encode($dt)}}" ><i class="fa fa-plus-square" ></i></a> -->
                                                    </td>
                                                    <td class="text-center" >
                                                        {{$dt->name}}
                                                    </td>
                                                    <td class="text-center" >
                                                        {{$dt->tanggal_format}}
                                                    </td>
                                                    <td class="text-center" >
                                                        {{$dt->source}}
                                                    </td>
                                                    <td class="text-right" >
                                                        {{number_format($dt->jumlah,2,'.',',')}}
                                                    </td>
                                                    <td class="text-right" >
                                                        {{number_format($dt->amount_due,2,'.',',')}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <table class="table" >
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" >
                                                <label>Amount Due</label>
                                            </td>
                                            <td class="text-right col-xs-4" >
                                                <p class="uang" id="tx-amount-due" >{{$sum_amount_due}}</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div class="col-xs-6" >
                                <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>RECONCILED</strong></h4>

                                <div style="max-height: 200px;overflow-y: scroll;">
                                    <table class="table table-striped table-condensed table-responsive" id="table-tobe-reconcile" >
                                        <thead>
                                            <tr>
                                                <th style="width: 5px!important;" class="text-center" >#</th>
                                                <th class="text-center" >Piutang#</th>
                                                <th class="text-center" >DO#</th>
                                                <th class="text-center" >Amount Due</th>
                                                <th class="text-center" >Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reconciled as $dt)
                                                <tr>
                                                    <td></td>
                                                    <td class="text-center" >{{$dt->piutang_ref}}</td>
                                                    <td class="text-center" >{{$dt->pengiriman_ref}}</td>
                                                    <td class="uang text-right" >{{$dt->last_amount_due}}</td>
                                                    <td class="uang text-right" >{{$dt->paid}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <table class="table" >
                                    <tbody>
                                        <tr>
                                            <td class="text-right" >
                                                <label>Paid</label>
                                            </td>
                                            <td class="text-right col-xs-4" >
                                                <p class="uang" id="tx-jumlah" >{{$data->jumlah - $data->residual}}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" >
                                                <label>Residual</label>
                                            </td>
                                            <td class="text-right" >
                                                <p class="uang" id="tx-residual" >{{$data->residual}}</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            @endif
                        </div>
                    </div>

            </div><!-- /.box-body -->
            <div class="box-footer" >
                @if($data->state == 'draft')
                    <button type="submit" class="btn btn-primary " id="btn-save"  ><i class="fa fa-save" ></i> Save</button>
                @endif
                
                @if($data->state != 'draft')
                <a class="btn btn-success" target="_blank" href="finance/payment/print/{{$data->id}}" ><i class="fa fa-print" ></i> Print</a>                
                @endif

                <a class="btn btn-danger" href="finance/payment" ><i class="fa fa-close" ></i> Close</a>

                @if($data->state == 'draft')
                    <a class="btn bg-maroon pull-right" href="finance/payment/confirm/{{$data->id}}" ><i class="fa fa-download" ></i> Confirm</a>
                    <a class="btn btn-default pull-right" href="finance/payment/delete/{{$data->id}}" id="btn-delete" style="margin-right: 10px;" ><i class="fa fa-trash" ></i> Delete</a>
                @endif

                @if($data->state == 'post')
                    <a class="btn bg-purple pull-right" href="finance/payment/reconcile/{{$data->id}}" ><i class="fa fa-random" ></i> Reconcile</a>

                    @if($data->residual < $data->jumlah)
                        <a class="btn btn-default pull-right" href="finance/payment/unreconcile/{{$data->id}}" style="margin-right: 10px;" ><i class="fa fa-refresh" ></i> Unreconcile</a>                        
                    @else
                        <a class="btn btn-warning pull-right" href="finance/payment/cancel/{{$data->id}}" style="margin-right: 10px;" ><i class="fa fa-reply" ></i> Cancel</a>                        
                    @endif
                @endif

                @if($data->state == 'rec')
                    <a class="btn btn-default pull-right" href="finance/payment/unreconcile/{{$data->id}}" style="margin-right: 10px;" ><i class="fa fa-refresh" ></i> Unreconcile</a>
                @endif
                
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

    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });    

    // select 2
    $("select[name=partner]").select2();
    // $("select[name=karyawan]").select2('val',[]);
    // $("select[name=customer]").select2();
    // $("select[name=customer]").select2('val',[]);

    $('select[name=type]').change(function(){
        val = $(this).val();
        if(val == 'so'){
            $('#input-lain').hide();
            $('#input-karyawan').hide();
            $('#input-customer').removeClass('hide');
            $('#input-customer').hide();
            $('#input-customer').fadeIn();

            $('select[name=customer]').select2('val',[]);
            $('select[name=customer]').attr('required','required');
            $('select[name=karyawan]').removeAttr('required');
            $('input[name=penyetor]').removeAttr('required');
        }else if(val == 'pl'){
            $('#input-customer').hide();
            $('#input-karyawan').hide();
            $('#input-lain').removeClass('hide');
            $('#input-lain').hide();
            $('#input-lain').fadeIn();

            $('input[name=penyetor]').attr('required','required');
            $('select[name=karyawan]').removeAttr('required');
            $('select[name=customer]').removeAttr('required');
        }else if(val == 'pk'){
            $('#input-customer').hide();
            $('#input-lain').hide();
            $('#input-karyawan').removeClass('hide');
            $('#input-karyawan').hide();
            $('#input-karyawan').fadeIn();
            
            $('select[name=karyawan]').select2('destroy');
            $('select[name=karyawan]').select2();
            $('select[name=karyawan]').select2('val',[]);

            $('select[name=karyawan]').attr('required','required');
            $('input[name=penyetor]').removeAttr('required');
            $('select[name=customer]').removeAttr('required');
            
        }
    });

    $('.btn-add-tagihan').click(function(){
        var rowTagihan = $(this).parent().parent();
        var tagihan = $(this).data('piutang');
        var tbRight = $('#table-tobe-reconcile');
        // tbRight.find('tbody').append($('<tr>').append($('<td>').attr('data-piutang',JSON.stringify(taghian)).text(tagihan.name))
        //                         .append($('<td>').text(tagihan.amount_due))
        //                 );
        var amountdue = Number(tagihan.amount_due) 
        tbRight.find('tbody').append($('<tr>')
                             .append($('<td>').addClass('text-center').append($('<a>').attr('data-tagihan',JSON.stringify(tagihan)).attr('href','#').addClass('btn-remove-tagihan hide').append($('<i>').addClass('fa fa-minus-square'))))
                             .append($('<td>').addClass('text-center').text(tagihan.name))
                             .append($('<td>').addClass('text-right').text(number_format(amountdue,2,'.',',')))
                             );
        rowTagihan.hide();

        calculateTobeReconcile();

        return false;
    });

    $(document).on('click','.btn-remove-tagihan',function(){
        var rowTagihan = $(this).parent().parent();
        var tagihan = $(this).data('tagihan');
        $('#row-'+tagihan.id).show();
        rowTagihan.remove();
        return false;
    });

    function calculateTobeReconcile(){
        var tbRight = $('#table-tobe-reconcile');
        var jumlah = 0;
        $('#table-tobe-reconcile > tbody > tr').each(function(){
            jumlah += Number($(this).find('td:last').text().replace('.00','').replace(',',''));
        });

        $('#tx-jumlah').autoNumeric('set',jumlah);

        // set residual
        var amountDue = $('input[name=jumlah]').autoNumeric('get');
        // alert(residual);
        residual = Number(amountDue) - Number(jumlah);
        $('#tx-residual').autoNumeric('set',residual);
    }

    $('select[name=customer]').change(function(){
        // get amount due
        var customer_id = $(this).val();
        $.get('finance/payment/get-amount-due/'+customer_id,function(res){
            $('input[name=amount_due]').autoNumeric('set',res);
        });
    });

    $('#btn-delete').click(function(){
        if(!confirm('Anda akan menghapus data ini?')){
            return false;
        }
    });

})(jQuery);
</script>
@append
