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

    span.select2-selection.select2-selection--single.select-clear {
        outline: none;
        border: none;
        background-color:#EEF0F0;
        padding-right: 5px;
        padding-left: 5px;
        height: 30px;
    }

    span.select2-selection.select2-selection--single.select-clear .select2-selection__arrow{
        visibility: hidden;
    }

</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="gaji/gaji-driver" >Gaji Driver</a> <i class="fa fa-angle-double-right" ></i> {{$data->state == 'draft' ? 'New' : $data->name}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <form role="form" method="POST" action="gaji/gaji-driver/update" >
            <input type="hidden" name="gaji_driver_id" value="{{$data->id}}">
            <input type="hidden" name="amount_due" value="{{$data->amount_due}}">
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->state == 'draft' ? 'New' : $data->name}}</h3></label>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn  btn-arrow-right pull-right disabled {{$data->state == 'paid' ? 'bg-blue' : 'bg-gray'}}" >PAID</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled {{$data->state == 'open' ? 'bg-blue' : 'bg-gray'}}" >OPEN</a>

                <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
                <a class="btn btn-arrow-right pull-right disabled {{$data->state == 'draft' ? 'bg-blue' : 'bg-gray'}}" >DRAFT</a>
            </div>
            <div class="box-body">
                <div class="box-body">
                    @if($piutang > 0)
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fa fa-warning"></i> Anda memiliki tagihan piutang kepada Driver ini sebesar Rp. <span class="uang" >{{$piutang}}</span> <a class="pull-right" href="" >check Out!</a></h5>
                    </div>
                    @endif
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Bulan</label>
                                
                                    <div class="hide" ><input type="text" name="bulan" class="form-control" value="{{$data->bulan}}" required></div>
                                    <input type="text" name="input_bulan" class="form-control" readonly value="{{$data->bulan}}" >
                                
                            </div>  
                            <div class="form-group">
                                <label >Driver</label>
                                
                                    <div class="hide" >{!! Form::select('partner',$partners,$data->partner_id,['class'=>'form-control select2','required']) !!}</div>
                                    <input type="text" name="input_partner" class="form-control" readonly value="{{$data->partner}}" >
                               
                            </div>
                        </div>
                        
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label>Periode Pembayaran</label>
                                
                                    <div class="hide" ><select data-default="{{$data->tanggal_format}}" name="tanggal" class="form-control"></select></div>
                                    <input type="text" name="input_tanggal" class="form-control" readonly value="{{$data->tanggal_format}}" >
                                
                            </div>
                        </div>

                        <div class="col-xs-12" >
                            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>DATA PENGIRIMAN</strong></h4> 
                            <table class="table table-bordered table-condensed" id="table-pengiriman" >
                                <thead>
                                    <tr>
                                        <th>Pekerjaan</th>
                                        <th>Material</th>
                                        <th>Kalkulasi</th>
                                        <th>Rit</th>
                                        <th>Volume</th>
                                        <th>Netto</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($data->detail as $dt)
                                    <tr data-detailid="{{$dt->id}}" >
                                        <td>{{$dt->pekerjaan}}</td>
                                        <td>{{$dt->material}}</td>
                                        <td class="col-kal" >{{$dt->kalkulasi}}</td>
                                        <td align="right" class="col-rit" >{{$dt->rit}}</td>
                                        <td align="right" class="col-vol" >{{$dt->volume}}</td>
                                        <td align="right" class="col-net" >{{$dt->netto}}</td>
                                        <td align="right" class="col-xs-2" >
                                            @if($data->state == 'draft')
                                            <input type="text" class="form-control input-harga uang no-border text-right" style="background-color: azure;" value="{{$dt->harga}}">
                                            @else 
                                                <div class="hide" ><input type="text" class="form-control input-harga uang no-border text-right" style="background-color: azure;" value="{{$dt->harga}}"></div>
                                                <span class="uang" >{{$dt->harga}}</span>
                                            @endif
                                        </td>
                                        <td align="right" class="col-jumlah uang col-xs-2" >{{$dt->jumlah}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-xs-4 pull-right" >
                            <table class="table table-condensed no-border" >
                                <tbody>
                                    <tr>
                                        <td class="text-bold text-right" >Jumlah</td>
                                        <td class="col-xs-6"  ><input name="jumlah" class="uang text-right text-bold form-control no-border no-padding " style="background-color: white;" readonly id="total-jumlah" value="{{$data->jumlah}}" ></td>
                                    </tr>
                                    <tr style="border-bottom:solid black 1px!important;" >
                                        <td class="text-bold text-right" >Potongan Bahan</td>
                                        <td align="right" >
                                            @if($data->state == 'draft')
                                                <input type="text" name="potongan_bahan" class="uang text-right text-bold form-control no-border no-padding" style="background-color: azure;" id="potongan-bahan" value="{{$data->potongan_bahan}}" >
                                            @else 
                                                <div class="hide" ><input type="text" name="potongan_bahan" class="uang text-right text-bold form-control no-border no-padding" style="background-color: azure;" id="potongan-bahan" value="{{$data->potongan_bahan}}" ></div>
                                                <span class="uang text-right text-bold" >{{$data->potongan_bahan}}</span>
                                            @endif 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold text-right" >Nett</td>
                                        <td class="col-xs-6" ><input name="gaji_nett" class="uang text-right text-bold form-control no-border no-padding " style="background-color: white;" readonly id="gaji-nett" value="{{$data->gaji_nett}}" ></td>
                                    </tr>
                                    @foreach($data->payments as $pay)
                                    <tr>
                                        <td align="right" >
                                            <a href="#" class="payment-info-btn" data-paymentid="{{$pay->id}}" data-original-title="" title=""><i class="fa text-green fa-info-circle"></i> </a>
                                            <i>Paid on {{$pay->tanggal_format}}</i>
                                        </td>
                                        <td class="uang form-control" style="font-style: italic;"  align="right">{{$pay->jumlah}}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="hide" id="row-dibayar" style="border-bottom:solid black 1px!important;"  >
                                        <td class="text-bold text-right" >Dibayar</td>
                                        <td><input type="text" name="dibayar" class="uang text-right text-bold form-control no-border no-padding" style="background-color: white;" readonly id="dibayar" value="" ></td>
                                    </tr>
                                    <tr class="{{$data->state == 'draft' ? 'hide' : ''}}" style="border-top: solid thin black;" >
                                        <td class="text-bold text-right" >Amount Due</td>
                                        <td class="uang text-right text-bold col-xs-6" id="amount-due" >{{$data->amount_due}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div><!-- /.box-body -->
            <div class="box-footer" >
                @if($data->state == 'draft')
                    <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
                @endif

                @if($data->state != 'draft')
                    <a class="btn btn-warning" id="btn-cancel" data-href="gaji/gaji-driver/to-cancel/{{$data->id}}" href="{{url()->current()}}#btn-cancel" ><i class="fa fa-reply-all" ></i> Cancel</a>
                @endif

                <a class="btn btn-danger" href="gaji/gaji-driver" ><i class="fa fa-close" ></i> Close</a>

                @if($data->state == 'draft')
                    <a href="gaji/gaji-driver/confirm/{{$data->id}}" class="btn bg-maroon pull-right" id="btn-confirm"  ><i class="fa fa-check" ></i> Confirm</a>
                @endif

                @if($data->state == 'open' && $data->dp == 'N')
                    <a class="btn bg-purple pull-right " id="btn-validate" href="gaji/gaji-driver/validate/{{$data->id}}" ><i class="fa fa-check" ></i> Validate</a>
                @endif

                @if($data->state == 'open')
                    <a class="btn bg-green pull-right" id="btn-dp" style="margin-right: 10px;" ><i class="fa fa-random" ></i> Down Payment</a>
                @endif

                <a class="btn bg-blue pull-right hide" id="btn-save-dp" style="margin-right: 10px;" ><i class="fa fa-save" ></i> Save Down Payment</a>
                @if($data->state == 'draft')
                    <a class="text-red btn pull-right " id="btn-delete" style="margin-right: 10px;" data-href="gaji/gaji-driver/delete/{{$data->id}}" href="{{url()->current()}}#btn-delete" ><i class="fa fa-close" ></i> Delete</a>
                @endif

                @if($data->state == 'paid' && $data->dp == 'N')
                <a class="btn bg-green pull-right " id="btn-print-pdf" href="gaji/gaji-driver/print-pdf/{{$data->id}}" target="_blank" ><i class="fa fa-file-pdf-o" ></i> PDF</a>
                @endif
            </div>
        </form>
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
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.',
            unformatOnSubmit: true
        });

    $('select[name=partner]').select2();

    // FORMAT TANGGAL
    $('input[name=bulan]').datepicker({
        format: "mm-yyyy",
        startView: "months", 
        minViewMode: "months",
        autoclose : true
    }).on('changeDate', function(e) {
        // get data minggu/pay day
        $.post('gaji/gaji-driver/get-pay-day',{
            'bulan' : $('input[name=bulan]').val()
        },function(res){
            var payday = JSON.parse(res);
            // alert(res);
            $('select[name=tanggal]').empty();
            $.each(payday,function(){
                $('select[name=tanggal]').append($('<option>', {value:this.tanggal_format, text:this.tanggal_full}));
            });

            $('select[name=tanggal]').val($('select[name=tanggal]').data('default'));
            $('input[name=input_tanggal]').val($('select[name=tanggal] option:selected').text());
            
        });

        // var form = $('<form>').attr('method','POST').attr('action','gaji/gaji-driver/get-pay-day').append($('input').attr('name','bulan').attr('type','text').val($('input[name=bulan]').val()));
        // $('body').append(form);
        // form.submit();

    });

    $('input[name=bulan]').trigger('changeDate');

    // calculate jumlah
    $(document).on('keyup','.input-harga',function(){
        var row = $(this).parent().parent();
        var kalkulasi = row.find('.col-kal').text().trim();
        var harga = Number($(this).autoNumeric('get'));
        var jumlah = 0;
        var rit = row.find('.col-rit').text();
        var vol = row.find('.col-vol').text();
        var net = row.find('.col-net').text();
        var qty = Number(kalkulasi == 'rit' ? rit : (kalkulasi == 'kubik' ? vol : (kalkulasi == 'ton' ? net : 0 ) ));
        var jumlah = Number(qty) * Number(harga);
        row.find('.col-jumlah').autoNumeric('set',jumlah);
        calculateTotalJumlah();
    });

    // calculate total jumlah
    function calculateTotalJumlah(){
        var table = $('#table-pengiriman');
        var totalJumlah = 0 ;
        table.find('tbody').find('tr').each(function(i,data){
            totalJumlah += Number($(this).find('.col-jumlah').autoNumeric('get'));
        });
        $('#total-jumlah').autoNumeric('set',totalJumlah);
        calculateGajiNett();
    }

    function calculateGajiNett(){
        var gajiNett = $('#total-jumlah').autoNumeric('get') - $('#potongan-bahan').autoNumeric('get');
        $('#gaji-nett').autoNumeric('set',gajiNett);
        $('#dibayar').autoNumeric('set',gajiNett);
        $('#amount-due').autoNumeric('set',gajiNett);
    }

    $(document).on('keyup','#potongan-bahan',function(){
        calculateGajiNett();
    });

    // calculate dibayar
    $(document).on('keyup','#dibayar',function(){
        amount_due = Number($('input[name=amount_due]').val());    
        dibayar = Number($(this).autoNumeric('get'));
        new_amount_due = amount_due - dibayar;
        $('#amount-due').autoNumeric('set',new_amount_due);
    });
    
    $('form').submit(function(){
        // add json input
        updateData = [];

        var table = $('#table-pengiriman');
        table.find('tbody').find('tr').each(function(i,data){
            item = {};
            item['gaji_driver_detail_id'] = $(this).data('detailid');
            item['harga'] = $(this).find('.input-harga').autoNumeric('get');
            updateData.push(item);
        });

        // alert(JSON.stringify(updateData));
        

        inputData = $('<input>').attr('type','hidden').attr('name','update_data').val(JSON.stringify(updateData));
        $('form').append(inputData);

        // $('.uang').autoNumeric('destroy');
        $('.uang').each(function(){
            uang = $(this).autoNumeric('get');
            $(this).autoNumeric('destroy');
            $(this).val(uang);
        });

        // return false;
    });

    $('#btn-delete').click(function(){
        if(confirm('Anda akan menghapus data ini?')){
            location.href = $(this).data('href');
        }
        return false;
    });

    $('#btn-dp').click(function(){
        $('#row-dibayar').removeClass('hide');
        $('input[name=dibayar]').removeAttr('readonly');
        $('input[name=dibayar]').css('background-color','lavender');

        // hide validate button
        $('#btn-validate').hide();
        $(this).hide();

        // tampilkan btn save dp
        $('#btn-save-dp').removeClass('hide');

        $('input[name=dibayar]').select();
        $('input[name=dibayar]').focus();

        // inputkan nilai bayar sesuai dengan amount_due
        amount_due = $('input[name=amount_due]').val();
        $('input[name=dibayar]').autoNumeric('set',amount_due);
        $('input[name=dibayar]').trigger('keyup');
    });

    // Save DP
    $('#btn-save-dp').click(function(){
        jsonData = {};
        jsonData['gaji_driver_id'] = $('input[name=gaji_driver_id]').val();
        jsonData['jumlah'] = $('input[name=dibayar]').autoNumeric('get');

        // $.post('gaji/gaji-driver/save-payment',{
        //     'payment' : JSON.stringify(jsonData)
        // },function(res){
        //     alert(res);
        // });

        // using form
        form = $('<form>').attr('method','POST').attr('action','gaji/gaji-driver/save-payment');
        form.append($('<input>').attr('type','text').attr('name','payment').val(JSON.stringify(jsonData)));
        $('body').append(form);
        form.submit();
    });

    $('.payment-info-btn').popover({
        trigger: 'focus',
        title: 'Payment Info',
        content:getPaymentInfo,
        html:true,
        placement:'left'
    });

    function getPaymentInfo(){
        var res='';
        $.ajax({
            url:'gaji/gaji-driver/get-payment-info/' + $(this).data('paymentid'),
            method:"GET",
            async:false,
            success:function(data){
                res = data;
            }
        })
        return res;
    }

    $('.payment-info-btn').click(function(){
        return false;
    });

    $('#btn-cancel').click(function(){
        if(confirm('Anda akan membatalkan transaksi ini?')){
            location.href = $(this).data('href');
        }

        return false;
    });


})(jQuery);
</script>
@append
