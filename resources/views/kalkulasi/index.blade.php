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

    .modal-batch-edit{
        width: 500px;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Kalkulasi Pengiriman
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
                <li><a id="btn-batch-edit-by-pekerjaan" href="#" ><i class="fa fa-th" ></i> Batch Edit by Pekerjaan</a></li>
                <li class="divider"></li>
                <li><a href="kalkulasi/filter-by-status/draft">Draft</a></li>
                <li><a href="kalkulasi/filter-by-status/open">Open</a></li>
                <li><a href="kalkulasi/filter-by-status/validated">Validated</a></li>
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
                            <label class="">{{count($data_kalkulasi)}}</label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box-body">
           {{--  <div class="text-center" >
                <label>Filter : </label> 
            <a class="btn btn-default btn-xs" > DRAFT</a>
                <a class="btn btn-warning btn-xs" > OPEN</a>
                <a class="btn btn-primary btn-xs" > VALIDATED</a>
                <a class="btn btn-success btn-xs" > DONE</a>
                <a class="btn btn-danger btn-xs" > CANCELED</a>  
            </div> --}}
            
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th>
                            Nomor<br/>Pengiriman
                        </th>
                        <th>
                            Customer 
                        </th>
                        <th>
                            Pekerjaan
                        </th>
                        <th>
                            Tanggal<br/>Pengiriman
                        </th>
                        <th>
                            Driver
                        </th>
                        <th>
                            Nopol
                        </th>
                        <th>MATERIAL</th>
                        <th>
                            Status
                        </th>
                        <th class="fit"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data_kalkulasi as $dt)
                        <tr>
                            <td class="text-center" >
                                {{$dt->kode_pengiriman}}
                            </td>
                            <td>
                                {{$dt->nama_customer}}
                            </td>
                            <td>
                                {{$dt->nama_pekerjaan}}
                            </td>
                            <td class="text-center" >
                                {{$dt->tanggal_pengiriman_format}}
                            </td>
                            <td>
                                {{$dt->karyawan}}
                            </td>
                            <td class="text-center">
                                {{$dt->nopol}}
                            </td>
                            <td class="text-center">
                                {{$dt->nama_material}}
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
                                    <a class="btn btn-primary btn-xs" href="kalkulasi/st-draft/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                @elseif($dt->status == 'OPEN')
                                    <a class="btn btn-primary btn-xs" href="kalkulasi/st-open/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                @elseif($dt->status =='VALIDATED')
                                    <a class="btn btn-primary btn-xs" href="kalkulasi/st-validated/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                @elseif($dt->status =='DONE')
                                    <a class="btn btn-primary btn-xs" ><i class="fa fa-edit" ></i></a>
                                @elseif($dt->status =='CANCELED')
                                    <a class="btn btn-primary btn-xs" href="kalkulasi/st-canceled/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

{{-- MODAL BATCH EDIT --}}
<div class="modal" id="modal-batch-edit" >
    <div class="modal-dialog modal-batch-edit">
      <div class="modal-content">
        <form name="form_batch_edit_pengiriman" action="kalkulasi/batch-edit" method="POST" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Batch Edit</h4>
          </div>
          <div class="modal-body">
            <table class="table table-condensed" >
              <tbody>
                <tr>
                  <td class="col-xs-3"><label>Driver</label></td>
                  <td>
                      {!! Form::select('select_driver',$select_driver,null,['class'=>'form-control','required','style'=>'width:100%;']) !!}
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

  {{-- MODAL BATCH EDIT --}}
<div class="modal" id="modal-batch-edit-by-pekerjaan" >
    <div class="modal-dialog modal-batch-edit-by-pekerjaan">
      <div class="modal-content">
        <form name="form_batch_edit_pengiriman" action="kalkulasi/batch-edit-by-pekerjaan" method="POST" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Batch Edit by Pekerjaan</h4>
          </div>
          <div class="modal-body">
            <table class="table table-condensed" >
              <tbody>
                <tr>
                  <td class="col-xs-3"><label>Pekerjaan</label></td>
                  <td>
                      {!! Form::select('select_pekerjaan',$select_pekerjaan,null,['class'=>'form-control','required','style'=>'width:100%;']) !!}
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

    // FORMAT SELECT2
    $('select[name=select_driver]').select2();
    $('select[name=select_pekerjaan]').select2();

    // BATCH EDIT CLICK
    // TAMPILKAN MODAL BATCH EDIT
    $('#btn-batch-edit').click(function(){
        $('select[name=select_driver]').select2('val','');
        $('#modal-batch-edit').modal('show');

        return false;
    });

    // TAMPILKAN MODAL BATCH EDIT by PEKERJAAN
    $('#btn-batch-edit-by-pekerjaan').click(function(){
        $('select[name=select_pekerjaan]').select2('val','');
        $('#modal-batch-edit-by-pekerjaan').modal('show');
        return false;
    });

})(jQuery);
</script>
@append