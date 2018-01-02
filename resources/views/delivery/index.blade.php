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
        Pengiriman
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <a class="btn btn-primary" id="btn-add" href="delivery/create" ><i class="fa fa-plus-circle" ></i> Create</a>
            <div class="btn-group">
                <a  class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-filter" ></i>
                    Filter
                </a>
                <ul class="dropdown-menu">
                  <li><a href="delivery/filter/draft">Draft</a></li>
                  <li><a href="delivery/filter/open">Open</a></li>
                  <li><a href="delivery/filter/done">Done</a></li>
                </ul>
            </div>
            <div class="btn-group">
                <a  class="btn bg-maroon dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-th-large" ></i>
                    Group by
                </a>
                <ul class="dropdown-menu">
                  <li><a href="delivery/groupby/customer">Customer</a></li>
                  <li><a href="delivery/groupby/pekerjaan">Pekerjaan</a></li>
                </ul>
            </div>
            <a class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash" ></i> Delete</a>

            <div class="pull-right" >
                <table style="background-color: #ECF0F5;" >
                    <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="fa fa-tags" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            JUMLAH DATA
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class="">{{count($pengiriman)}}</label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;" class="text-center">
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;" />
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
                    <tr>
                        <td>
                            <input type="checkbox" class="ck_row" name="ck_{{$dt->id}}" style="margin-left:15px;padding:0;" data-originalid="{{$dt->id}}"  />
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
                            -
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-success btn-xs" href="delivery/show/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
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

    var TBL_DATA = $('#table-data').DataTable({
        sort:false
    });

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