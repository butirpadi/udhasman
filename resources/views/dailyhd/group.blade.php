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
        <a href="dailyhd" >Operasional Alat Berat</a> <i class="fa fa-angle-double-right" ></i> Group by : <i>{{$group_by}}</i>
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        @include('dailyhd.box-header')
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;">
                            <input type="checkbox" name="ck_all"  >
                        </th>
                        <th  >Ref#</th>
                        <th  >Tanggal</th>
                        <th>Alat</th>
                        <th>Lokasi</th>
                        <th>Pengawas</th>
                        <th>Operator</th>
                        <th>Solar</th>
                        <th>Oli</th>
                        <th>Jam<br/>kerja</th>
                        <!-- <th>Status</th> -->
                        <th style="width:25px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr class="row-grouped" data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td colspan="11" style="padding-left: 20px;" >
                            @if($group_by == 'alat_id')
                                <strong>{{$dt->kode_alat . ' - ' . $dt->alat . ' ('.$dt->jumlah . ')'}}</strong>
                            @elseif($group_by == 'lokasi_galian_id')
                                <strong>{{$dt->lokasi_galian . ' ('.$dt->jumlah . ')'}}</strong>
                            @elseif($group_by == 'pengawas_id')
                                <strong>{{$dt->pengawas . ' ('.$dt->jumlah . ')'}}</strong>
                            @elseif($group_by == 'operator_id')
                                <strong>{{$dt->operator . ' ('.$dt->jumlah . ')'}}</strong>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
        @include('dailyhd.box-footer')
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // var TBL_DATA = $('#table-data').DataTable({
    //     sort:false
    // });

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
                var row = $(this).parent().parent();
                row.fadeOut(150,function(){
                    TBL_DATA.row( row ).remove().draw();      
                });
                
            });

            // remove check all & btn delete
            $('input[name=ck_all]').prop('checked',false);
            $('input[name=ck_all]').trigger('change');

            
            $.post('dailyhd/delete',{
                'dataid' : JSON.stringify(dataid)
            },function(){
            });
        }

        e.preventDefault();
        return false;
    });

    

})(jQuery);
</script>
@append