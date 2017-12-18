@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/select2.min.css">

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }

    .select2{
        width:100%!important;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="penjualan" >Pesanan Penjualan</a> 
        <i class="fa fa-angle-double-right" ></i> Filter
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <!-- <div class="btn-group">
              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench" ></i>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a id="btn-batch-edit" href="penjualan/create" ><i class="fa fa-plus-circle" ></i> Tambah Baru</a></li>
                <li><a href="#" id="btn-filter"><i class="fa fa-filter" ></i> Filter</a></li>
                <li class="divider"></li>
                <li><a href="penjualan/filter-by-status/draft">Draft</a></li>
                <li><a href="penjualan/filter-by-status/open">Open</a></li>
                <li><a href="penjualan/filter-by-status/validated">Validated</a></li>
              </ul>
            </div> -->

            <a class="btn btn-primary" href="penjualan/cetak-filter" id="btn-cetak" ><i class="fa fa-print" ></i> Cetak</a>
            <a class="btn btn-success" href="penjualan/cetak-filter-detail" id="btn-cetak-detail" ><i class="fa fa-print" ></i> Cetak Detail</a>
            <a class="btn btn-danger" href="penjualan"  ><i class="fa fa-close" ></i> Close</a>

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
                            <label class="">{{count($data)}}</label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box-body">
            <form name="form_filter_cetak" method="POST" action="penjualan/cetak-filter" target="_blank">
                <table class="table table-condensed no-border" >
                    <tbody>
                        <tr>
                            <td class="col-xs-2">
                                <label>Tanggal</label>
                            </td>
                            <td class="col-xs-4" >
                                <div class="row" >
                                    <div class="col-xs-6" >
                                        <input type="text" class="form-control" name="tanggal_awal" readonly="readonly" value="{{$filter_tanggal_awal != '' ? $filter_tanggal_awal : '-' }}"  >
                                    </div>
                                    <div class="col-xs-6" >
                                        <input type="text" class="form-control" name="tanggal_akhir" readonly="readonly" value="{{$filter_tanggal_akhir != '' ? $filter_tanggal_akhir : '-'}}"  >
                                    </div>
                                </div>
                            </td>
                            <td class="col-xs-2">
                                <label>Customer</label>
                            </td>
                            <td class="col-xs-4" >
                                <input type="text" class="form-control" name="customer" value="{{$filter_customer ? $filter_customer->nama : '-'}}"  readonly>
                                <input type="hidden" class="form-control" name="customer_id" value="{{$filter_customer ? $filter_customer->id : '-'}}"  readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Pekerjaan</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="pekerjaan" value="{{$filter_pekerjaan ? $filter_pekerjaan->nama : '-'}}" readonly>
                                <input type="hidden" class="form-control" name="pekerjaan_id" value="{{$filter_pekerjaan ? $filter_pekerjaan->id : '-'}}" readonly>
                            </td>
                            <td>
                                <label>Status</label>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="status" value="{{$filter_status ? $filter_status : '-'}}" readonly>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th style="width:25px;" class="text-center">
                            <input type="checkbox" name="ck_all" style="margin-left:15px;padding:0;"  >
                        </th>
                        <th>Tanggal</th>
                        <th>Nomor<br/>Penjualan</th>
                        <th>Customer</th>
                        <th>Pekerjaan</th>
                        <th style="width: 75px;" class="text-center" >Qty <br/> Order</th>
                        <th>Status</th>
                        <th class="fit" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr  data-id="{{$dt->id}}">
                        <td class="text-center" >
                            @if($dt->status == 'OPEN')
                                <input type="checkbox" class="ck_row" >
                            @endif
                        </td>
                        <td class="text-center" >
                            {{$dt->tanggal_format}}
                        </td>
                        <td class="text-center" >
                            {{$dt->order_number}}
                        </td>
                        <td class="" >
                            {{$dt->nama_customer}}
                        </td>
                        <td class="{{$dt->is_direct_sales == 'Y' ? 'text-center':''}}" >
                            @if($dt->is_direct_sales == 'N')
                                {{$dt->nama_pekerjaan}}
                            @else
                                <label class="label label-success" >DIRECT SALES</label>
                            @endif
                        </td>
                        <td class="text-right" >
                            {{$dt->qty}}
                        </td>
                        <td class="text-center" >
                            @if($dt->status == 'OPEN')
                                <label class="label label-warning" >OPEN</label>
                            @elseif($dt->status =='VALIDATED')
                                <label class="label label-primary" >VALIDATED</label>
                            @elseif($dt->status =='DONE')
                                <label class="label label-success" >DONE</label>
                            @elseif($dt->status =='CANCELED')
                                <label class="label label-danger" >CANCELED</label>
                            @endif
                        </td>
                        <td class="text-center fit" >
                            <a class="btn btn-primary btn-xs" href="penjualan/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

{{-- MODAL QUICK EDIT --}}
<div class="modal" id="modal-filter" >
    <div class="modal-dialog">
      <div class="modal-content">
        <form name="form_filter" action="penjualan/filter" method="POST" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Filter</h4>
          </div>
          <div class="modal-body">
            <table class="table table-condensed no-border" >
              <tbody>
                <tr>
                    <td class="col-xs-3">
                        <label>
                            <input type="checkbox" name="ck_tanggal"> Tanggal
                        </label>
                    </td>
                    <td>
                      <div class="row" >
                          <div class="col-xs-6" >
                              <input type="text" class="form-control" name="tanggal_awal">
                          </div>
                          <div class="col-xs-6" >
                              <input type="text" class="form-control" name="tanggal_akhir" readonly>
                          </div>
                      </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="ck_customer"> Customer
                        </label>
                    </td>
                    <td>
                        {!! Form::select('customer',$select_customer,null,['class'=>'form-control select2']) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="ck_pekerjaan"> Pekerjaan
                        </label>
                    </td>
                    <td>
                        {!! Form::select('pekerjaan',$select_pekerjaan,null,['class'=>'form-control select2']) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="ck_status"> Status
                        </label>
                    </td>
                    <td>
                        {!! Form::select('status',['DRAFT'=>'DRAFT','OPEN'=>'OPEN','VALIDATED'=>'VALIDATED','DONE'=>'DONE'],null,['class'=>'form-control']) !!}
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/select2/select2.full.min.js"></script>

<script type="text/javascript">
(function ($) {

   

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
            var dataid = [];
            $('input.ck_row:checked').each(function(i){
                var data_id = $(this).parent().parent().data('id');
                // alert(data_id);
                var newdata = {"id":data_id}
                dataid.push(newdata);
            });

            var deleteForm = $('<form>').attr('method','POST').attr('action','penjualan/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            $('body').append(deleteForm);
            deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });


    // FILTER DATA
    // TAMPILKAN MODAL FILTER
    $('#btn-filter').click(function(){
        $('#modal-filter').modal('show');

        return false;
    });

    // MODAL FILTER CLOSED
    $('#modal-filter').on('hidden.bs.modal', function () {
        // clear input
        $(this).find('form').find('input:text').val('');
        $(this).find('form').find('input:checkbox').removeAttr('checked');
        $(this).find('form').find('select').val([]);
        $(this).find('form').find('select[name=customer]').select2('val','');
        $(this).find('form').find('select[name=pekerjaan]').select2('val','');

        // // disable all input
        // $(this).find('form').find('input:text').attr('disabled','disabled');
        // $(this).find('form').find('select').attr('disabled','disabled');

        // // disable select2
        // $(this).find('form').find('select.select2').prop('disabled',true);;
    });

    // CETAK DATA FILTER
    $('#btn-cetak').click(function(){
        // change action url
        $('form[name=form_filter_cetak]').attr('action','penjualan/cetak-filter');
        $('form[name=form_filter_cetak]').submit();

        return false;
    });

    // CETAK DATA FILTER
    $('#btn-cetak-detail').click(function(){
        // change action url
        $('form[name=form_filter_cetak]').attr('action','penjualan/cetak-filter-detail');
        $('form[name=form_filter_cetak]').submit();

        return false;
    });
    

})(jQuery);
</script>
@append