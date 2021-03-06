@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }
</style>

@append

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Purchase Order Reports
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >Report Options</h3></label>
            </div>
            <div class="box-body">
                <table class="table " >
                    <tbody>
                        <tr>
                            <td>
                                <label>Order Date</label>
                            </td>
                            <td>
                                <input type="text" class="form-control input-date" name="start_date">
                            </td>
                            <td>
                                <input type="text" class="form-control input-date" name="end_date">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" >
                                
                                <div class="checkbox" >
                                    <label>
                                        <input type="checkbox" name="ck_detail_report" >
                                        <b>Tampilkan detail report per product</b>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                
                                <div class="checkbox" >
                                    <label>
                                        <input type="checkbox" name="ck_supplier" data-filter='filter_by_supplier'>
                                        <b>Supplier</b>
                                    </label>
                                </div>
                            </td>
                            <td colspan="2" >
                                {!! Form::select('supplier',$select_supplier,null,['class'=>'form-control','readonly filter_by_supplier']) !!}
                            </td>
                        </tr>
                        {{-- <tr>
                            <td>
                                <div class="checkbox" >
                                    <label>
                                        <input type="checkbox" name="ck_status" data-filter='filter_by_status'> 
                                        <b>Status</b>
                                    </label>   
                                </div>
                                
                            </td>
                            <td colspan="2" >
                                {!! Form::select('status',['O'=>'Open','V'=>'Validated','D'=>'Done'],null,['class'=>'form-control','readonly filter_by_supplier']) !!}
                            </td>
                        </tr> --}}
                        <tr>
                            <td></td>
                            <td colspan="2" >
                                <button class="btn btn-primary " id="btn-submit" >Submit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </section><!-- /.content -->
@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // SET DATEPICKER
    $('.input-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('input[name=start_date]').change(function(){
        $('input[name=end_date]').datepicker('remove');
        $('.input-date').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            startDate:$('input[name=start_date]').val()
        });
    });
    // END OF SET DATEPICKER

    // SUBMIT FORM
    $('#btn-submit').click(function(){
        var filter_by_supplier = $('input[name=ck_supplier]').prop('checked');
        var filter_by_status = $('input[name=ck_status]').prop('checked');

        // alert(filter_by_supplier)

        // filter by date range
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();
        var is_detailed_report = $('input[name=ck_detail_report]').prop('checked');

        if(filter_by_supplier && filter_by_status){
            // filter by status & supplier
        }else if(filter_by_status){
            // filter by status

        }else if(filter_by_supplier){
            // filter by supplier
            var supplier = $('select[name=supplier]').val();
            var newform = $('<form>').attr('method','POST').attr('action','report/purchase/filter-by-date-n-supplier');
            newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
            newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
            newform.append($('<input>').attr('type','hidden').attr('name','supplier').val(supplier));
            newform.append($('<input>').attr('type','hidden').attr('name','is_detailed_report').val(is_detailed_report));
            
            newform.submit();
        }else{            

            var newform = $('<form>').attr('method','POST').attr('action','report/purchase/filter-by-date');
            newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
            newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
            newform.append($('<input>').attr('type','hidden').attr('name','is_detailed_report').val(is_detailed_report));

            newform.submit();
        }
    });

    // FILTER BY SUPPLIER
    $('input[name=ck_supplier]').change(function(){
        if($('input[name=ck_supplier]').prop('checked')){
            // enable input supplier
            $('select[name=supplier]').removeAttr('readonly');
        }else{
            $('select[name=supplier]').attr('readonly','readonly');
        }
    });

    // FILTER BY STATUS
    $('input[name=ck_supplier]').change(function(){
        if($('input[name=ck_supplier]').prop('checked')){
            // enable input supplier
            $('select[name=supplier]').removeAttr('readonly');
        }else{
            $('select[name=supplier]').attr('readonly','readonly');
        }
    });

})(jQuery);
</script>
@append
