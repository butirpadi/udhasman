@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="finance/payment" >Payment</a> <i class="fa fa-angle-double-right" ></i> Filter {{$filterby}} : <i>{{$filter}}</i>
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
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td class="text-center" >
                            {{$dt->name}}
                        </td>
                        <td class="text-center" >
                            {{$dt->tanggal_format}}
                        </td>
                        <td class="" >
                            {{$dt->partner}}
                        </td>
                        <td class="text-right uang" >{{$dt->jumlah}}</td>
                        <td class="text-right uang" >{{$dt->residual}}</td>
                        <td class="text-center" >
                            @if($dt->state == 'draft')
                                <label class="label label-danger">DRAFT</label>
                            @elseif($dt->state == 'post')
                                <label class="label label-warning">POSTED</label>
                            @elseif($dt->state == 'rec')
                                <label class="label label-success">RECONCILED</label>
                            @endif
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-primary btn-xs" href="finance/payment/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
        <div class="box-footer clearfix" >
            <div class="pull-right" >
                {{ $data->links() }}
            </div>
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

    // var TBL_KATEGORI = $('#table-data').DataTable({
    //     sort:false,
    // });

    // add class to pagination
    $('ul.pagination').addClass('pagination-sm no-margin');

    // check all checkbox
    $('input[name=ck_all]').change(function(){
        if($(this).prop('checked')){
            $('input.ck_row').prop('checked',true);
        }else{
            $('input.ck_row').prop('checked',false);

        };
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
            var dataid = [];
            $('input.ck_row:checked').each(function(i){
                var data_id = $(this).parent().parent().data('id');
                // alert(data_id);
                var newdata = {"id":data_id}
                dataid.push(newdata);
            });

            var deleteForm = $('<form>').attr('method','POST').attr('action','finance/payment/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            $('body').append(deleteForm);
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    

})(jQuery);
</script>
@append