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
        <a href="finance/hutang" >Hutang</a> <i class="fa fa-angle-double-right" ></i> {{$data->name}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <form role="form" method="POST" action="finance/hutang/update" >
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->name}}</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data->state == 'paid' ? 'bg-blue' : 'bg-gray'}}" >PAID</a>

            <!-- <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >VALIDATED</a> -->

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->state == 'open' ? 'bg-blue' : 'bg-gray'}}" >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->state == 'draft' ? 'bg-blue' : 'bg-gray'}}" >DRAFT</a>
        </div>
        <div class="box-body">
                <div class="box-body">
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label for="customerLabel">Tanggal</label>
                                <input type="text" name="tanggal" class="input-tanggal form-control" value="{{$data->tanggal_format}}" required {{$data->state == 'open' ? 'readonly':''}} />
                                <input type="hidden" name="original_id" value="{{$data->id}}" required />    
                            </div>  
                            <div class="form-group">
                                <label >Partner</label>
                                @if($data->state != 'draft')
                                    <input type="text" class="form-control" name="partner" value="{{$data->partner}}" readonly >
                                @else
                                    {!! Form::select('partner',$partners,$data->partner_id,['class'=>'form-control select2']) !!}
                                @endif
                            </div>
                            <div class="form-group">
                                <label >Desc</label>
                                <textarea name="desc" class="form-control" rows="4" maxlength="250" {{$data->state == 'open' ? 'readonly':''}} >{{$data->desc}}</textarea>
                            </div>
                            
                        </div>
                        <div class="col-xs-6" >
                            @if($data->type == 'pembelian')
                            <div class="form-group">
                                <label >Source Document</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="source"  value="{{$data->source}}" disabled/>
                                    <span class="input-group-btn">
                                      <a href="finance/hutang/show-po/{{$data->id}}/{{$data->po->id}}" class="btn btn-success btn-flat"><i class="fa  fa-external-link" ></i></a>
                                    </span>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label >Jumlah</label>
                                <input name="jumlah" class="form-control text-right" value="{{$data->jumlah}}" required {{$data->state == 'open' ? 'readonly':''}} />
                            </div>
                            <div class="form-group" >
                                <table class="table" >
                                    <tbody>
                                        <tr>
                                            <td class="text-right" >
                                                <label style="margin:0;">Total :</label>
                                                <br/><br/>
                                                @foreach($payments as $pay)
                                                    <p>
                                                        <a href="#" class="payment-info-btn" data-paymentid="{{$pay->id}}"  ><i class="fa text-green fa-info-circle"></i> </a>&nbsp;&nbsp;&nbsp;
                                                        <i>Paid on {{$pay->tanggal_format}}</i>
                                                    </p>
                                                @endforeach
                                            </td>
                                            <td class="text-right uang" >
                                                {{number_format($data->jumlah,2,'.',',')}}
                                                <br/><br/>
                                                @foreach($payments as $pay)
                                                    <p><i>{{number_format($pay->jumlah,2,'.',',')}}</i></p>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr style="font-size: 12pt;" >
                                            <td class="text-right" ><label>Amount Due :</label></td>
                                            <td class="text-right " >
                                                <label class="uang" >{{number_format($data->amount_due,2,'.',',')}}</label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            @if($data->state == 'draft')
                <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            @endif

            

            @if($data->state == 'draft')
                <a class="btn btn-success pull-right" id="btn-confirm" href="finance/hutang/confirm/{{$data->id}}" ><i class="fa fa-check" ></i> Confirm</a>
                <a class="btn btn-default pull-right" style="margin-right: 10px;" id="btn-delete" href="finance/hutang/delete/{{$data->id}}" ><i class="fa fa-trash" ></i> Delete</a>
            @endif
            
            @if($data->state == 'open')
                @if(count($payments) == 0)
                    <a class="btn btn-warning " id="btn-cancel" href="finance/hutang/cancel/{{$data->id}}" ><i class="fa fa-reply" ></i> Cancel</a>                    
                @endif

                <a class="btn bg-maroon pull-right" href="finance/hutang/reg-payment/{{$data->id}}" ><i class="fa fa-download" ></i> Register Payment</a>
            @endif

            <a class="btn btn-danger" id="btn-cancel-save" href="finance/hutang" ><i class="fa fa-close" ></i> Close</a>


            @if($data->state == 'O' || $data->state == 'D')
                <a class="btn btn-success pull-right" id="btn-validate" href="finance/hutang/validate/{{$data->id}}" ><i class="fa fa-check" ></i> Validate</a>
                <!-- <a class="btn bg-maroon pull-right" href="finance/hutang/reg-payment/{{$data->id}}" ><i class="fa fa-download" ></i> Register Payment</a> -->
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

    $('.payment-info-btn').popover({
        trigger: 'focus',
        title: 'Payment Info',
        content:getPaymentInfo,
        html:true,
        placement:'left'
    });

    function getPaymentInfo(){
        var res='test';
        // $.get('finance/hutang/get-payment-info/'+$(this).data('paymentid'),function(data){
        //     res = data;
        // });
        $.ajax({
            url:'finance/hutang/get-payment-info/'+$(this).data('paymentid'),
            method:"GET",
            async:false,
            success:function(data){
                res = data;
            }
        })
        return res;
    }

    $('.payment-info-btn').click(function(){
        return false;
    });

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

    $('#btn-validate').click(function(){
        if(!confirm('Anda akan mem-validasi data ini?')){
            return false;
        }
    });

    // delete
    $('#btn-delete').click(function(){
        if(!confirm('Anda akan menghapus data hutang ini?')){
            return false;
        }
    });

    // cancel
    $('#btn-cancel').click(function(){
        if(!confirm('Anda akan membatalkan data hutang ini?')){
            return false;
        }
    });

})(jQuery);
</script>
@append
