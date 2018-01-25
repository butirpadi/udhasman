@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }

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
        <a href="pembelian" >Pembelian</a> <i class="fa fa-angle-double-right" ></i> Group by : <i>{{$groupby}}</i>
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
                    <tr  data-id="{{$dt->id}}" class="row-grouped" data-groupby="{{$groupby}}" data-groupid="{{$groupby == 'supplier' ? $dt->supplier_id : $dt->supplier_id}}" >
                        <td colspan="4" class="text-bold" style="padding-left: 25px;" >
                            {{$dt->supplier . ' (' . $dt->jumlah . ')' }}
                        </td>
                        <td class="text-right uang" >
                            {{$dt->sum_total}}
                        </td>
                        <td colspan="3" ></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
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

    $('.row-grouped').click(function(){
        groupby = $(this).data('groupby');
        groupid = $(this).data('groupid');
        if(groupid==''){
            groupid=0;
        }
        parentrow = $(this);

        $.get('pembelian/groupdetail/'+groupby+'/'+groupid, function(data) {
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

                if(item.bill_state == 'draft'){
                    bill_status = '<label class="label label-danger" >DRAFT</label>';
                }else if(item.state =='open'){
                    bill_status = '<label class="label label-warning" >OPEN</label>';
                }else if(item.state =='done'){
                    bill_status = '<label class="label label-success" >DONE</label>';
                }else{
                    bill_status = '-';
                }

                
                parentrow.after(
                        $('<tr>').addClass('row-child hide').attr('data-parentid',groupid)
                                    .append(
                                        $('<td>').addClass('text-center').text(item.ref)
                                    ).append(
                                        $('<td>').addClass('text-center').text(item.tanggal_format)
                                    ).append(
                                        $('<td>').addClass('text-center').text(item.nomor_nota)
                                    ).append(
                                        $('<td>').text(item.supplier)
                                    ).append(
                                        $('<td>').addClass('text-right uang').text(item.total )
                                    ).append(
                                        $('<td>').addClass('text-center').html(status)
                                    ).append(
                                        $('<td>').addClass('text-center').html(bill_status)
                                    ).append(
                                        $('<td>').addClass('text-center').html('<a target="_blank" class="btn btn-success btn-xs" href="pembelian/edit/' + item.id + '" ><i class="fa fa-edit" ></i></a>')
                                    )
                    );
            });
            
            $('.row-child').removeClass('hide');
            $('.row-child').hide();
            $('.row-child').fadeIn();

            $('.uang').autoNumeric('init',{
                vMin:'0.00',
                vMax:'9999999999.00',
                aSep: ',',
                aDec: '.'
            });

        }); 


    });

    

})(jQuery);
</script>
@append