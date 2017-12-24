@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/dist/css/select2.min.css">

<style>
    /*#table-data > tbody > tr{
        cursor:pointer;
    }*/

    .table.table-data thead tr th{
        text-align: center;
        text-transform: uppercase;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="kalkulasi" >Kalkulasi Pengiriman</a> <i class="fa fa-angle-double-right" ></i> 
        Batch Edit
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" >
            <h3 style="margin:0;padding:0;" >{{$data_karyawan->nama . ' [' . $data_karyawan->nopol .']'}}</h3>
        </div>
        <div class="box-body">
            <input type="hidden" name="karyawan_id" value="{{$data_karyawan->id}}"> 
            {{-- <input type="hidden" name="has_saved_count" value="{{$has_saved_count}}">  --}}
            <input type="hidden" name="data_kalkulasi_open" value="{{json_encode($data_kalkulasi)}}"> 

            <table class="table table-condensed no-border table-master-header" >
                <tbody>
                    <tr>
                        <td class="col-xs-2" >
                            <label>Driver</label> 
                        </td>
                        <td class="col-xs-4" >
                            {{$data_karyawan->nama}}
                        </td>
                        <td class="col-xs-2" >
                            <label>Nopol</label>
                        </td>
                        <td class="col-xs-4" id="label-tanggal-order" >
                            {{$data_karyawan->nopol}}
                        </td>
                    </tr>
                </tbody>
            </table>
            <br/>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <?php $rownum=1; ?>
           
            <table class="table table-bordered table-condensed" id="table-data">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;" >NO</th>
                        <th class="text-center" >NOMOR<br/>PENGIRIMAN</th>
                        <th class="text-center" >TANGGAL<br>PENGIRIMAN</th>
                        <th class="text-center" >CUSTOMER</th>
                        <th class="text-center" >PEKERJAAN</th>
                        <th class="text-center" >MATERIAL</th>
                        <th class="text-center" >KALKULASI</th>
                        <th class="text-center" >TOTAL</th>
                        <th class="text-center" >STATUS</th>
                        <th class="text-center" ></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rownu=1; ?>
                    @foreach($data_kalkulasi as $dt)
                        <tr>
                            <td class="text-center">{{$rownum++}}</td>
                            <td class="text-center" >
                                {{$dt->kode_pengiriman}}
                            </td>
                            <td class="text-center" >
                                {{$dt->tanggal_pengiriman_format}}
                            </td>
                            <td>
                                {{$dt->nama_customer}}
                            </td>
                            <td>
                                {{$dt->nama_pekerjaan}}
                            </td>
                            <td>
                                {{$dt->nama_material}}
                            </td>
                            <td class="text-center" >
                                {{$dt->kalkulasi}}
                            </td>
                            <td class="uang text-right" >
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
                            <td class="text-center" >
                                <a class="btn btn-primary btn-xs btn-edit-row" ><i class="fa fa-edit" ></i></a>
                            </td>
                        </tr>
                        <tr class="row-detail hide" data-kalkulasiid="{{$dt->id}}" >
                            <td colspan="9" >
                                <div  style="margin: 5px;border:1.3pt dashed grey; margin-bottom: 0; padding: 5px; padding-bottom: 0px; border-radius: 5px;background-color: whitesmoke;" >

                                    <table class="table no-border table-condensed" style="background-color: transparent;margin-bottom: 5px;" >
                                        <tbody>
                                            <tr>
                                                <td class="col-xs-1">
                                                    <label>Kalkulasi</label>
                                                    <input type="hidden" name="kalkulasi_id" value="{{$dt->id}}">
                                                </td>
                                                <td class="col-xs-2">
                                                    {!! Form::select('kalkulasi',['KUBIKASI'=>'KUBIKASI','TONASE'=>'TONASE','RITASE'=>'RITASE'],null,['class'=>'form-control']) !!}
                                                </td>
                                                <td class="col-xs-1">
                                                    <label>Nota Timbang*</label>
                                                </td>
                                                <td class="col-xs-2">
                                                    <input type="text" name="nota_timbang" class="form-control">
                                                </td>
                                                <td class="col-xs-1">
                                                </td>
                                                <td class="col-xs-2"></td>
                                                <td class="col-xs-1"></td>
                                                <td class="col-xs-2"></td>
                                            </tr>
                                            <tr class="row-kubikasi hide" >
                                                <td>
                                                    <label>Panjang</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="panjang" class="form-control text-right uang">
                                                </td>
                                                <td>
                                                    <label>Lebar</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="lebar" class="form-control text-right uang">
                                                </td>
                                                <td>
                                                    <label>Tinggi</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="tinggi" class="form-control text-right uang">
                                                </td>
                                                <td>
                                                    <label>Volume</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="volume" class="form-control text-right uang" readonly>
                                                </td>
                                            </tr>
                                            <tr class="row-tonase hide" >
                                                <td>
                                                    <label>Gross</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="gross" class="form-control text-right uang">
                                                </td>
                                                <td>
                                                    <label>Tare</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="tare" class="form-control text-right uang">
                                                </td>
                                                <td>
                                                    <label>Netto</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="netto" class="form-control text-right uang" readonly>
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label>Harga</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="harga_satuan" class="form-control text-right uang">
                                                </td>
                                                <td>
                                                    <label>Total</label>
                                                </td>
                                                <td>
                                                    <input type="text" name="total" class="form-control text-right uang" readonly>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">
                                                    {{-- <a class="btn btn-primary btn-xs btn-save-detail" ><i class="fa fa-save" ></i> Save</a> --}}
                                                    <a class="btn btn-danger btn-xs btn-close-detail" ><i class="fa fa-close" ></i> Close</a>
                                                </td>
                                            </tr>                                            
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            <button class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            {{-- <button class="btn btn-success" id="btn-save-validate" ><i class="fa fa-check" ></i> Save & Validate</button> --}}
            <a class="btn btn-danger" href="kalkulasi" ><i class="fa fa-close" ></i> Close</a>

            <span class="pull-right" >*Nota Timbang: Kosongkan untuk cetak otomatis</span>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->
    <form method="POST" action="kalkulasi/batch-edit-update" name="form_update_kalkulasi" >
        <input type="hidden" name="data_kalkulasi">
    </form>
@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/select2/dist/js/select2.full.min.js"></script>

<script type="text/javascript">
(function ($) {

    $("select[name=kalkulasi]").val([]);
    $(".select2").val([]);

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        startDate: $('#label-tanggal-order').text()
    });

    // UANG FORMAT
    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.99'
        });

    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

    // LOAD DATA ON PAGE LOAD
    function loadDataOnLoad(){
        var data_kalkulasi = JSON.parse($('input[name=data_kalkulasi_open]').val());
        
        $.each(data_kalkulasi,function(){
            var dk = $(this)[0];

            if(dk.status == 'OPEN'){
                var aRow = $('.row-detail[data-kalkulasiid='+dk.id+']');
                aRow.removeClass('hide');
                var aTable = aRow.children('td:first').children('div').children('table');
                // FILL INPUT
                aTable.find('select[name=kalkulasi]').val(dk.kalkulasi);
                kalkulasiChange(aTable.find('select[name=kalkulasi]'));
                aTable.find('input[name=nota_timbang]').val(dk.no_nota_timbang);
                aTable.find('input[name=harga_satuan]').autoNumeric('set',dk.unit_price);
                aTable.find('input[name=total]').autoNumeric('set',dk.total);

                aTable.find('input[name=panjang]').autoNumeric('set',dk.panjang);
                aTable.find('input[name=lebar]').autoNumeric('set',dk.lebar);
                aTable.find('input[name=tinggi]').autoNumeric('set',dk.tinggi);
                aTable.find('input[name=volume]').autoNumeric('set',dk.volume);

                aTable.find('input[name=gross]').autoNumeric('set',dk.gross);
                aTable.find('input[name=tare]').autoNumeric('set',dk.tare);
                aTable.find('input[name=netto]').autoNumeric('set',dk.netto);
                // var aTable = 
            }
        });
    }  
    loadDataOnLoad();


    // TAMPIKLKAN FORM EDIT ON ROW
    $('.btn-edit-row').click(function(){
        var aRow = $(this).parent().parent();
        var nextRow = aRow.next();
        var rowDiv = nextRow.children('td:first').children('div:first');

        nextRow.removeClass('hide');
        // nextRow.show();
        rowDiv.hide();
        rowDiv.slideDown(250);
    });

    // BUTTON CLOSE DETAIL
    $('.btn-close-detail').click(function(){
        var detailDiv = $(this).parent('td').parent('tr').parent('tbody').parent('table').parent('div');
        var detailRow = detailDiv.parent().parent();
        
        detailDiv.slideUp(250,function(){
            detailRow.addClass('hide');
        });
    });


    // KALKULASI CHANGE
    $('select[name=kalkulasi]').change(function(){
        kalkulasiChange($(this));
    });

    function kalkulasiChange(selectElm){
        var val = selectElm.val();
        var kalkTable = selectElm.parent().parent().parent();
        var rowKubikasi = kalkTable.find('.row-kubikasi');
        var rowTonase =  kalkTable.find('.row-tonase');
        // var rowRitase = $(this).closest('.row-ri');

        if(val == 'KUBIKASI'){
            rowKubikasi.removeClass('hide');
            rowTonase.addClass('hide');
        }else if(val == 'TONASE'){
            rowKubikasi.addClass('hide');
            rowTonase.removeClass('hide');
        }else if(val == 'RITASE'){
            rowKubikasi.addClass('hide');
            rowTonase.addClass('hide');
        }

        // clear all input
        kalkTable.find('input[type=text]').val('');
    }

    // PERHITUNGAN OTOMATIS VOLUME
    $('input[name=panjang],input[name=lebar],input[name=tinggi]').keyup(function(){
        // alert('change');
        var aRow = $(this).parent().parent();
        var aTable = aRow.parent();
        var panjang = aRow.find('input[name=panjang]').autoNumeric('get');
        var lebar = aRow.find('input[name=lebar]').autoNumeric('get');
        var tinggi = aRow.find('input[name=tinggi]').autoNumeric('get');


        var vol = panjang * lebar * tinggi;

        aRow.find('input[name=volume]').autoNumeric('set',vol);
        
        hitungTotalHarga(aTable);
    });

    // PERHITUNGA TOTAL HARGA
    function hitungTotalHarga(aTable){
        var kalkulasi = aTable.find('select[name=kalkulasi]').val();
        var harga_satuan = aTable.find('input[name=harga_satuan]').autoNumeric('get');
        var total = 0;

        if(kalkulasi == 'KUBIKASI'){
            var panjang = aTable.find('input[name=panjang]').autoNumeric('get');
            var lebar = aTable.find('input[name=lebar]').autoNumeric('get');
            var tinggi = aTable.find('input[name=tinggi]').autoNumeric('get');

            total = panjang * lebar * tinggi * harga_satuan;
        }else if(kalkulasi == 'TONASE'){
            var gross = aTable.find('input[name=gross]').autoNumeric('get');
            var tare = aTable.find('input[name=tare]').autoNumeric('get');
            total = (gross - tare) * harga_satuan;
        }else{
            total = harga_satuan;
        }

        aTable.find('input[name=total]').autoNumeric('set',total);
    }
    $('input[name=harga_satuan]').keyup(function(){
        var aTable = $(this).parent().parent().parent();
        hitungTotalHarga(aTable);

    });

    // AUTO GENERATE NOTA TIBANG
    $('input[name=auto_nota_timbang]').change(function(){
        var input_nota_timbang = $(this).parent().parent().prev().children('input');
        if($(this).is(":checked")){
            // disable input nota_timbang
            input_nota_timbang.val('');
           input_nota_timbang.attr('readonly','readonly');
        }else{
            input_nota_timbang.removeAttr('readonly');
        }
    });

    // SAVE DATA KALKULASI KESELURUHAN
    $('#btn-save').click(function(){
        var data_kalkulasi = JSON.parse('{"data" : [] }');

        $('.row-detail').each(function(){
            var aTable = $(this).children('td:first').children('div').children('table');

            // // aTable.hide();
            var kalkulasi_id = aTable.find('input[name=kalkulasi_id]').val();
            var kalkulasi = aTable.find('select[name=kalkulasi]').val();
            var nota_timbang = aTable.find('input[name=nota_timbang]').val();
            var harga_satuan = aTable.find('input[name=harga_satuan]').autoNumeric('get');
            var panjang = "";
            var lebar = "";
            var tinggi = "";
            var volume = "";
            var gross = "";
            var tare = "";
            var netto = "";
            var total = "";

            if(kalkulasi == 'KUBIKASI'){
                 panjang = aTable.find('input[name=panjang]').autoNumeric('get');
                 lebar = aTable.find('input[name=lebar]').autoNumeric('get');
                 tinggi = aTable.find('input[name=tinggi]').autoNumeric('get');
                 volume = panjang * lebar * tinggi;
                 total = volume * harga_satuan;

            }else if(kalkulasi == 'TONASE'){    
                 gross = aTable.find('input[name=gross]').autoNumeric('get');
                 tare = aTable.find('input[name=tare]').autoNumeric('get');
                 netto = gross - tare;
                 total = harga_satuan * netto;

            }else if(kalkulasi == 'RITASE'){
                 total  = harga_satuan;

            }

            data_kalkulasi.data.push({
                "kalkulasi_id": kalkulasi_id,
                "harga_satuan": harga_satuan,
                "panjang":panjang,
                "lebar":lebar,
                "tinggi":tinggi,
                "volume":volume,
                "gross":gross,
                "tare":tare,
                "netto":netto,
                "total":total,
                "kalkulasi" : kalkulasi,
                "nota_timbang" : nota_timbang
            });


        });

        // alert(JSON.stringify(data_kalkulasi));

        $('input[name=data_kalkulasi]').val(JSON.stringify(data_kalkulasi));     
        // $('form[name=form_update_kalkulasi]').ajaxSubmit(function(){
        //     alert('done');
        // }); 
        $('form[name=form_update_kalkulasi]').submit(); 


    });

    // SAVE DATA KALKULASI PER ROW
    $('.btn-save-detail').click(function(){
        var aTable = $(this).parent().parent().parent();
        var kalkulasi = aTable.find('select[name=kalkulasi]').val();
        var nota_timbang = aTable.find('input[name=nota_timbang]').val();
        var harga_satuan = aTable.find('input[name=harga_satuan]').autoNumeric('get');
        data_master = {
                        "kalkulasi_id": $('input[name=kalkulasi_id]').val(),
                        "harga_satuan": harga_satuan,
                        "panjang":"",
                        "lebar":"",
                        "tinggi":"",
                        "volume":"",
                        "gross":"",
                        "tare":"",
                        "netto":"",
                        "total":"",
                        "kalkulasi" : kalkulasi,
                        "nota_timbang" : nota_timbang
                    };

        if(kalkulasi == 'KUBIKASI'){
            var panjang = aTable.find('input[name=panjang]').autoNumeric('get');
            var lebar = aTable.find('input[name=lebar]').autoNumeric('get');
            var tinggi = aTable.find('input[name=tinggi]').autoNumeric('get');
            var vol = panjang * lebar * tinggi;
            var total = vol * harga_satuan;

            data_master.panjang = panjang;
            data_master.lebar = lebar;
            data_master.tinggi = tinggi;
            data_master.volume = vol;
            data_master.total = total;
        }else if(kalkulasi == 'TONASE'){    
            var gross = aTable.find('input[name=gross]').autoNumeric('get');
            var tare = aTable.find('input[name=tare]').autoNumeric('get');
            var netto = gross - tare;
            var total = harga_satuan * netto;

            data_master.gross = gross;
            data_master.tare = tare;
            data_master.netto = netto;
            data_master.total = total;
        }else if(kalkulasi == 'RITASE'){
            var total  = harga_satuan;

            data_master.total = total;
        }


        // var newform = $('<form>').attr('method','POST').attr('action','kalkulasi/batch-edit-update');
        // newform.append($('<input>').attr('type','hidden').attr('name','data_master').val(JSON.stringify(data_master)));
        // $('body').append(newform);
        // // newform.submit();
        // newform.ajaxForm(function(){
        //     alert('form done');
        // });
        $('input[name=data_master]').val(JSON.stringify(data_master));
        // $('form[name=form_update_kalkulasi]').submit();        
        $('form[name=form_update_kalkulasi]').ajaxSubmit(function(){
            alert('done');
        });        
    });

    // $('form[name=form_update_kalkulasi]').ajaxForm(function(){
    //     // alert('done');
    // });


})(jQuery);
</script>
@append