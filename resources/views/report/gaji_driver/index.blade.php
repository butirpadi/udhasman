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
        Report Gaji
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active" ><a href="#tab_2" data-toggle="tab">Report Options</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_2">
                <form name="form-group-report" method="POST" action="report/gaji-driver/submit" target="_blank" >
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
                            <div class="form-group" id="input-customer" >
                                <label>Partner</label>
                                {!! Form::select('driver',$driver,null,['class'=>'form-control select2']) !!}
                            </div>
                            <!-- <div class="form-group" id="input-customer" >
                                <label>Tipe gaji-driver</label>
                                {!! Form::select('type',['lain'=>'gaji-driver Lain-lain', 'pembelian'=>'gaji-driver Pembelian'],null,['class'=>'form-control select2']) !!}
                            </div> -->
                            
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
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
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
        var form = $('form[name=form-group-report]').clone();
        form.attr('name','duplicate-form');
        form.attr('target','_blank')
        $('#report-detail').after(form);

        form.find('select[name=tipe_report]').val($('form[name=form-group-report]').find('select[name=tipe_report]').val());
        form.find('select[name=driver]').val($('form[name=form-group-report]').find('select[name=driver]').val());
        
        form.attr('action','report/gaji-driver/submit-pdf');                    
        form.submit();
        form.remove();
    });

    $('#btn-excel').click(function(){
        var form = $('form[name=form-group-report]').clone();
        form.attr('name','duplicate-form');
        form.attr('target','_blank')
        $('#report-detail').after(form);
        
        form.find('select[name=tipe_report]').val($('form[name=form-group-report]').find('select[name=tipe_report]').val());
        form.find('select[name=driver]').val($('form[name=form-group-report]').find('select[name=driver]').val());

        form.attr('action','report/gaji-driver/submit-excel');       
        form.submit();
        form.remove();
    });


})(jQuery);
</script>
@append
