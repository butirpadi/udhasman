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

    .select2{
        width: 100%!important;
    }

</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="pengiriman" >Report Presensi</a>  
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <!-- <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >Report Pengiriman</h3></label>
        </div>
        <div class="box-body">
            
            
        </div>
    </div> -->

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active" ><a href="#tab_2" data-toggle="tab">Report Options</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_2">
                <form target="_blank" name="form-group-report" method="POST" action="report/presensi/submit" >
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Tanggal</label>
                                <input type="text" name="tanggal_awal" class="input-tanggal form-control" value="{{date('m-Y')}}" required>
                                <!-- <div class='input-group' style="width: 100%;" >
                                    <input type="text" name="tanggal_awal" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required>
                                    <div class='input-group-field' style="padding-left: 5px;" >
                                        <input  type="text" name="tanggal_akhir" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required>

                                    </div>
                                </div> -->
                            </div>
                            <div class="form-group">
                                <label >Tipe Report</label>
                                <select name="tipe_report" id="tipe_report_group" class="form-control">
                                    <option value="summary" >Summary</option>
                                    <option value="detail" >Detail</option>
                                    <!-- <option value="grafik" >Grafik</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Group by</label>
                                <select name="group_by" class="form-control">
                                    <option value="alat_id" >Alat</option>
                                    <option value="lokasi_galian_id" >Lokasi Galian</option>
                                    <option value="pengawas_id" >Pengawas</option>
                                    <option value="operator_id" >Operator</option>
                                </select>
                            </div>
                            <div class="form-group" id="input-alat" >
                                <label>Alat</label>
                                {!! Form::select('alat',$alat,null,['class'=>'form-control select2']) !!}
                            </div>
                            <div class="form-group hide" id="input-lokasi-galian" >
                                <label>Lokasi Galian</label>
                                {!! Form::select('lokasi_galian',$lokasi,null,['class'=>'form-control select2']) !!}
                            </div>
                            <div class="form-group hide" id="input-pengawas" >
                                <label>Pengawas</label>
                                {!! Form::select('pengawas',$partners,null,['class'=>'form-control select2']) !!}
                            </div>
                            <div class="form-group hide" id="input-operator" >
                                <label>Operator</label>
                                {!! Form::select('operator',$partners,null,['class'=>'form-control select2']) !!}
                            </div>
                            
                        </div> 
                        <div class="col-xs-12" >
                            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-check" ></i> Submit</button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="box box-solid hide " id="box-report" >
        <div class="box-body" id="report-detail" >
            
        </div>
        <div class="box-footer" >
            <a class="btn btn-primary" id="btn-print-pdf" ><i class="fa fa-file-pdf-o" ></i> PDF</a>
            <a class="btn btn-success" id="btn-excel" ><i class="fa fa-file-excel-o" ></i> XLS</a>
        </div>
    </div>

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

    //Initialize Select2 Elements
    $(".select2").val([]);
    $('.select2').select2();

    // SET DATEPICKER
    // $('.input-tanggal').datepicker({
    //     format: 'dd-mm-yyyy',
    //     todayHighlight: true,
    //     autoclose: true
    // });
    $('.input-tanggal').datepicker({
        format: "mm-yyyy",
        viewMode: "months", 
        minViewMode: "months",
        autoclose: true
    });

    $('form[name=form-group-report]').submit(function(){
        $.post($(this).attr('action'),$(this).serialize(),function(res){
            $('#report-detail').html(res);
            $('#box-report').removeClass('hide');
            $('#box-report').hide();
            $('#box-report').fadeIn(500);
        });
        return false;
    });

    
    $('#btn-print-pdf').click(function(){
        // var form = $('<form>');
        var form = $('form[name=form-group-report]').clone();
        form.attr('name','duplicate-form');
        // form.html($('form[name=form-group-report]').serialize());
        // form.attr('method','POST');
        form.attr('target','_blank')
        $('#report-detail').after(form);
        form.find('select[name=group_by]').val($('form[name=form-group-report]').find('select[name=group_by]').val());
        form.find('select[name=tipe_report]').val($('form[name=form-group-report]').find('select[name=tipe_report]').val());
        form.find('select[name=alat]').val($('form[name=form-group-report]').find('select[name=alat]').val());
        form.find('select[name=lokasi_galian]').val($('form[name=form-group-report]').find('select[name=lokasi_galian]').val());
        form.find('select[name=pengawas]').val($('form[name=form-group-report]').find('select[name=pengawas]').val());
        form.find('select[name=operator]').val($('form[name=form-group-report]').find('select[name=operator]').val());        
        form.attr('action','report/presensi/submit-pdf');            
        form.submit();
        form.remove();
    });

    $('#btn-excel').click(function(){
        // var form = $('<form>');
        var form = $('form[name=form-group-report]').clone();
        form.attr('name','duplicate-form');
        // form.html($('form[name=form-group-report]').serialize());
        // form.attr('method','POST');
        form.attr('target','_blank')
        $('#report-detail').after(form);
        form.find('select[name=group_by]').val($('form[name=form-group-report]').find('select[name=group_by]').val());
        form.find('select[name=tipe_report]').val($('form[name=form-group-report]').find('select[name=tipe_report]').val());
        form.find('select[name=alat]').val($('form[name=form-group-report]').find('select[name=alat]').val());
        form.find('select[name=lokasi_galian]').val($('form[name=form-group-report]').find('select[name=lokasi_galian]').val());
        form.find('select[name=pengawas]').val($('form[name=form-group-report]').find('select[name=pengawas]').val());
        form.find('select[name=operator]').val($('form[name=form-group-report]').find('select[name=operator]').val());
        form.attr('action','report/presensi/submit-excel');       
        form.submit();
        form.remove();
    });

    $('select[name=group_by]').change(function(){
        var groupby = $(this).val();

        // clear input
        $('select[name=alat]').select2('val','');
        $('select[name=lokasi_galian]').select2('val','');
        $('select[name=pengawas]').select2('val','');
        $('select[name=operator]').select2('val','');

        if(groupby == 'alat_id'){
            $('#input-alat').removeClass('hide');
            $('#input-lokasi-galian').addClass('hide');
            $('#input-pengawas').addClass('hide');
            $('#input-operator').addClass('hide');
        }else if(groupby == 'lokasi_galian_id'){
            $('#input-lokasi-galian').removeClass('hide');
            $('#input-alat').addClass('hide');
            $('#input-pengawas').addClass('hide');
            $('#input-operator').addClass('hide');
        }else if(groupby == 'pengawas_id'){
            $('#input-pengawas').removeClass('hide');
            $('#input-lokasi-galian').addClass('hide');
            $('#input-alat').addClass('hide');
            $('#input-operator').addClass('hide');            
        }else if(groupby == 'operator_id'){
            $('#input-operator').removeClass('hide');
            $('#input-lokasi-galian').addClass('hide');
            $('#input-pengawas').addClass('hide');
            $('#input-alat').addClass('hide');
            
        }
    });


})(jQuery);
</script>
@append