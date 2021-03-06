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
        Purchase Orders
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <a class="btn btn-primary" id="btn-add" href="purchase/order/create" ><i class="fa fa-plus-circle" ></i> Tambah Baru</a>
            <a class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash" ></i> Delete</a>

            <div class="pull-right" >
                <table style="background-color: #ECF0F5;" >
                    <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="ft-rupiah" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            AMOUNT DUE
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class="uang">{{$amount_due}}</label>
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
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;"  >
                        </th>
                        <th>Ref#</th>
                        <th>Date</th>
                        <th>Supplier Ref#</th>
                        <th>Supplier</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr  data-id="{{$dt->id}}">
                        <td class="text-center" >
                            @if($dt->status == 'O')
                                <input type="checkbox" class="ck_row" >
                            @endif
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->order_number}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->order_date_formatted}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->supplier_ref}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->supplier}}
                        </td>
                        <td class="row-to-edit uang text-right" >
                            {{$dt->total}}
                        </td>
                        <td class="row-to-edit text-center " >
                            @if($dt->status == 'O')
                                <label class="label label-danger" >OPEN</label>
                            @elseif($dt->status == 'V')
                                <label class="label label-warning" >VALIDATED</label>
                            @else
                                <label class="label label-success" >DONE</label>
                            @endif    
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-primary btn-xs" href="purchase/order/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
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

    // // ==========================================================================
    // // FILTER SECTION
    // $('select[name=select_filter_by]').change(function(){
    //     var filter_by = $(this).val();

    //     // hide filter input
    //     $('.input-filter').removeClass('hide');
    //     $('.input-filter').hide();

    //     if(filter_by == 'order_number' || filter_by == 'customer' || filter_by == 'pekerjaan' ){
    //         $('input[name=filter_string]').show();
    //     }else if(filter_by == 'order_date'){
    //         $('.input-filter-by-date').show();
    //     }else{
    //         // order by status open, validated, done
    //         // otomatis submit tanpa tombol click
    //         var filter_by = $('select[name=select_filter_by]').val();
    //         var formFilter = $('<form>').attr('method','GET').attr('action','purchase/order/filter');
    //         formFilter.append($('<input>').attr('type','hidden').attr('name','filter_by').val(filter_by));
    //         formFilter.submit();
    //     }

    // });

    // $('#btn-submit-filter').click(function(){
    //     var filter_by = $('select[name=select_filter_by]').val();
    //     var formFilter = $('<form>').attr('method','GET').attr('action','purchase/order/filter');

    //     if(filter_by == 'order_date'){
    //         formFilter.append($('<input>').attr('type','hidden').attr('name','date_start').val($('input[name=input_filter_date_start]').val()));
    //         formFilter.append($('<input>').attr('type','hidden').attr('name','date_end').val($('input[name=input_filter_date_end]').val()));
    //     }
    //     else{
    //         // FILTER BY STRING
    //         formFilter.append($('<input>').attr('type','hidden').attr('name','filter_string').val($('input[name=filter_string]').val()));
    //         // formFilter.append($('<input>').attr('type','hidden').attr('name','total').val($('input[name=input_filter_total]').autoNumeric('get')));
    //     }

    //     formFilter.append($('<input>').attr('type','hidden').attr('name','filter_by').val(filter_by));
    //     formFilter.submit();
    // });
    // // END OF FILTER SECTION
    // // ==========================================================================

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // SET AUTONUMERIC
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });
    // END OF SET AUTONUMERIC

    var TBL_DATA = $('#table-data').DataTable({
        // "columns": [
        //     {className: "text-center","orderable": false},
        //     {className: "text-right"},
        //     null,
        //     null,
        //     null,
        //     null,
        //     null,
        //     {className: "text-center"},
        //     // {className: "text-center"}
        // ],
        // order: [[ 1, 'asc' ]],
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

    // Row Clicked
    $('.row-to-edit').click(function(){        
        var row = $(this).parent();        
        var data_id = row.data('id');            
        location.href = 'purchase/order/edit/' + data_id ;        
    });

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

            var deleteForm = $('<form>').attr('method','POST').attr('action','purchase/order/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    

})(jQuery);
</script>
@append