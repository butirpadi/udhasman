@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

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
        Payroll Driver
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <a class="btn btn-primary " id="btn-add" href="gaji/driver/add" ><i class="fa fa-plus-circle" ></i> Input Presensi</a>
            <a class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash" ></i> Delete</a>
        </div>
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th >Tanggal</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td class="row-bulan" data-bulan="{{$dt->bulan}}" >
                            {{$dt->bulan }}
                        </td>
                        <td class="text-center" >
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    var TBL_KATEGORI = $('#table-data').DataTable({
        sort:false
    });

    

    // get data tanggal on row bulan
    $('.row-bulan').click(function(){
        var parent_row = $(this).parent();
        // clear child row
        if($('tr.row-child').length > 0){
            if ($('tr.row-child:first').prev().is(parent_row) ){
                $('tr.row-child').remove();                
            }else{
                $('tr.row-child').remove();                
                var url = 'gaji/driver/get-payroll-at-month/'+$(this).data('bulan');
                $.get(url,function(res){
                var data = JSON.parse(res);
                $.each(data,function(i,item){
                    parent_row.after($('<tr>').addClass('row-child')
                                        .append($('<td>').css('padding-left','50px').text(item.payment_date_formatted))
                                        .append($('<td>').attr('align','center').append($('<a>').attr('href','gaji/driver/show-payroll-table/'+item.payment_date_formatted).addClass('btn btn-success btn-xs').append($('<i>').addClass('fa fa-edit'))))
                                        
                        );
                });
                

            });    
            }
        }else{
            var url = 'gaji/driver/get-payroll-at-month/'+$(this).data('bulan');
            $.get(url,function(res){
                var data = JSON.parse(res);
                $.each(data,function(i,item){
                    parent_row.after($('<tr>').addClass('row-child')
                                        .append($('<td>').css('padding-left','50px').text(item.payment_date_formatted))
                                        .append($('<td>').attr('align','center').append($('<a>').attr('href','gaji/driver/show-payroll-table/'+item.payment_date_formatted).addClass('btn btn-success btn-xs').append($('<i>').addClass('fa fa-edit'))))
                        );
                });
            });            
        }
    });

})(jQuery);
</script>
@append