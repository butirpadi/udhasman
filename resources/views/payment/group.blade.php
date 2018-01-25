@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
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
        <a href="finance/payment" >Payment</a> <i class="fa fa-angle-double-right" ></i> Group by : <i>{{$groupby}}</i>
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        @include('payment.box-header')
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th>ref#</th>
                        <th>Tanggal</th>
                        <!-- <th>tipe</th> -->
                        <th>Partner</th>
                        <th>jumlah</th>
                        <th>residual</th>
                        <th>status</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}" class="row-grouped" data-groupby="{{$groupby}}" data-groupid="{{$groupby=='partner' ? $dt->partner_id : ''}}" >
                        <td colspan="3" class="text-bold" style="padding-left: 20px;" >
                            {{$dt->partner . ' (' . $dt->jumlah .')'}}
                        </td>
                        <td class="text-right text-bold uang" >{{$dt->sum_jumlah}}</td>
                        <td class="text-right text-bold uang" >{{$dt->sum_residual}}</td>
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
                        <label><b>RESIDUAL PAYMENT :</b></label>
                    </td>
                    <td class="text-right col-xs-6"   >
                        <label class="uang">{{$sum_residual}}</label>
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

    $('.row-grouped').click(function(){
        groupby = $(this).data('groupby');
        groupid = $(this).data('groupid');
        if(groupid==''){
            groupid=0;
        }
        parentrow = $(this);

        $.get('finance/payment/groupdetail/'+groupby+'/'+groupid, function(data) {
            // clear child
            $('tr.row-child').remove();

                $.each(data, function(i,item) {
                    if(item.state == 'draft'){
                        status = '<label class="label label-danger" >DRAFT</label>';
                    }else if(item.state =='post'){
                        status = '<label class="label label-warning" >POSTED</label>';
                    }else if(item.state =='rec'){
                        status = '<label class="label label-success" >RECONCILED</label>';
                    }
                    var karyawan = '';
                    if(item.karyawan ){
                        karyawan = item.karyawan + ' / ';
                    }
                    parentrow.after(
                        $('<tr>').addClass('row-child hide').attr('data-parentid',groupid)
                                .append(
                                        $('<td>').addClass('text-center').text(item.name)
                                    )
                                .append(
                                        $('<td>').addClass('text-center').text(item.tanggal_format)
                                    )
                                .append(
                                        $('<td>').text(item.partner)
                                    )
                                .append(
                                        $('<td>').addClass('text-right uang').text(item.jumlah)
                                    )
                                .append(
                                        $('<td>').addClass('text-right uang').text(item.residual)
                                    )
                                .append(
                                        $('<td>').addClass('text-center').html(status )
                                    )
                                .append(
                                        $('<td>').addClass('text-center').html('<a target="_blank" class="btn btn-success btn-xs" href="finance/payment/edit/' + item.id + '" ><i class="fa fa-edit" ></i></a>')
                                    )
                        );
                });

                // format auto numeric
                $('.uang').autoNumeric('init',{
                    vMin:'0.00',
                    vMax:'9999999999.00',
                    aSep: ',',
                    aDec: '.'
                });

                $('.row-child').removeClass('hide');
                $('.row-child').hide();
                $('.row-child').fadeIn();

            });


    });

    

})(jQuery);
</script>
@append