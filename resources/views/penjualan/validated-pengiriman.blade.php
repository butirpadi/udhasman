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
            
            <table class="table table-condensed no-border table-master-header" >
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
                        <td class="col-lg-4" >
                            {{$data_penjualan->tanggal_format}}
                        </td>
                    </tr>
                    <tr>
                        <td class="col-lg-2">
                            <label>Customer</label>
                        </td>
                        <td class="col-lg-4" >
                            {{$data_penjualan->nama_customer}}
                        </td>
                        <td>
                            <label>Pekerjaan</label>
                        </td>
                        <td>
                            {{$data_penjualan->nama_pekerjaan}}
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
                        <th >Lokasi Galian</th>
                        <th>Tanggal Pengiriman</th>
                        <th>Driver</th>
                        <th>Nopol</th>
                        <th >Kalkulasi</th>
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
                                {!! $dt->lokasi_galian !!}
                            </td>
                            <td class="text-center">
                                {{$dt->tanggal_format}}
                            </td>
                            <td>
                                {!! $dt->karyawan !!}
                            </td>
                            <td>
                                {!! $dt->nopol !!}
                            </td>
                            <td class="text-center" >
                                <a class="btn btn-success btn-xs btn-input-kalkulasi" ><i class="fa fa-balance-scale" ></i></a>
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" id="btn-cancel-save" href="penjualan/edit/{{$data_penjualan->id}}" ><i class="fa fa-close" ></i> Close</a>
            
        </div>
    </div><!-- /.box -->

</div>

</section><!-- /.content -->

<div class="example-modal">
    <div class="modal" id="modal-validate-pengiriman">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button> --}}
            <h4 class="modal-title">Validate Delivery Order</h4>
          </div>
          <form name="form_validate_pengiriman" method="POST" action="delivery/order/to-validate" >
            <input type="hidden" name="pengiriman_id" value=""  >
            <div class="modal-body">
                <table class="table table-bordered table-condensed" id="table-kalkulasi" >
                    <tbody>
                        <tr>
                            <td><label>No Nota</label></td>
                            <td>
                                <input type="text" autocomplete="off" name="no_nota_timbang" class="form-control" value="CUST/" >
                            </td>
                        </tr>
                        <tr>
                            <td><label>Kalkulasi</label></td>
                            <td>
                                <select name="kalkulasi" class="form-control" >
                                    <option value="R" >Ritase</option>
                                    <option value="K" >Kubikasi</option>
                                    <option value="T" >Tonase</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td>
                                <label>Panjang</label>
                                </td>
                            <td>
                                <input type="text" name="panjang" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td><label>Lebar</label></td>
                            <td>
                                <input type="text" name="lebar" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td><label>Tinggi</label></td>
                            <td>
                                <input type="text" name="tinggi" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td><label>Volume</label></td>
                            <td>
                                <input type="text" name="volume" class="form-control text-right " disabled>
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td><label>Gross</label></td>
                            <td>
                                <input type="text" name="gross" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td><label>Tarre</label></td>
                            <td>
                                <input type="text" name="tarre" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td><label>Netto</label></td>
                            <td>
                                <input type="text" name="netto" class="form-control text-right" disabled>
                            </td>
                        </tr>
                        <tr class="row-price" >
                            <td>
                                <label>Unit Price</label>
                            </td>
                            <td>
                                <input type="text" name="unit_price" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-price" >
                            <td>
                                <label>Total</label>
                            </td>
                            <td>
                                <input type="text" name="total" class="form-control text-right" disabled>
                            </td>
                        </tr>

                    </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" ></i> Save</button>
                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
              </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
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

    // TAMPILKAN FORM PERHITUNGAN / KALKULASI BOBOT PENGIRIMAN
    $('.btn-input-kalkulasi').click(function(){
        clearFormKalkulasi();

        var aRow = $(this).parent().parent();

        var nomor_pengiriman = aRow.children('td:first').next().text();

        // set pengiriman id
         $('input[name=pengiriman_id]').val(aRow.data('id'));

         // set nomor norta timbang
         $('input[name=no_nota_timbang]').val('CUST/'+ $.trim(nomor_pengiriman));

         // tampilkan form kalkulasi
        $('#modal-validate-pengiriman').modal('show');
        return false;
    });

    // ===================================================================

    // KALKULASI 

    // CLEAR KALKULASI FORM
    function clearFormKalkulasi(){
        // HIDE SOME ELEMENT
        $('.row-tonase, .row-kubikasi, .row-price').hide();
        $('select[name=kalkulasi]').val([]);
        $('input[name=pengiriman_id]').val('');
        $('form[name=form_validate_pengiriman]').find('input:text').val('');
    }
    clearFormKalkulasi();

    // KALKULASI DO
    $('select[name=kalkulasi]').change(function(){
        // clear input
        $('#table-kalkulasi input:not([name=no_nota_timbang])').val('');

        if($(this).val() == 'R'){
            $('.row-kubikasi, .row-tonase').hide();
            $('.row-price').show();
        }else if($(this).val() == 'K'){
            $('.row-kubikasi').show();
            $('.row-tonase').hide();
            $('.row-price').show();
        }else{
            $('.row-kubikasi').hide();
            $('.row-tonase').show();
            $('.row-price').show();
        }
    });
    // END OF KALKULASI DO

    // CALCULATE KUBIKASI
    $('input[name=panjang], input[name=lebar], input[name=tinggi], input[name=unit_price]').keyup(function(){
        
        if($('select[name=kalkulasi]').val() == 'K'){
            var panjang = $('input[name=panjang]').autoNumeric('get');

            var lebar = $('input[name=lebar]').autoNumeric('get');
            // alert(lebar);
            var tinggi = $('input[name=tinggi]').autoNumeric('get');
            // alert(tinggi);
            var volume = Number(panjang) * Number(lebar) * Number(tinggi);
            // alert('volume ' + volume);
            $('input[name=volume]').autoNumeric('set',volume);
            // pembulatan volume
            volume = $('input[name=volume]').autoNumeric('get');

            // hitung total harga
            var price = $('input[name=unit_price]').autoNumeric('get');
            var total = Number(price) * Number(volume);

            $('input[name=total]').autoNumeric('set',total);
        }

        
    });
    // END OF CALCULATE KUBIKASI
    

    // CALCULATE TONASE
    $('input[name=gross], input[name=tarre], input[name=unit_price]').keyup(function(){
        // alert($('select[name=kalkulasi]').val());
        if($('select[name=kalkulasi]').val() == 'T'){
            var gross = $('input[name=gross]').autoNumeric('get');
        
            var tarre = $('input[name=tarre]').autoNumeric('get');
            // alert(lebar);
            var netto = Number(gross) - Number(tarre);
            
            $('input[name=netto]').autoNumeric('set',netto);

            // hitung total harga
            var price = $('input[name=unit_price]').autoNumeric('get');
            var total = Number(price) * Number(netto);

            $('input[name=total]').autoNumeric('set',total);
        }
        
    });
    // END CALCULATE TONASE

    // CALCULATE RITASE
    $('input[name=unit_price]').keyup(function(){
        // alert($('select[name=kalkulasi]').val());
        if($('select[name=kalkulasi]').val() == 'R'){
            var unit_price = $('input[name=unit_price]').autoNumeric('get');

            $('input[name=total]').autoNumeric('set',unit_price);
        }
        
    });
    // END CALCULATE RITASE

    
})(jQuery);
</script>
@append