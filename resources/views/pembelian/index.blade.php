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
        Pembelian
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        @include('pembelian.box-header')
        <div class="box-body">
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;" class="text-center">
                            <input type="checkbox" name="ck_all" >
                            <!-- <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;"  > -->
                        </th>
                        <th>Ref#</th>
                        <th>Tanggal</th>
                        <th>Nomor Nota</th>
                        <th>Supplier</th>
                        <th>Total</th>
                        <th>state</th>
                        <th>bill state</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr  data-id="{{$dt->id}}" >
                        <td class="text-center" >
                            @if($dt->state == 'draft')
                                <input type="checkbox" class="ck_row" >
                            @endif
                        </td>
                        <td class="text-center" >
                            {{$dt->ref}}
                        </td>
                        <td class="text-center" >
                            {{$dt->tanggal_format}}
                        </td>
                        <td class="text-center" >
                            {{$dt->supplier_ref}}
                        </td>
                        <td class="" >
                            {{$dt->supplier}}
                        </td>
                        <td class="uang text-right" >
                            {{$dt->total}}
                        </td>
                        <td class="text-center" >
                            @if($dt->state == 'draft')
                                <label class="label label-warning" >DRAFT</label>
                            @elseif($dt->state == 'done')
                                <label class="label label-success" >DONE</label>
                            @endif
                        </td>
                        <td class="text-center" >
                            @if($dt->bill_state == 'draft')
                                <label class="label label-danger" >DRAFT</label>
                            @elseif($dt->bill_state == 'open')
                                <label class="label label-warning" >OPEN</label>
                            @elseif($dt->bill_state == 'paid')
                                <label class="label label-success" >PAID</label>
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-primary btn-xs" href="pembelian/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
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
    //         // order by state open, validated, done
    //         // otomatis submit tanpa tombol click
    //         var filter_by = $('select[name=select_filter_by]').val();
    //         var formFilter = $('<form>').attr('method','GET').attr('action','pembelian/filter');
    //         formFilter.append($('<input>').attr('type','hidden').attr('name','filter_by').val(filter_by));
    //         formFilter.submit();
    //     }

    // });

    // $('#btn-submit-filter').click(function(){
    //     var filter_by = $('select[name=select_filter_by]').val();
    //     var formFilter = $('<form>').attr('method','GET').attr('action','pembelian/filter');

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

    // add class to pagination
    $('ul.pagination').addClass('pagination-sm no-margin');

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
    //     // "columns": [
    //     //     {className: "text-center","orderable": false},
    //     //     {className: "text-right"},
    //     //     null,
    //     //     null,
    //     //     null,
    //     //     null,
    //     //     null,
    //     //     {className: "text-center"},
    //     //     // {className: "text-center"}
    //     // ],
    //     // order: [[ 1, 'asc' ]],
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
            var dataid = [];
            $('input.ck_row:checked').each(function(i){
                var data_id = $(this).parent().parent().data('id');
                // alert(data_id);
                var newdata = {"id":data_id}
                dataid.push(newdata);
            });

            var deleteForm = $('<form>').attr('method','POST').attr('action','pembelian/delete');
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