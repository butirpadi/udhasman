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
        <a href="pengiriman" >Report Pengiriman</a>  
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
          <li class="hide"><a href="#tab_1" data-toggle="tab">Basic</a></li>
          <li class="active" ><a href="#tab_2" data-toggle="tab">Group Report</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane hide" id="tab_1">
                <form method="POST" action="report/pengiriman/default-report" target="_blank">
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Tanggal</label>
                                <div class='input-group' style="width: 100%;" >
                                    <input type="text" name="tanggal_awal" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required>
                                    <div class='input-group-field' style="padding-left: 5px;" >
                                        <input  type="text" name="tanggal_akhir" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label >Tipe Report</label>
                                <select name="tipe_report" class="form-control">
                                    <option value="summary" >Summary</option>
                                    <option value="detail" >Detail</option>
                                    <!-- <option value="grafik" >Grafik</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Customer</label>
                                {!! Form::select('customer',$select_customer,null,['class'=>'form-control select2']) !!}
                            </div>
                            <div class="form-group">
                                <label >Pekerjaan</label>
                                {!! Form::select('pekerjaan',[],null,['class'=>'form-control select2']) !!}
                            </div>
                            <div class="form-group">
                                <label >Material</label>
                                {!! Form::select('material',$select_material,null,['class'=>'form-control select2']) !!}
                            </div>
                        </div> 
                        <div class="col-xs-12" >
                            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-check" ></i> Submit</button>
                            <!-- <a class="btn btn-danger" id="btn-cancel-save" href="pengiriman" ><i class="fa fa-close" ></i> Close</a> -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane active" id="tab_2">
                <!-- <form method="POST" action="report/pengiriman/group-report" target="_blank"> -->
                <form name="form-group-report" method="POST" action="report/pengiriman/group-report-inline" target="_blank">
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Tanggal</label>
                                <div class='input-group' style="width: 100%;" >
                                    <input type="text" name="tanggal_awal" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required>
                                    <div class='input-group-field' style="padding-left: 5px;" >
                                        <input  type="text" name="tanggal_akhir" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required>

                                    </div>
                                </div>
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
                                    <option value="customer" >Customer</option>
                                    <option value="pekerjaan" >Pekerjaan</option>
                                    <option value="material" >Material</option>
                                    <option value="lokasi" >Lokasi Galian</option>
                                    <option value="driver" >Driver</option>
                                </select>
                            </div>
                            <div class="form-group" id="input-customer" >
                                <label>Customer</label>
                                {!! Form::select('customer',$select_customer,null,['class'=>'form-control select2']) !!}
                            </div>
                            <div class="form-group hide" id="input-material" >
                                <label>Material</label>
                                {!! Form::select('material',$select_material,null,['class'=>'form-control select2']) !!}
                            </div>
                            <div class="form-group hide" id="input-pekerjaan" >
                                <label>Pekerjaan</label>
                                {!! Form::select('pekerjaan',$select_pekerjaan,null,['class'=>'form-control select2']) !!}
                            </div>
                            <div class="form-group hide" id="input-lokasi-galian" >
                                <label>Lokasi Galian</label>
                                {!! Form::select('lokasi_galian',$select_lokasi_galian,null,['class'=>'form-control select2']) !!}
                            </div>
                            <div class="form-group hide" id="input-driver" >
                                <label>Lokasi Driver</label>
                                {!! Form::select('driver',$select_driver,null,['class'=>'form-control select2']) !!}
                            </div>
                        </div> 
                        <div class="col-xs-12" >
                            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-check" ></i> Submit</button>
                            <!-- <a class="btn btn-danger" id="btn-cancel-save" href="pengiriman" ><i class="fa fa-close" ></i> Close</a> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="box box-solid hide" id="box-report" >
        <div class="box-body" >
            <div class="row" >
                <div class="col-xs-12" id="report-detail" ></div>
                <div class="col-xs-12 hide" id="report-detail-button" >
                    <a class="btn btn-primary" id="btn-print-pdf" ><i class="fa fa-file-pdf-o" ></i> PDF</a>
                    <a class="btn btn-success" ><i class="fa fa-file-excel-o" ></i> XLS</a>
                </div>      
            </div>
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
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });

    // SET PEKERJAAN
    $('select[name=customer]').change(function(){
        $.get('master/pekerjaan-get-by-id/'+$(this).val(),function(data){
            data_pekerjaan = JSON.parse(data);
            // $("select[name=pekerjaan]").val([]).trigger('change');
            $('select[name=pekerjaan]').empty();
            $.each(data_pekerjaan,function(dt){
                // alert(data_pekerjaan[dt].nama + ' -- ' + data_pekerjaan[dt].id);
                $('select[name=pekerjaan]').append($('<option>', { 
                    value: data_pekerjaan[dt].id,
                    text : data_pekerjaan[dt].text 
                }));
            });
            // $("select[name=pekerjaan]").reset();
            $("select[name=pekerjaan]").select2('destroy');
            $("select[name=pekerjaan]").val([]);
            $("select[name=pekerjaan]").select2();
        });
    });


    // set form action
    detail_action = "report/pengiriman/group-detail-report";
    detail_inline_action = "report/pengiriman/group-detail-report-inline";
    summary_action = "report/pengiriman/group-report";
    summary_action_inline = "report/pengiriman/group-report-inline";
    $('#tipe_report_group').change(function(){
        if($(this).val() == 'detail'){
            // $(this).parents('form').last().attr('action',detail_action);
            $(this).parents('form').last().attr('action',detail_inline_action);
        }else{
            // $(this).parents('form').last().attr('action',summary_action);
            $(this).parents('form').last().attr('action',summary_action_inline);

        }
    });


    $('select[name=group_by]').change(function(){
        var groupby  = $(this).val();

        // clear select
        $('.select2').select2('destroy');
        $('.select2').val([]);
        $('.select2').select2();

        if(groupby == 'customer'){
            $('#input-customer').removeClass('hide');
            $('#input-material').addClass('hide');
            $('#input-pekerjaan').addClass('hide');
            $('#input-lokasi-galian').addClass('hide');
            $('#input-driver').addClass('hide');
        }else if(groupby == 'material'){
            $('#input-customer').addClass('hide');
            $('#input-material').removeClass('hide');
            $('#input-pekerjaan').addClass('hide');
            $('#input-lokasi-galian').addClass('hide');
            $('#input-driver').addClass('hide');
        }else if(groupby == 'pekerjaan'){
            $('#input-customer').addClass('hide');
            $('#input-material').addClass('hide');
            $('#input-pekerjaan').removeClass('hide');
            $('#input-lokasi-galian').addClass('hide');
            $('#input-driver').addClass('hide');
        }else if(groupby == 'lokasi'){
            $('#input-customer').addClass('hide');
            $('#input-material').addClass('hide');
            $('#input-pekerjaan').addClass('hide');
            $('#input-lokasi-galian').removeClass('hide');
            $('#input-driver').addClass('hide');
        }else if(groupby == 'driver'){
            $('#input-customer').addClass('hide');
            $('#input-material').addClass('hide');
            $('#input-pekerjaan').addClass('hide');
            $('#input-lokasi-galian').addClass('hide');
            $('#input-driver').removeClass('hide');
        }

    });

    $('form[name=form-group-report]').submit(function(){
        $.post($(this).attr('action'),$(this).serialize(),function(res){
            $('#report-detail').html(res);
            $('#report-detail-button').removeClass('hide');
            $('#box-report').removeClass('hide');
            $('#box-report').hide();
            $('#box-report').slideDown(500);
        });
        return false;
    });

    $('#btn-print-pdf').click(function(){
        // var form = $('<form>');
        var form = $('form[name=form-group-report]').clone();
        form.attr('name','duplicate-form');
        // form.html($('form[name=form-group-report]').serialize());
        // form.attr('method','POST');
        // form.attr('target','_blank')
        $('#report-detail').after(form);
        form.find('select[name=group_by]').val($('form[name=form-group-report]').find('select[name=group_by]').val());
        form.find('select[name=tipe_report]').val($('form[name=form-group-report]').find('select[name=tipe_report]').val());
        form.find('select[name=customer]').val($('form[name=form-group-report]').find('select[name=customer]').val());
        form.find('select[name=lokasi_galian]').val($('form[name=form-group-report]').find('select[name=lokasi_galian]').val());
        form.find('select[name=pekerjaan]').val($('form[name=form-group-report]').find('select[name=pekerjaan]').val());
        form.find('select[name=material]').val($('form[name=form-group-report]').find('select[name=material]').val());
        form.find('select[name=driver]').val($('form[name=form-group-report]').find('select[name=driver]').val());
        if(form.find('select[name=tipe_report]').val() == 'summary' ){
            form.attr('action',summary_action);            
        }else{
            form.attr('action',detail_action);            
        }
        form.submit();
        form.remove();
    });


})(jQuery);
</script>
@append
