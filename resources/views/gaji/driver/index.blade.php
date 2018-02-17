@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }

    .row-grouped td{
        color: #ffffff;
        padding: 10px 20px;
        background: -moz-linear-gradient(
            top,
            #dbf4ff 0%,
            #4eabf2 25%,
            #0e4f96);
        background: -webkit-gradient(
            linear, left top, left bottom,
            from(#dbf4ff),
            color-stop(0.25, #4eabf2),
            to(#0e4f96));
        -moz-border-radius: 6px;
        -webkit-border-radius: 6px;
        border-radius: 6px;
        border: 1px solid #006eb8;
        -moz-box-shadow:
            0px 1px 3px rgba(000,000,000,0.5),
            inset 0px -1px 0px rgba(255,255,255,0.7);
        -webkit-box-shadow:
            0px 1px 3px rgba(000,000,000,0.5),
            inset 0px -1px 0px rgba(255,255,255,0.7);
        box-shadow:
            0px 1px 3px rgba(000,000,000,0.5),
            inset 0px -1px 0px rgba(255,255,255,0.7);
        text-shadow:
        0px -1px 1px rgba(000,000,000,0.2),
        0px 1px 0px rgba(255,255,255,0.3);
        cursor: pointer;
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
            <a class="btn btn-primary " id="btn-add" href="gaji/driver/add" ><i class="fa fa-plus-circle" ></i> Input Payroll</a>
            <a class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash" ></i> Delete</a>

            <div class="pull-right" >
                <div class="box-tools" style="margin-top: 6px;">
                    <form method="GET" action="delivery/search" >
                        <div class="input-group input-group-sm" style="width: 150px;">
                          <input type="text" name="val" class="form-control pull-right" placeholder="Search" value="{{isset($search_val) ? $search_val : ''}}" >

                          <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                          </div>
                        </div>                    
                    </form>
                </div>
            </div>
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
                    <tr class="row-grouped" data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td colspan="2" class="row-bulan" data-bulan="{{$dt->bulan}}" >
                            {{$dt->bulan }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
        <div class="box-footer" >
            <i>Showing {{($data->currentpage()-1)*$data->perpage()+1}} to {{(($data->currentpage()-1)*$data->perpage())+$data->count()}} of {{$data->total()}} entries</i>
            <div class="pull-right" >
                {{$data->links()}}
            </div>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // var TBL_KATEGORI = $('#table-data').DataTable({
    //     sort:false
    // });

    $('ul.pagination').addClass('pagination-sm no-margin');

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