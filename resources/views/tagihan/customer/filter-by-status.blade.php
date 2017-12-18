@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/select2.min.css">

<style>
    /*#table-data > tbody > tr{
        cursor:pointer;
    }*/
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="tagihan-customer" >Data Tagihan Customer</a> <i class="fa fa-angle-double-right" ></i> 
        Status : {{ucwords($status)}}
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
                <li><a id="btn-cetak-tagihan" href="#" ><i class="fa fa-print" ></i> Cetak Rincian Tagihan</a></li>
                <li class="divider"></li>
                <li><a href="tagihan-customer/filter-by-status/draft">Draft</a></li>
                <li><a href="tagihan-customer/filter-by-status/open">Open</a></li>
                <li><a href="tagihan-customer/filter-by-status/validated">Validated</a></li>
              </ul>
            </div>
            {{-- <a class="btn btn-primary" ><i class="fa fa-download" ></i> Pembayaran</a> --}}

            <div class="pull-right" >
                <table style="background-color: #ECF0F5;" >
                    <tr>
                        <td class="bg-green text-center" rowspan="2" style="width: 50px;" ><i class="ft-rupiah" ></i></td>
                        <td style="padding-left: 10px;padding-right: 5px;">
                            {{strtoupper($status)}} TOTAL
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"  style="padding-right: 5px;  width: 150px;" >
                            <label class="uang">{{$total_tagihan}}</label>
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
            </div> --}}

            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th>Nomor<br/>Penjualan</th>
                        <th>Tanggal<br/>Order</th>
                        <th>Customer</th>
                        <th>Pekerjaan</th>
                        <th>Nomor<br/>Pengiriman</th>
                        <th>Tanggal<br/>Pengiriman</th>
                        <th>Material</th>
                        {{-- <th>Kalkulasi</th> --}}
                        <th>Total</th>
                        <th>Status</th>
                        <th class="fit" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data_tagihan as $dt)
                    <tr  data-id="{{$dt->id}}">
                        <td class="text-center" >
                            {{$dt->order_number}}
                        </td>
                        <td class="text-center" >
                            {{$dt->tanggal_order_format}}
                        </td>
                        <td class="" >
                            {{$dt->nama_customer}}
                        </td>
                        <td class="text-center" >
                            {{$dt->nama_pekerjaan}}
                        </td>
                        <td class="text-center" >
                            {{$dt->kode_pengiriman}}
                        </td>
                        <td class="text-center" >
                            {{$dt->tanggal_pengiriman_format}}
                        </td>
                        <td class="text-center" >
                            {{$dt->nama_material}}
                        </td>
                        {{-- <td class="text-center" >
                            {{$dt->kalkulasi}}
                        </td> --}}
                        <td class="text-right uang" >
                            {{$dt->total}}
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
                            {{-- @if($dt->status == 'DRAFT')
                                <a class="btn btn-primary btn-xs" href="pengiriman/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            @elseif($dt->status == 'OPEN')
                                <a class="btn btn-primary btn-xs" href="pengiriman/open/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            @elseif($dt->status == 'VALIDATED')
                                <a class="btn btn-primary btn-xs" href="pengiriman/view-validated/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            @elseif($dt->status == 'CANCELED')
                                <a class="btn btn-primary btn-xs" href="pengiriman/view-canceled/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                            @endif --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <div class="modal" id="modal-batch-edit" >
    <div class="modal-dialog">
      <div class="modal-content">
        <form name="form_batch_edit_tagihan" action="tagihan-customer/batch-edit" method="POST" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Batch Edit</h4>
          </div>
          <div class="modal-body">
            <table class="table table-condensed" >
              <tbody>
                <tr>
                  <td class="col-xs-3"><label>Customer</label></td>
                  <td>
                      {!! Form::select('customer',$select_customer,null,['class'=>'form-control','required','style'=>'width:100%;']) !!}
                  </td>
                </tr>
                <tr>
                    <td>
                        <label>Pekerjaan</label>
                    </td>
                    <td>
                       {!! Form::select('pekerjaan',[],null,['class'=>'form-control','required','style'=>'width:100%;']) !!} 
                    </td>
                </tr>
                
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">OK</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

</section><!-- /.content -->

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

    // select2
    $('select[name=customer]').val([]);
    $('select[name=customer]').select2();

    // select customer change, get pekerjaan
    $('select[name=customer]').change(function(){
        // get data pekerjaan
        var customer_id = $('select[name=customer]').val();
        $.get('tagihan-customer/get-pekerjaan/'+customer_id,function(res){
            var pekerjaan = JSON.parse(res);

            // clear select
            $('select[name=pekerjaan]').empty();

            $.each(pekerjaan,function(){
                $('select[name=pekerjaan]').append($('<option>', {value:$(this)[0].id, text:$(this)[0].nama}));

            });
            $('select[name=pekerjaan]').select2();
            $('select[name=pekerjaan]').select2('val','');
            // alert(pekerjaan.nama);
        });
    });

    var TBL_DATA = $('#table-data').DataTable({
        sort:false
    });

    // SET AUTONUMERIC
    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
        });

    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

    // BATCH EDIT
    $('#btn-batch-edit').click(function(){
        $('select[name=customer]').select2('val','');
        $('#modal-batch-edit').modal('show');

        return false;
    });

})(jQuery);
</script>
@append