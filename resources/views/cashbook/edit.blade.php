@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

<style>
    .col-top-item{
        /*cursor:pointer;*/
        border: thin solid #CCCCCC;
        
    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
    }
</style>
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="finance/cashbook" >Buku Kas</a> 
        <i class="fa fa-angle-double-right" ></i> {{$data->cash_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <form method="POST" action="finance/cashbook/update" >
        <input type="hidden" name="cashbook_id" value="{{$data->id}}">
        <div class="box box-solid" >
            @include('cashbook.form-header')
          <div class="box-body" >
            <table class="table table-condensed" >
                <tbody>
                    <tr>
                        <td class="col-lg-2 col-md-2 col-sm-2" >
                            <label>Jenis Kas</label>
                        </td>
                        <td class="col-lg-4 col-md-4 col-sm-4" >
                            @if($data->state == 'draft')
                            {{Form::select('jenis_kas',['I'=>'Debit','O'=>'Credit'],$data->in_out,['class'=>'form-control'])}}
                            @else 
                                <div class="hide" >
                                    {{Form::select('jenis_kas',['I'=>'Debit','O'=>'Credit'],$data->in_out,['class'=>'form-control'])}}
                                </div>
                                <input type="text" name="jenis_kas_input" class="form-control" readonly value="{{$data->in_out == 'I' ? 'DEBIT' : 'CREDIT'}}">
                            @endif
                        </td>
                        <td class="col-lg-2 col-md-2 col-sm-2" ><label>Tanggal</label></td>
                        <td class="col-lg-4 col-md-4 col-sm-4" >
                            <input type="text" class="form-control input-date" name="tanggal" value="{{$data->tanggal_formatted}}" required autocomplete="off" {{$data->state == 'draft' ? '' : 'readonly'}} >
                        </td>
                    </tr>
                    <tr>
                        <td class="col-lg-2 col-md-2 col-sm-2" >
                            <label>Keterangan</label>
                        </td>
                        <td>
                            <input type="text" name="keterangan" class="form-control" required autocomplete="off" value="{{$data->desc}}" {{$data->state == 'draft' ? '' : 'readonly'}} >
                        </td>
                        <td><label>Jumlah</label></td>
                        <td>
                            <input type="text" name="jumlah" class="form-control uang text-right" value="{{$data->jumlah}}" required {{$data->state == 'draft' ? '' : 'readonly'}} > 
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        <div class="box-footer" >
            @if($data->state == 'draft')
                <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            @endif
            <a class="btn btn-danger" href="finance/cashbook" ><i class="fa fa-close" ></i> Close</a>
            
            @if($data->state == 'draft')
                <a class="btn btn-success pull-right" href="finance/cashbook/confirm/{{$data->id}}" ><i class="fa fa-check" ></i> Confirm</a>
                <a class="text-red btn pull-right " id="btn-delete" style="margin-right: 10px;" data-href="finance/cashbook/delete/{{$data->id}}" href="{{url()->current()}}#btn-delete" ><i class="fa fa-close" ></i> Delete</a>
            @endif
            @if($data->state == 'post')
                <a class="btn btn-success pull-right " id="btn-pdf" style="margin-right: 10px;" href="finance/cashbook/print-pdf/{{$data->id}}" target="_blank" ><i class="fa fa-file-pdf-o" ></i> PDF</a>
            @endif
        </div>
    </form>
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // SET DATEPICKER
    $('.input-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    // END OF AUTONUMERIC
   
    $('#btn-delete').click(function(){
        if(confirm('Anda akan menghapus data ini?')){
            url = $(this).data('href');
            alert(url);
        }

        return false;
    });

// alert('pret');
})(jQuery);
</script>
@append