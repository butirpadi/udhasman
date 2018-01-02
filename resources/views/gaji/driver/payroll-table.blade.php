@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }

    dl.dl-horizontal dt{
        text-align: left;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="gaji/driver" >Payroll Driver</a> 
        <i class="fa fa-angle-double-right" ></i> 
        {{$tanggal_penggajian}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$tanggal_penggajian}}</h3></label>
        </div>
        <div class="box-body">
            

            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped  table-data" id="table-payroll" >
                <thead>
                    <tr>
                        <th>Ref#</th>
                        <th>Tanggal Pembayaran</th>
                        <th class="col-sm-2 col-md-2 col-lg-2" >Kode Karyawan</th>
                        <th>Nama</th>
                        {{-- <th>Total Gaji</th> --}}
                        <th>Status</th>
                        <th class="col-sm-2 col-md-2 col-lg-2" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                        <td class="text-center" >
                            @if($dt->payroll)
                                {{$dt->payroll->payroll_number}}
                            @endif
                        </td>
                        <td class="text-center" >
                            @if($dt->payroll)
                                {{$dt->payroll->payment_date_formatted}}
                            @endif
                        </td>
                        <td class="text-center" >
                            {{$dt->kode}}
                        </td>
                        <td class="row-to-edit" >
                            {{$dt->nama}}
                        </td>
                        {{-- <td class="uang text-right" >
                            {{$dt->gaji_pokok * (($dt->total_pagi + $dt->total_siang)/2)}}
                        </td> --}}
                        <td class="text-center" >
                            @if($dt->payroll)
                                @if($dt->payroll->status == 'P')
                                    <label class="label label-success">PAID</label>
                                @elseif($dt->payroll->status == 'O')
                                    <label class="label label-primary">OPEN</label>
                                @else
                                    <label class="label label-warning">DRAFT</label>
                                @endif

                            @else
                                <label class="label label-warning">DRAFT</label>
                            @endif
                        </td>
                        <td class="text-center" >
                            @if($dt->payroll)
                                    <a class="btn btn-success btn-xs" href="gaji/driver/edit-pay/{{$dt->payroll->id}}" data-toggle="tooltip" data-title="Edit" ><i class="fa fa-edit" ></i></a>
                                <!-- @if($dt->payroll->status != 'P')
                                    <a class="btn btn-success btn-xs" href="gaji/driver/edit-pay/{{$dt->payroll->id}}" data-toggle="tooltip" data-title="Edit" ><i class="fa fa-edit" ></i></a>
                                @elseif($dt->payroll->status == 'O')
                                    <a class="btn btn-success btn-xs" href="gaji/driver/edit-pay/{{$dt->payroll->id}}" data-toggle="tooltip" data-title="Edit" ><i class="fa fa-edit" ></i></a>
                                @else
                                <a class="btn btn-primary btn-xs" href="gaji/driver/pay/{{$dt->id}}/{{$tanggal_penggajian}}" data-toggle="tooltip" data-title="Edit" ><i class="fa fa-edit" ></i></a>
                                    
                                @endif -->

                                <!-- @if($dt->payroll->status == 'P') -->
                                    <a target="_blank" class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Print PDF"  href="gaji/driver/print-pdf/{{$dt->payroll->id}}" ><i class="fa fa-file-pdf-o" ></i></a>
                                    <a target="_blank" class="btn btn-primary btn-xs" data-toggle="tooltip" data-title="Print & Copy" href="gaji/driver/print-copy/{{$dt->payroll->id}}" ><i class="fa fa-copy" ></i></a>
                                    <a target="_blank" class="btn btn-warning btn-xs" data-toggle="tooltip" data-title="Direct Print" href="gaji/driver/print-direct/{{$dt->payroll->id}}" ><i class="fa fa-print" ></i></a>
                                <!-- @endif -->
                            @else
                                <a class="btn btn-primary btn-xs" href="gaji/driver/pay/{{$dt->id}}/{{$tanggal_penggajian}}" ><i class="fa fa-edit" ></i></a>
                                
                            @endif

                        </td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="gaji/driver" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {
    // format table
    var TBL_KATEGORI = $('#table-payroll').DataTable({
        sort:false
    });

    // format auto numeric uang
  $('.uang').autoNumeric('init',{
      vMin:'0',
      vMax:'9999999999'
  });

  $('.uang').each(function(){
    $(this).autoNumeric('set',$(this).autoNumeric('get'));
  });


  // CHECK ALL
  $('input[name=ck_all]').change(function(){
    $('.ck_row').prop('checked',$(this).prop('checked'));
    $('.ck_row').trigger('change');
  });
  $('.ck_row').change(function(){
    if($('.ck_row:checked').length > 0){
        $('#btn-delete').removeClass('hide');
        $('#btn-delete').show();
    }else{
        $('#btn-delete').hide();
    };

    if($('.ck_row:checked').length < $('.ck_row').length){
        $('input[name=ck_all]').prop('checked',false);
    }

  });

  // delete payroll 
  $('#btn-delete').click(function(){
    // if(confirm('Anda akan menghapus data ini?')){

    // }

    $('.ck_row:checked').each(function(){
        var row = $(this).parent().parent();
        var payroll_id = row.data('id');
        $.get('payroll/driver/cancel-payroll/'+payroll_id,null,function(){
            row.fadeOut(250);
            row.remove();
        });
    });
  });

})(jQuery);
</script>
@append
