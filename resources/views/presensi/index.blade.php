@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

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
        Presensi Karyawan
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <a class="btn btn-primary " id="btn-add" href="attendance/attend" ><i class="fa fa-plus-circle" ></i> Input Presensi</a>
            <a class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash" ></i> Delete</a>
            
            <div class="pull-right" >
                <!-- <table style="background-color: #ECF0F5;" >
                    <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="fa fa-tags" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            JUMLAH DATA
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;" >
                            <label class="uang">{{count($data)}}</label>
                        </td>
                    </tr>
                </table> -->
            </div>
        </div>
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th >Tanggal</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td class="row-bulan" data-bulan="{{$dt->bulan}}" >
                            {{$dt->bulan . ' (' . $dt->jumlah . ')'}}
                        </td>
                        <td class="text-center" >
                            
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

<script type="text/javascript">
(function ($) {

    var TBL_KATEGORI = $('#table-data').DataTable({
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
    // $(document).on('click','.row-to-edit',function(){        
    //     var row = $(this).parent();        
    //     var data_id = row.data('id');            
    //     location.href = 'master/alat/edit/' + data_id ;        
    // });

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

            var deleteForm = $('<form>').attr('method','POST').attr('action','master/alat/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            $('body').append(deleteForm);
deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    // get data tanggal on row bulan
    $('.row-bulan').click(function(){
        var parent_row = $(this).parent();
        // clear child row
        if($('tr.row-child').length > 0){
            if ($('tr.row-child:first').prev().is(parent_row) ){
                $('tr.row-child').remove();                
            }else{
                $('tr.row-child').remove();                
                var url = 'presensi/get-presensi-by-bulan/'+$(this).data('bulan');
                $.get(url,function(res){
                var data = JSON.parse(res);
                $.each(data,function(i,item){
                    parent_row.after($('<tr>').addClass('row-child')
                                        .append($('<td>').css('padding-left','50px').text(item.tgl_format))
                                        .append($('<td>').attr('align','center').append($('<a>').attr('href','attendance/attend/'+item.tgl_format).addClass('btn btn-success btn-xs').append($('<i>').addClass('fa fa-edit'))))
                                        
                        );
                });
                

            });    
            }
        }else{
            var url = 'presensi/get-presensi-by-bulan/'+$(this).data('bulan');
            $.get(url,function(res){
                var data = JSON.parse(res);
                $.each(data,function(i,item){
                    parent_row.after($('<tr>').addClass('row-child')
                                        .append($('<td>').css('padding-left','50px').text(item.tgl_format))
                                        .append($('<td>').attr('align','center').append($('<a>').attr('href','attendance/attend/'+item.tgl_format).addClass('btn btn-success btn-xs').append($('<i>').addClass('fa fa-edit'))))
                        );
                });
            });            
        }
    });

    

})(jQuery);
</script>
@append