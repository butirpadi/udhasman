@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

<style>
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
        <a href="delivery" >Pengiriman</a> <i class="fa fa-angle-double-right" ></i> Group by : <i>{{$group}}</i>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box box-solid">
        @include('delivery.box-header')
        <div class="box-body">
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;" class="text-center">
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;" />
                        </th>
                        <th>Customer</th>
                        <th>Pekerjaan</th>
                        <th>Ref#</th>
                        <th>Tanggal</th>
                        <th>Material</th>
                        <th>Driver/Nopol</th>
                        <!-- <th>Kalkulasi</th> -->
                        <th>Status</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengiriman as $dt)
                    <tr class="row-grouped" data-groupby="{{$group}}" data-groupid="{{$group == 'pekerjaan' ? $dt->pekerjaan_id : $dt->customer_id}}" >
                        <td></td>
                        <td colspan="8" >
                            @if($group == 'customer')
                                <strong><i>{{$dt->customer . ' (' . $dt->jumlah . ')'}}</i></strong>
                            @endif
                            @if($group == 'pekerjaan')
                                <strong><i>{{$dt->customer . ' - ' . $dt->pekerjaan . ' (' . $dt->jumlah . ')'}}</i></strong>
                            @endif
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
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

   

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // SET AUTONUMERIC
    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });
    // END OF SET AUTONUMERIC

    // var TBL_DATA = $('#table-data').DataTable({
    //     sort:false
    // });

    // check all checkbox
    $('input[name=ck_all]').change(function(){
        if($(this).prop('checked')){
            $('input.ck_row').prop('checked',true);
        }else{
            $('input.ck_row').prop('checked',false);

        };
        showOptionButton();
    });

    // tampilkan btn delete
    $(document).on('change','.ck_row',function(){
        showOptionButton();
    });

    function showOptionButton(){
        var checkedCk = $('input.ck_row:checked');
        
        if(checkedCk.length > 0){
            // tampilkan option button
            $('#btn-delete').removeClass('hide');
        }else{
            $('#btn-delete').addClass('hide');
        }
    }

    // Delete Data Lokasi
    $('#btn-delete').click(function(e){
        if(confirm('Anda akan menhapus data ini?')){
            var dataids = [];
            $('input.ck_row:checked').each(function(i){
                // var data_id = $(this).parent().parent().data('id');
                var data_id = $(this).data('originalid');
                // alert(data_id);
                var newdata = {"id":data_id}
                dataids.push(newdata);
            });

            var deleteForm = $('<form>').attr('method','POST').attr('action','delivery/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataids').attr('value',JSON.stringify(dataids)));
            $('body').append(deleteForm);
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    $('.row-grouped').click(function(){
        groupby = $(this).data('groupby');
        groupid = $(this).data('groupid');
        if(groupid==''){
            groupid=0;
        }
        parentrow = $(this);

        $.getJSON('delivery/groupdetail/'+groupby+'/'+groupid, function(data) {
            // clear child
            $('tr.row-child').remove();

                $.each(data, function(i,item) {
                    if(item.state == 'draft'){
                        status = '<label class="label label-danger" >DRAFT</label>';
                    }else if(item.state =='open'){
                        status = '<label class="label label-warning" >OPEN</label>';
                    }else if(item.state =='done'){
                        status = '<label class="label label-success" >DONE</label>';
                    }
                    var karyawan = '';
                    if(item.karyawan ){
                        karyawan = item.karyawan + ' / ';
                    }
                    parentrow.after(
                            $('<tr>').addClass('row-child hide').attr('data-parentid',groupid).append(
                                            $('<td>')
                                        ).append(
                                            $('<td>').text(item.customer)
                                        ).append(
                                            $('<td>').text(item.pekerjaan)
                                        ).append(
                                            $('<td>').text(item.name)
                                        ).append(
                                            $('<td>').addClass('text-center').text(item.order_date_format)
                                        ).append(
                                            $('<td>').text(item.material )
                                        ).append(
                                            $('<td>').text(karyawan + item.nopol)
                                        ).append(
                                            $('<td>').addClass('text-center').html(status)
                                        ).append(
                                            $('<td>').addClass('text-center').html('<a target="_blank" class="btn btn-success btn-xs" href="delivery/edit/' + item.id + '" ><i class="fa fa-edit" ></i></a>')
                                        )
                        );
                });

                // row child add
                // parentrow.after($('<div>').addClass('div-row'));
                // $('.row-child').appendTo($('.div-row'));
                // $('.row-child').removeClass('hide');
                // $('.div-row').hide();
                // $('.div-row').slideDown(500,function(){
                //     parentrow.after($('.row-child'));
                //     $('.div-row').remove();
                // });
                $('.row-child').removeClass('hide');
                $('.row-child').hide();
                $('.row-child').fadeIn();

            });

        // $.get('delivery/groupdetail/'+groupby+'/'+groupid,null,function(res){
        //     // add child row
        //     data = JSON.stringify(res);

        //     // $.each(dt,function(){
        //     //     alert('ok');
        //     // });

            
        // });


    });

    

})(jQuery);
</script>
@append