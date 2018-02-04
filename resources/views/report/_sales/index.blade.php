@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
{{-- <link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/> --}}
<link href="plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }

    .table > tbody > tr > td >  .checkbox {
        margin-top: 0;
        margin-bottom: 0;
    }
</style>

@append

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Sales Reports
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
                <table class="table table-condensed table-bordered" >
                    <tbody>
                        <tr>
                            <td class="col-sm-2 col-md-2 col-lg-2" >
                                <label>Tanggal</label>
                            </td>
                            <td class="col-sm-4 col-md-4 col-lg-4" >
                                <input type="text" class="form-control input-date text-center" name="date_range">
                            </td>
                            <td class="col-sm-4 col-md-4 col-lg-4" >
                                {{-- <input type="text" class="form-control input-date" name="end_date"> --}}
                            </td>
                        </tr>
                        {{-- <tr>
                            <td>                                
                                <div class="checkbox"  >
                                    <label>
                                        <input type="checkbox" name="ck_detail_per_material" data-filter='filter_by_detail_material'>
                                        <b>Detail per material</b>
                                    </label>
                                </div>
                            </td>
                            <td colspan="2" >
                                
                            </td>
                        </tr> --}}

                        <tr >
                            <td >
                                {{-- <div class="radio" >
                                    <label>
                                        <input type="radio" name="rd_filter_by" value="by_sales_type" >
                                        <b>Per Sales Type</b>
                                    </label>
                                </div> --}}
                                <label>Sales Type</label>
                            </td>
                            <td > 
                                <select name="sales_type" class="form-control" {{-- disabled="disabled" --}}  >
                                    <option value="0" >Semua Jenis</option>
                                    <option value="1" >Direct Sales</option>
                                    <option value="2" >Non Direct Sales (Mitra)</option>
                                </select>
                            </td>
                            <td>
                                
                            </td>
                        </tr>

                        {{-- FILTER BY CUSTOMER --}}
                        <tr >
                            <td >
                                <div class="radio" >
                                    <label>
                                        <input type="radio" name="rd_filter_by" value="by_customer" >
                                        <b>Per Customer</b>
                                    </label>
                                </div>

                            </td>
                            <td >
                                <div class="input-group">
                                    <span class="input-group-addon">Customer</span>
                                    {!! Form::select('customer',$select_customer,null,['class'=>'form-control filter_by_customer','disabled ']) !!}
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">Pekerjaan</span>
                                    {!! Form::select('pekerjaan',[],null,['class'=>'form-control filter_by_customer','disabled ']) !!}
                                </div>
                            </td>
                        </tr>
                       
                        {{-- ============================================================= --}}

                        

{{--                         <tr>
                            <td>                                
                                <div class="radio" >
                                    <label>
                                        <input type="radio" name="rd_filter_by" value="by_lokasi_galian" >
                                        <b>Per Lokasi Galian</b>
                                    </label>
                                </div>
                            </td>
                            <td colspan="2" >
                                {!! Form::select('lokasi_galian',$select_lokasi_galian,null,['class'=>'form-control','disabled filter_by_galian']) !!}
                            </td>
                        </tr> --}}
                        
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
                                {!! Form::select('status',['O'=>'Open','V'=>'Validated','D'=>'Done'],null,['class'=>'form-control','disabled filter_by_customer']) !!}
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
<script src="plugins/bootstrap-daterangepicker/moment.js" type="text/javascript"></script>
<script src="plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
{{-- <script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script> --}}

<script type="text/javascript">
(function ($) {

    // SET DATEPICKER
    // $('.input-date').datepicker({
    //     format: 'dd-mm-yyyy',
    //     todayHighlight: true,
    //     autoclose: true
    // });
    // $('input[name=start_date]').change(function(){
    //     $('input[name=end_date]').datepicker('remove');
    //     $('.input-date').datepicker({
    //         format: 'dd-mm-yyyy',
    //         todayHighlight: true,
    //         autoclose: true,
    //         startDate:$('input[name=start_date]').val()
    //     });
    // });

    $('input[name=date_range]').daterangepicker({
            "autoApply": true,
            locale: {
              format: 'DD/MM/YYYY'
            },
        }, 
        function(start, end, label) {
            // alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            start_date = start.format('DD-MM-YYYY');
            end_date = end.format('DD-MM-YYYY');
        });
    // END OF SET DATEPICKER

    // select to null
    $('select').val([]);
    $('input[name=date_range]').val('');

    // SELECT CUSTOMER CHANGE
    $('select[name=customer]').change(function(){
        // get data pekerjaan
        var customer_id = $(this).val();
        var url = 'api/get-pekerjaan-by-customer/'+customer_id;
        $('select[name=pekerjaan]').empty();
        $('select[name=pekerjaan]').val([]);
        // add semua option
            $('select[name=pekerjaan]')
                    .append($("<option></option>")
                    .attr("value",0)
                    .text('Semua Pekerjaan'));

        $.get(url,null,function(res){
            var select_data = JSON.parse(res);
            $.each(select_data,function(i){
                $('select[name=pekerjaan]')
                    .append($("<option></option>")
                    .attr("value",this.id)
                    .text(this.nama));
            });
        });
    });
    // END OF SELECT CUSTOMER CHANGE

    // FILTER BY customer
    $('input[name=rd_filter_by]').change(function(){
        rd_filter_by = $(this).val();
        if(rd_filter_by == 'by_customer'){
            // enable filter by customer
            $('.filter_by_customer').removeAttr('disabled');
            // empty select pekerjaan
            $('select[name=pekerjaan]').empty();

            // disable select lokasi galian
            // $('select[name=lokasi_galian]').attr('disabled','disabled');

            // disable select sales typ
            // $('select[name=sales_type]').attr('disabled','disabled');
        }else{
            // disable filter by customer
            $('.filter_by_customer').attr('disabled','disabled');
            $('.filter_by_customer').val([]);

            // enable kan select lokasi galian
            // $('select[name=lokasi_galian]').removeAttr('disabled');

            // enablekan selecct sales type
            // $('select[name=sales_type]').removeAttr('disabled');

        }
    });

    // FILTER BY LOKASI GALIAN
    $('input[name=ck_galian]').change(function(){
        if($('input[name=ck_galian]').prop('checked')){
            // enable input customer
            $('select[name=lokasi_galian]').removeAttr('disabled');
        }else{
            $('select[name=lokasi_galian]').attr('disabled','disabled');
        }
    });

    // FILTER BY STATUS
    $('input[name=ck_customer]').change(function(){
        if($('input[name=ck_customer]').prop('checked')){
            // enable input customer
            $('select[name=customer]').removeAttr('disabled');
        }else{
            $('select[name=customer]').attr('disabled','disabled');
        }
    });

    // SUBMIT FORM
    var start_date;
    var end_date;
    var rd_filter_by;
    $('#btn-submit').click(function(){
        var sales_type = $('select[name=sales_type]').val();
        if(start_date != "" 
                && start_date != undefined 
                && end_date != "" 
                && end_date != undefined 
                && sales_type != ""
                && sales_type != null
                && sales_type != undefined){

            // submit form
            var newform = $('<form>').attr('method','POST');
            newform.attr('action','report/sales/report-by-date');

            if(rd_filter_by == 'by_customer'){
                var customer_id = $('select[name=customer]').val();
                var pekerjaan_id = $('select[name=pekerjaan]').val();

                // if(pekerjaan_id == 0){
                //     // semua pekerjaan
                //     newform.attr('action','report/sales/report-by-customer');
                // }else{
                //     newform.attr('action','report/sales/report-by-customer-pekerjaan');
                //     newform.append($('<input>').attr('type','hidden').attr('name','pekerjaan_id').val(pekerjaan_id));

                // }
                
                newform.attr('action','report/sales/report-by-customer');
                newform.append($('<input>').attr('type','hidden').attr('name','pekerjaan_id').val(pekerjaan_id));
                newform.append($('<input>').attr('type','hidden').attr('name','customer_id').val(customer_id));

            }
            // else if(rd_filter_by == 'by_sales_type'){
            //     // var lokasi_galian_id = $('select[name=lokasi_galian]').val();
            //     // newform.attr('action','report/sales/report-by-lokasi-galian');
            //     // newform.append($('<input>').attr('type','hidden').attr('name','lokasi-galian_id').val(lokasi_galian_id));

            //     var sales_type = $('select[name=sales_type]').val();

            //     if(sales_type == 0){
            //         // semua jenis
            //         // newform.attr('action','report/sales/report-by-sales-type-all');
                    
            //     }else{ 
            //         newform.attr('action','report/sales/report-by-sales-type');
            //         newform.append($('<input>').attr('type','hidden').attr('name','sales_type').val(sales_type));
                    
            //     }

            // }

            newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
            newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
            newform.append($('<input>').attr('type','hidden').attr('name','sales_type').val(sales_type));
            newform.submit();

            // ------------------------------
        }else{
            alert('Lengkapi data yang kosong');
        }

        return false;

        

        

        // ===========================================================


        // var start_date = $('input[name=date_range]').data('daterangepicker').getStartDate();
        // alert(start_date);

        // var filter_by_customer = $('input[name=ck_customer]').prop('checked');
        // var filter_by_status = $('input[name=ck_status]').prop('checked');

        // // alert(filter_by_customer)

        // // filter by date range
        // var start_date = $('input[name=start_date]').val();
        // var end_date = $('input[name=end_date]').val();

        // var ck_detail = $('input[name=ck_detail_per_material]').prop('checked');
        // var ck_customer = $('input[name=ck_customer]').prop('checked');

        // var newform = $('<form>').attr('method','POST');

        // if(ck_detail && ck_customer){
        //     var customer_id = $('select[name=customer]').val();
        //     newform.attr('action','report/sales/report-by-customer-detail')
        //     newform.append($('<input>').attr('type','hidden').attr('name','customer_id').val(customer_id));
        // }else if(ck_detail){
        //     // hanya tanggal
        //     newform.attr('action','report/sales/report-by-date-detail')
                        
        // }else if(ck_customer){
        //     var customer_id = $('select[name=customer]').val();
        //     newform.attr('action','report/sales/report-by-customer')
        //     newform.append($('<input>').attr('type','hidden').attr('name','customer_id').val(customer_id));            
        // }else{
        //     // hanya tanggal
        //     newform.attr('action','report/sales/report-by-date')
            
        // }

        // newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
        // newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
        // newform.submit();

        // // if(filter_by_customer && filter_by_status){
        // //     // filter by status & customer
        // // }else if(filter_by_status){
        // //     // filter by status

        // // }else if(filter_by_customer){
        // //     // filter by customer
        // //     var customer = $('select[name=customer]').val();
        // //     var newform = $('<form>').attr('method','POST').attr('action','report/purchase/filter-by-date-n-customer');
        // //     newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
        // //     newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
        // //     newform.append($('<input>').attr('type','hidden').attr('name','customer').val(customer));
        // //     newform.submit();
        // // }else{            

        // //     var newform = $('<form>').attr('method','POST').attr('action','report/purchase/filter-by-date');
        // //     newform.append($('<input>').attr('type','hidden').attr('name','start_date').val(start_date));
        // //     newform.append($('<input>').attr('type','hidden').attr('name','end_date').val(end_date));
        // //     newform.submit();
        // // }
    });

    

})(jQuery);
</script>
@append
