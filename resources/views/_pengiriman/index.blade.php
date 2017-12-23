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
        Pengiriman
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <div class="btn-group">
              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-wrench" ></i>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a id="btn-batch-edit" href="#" ><i class="fa fa-th" ></i> Batch Edit</a></li>
                <li><a href="#"><i class="fa fa-filter" ></i> Filter</a></li>
              </ul>
            </div>

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
                            <label class="">{{count($data_pengiriman)}}</label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box-body">
            {{-- <div class="text-center" >
                <label>Filter : </label> 
            <a class="btn btn-default btn-xs" > DRAFT</a>
                <a class="btn btn-warning btn-xs" > OPEN</a>
                <a class="btn btn-primary btn-xs" > VALIDATED</a>
                <a class="btn btn-success btn-xs" > DONE</a>
                <a class="btn btn-danger btn-xs" > CANCELED</a>  
            </div>
 --}}
            <table class="table table-bordered table-condensed table-striped" id="table-data" >
                <thead>
                    <tr>
                        <th>Nomor<br/>Pengiriman</th>
                        <th>Nomor<br/>Penjualan</th>
                        <th>Customer</th>
                        <th>Pekerjaan</th>
                        <th>Tanggal<br/>Pengiriman</th>
                        <th>Material</th>
                        <!-- <th>Lokasi<br/>Galian</th> -->
                        <!-- <th>Driver</th> -->
                        <th>Nopol</th>
                        <th>Status</th>
                        <th class="fit" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data_pengiriman as $dt)
                    <tr  data-id="{{$dt->id}}">
                        <td class="text-center" >
                            {{$dt->kode_pengiriman}}
                        </td>
                        <td class="text-center" >
                            {{$dt->order_number}}
                        </td>
                        <td class="" >
                            {{$dt->nama_customer}}
                        </td>
                        <td class="" >
                            {{$dt->nama_pekerjaan}}
                        </td>
                        <td class="text-center" >
                            {{$dt->tanggal_format}}
                        </td>
                        <td class="" >
                            {{$dt->material}}
                        </td>
                       <!--  <td class="" >
                            {{$dt->lokasi_galian}}
                        </td> -->
                        <!-- <td class="" >
                            {{$dt->karyawan}}
                        </td> -->
                        <td class="text-center" >
                            {{$dt->nopol}}
                        </td>
                        <td class="text-center" >
                             @if($dt->status == 'DRAFT')
                                <label class="label label-default" >DRAFT</label>
                            @elseif($dt->status == 'OPEN')
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
                            @if($dt->status == 'DRAFT')
                                <a class="btn btn-primary btn-xs" href="pengiriman/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            @elseif($dt->status == 'OPEN')
                                <a class="btn btn-primary btn-xs" href="pengiriman/open/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            @elseif($dt->status == 'VALIDATED')
                                <a class="btn btn-primary btn-xs" href="pengiriman/view-validated/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            @elseif($dt->status == 'DONE')
                                <a class="btn btn-primary btn-xs" href="pengiriman/view-done/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            @elseif($dt->status == 'CANCELED')
                                <a class="btn btn-primary btn-xs" href="pengiriman/view-canceled/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

{{-- MODAL QUICK EDIT --}}
<div class="modal" id="modal-batch-edit" >
    <div class="modal-dialog">
      <div class="modal-content">
        <form name="form_batch_edit_pengiriman" action="pengiriman/batch-edit" method="POST" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Batch Edit</h4>
          </div>
          <div class="modal-body">
            <table class="table table-condensed" >
              <tbody>
                <tr>
                  <td class="col-xs-3"><label>Nomor Penjualan</label></td>
                  <td>
                      {!! Form::select('nomor_penjualan',$select_nomor_penjualan,null,['class'=>'form-control','required']) !!}
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

<script type="text/javascript">
(function ($) {

    var TBL_DATA = $('#table-data').DataTable({
        sort:false
    });

    // BATCH EDIT
    $('#btn-batch-edit').click(function(){
        $('select[name=nomor_penjualan]').val([]);
        $('#modal-batch-edit').modal('show');

        return false;
    });

})(jQuery);
</script>
@append