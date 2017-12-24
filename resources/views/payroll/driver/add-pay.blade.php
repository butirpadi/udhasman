@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/dist/css/select2.min.css">
<style>
    .autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
    .autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
    .autocomplete-selected { background: #FFE291; }
    .autocomplete-suggestions strong { font-weight: normal; color: red; }
    .autocomplete-group { padding: 2px 5px; }
    .autocomplete-group strong { display: block; border-bottom: 1px solid #000; }

    .table-row-mid > tbody > tr > td {
        vertical-align:middle;
    }

    input.input-clear {
        display: block;
        padding: 0;
        margin: 0;
        border: 0;
        width: 100%;
        background-color:#EEF0F0;
        float:right;
        padding-right: 5px;
        padding-left: 5px;
    }

    /*#table-payroll tr th{
      text-align: center;
    }*/

    .col-first{
        border-right: solid #F4F4F4 2px;
    }

    .form-horizontal .form-group .control-label{
      text-align: left;
    }

    .table-data thead tr th small{
      font-size:0.5em;
    }


</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="payroll/payroll-driver" >Payroll Option</a>
        <i class="fa fa-angle-double-right" ></i>
        <a href="payroll/payroll-driver/show-payroll-table/{{$tanggal_gaji}}/ST" >Payroll Driver</a>
        <i class="fa fa-angle-double-right" ></i>
        Edit Payroll
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >New</h4></label> --}}
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >Payroll Driver</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Paid</a>
            
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Draft</a>
        </div>
        <div class="box-body">
          <div class="row" >
            <div class="col-sm-6" >
              <div class="form-horizontal" >
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Kode Karyawan</label>
                  <div class="col-sm-8">
                    <input type="hidden" name="karyawan_id" value="{{$data->id}}">
                    <input type="hidden" name="total_pagi" value="{{$data->total_hadir_pagi}}">
                    <input type="hidden" name="total_siang" value="{{$data->total_hadir_siang}}">
                    <input type="text" readonly  name="kode" class="form-control" value="{{$data->kode}}">
                  </div>
                </div>
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Nama</label>
                  <div class="col-sm-8">
                    <input type="text" readonly  name="nama" class="form-control" value="{{$data->nama}}">
                  </div>
                </div>
                
                
              </div>
            </div>
            <div class="col-sm-6" >
              <div class="form-horizontal" >
                <div class="form-group">
                  <label  class="col-sm-4 control-label">Periode Pembayaran</label>
                  <div class="col-sm-8">
                    <input type="text" readonly  name="periode_pembayaran" class="form-control" value="{{$tanggal_gaji}}">
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div><!-- /.box-body -->
        <div class="box-footer" >
          <a class="btn btn-primary" id="btn-save-payroll" ><i class="fa fa-save" ></i> Save</a>
          
          <a class="btn btn-danger" href="payroll/payroll-driver/show-payroll-table/{{$tanggal_gaji}}" ><i class="fa fa-close" ></i> Close</a>
        </div>
        
    </div><!-- /.box -->


</section><!-- /.content -->

<!-- /.modal -->
</div>

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<!-- Select2 -->
    <script src="plugins/select2/dist/js/select2.full.min.js"></script>



<script type="text/javascript">
(function ($) {
  $('.uang').autoNumeric('init',{
      vMin:'0',
      vMax:'9999999999'
  });

  $('.uang').each(function(){
    $(this).autoNumeric('set',$(this).autoNumeric('get'));
  });
  // INPUT POTONGAN ON KEYUP
  $('input[name=potongan]').keyup(function(){
    // calculate
    var jumlah_gaji = $('input[name=jumlah_gaji]').autoNumeric('get');
    var potongan = $(this).autoNumeric('get');
    var total_gaji = jumlah_gaji - potongan;
    $('input[name=gaji_bersih]').autoNumeric('set',total_gaji);
  });

  // SAVE PAYROLL
  $('#btn-save-payroll').click(function(){
    var karyawan_id = $('input[name=karyawan_id]').val();
    var total_pagi = $('input[name=total_pagi]').val();
    var total_siang = $('input[name=total_siang]').val();
    var basic_pay = $('input[name=gaji_pokok]').autoNumeric('get');
    var potongan = $('input[name=potongan]').autoNumeric('get');
    var pay_date = $('input[name=periode_pembayaran]').val();

    var newform = $('<form>').attr('method','POST').attr('action','payroll/payroll-driver/insert-pay');
    newform.append($('<input>').attr('type','hidden').attr('name','karyawan_id').val(karyawan_id));
    newform.append($('<input>').attr('type','hidden').attr('name','total_pagi').val(total_pagi));
    newform.append($('<input>').attr('type','hidden').attr('name','total_siang').val(total_siang));
    newform.append($('<input>').attr('type','hidden').attr('name','basic_pay').val(basic_pay));
    newform.append($('<input>').attr('type','hidden').attr('name','potongan').val(potongan));
    newform.append($('<input>').attr('type','hidden').attr('name','pay_date').val(pay_date));
    newform.submit();

  });

})(jQuery);
</script>
@append
