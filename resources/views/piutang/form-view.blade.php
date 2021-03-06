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
    @foreach($datas as $data)
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-right" >
            <a class="btn btn-flat btn-sm btn-default" id="btn-prev" ><i class="fa fa-angle-double-left" ></i> </a>
            <a class="btn btn-flat btn-sm btn-default" id="btn-next" ><i class="fa fa-angle-double-right" ></i> </a>
            <a class="btn btn-flat btn-sm btn-default" ><i class="fa fa-list" ></i> </a>
            <a class="btn btn-flat btn-sm btn-default" href="finance/piutang/form-view" ><i class="fa fa-edit" ></i> </a>
        </div>
        <h1>
            <a href="finance/piutang" >Piutang</a> <i class="fa fa-angle-double-right" ></i> {{$data->name}}
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <form role="form" method="POST" action="finance/piutang/update" >
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->name}}</h3></label>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn  btn-arrow-right pull-right disabled {{$data->state == 'paid' ? 'bg-blue':'bg-gray'}}" >PAID</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled {{$data->state == 'open' ? 'bg-blue':'bg-gray'}}"" >OPEN</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled {{$data->state == 'draft' ? 'bg-blue':'bg-gray'}}"" >DRAFT</a>
            </div>
            <div class="box-body">
                    <div class="box-body">
                        <div class="row" >
                            <div class="col-xs-6" >
                                <div class="form-group">
                                    <label for="customerLabel">Tanggal</label>
                                    <input type="text" name="tanggal" class="input-tanggal form-control" value="{{$data->tanggal_format}}" required {{$data->state == 'draft' ? '':'readonly'}} >    
                                    <input type="hidden" name="original_id" value="{{$data->id}}" />    
                                </div>  
                                <div class="form-group">
                                    @if($data->type == 'so')
                                        <label >Source Document</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="source" value="{{$data->source}}" readonly>
                                            <span class="input-group-btn">
                                              <a href="finance/piutang/show-so/{{$data->id}}" class="btn btn-success btn-flat"><i class="fa  fa-external-link"></i></a>
                                            </span>
                                        </div>
                                        <div class="hide" >
                                            {!! Form::select('type',['pk'=>'Piutang Karyawan','pl'=>'Piutang Lain'],$data->type,['class'=>'form-control']) !!}
                                        </div>
                                    @else
                                        <label >Jenis Piutang</label>
                                        @if($data->state == 'draft')
                                            {!! Form::select('type',['pk'=>'Piutang Karyawan','pl'=>'Piutang Lain'],$data->type,['class'=>'form-control']) !!}                                        
                                        @else
                                            <input type="text" class="form-control" name="show_type" readonly value="{{$data->type == 'pl' ? 'Piutang Lain-lain' : 'Piutang Karyawan'}}" />
                                            <div class="hide" >
                                                {!! Form::select('type',['pk'=>'Piutang Karyawan','pl'=>'Piutang Lain'],$data->type,['class'=>'form-control']) !!}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="form-group {{$data->type == 'pk' ? '' : 'hide'}}" id="input-karyawan" >
                                    <label >Karyawan</label>
                                    @if($data->state == 'draft')
                                        {!! Form::select('karyawan',$karyawans,$data->karyawan_id,['class'=>'form-control','required']) !!}
                                    @else
                                        <div class="hide" >
                                            {!! Form::select('karyawan',$karyawans,$data->karyawan_id,['class'=>'form-control','required']) !!}
                                        </div>
                                        <input type="text" name="show_karyawan" value="{{$data->karyawan . ' - ' . $data->kode_karyawan}}" class="form-control" readonly /> 
                                    @endif
                                </div>
                                <div class="form-group {{$data->type == 'pl' ? '':'hide'}}" id="input-lain" >
                                    <label >Penerima</label>
                                    <input type="text" name="penerima" class="form-control" {{$data->state == 'draft' ? '':'readonly'}} value="{{$data->penerima}}" />
                                </div>
                            </div>
                            <div class="col-xs-6 " >
                                <div class="form-group" id="input-lain" >
                                    <label >Desc</label>
                                    <textarea name="desc" class="form-control" rows="2" maxlength="250" {{$data->state == 'draft' ? '':'readonly'}} >{{$data->desc}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label >Jumlah</label>
                                    <input name="jumlah" class="form-control text-right" value="{{$data->jumlah}}" required {{$data->state == 'draft' ? '':'readonly'}} />
                                </div>
                                <div class="form-group" >
                                    
                                </div>
                            </div>
                        </div>
                    </div>

            </div><!-- /.box-body -->
            <div class="box-footer" >
                <button type="submit" class="btn btn-primary {{$data->state == 'draft' ? '' : 'hide'}}" id="btn-save"  ><i class="fa fa-save" ></i> Save</button>
                
                <a class="btn btn-warning {{$data->state == 'open' ? '':'hide' }}" id="btn-cancel" href="finance/piutang/cancel/{{$data->id}}" ><i class="fa fa-reply" ></i> Cancel</a>

                @if($data->state != 'draft' && $data->type != 'so')
                    <!-- <a target="_blank" class="btn btn-success " id="btn-print" href="finance/piutang/print/{{$data->id}}"  ><i class="fa fa-print" ></i> Print</a> -->
                    <a target="_blank" class="btn btn-primary" id="btn-pdf" href="finance/piutang/pdf/{{$data->id}}" ><i class="fa fa-print" ></i> Print</a>
                @endif
                
                <a class="btn btn-danger" id="btn-close" href="finance/piutang" ><i class="fa fa-close" ></i> Close</a>
                

                <a class="btn bg-maroon pull-right {{$data->state == 'draft' ? '':'hide'}} " id="btn-confirm" href="finance/piutang/confirm/{{$data->id}}" ><i class="fa fa-check"></i> Confirm</a>

                <a class="btn btn-default pull-right {{$data->state == 'draft' ? '':'hide'}}" style="margin-right: 10px;" id="btn-delete" href="finance/piutang/delete/{{$data->id}}" ><i class="fa fa-trash-o"></i> Delete</a>

                <a class="btn bg-purple pull-right {{$data->state == 'open' ? '':'hide'}}" style="margin-right: 10px;" id="btn-payment" href="finance/piutang/pay/{{$data->id}}" ><i class="fa fa-download"></i> Register Payment</a>
            </div>
            </form>
        </div><!-- /.box -->

    </section><!-- /.content -->

    <!-- /.modal -->
    </div>
    {{$datas->links()}}
    @endforeach
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

    $('ul.pager').addClass('pagination pagination-sm no-margin hide');
    $('ul.pagination').removeClass('pager');
    
    $('#btn-next').click(function(){
        var abtn = $('ul.pagination').find('li:last').find('a');
        if(abtn.attr('href')){
            location.href = abtn.attr('href');
        }
        return false;
    });

    $('#btn-prev').click(function(){
        var abtn = $('ul.pagination').find('li:first').find('a');
        if(abtn.attr('href')){
            location.href = abtn.attr('href');
        }
        return false;
    });


    // $('select[name=type]').val([]);
    $("select[name=karyawan]").select2();
    // $("select[name=karyawan]").select2('val',[]);

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

    $('select[name=type]').change(function(){
        $('#input-karyawan').removeClass('hide');
        $('#input-karyawan').hide();
        $('#input-lain').removeClass('hide');
        $('#input-lain').hide();

        if($(this).val() == 'pk'){
            $("select[name=karyawan]").select2('destroy'); 
            $('#input-karyawan').fadeIn();
            $("select[name=karyawan]").select2();
            $("select[name=karyawan]").select2('val',[]);
            $('#input-lain').hide();

            // add required to input karuyawan
            $('select[name=karyawan]').attr('required','required');
        }else{
            $('#input-karyawan').hide();
            $('#input-lain').fadeIn();
            $('textarea[name=desc]').val('');

            // remove required karyawan
            $('select[name=karyawan]').removeAttr('required');
        }
    });

    // delete
    $('#btn-delete').click(function(){
        if(!confirm('Anda akan menghapus data hutang ini?')){
            return false;
        }
    });

    $('#btn-cancel').click(function(){
        if(!confirm('Anda akan membatalkan piutang ini?')){
            return false;
        }
    });

    $('.payment-info-btn').popover({
        trigger: 'focus',
        title: 'Payment Info',
        content:getPaymentInfo,
        html:true,
        placement:'left'
    });

    function getPaymentInfo(){
        var res='';
        $.ajax({
            url:'finance/piutang/get-payment-info/'+$(this).data('paymentid'),
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

})(jQuery);
</script>
@append
