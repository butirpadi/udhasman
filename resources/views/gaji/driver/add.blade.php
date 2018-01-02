@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
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
        <a href="gaji/driver" >Payroll Driver</a> 
        <i class="fa fa-angle-double-right" ></i> New
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>
        </div>
        <div class="box-body">
            <form name="form-option" method="POST" action="gaji/driver/show-payroll-table" >
                <div class="row" >
                    <div class="col-xs-6" >
                        <div class="form-group" >
                            <label>Bulan</label>
                            <input type="text" name="bulan" class="form-control" required>
                        </div>  
                    </div>
                    <div class="col-xs-6" >
                        <label>Periode Pembayaran</label>
                        <select name="payday" class="form-control"></select>
                    </div>
                    <div class="col-xs-12" >
                        <button type="submit" class="btn btn-primary" >Submit</button>
                    </div>
                </div>

            </form>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // FORMAT TANGGAL
    $('input[name=bulan]').datepicker({
        format: "mm-yyyy",
        startView: "months", 
        minViewMode: "months",
        autoclose : true
    }).on('changeDate', function(e) {
        // get data minggu/pay day
        $.post('gaji/driver/get-pay-day',{
            'bulan' : $('input[name=bulan]').val()
        },function(res){
            var payday = JSON.parse(res);
            // alert(res);
            $('select[name=payday]').empty();
            $.each(payday,function(){
                $('select[name=payday]').append($('<option>', {value:this.tanggal, text:this.tanggal_full}));
            });
            
        });

    });

    // submit
    $('form[name=form-option]').submit(function(){
        var bulan = $('input[name=bulan]').val();
        var select_tanggal = $('select[name=payday]').val();
        var tanggal = select_tanggal + "-" + bulan;
        var kode_jabatan = $('select[name=jabatan]').val();
        var url = $(this).attr('action');
        location.href = url+'/' + tanggal ; 

        return false;
    });

})(jQuery);
</script>
@append
