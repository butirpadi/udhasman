@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="delivery" >Pengiriman</a> <i class="fa fa-angle-double-right" ></i> Search : <i>{{$search_val}}</i>
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
                            <input type="checkbox" name="ck_all" />
                        </th>
                        <th>Ref#</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Pekerjaan</th>
                        <th>Material</th>
                        <th>Driver/Nopol</th>
                        <th>state</th>
                        <th>Invoice State</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengiriman as $dt)
                    <tr class="{{$dt->state == 'draft' ? 'text-maroon':''}}" >
                        <td class="text-center" >
                            @if($dt->state == 'draft')
                            <input type="checkbox" class="ck_row" name="ck_{{$dt->id}}"  data-originalid="{{$dt->id}}"  />
                            @endif
                        </td>
                        <td class="text-center" >
                            {{$dt->name}}
                        </td>
                        <td class="text-center" >
                            {{$dt->order_date_format}}
                        </td>
                        <td>
                            {{$dt->customer}}
                        </td>
                        <td>
                            {{$dt->pekerjaan}}
                        </td>
                        <td>
                            {{$dt->material}}
                        </td>
                        <td>
                            {{$dt->karyawan}}
                            @if($dt->karyawan_id)
                            /
                            @endif
                            {{$dt->nopol}}
                        </td>
                        <td class="text-center" >
                            @if($dt->state == 'open')
                                <label class="label label-warning">OPEN</label>
                            @elseif($dt->state == 'draft')
                                <label class="label label-danger">DRAFT</label>
                            @elseif($dt->state == 'done')
                                <label class="label label-success">DONE</label>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($dt->invoice_state == 'draft')
                                <label class="label label-danger">DRAFT</label>
                            @elseif($dt->invoice_state == 'open')
                                <label class="label label-warning">OPEN</label>
                            @elseif($dt->invoice_state == 'paid')
                                <label class="label label-success">PAID</label>
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-success btn-xs" href="delivery/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            <!-- <a class="btn btn-success btn-xs" href="delivery/show/{{$dt->id}}" ><i class="fa fa-edit" ></i></a> -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <div class="pull-right" >
                {{$pengiriman->links()}}
            </div>
        </div>
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

    

})(jQuery);
</script>
@append