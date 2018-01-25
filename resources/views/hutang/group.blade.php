@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<style>
    .row-group td{
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
        <a href="finance/hutang" >Hutang</a> <i class="fa fa-angle-double-right" ></i> Group by : <i>{{ucwords($groupby)}}</i>
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        @include('hutang.box-header')
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <!-- <th style="width:25px;" class="text-center">
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;" >
                        </th> -->
                        <th>ref#</th>
                        <th>Tanggal</th>
                        <th>tipe</th>
                        <th>source</th>
                        <th>partner</th>
                        <th>jumlah</th>
                        <th>amount due</th>
                        <th>status</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr class="row-group" data-partnerid="{{$dt->partner_id}}" >
                        <td colspan="5" style="padding-left: 10px;"   >
                            <label>{{$dt->partner . ' (' . $dt->count . ')'}}</label>
                        </td>
                        <td class="text-right uang text-bold" >{{$dt->sum_jumlah}}</td>
                        <td class="text-right uang text-bold" >{{$dt->sum_amount_due}}</td>
                        <td colspan="2" ></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix" >
            <table style="font-size: 13pt!important;" >
                <tr>
                    <td class="col-xs-6 text-right" >
                        <label><b>SALDO HUTANG :</b></label>
                    </td>
                    <td class="text-right col-xs-6"   >
                        <label class="uang">{{$sum_amount_due}}</label>
                    </td>
                </tr>
            </table>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    $('.uang').autoNumeric('init',{
                vMin:'0.00',
                vMax:'9999999999.00',
                aSep: ',',
                aDec: '.'
            });

    $('.row-group').click(function(){
        // get detail data
        var row = $(this);
        var partnerid = $(this).data('partnerid');
        $.get('finance/hutang/get-by-partner/'+partnerid,function(res){
            // clear row-child
            $('.row-child').remove();
            // add new child
            var total_jumlah = 0;
            var total_amount_due = 0;
            var last_row = null;
            $.each(res,function(i,data){
                var tipe = 'Hutang Dagang';
                if(data.type == 'lain'){
                    tipe = 'Hutang lain-lain';
                }

                var state = $('<label>').addClass('label label-warning').text('OPEN');
                if(data.state == 'draft'){
                    state = $('<label>').addClass('label label-danger').text('DRAFT');
                }else if(data.state == 'paid'){
                    state = $('<label>').addClass('label label-success').text('PAID');
                }

                var newrow = $('<tr>').addClass('row-child').append($('<td>').addClass('text-center').text(data.name))
                                      .append($('<td>').addClass('text-center').text(data.tanggal_format))
                                      .append($('<td>').addClass('text-center').text(tipe))
                                      .append($('<td>').addClass('text-center').text(data.source))
                                      .append($('<td>').text(data.partner))
                                      .append($('<td>').addClass('text-right td-uang').text(data.jumlah))
                                      .append($('<td>').addClass('text-right td-uang').text(data.amount_due))
                                      .append($('<td>').addClass('text-center').append(state))
                                      .append($('<td>').addClass('text-center').append($('<a>').addClass('btn btn-primary btn-xs').attr('target','_blank').attr('href','finance/hutang/edit/'+data.id).append($('<i>').addClass('fa fa-edit'))));
                row.after(newrow);
                total_amount_due += Number(data.amount_due);
                total_jumlah += Number(data.jumlah);
                if(!last_row){
                    last_row = newrow;
                }

            });

            // // Total Row
            // var total_row = $('<tr>').addClass('row-child')
            //                         .append($('<td>').attr('colspan',5).addClass('text-center').css('font-weight','bolder').text('TOTAL'))
            //                         .append($('<td>').addClass('text-right td-uang').css('font-weight','bolder').text(total_jumlah))
            //                         .append($('<td>').addClass('text-right td-uang').css('font-weight','bolder').text(total_amount_due))
            //                         .append($('<td>'))
            //                         .append($('<td>'));
            //     last_row.after(total_row);

            $('.td-uang').autoNumeric('init',{
                vMin:'0.00',
                vMax:'9999999999.00',
                aSep: ',',
                aDec: '.'
            });

            $('.row-child').removeClass('hide');
            $('.row-child').hide();
            $('.row-child').fadeIn();
                
        }).done(function(){
            
            
        });
    });
})(jQuery);
</script>
@append