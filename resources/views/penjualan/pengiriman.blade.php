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

   
    span.select2-selection.select2-selection--single.select-clear .select2-selection__arrow{
        visibility: hidden;
    }

    #table-product thead tr th{
        text-align: center;
    }

    #table-data-master tbody tr td{
        margin:0;
        padding: 0;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="penjualan" >Pesanan Penjualan</a> 
        <i class="fa fa-angle-double-right" ></i> 
        <a href="penjualan/edit/{{$data_penjualan->id}}" >{{$data_penjualan->order_number}}</a> 
        <i class="fa fa-angle-double-right" ></i> 
        Pengiriman
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >Pengiriman</h3></label>
            
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$status_pengiriman == 'DONE' ? 'bg-blue' : 'bg-gray'}}" >DONE</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$status_pengiriman == 'VALIDATED' ? 'bg-blue' : 'bg-gray'}}" >VALIDATED</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$status_pengiriman == 'OPEN' ? 'bg-blue' : 'bg-gray'}}" >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$status_pengiriman == 'DRAFT' ? 'bg-blue' : 'bg-gray'}}"  >DRAFT</a>
        </div>
        <div class="box-body">
            <input type="hidden" name="penjualan_id" value="{{$data_penjualan->id}}">
            <input type="hidden" name="data_penjualan" value="{{json_encode($data_penjualan)}}">
            <input type="hidden" name="data_pengiriman" value="{{json_encode($data)}}">

            <table class="table table-condensed no-border table-master-header" id="table-data-master" >
                <tbody>
                    <tr>
                        <td class="col-lg-2">
                            <label>Nomor Penjualan</label>
                        </td>
                        <td class="col-lg-4" >
                            {{$data_penjualan->order_number}}
                        </td>
                        <td class="col-lg-2" >
                            <label>Tanggal Order</label>
                        </td>
                        <td class="col-lg-4 label-tanggal" >
                            {{$data_penjualan->tanggal_format}}
                        </td>
                    </tr>
                    <tr>
                        <td class="col-lg-2">
                            <label>Customer</label>
                        </td>
                        <td class="col-lg-4 lbl-customer" >
                            {{$data_penjualan->nama_customer}}
                        </td>
                        <td>
                            <label>Pekerjaan</label>
                        </td>
                        <td class="lbl-pekerjaan" >
                            {{$data_penjualan->nama_pekerjaan}}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <label>Alamat</label>
                        </td>
                        <td class="lbl-alamat" >
                            {{$data_pekerjaan->alamat . ', ' . $data_pekerjaan->desa . ', ' . $data_pekerjaan->kecamatan . ', ' . $data_pekerjaan->kabupaten . ', ' . $data_pekerjaan->provinsi}}
                        </td>
                    </tr>
                </tbody>
            </table>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th style="width:25px;"  >NO</th>
                        <th>Nomor Pengiriman</th>
                        <th>Material</th>
                        <th class="col-sm-2" >Lokasi Galian</th>
                        <th style="width: 120px;" >Tanggal Pengiriman</th>
                        <th class="col-sm-3" >Driver</th>
                        <th style="width: 120px;" >Nopol</th>
                        <th style="width: 75px;" >Surat Jalan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownum=1; ?>
                    @foreach($data as $dt)
                        <tr data-id="{{$dt->id}}" class="row-data-product" >
                            <td class="text-center" >
                                {{$rownum++}}
                            </td>
                            <td class="text-center"  >
                                {{$dt->kode_pengiriman}}
                            </td>
                            <td>
                                {{$dt->material}}
                            </td>
                            <td>
                                {!! Form::select('lokasi_galian',$select_lokasi_galian,$dt->lokasi_galian_id,['class'=>'form-control select2','style'=>'width:100%;']) !!}
                            </td>
                            <td>
                                <input type="text" name="tanggal_kirim" class="form-control input-tanggal text-center" value="{{$dt->tanggal_format}}">
                            </td>
                            <td>
                                {!! Form::select('driver',$select_driver,$dt->karyawan_id,['class'=>'form-control select2','style'=>'width:100%;']) !!}
                            </td>
                            <td>
                                {!! Form::select('nopol',$select_nopol,$dt->karyawan_id,['class'=>'form-control select2','disabled','style'=>'width:100%;']) !!}
                            </td>
                            <td class="text-center" >
                                @if($dt->status == 'OPEN')
                                    <a class="btn btn-success btn-xs btn-surat-jalan" ><i class="fa fa-newspaper-o" ></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" href="penjualan/edit/{{$data_penjualan->id}}" ><i class="fa fa-close" ></i> Close</a>
            
            @if($can_validate)
                <a class="btn btn-success pull-right"  id="btn-validate" href="penjualan/validate-pengiriman/{{$data_penjualan->id}}" ><i class="fa fa-check" ></i> Validate</a>
            @endif
        </div>
    </div><!-- /.box -->

    <div class="example-modal">
    <div class="modal" id="modal-surat-jalan">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Surat Jalan</h4>
          </div>
            <div class="modal-body">
                <!-- info row -->
                      <div class="row invoice-info">
                        <div class="col-sm-8 invoice-col">
                          Kepada Yth.
                          <address>
                            <strong class="modal-lbl-customer">Admin, Inc.</strong><br>
                            <b>Pekerjaan :</b> <span class="modal-lbl-pekerjaan" ></span> <br/>
                            <span class="modal-lbl-alamat" ></span>
                            {{-- Phone: (804) 123-5432<br> --}}
                          </address>
                        </div><!-- /.col -->
                        
                        <div class="col-sm-4 invoice-col">
                          <b class="modal-lbl-nomor-invoice">Nomor <span class="modal-lbl-nomor-pengiriman" ></span> </b><br>
                          <b>Tanggal :</b> <span class="modal-lbl-tanggal" ></span><br>
                          <b>Nopol :</b> <span class="modal-lbl-nopol" ></span><br>
                          <b>Driver :</b> <span class="modal-lbl-driver" ></span><br>
                        </div><!-- /.col -->
                      </div><!-- /.row -->

                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table-responsive">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Material</th>
                                <th class="col-xs-1">Quantity</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="modal-lbl-material" ></td>
                                <td class="text-right" >1</td>
                              </tr>
                            </tbody>
                          </table>
                        </div><!-- /.col -->
                      </div><!-- /.row -->
              </div>
              <div class="modal-footer">
                <a class="btn btn-primary pull-left" ><i class="fa fa-print" ></i> Print</a>
                <a class="btn btn-success pull-left" ><i class="fa fa-file-pdf-o" ></i> Generate PDF</a>
                <a class="btn btn-default pull-left" ><i class="fa fa-copy" ></i> Generate PDF & Copy</a>
                <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
              </div>
          
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
<!-- /.modal -->
</div>

</section><!-- /.content -->


@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/select2/dist/js/select2.full.min.js"></script>

<script type="text/javascript">
(function ($) {
    var data_pengiriman = JSON.parse($('input[name=data_pengiriman]').val());
    var data_penjualan = JSON.parse($('input[name=data_penjualan]').val());

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: data_penjualan.tanggal_format
    });
    // END OF SET DATEPICKER

    // $('select[name=lokasi_galian]').val([]);
    // $('select[name=driver]').val([]);
    // $('select[name=nopol]').val([]);

    // periksa data sudahs terisi atau belum
    function checkInitData(){
        $.each(data_pengiriman,function(){
            if($(this)[0].karyawan_id == null){
                var aRow = $('tr[data-id=' + $(this)[0].id + ']');
                aRow.find('select[name=driver]').val([]);
                aRow.find('select[name=nopol]').val([]);
            };

            if($(this)[0].lokasi_galian_id == null){
                var aRow = $('tr[data-id=' + $(this)[0].id + ']');
                aRow.find('select[name=lokasi_galian]').val([]);
            };
        });
    }
    checkInitData();

    $('.select2').select2({ containerCssClass : "select-clear" });

    $('select[name=driver]').change(function(){
        // alert($(this).val());
        $(this).parent().next().children('select').val($(this).val()).change();
    });

    // SAVE PENGIRIMAN
    $('#btn-save').click(function(){
        var master = {"penjualan_id":$('input[name=penjualan_id]').val()};

        var detail = JSON.parse('{"detail" : [] }');

        $('.row-data-product').each(function(){
            var row  = $(this);
            var tanggal  = row.find('input[name=tanggal_kirim]').val();
            var lokasi_galian  = row.find('select[name=lokasi_galian]').val();
            var driver  = row.find('select[name=driver]').val();

            detail.detail.push({
                'pengiriman_id' : row.data('id'),
                'tanggal' : tanggal,
                'lokasi_galian' : lokasi_galian,
                'driver' : driver
            });
        });

        var newform = $('<form>').attr('method','POST').attr('action','penjualan/update/pengiriman');
        newform.append($('<input>').attr('type','hidden').attr('name','master').val(JSON.stringify(master)));
        newform.append($('<input>').attr('type','hidden').attr('name','detail').val(JSON.stringify(detail)));
        $('body').append(newform);
        newform.submit();

        return false;
    });

    // CETAK SURAT JALAN
    $('.btn-surat-jalan').click(function(){
        var aRow = $(this).parent().parent();
        var pengirimanId = aRow.data('id');
        var alamat = $('.lbl-alamat').text();
        var customer = $('.lbl-customer').text();
        var pekerjaan = $('.lbl-pekerjaan').text();
        var tanggal = $('.lbl-tanggal').text();
        var material = aRow.children('td:first').next().next().text();
        var nomor_pengiriman = aRow.children('td:first').next().text();
        var driver = aRow.children('td:first').next().next().next().next().next().children('select:first').find('option:selected').text();
        var nopol = aRow.children('td:first').next().next().next().next().next().next().children('select:first').find('option:selected').text();

        // set data ke modal
        $('.modal-lbl-customer').text(customer);
        $('.modal-lbl-pekerjaan').text(pekerjaan);
        $('.modal-lbl-alamat').text(alamat);
        $('.modal-lbl-tanggal').text(tanggal);
        $('.modal-lbl-nopol').text(nopol);
        $('.modal-lbl-driver').text(driver);
        $('.modal-lbl-material').text(material);

        $('#modal-surat-jalan').modal('show');

        return false;
    });
    
})(jQuery);
</script>
@append