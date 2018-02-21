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
        <a href="gaji/gaji-driver" >Gaji Driver</a> <i class="fa fa-angle-double-right" ></i> Group by : <i>{{$group}}</i>
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        @include('gaji.gaji_driver.box-header')
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode</th>
                        <th>Driver</th>
                        <th>Jumlah</th>
                        <th>Amount Due</th>
                        <th>State</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr class="row-grouped" data-groupby="{{$group}}" data-groupid="{{$group == 'partner_id' ? $dt->partner_id : ''}}">
                        <td colspan="7" style="padding-left: 10px;" >
                            {{$dt->partner . ' ('.$dt->jumlah.')'}}
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
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    function autoNum(){
        $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.',
            unformatOnSubmit: true
        });
    }

    // add class to pagination
    $('ul.pagination').addClass('pagination-sm no-margin');

    $('.row-grouped').click(function(){
        groupby = $(this).data('groupby');
        groupid = $(this).data('groupid');
        if(groupid==''){
            groupid=0;
        }
        parentrow = $(this);

        url = 'gaji/gaji-driver/group-detail/'+groupby+'/'+groupid;
        $.getJSON(url, function(data) {
            // alert(data);
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
                                            $('<td>').attr('align','center').text(item.state == 'draft' ? 'Draft' : item.name)
                                        ).append(
                                            $('<td>').attr('align','center').text(item.tanggal_format)
                                        ).append(
                                            $('<td>').text(item.kode_partner + ' - ' + item.partner)
                                        ).append(
                                            $('<td>').attr('align','right').addClass('uang').text(item.gaji_nett)
                                        ).append(
                                            $('<td>').attr('align','right').addClass('uang').text(item.gaji_nett)
                                        ).append(
                                            $('<td>').attr('align','center').html(status)
                                        ).append(
                                            $('<td>').addClass('text-center').html('<a target="_blank" class="btn btn-success btn-xs" href="gaji/gaji-driver/edit/' + item.id + '" ><i class="fa fa-edit" ></i></a>')
                                        )
                        );
                });

                // row child add
                $('.row-child').removeClass('hide');
                $('.row-child').hide();
                $('.row-child').fadeIn();
                autoNum();

            });

    });

})(jQuery);
</script>
@append