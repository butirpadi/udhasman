@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Hutang
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
                        <!-- <th>tipe</th> -->
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
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}" class="{{$dt->state=='draft'?'text-maroon':''}}">
                        <!-- <td class="text-center" >
                                <input type="checkbox" class="ck_row" >
                        </td> -->
                        <td class="text-center" >
                            {{$dt->name}}
                        </td>
                        <td class="text-center" >
                            {{$dt->tanggal_format}}
                        </td>
                        <!-- <td class="text-center" >
                            @if($dt->type == 'pembelian')
                                Hutang Dagang
                            @else
                                Hutang Lain-lain
                            @endif
                        </td> -->
                        <td class="text-center" >
                            @if($dt->source != '')
                                {{$dt->source}}
                            @else
                                -
                            @endif
                        </td>
                        <td class="" >
                            {{$dt->partner}}
                        </td>
                        <td class="text-right" >
                            {{number_format($dt->jumlah,2,'.',',')}}
                        </td>
                        <td class="text-right" >
                            {{number_format($dt->amount_due,2,'.',',')}}
                        </td>
                        <td class="text-center" >
                            @if($dt->state == 'draft')
                                <label class="label label-danger">DRAFT</label>
                            @elseif($dt->state == 'open')
                                <label class="label label-warning">OPEN</label>
                            @elseif($dt->state == 'paid')
                                <label class="label label-success">PAID</label>
                            @endif
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-primary btn-xs" href="finance/hutang/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
        @include('hutang.box-footer')
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

            var deleteForm = $('<form>').attr('method','POST').attr('action','finance/hutang/delete');
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