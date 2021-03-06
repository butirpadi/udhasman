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
        <a href="delivery" >Pengiriman</a> <i class="fa fa-angle-double-right" ></i> {{$pengiriman->name}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <form role="form" method="POST" action="delivery/update" >
        @include('delivery.edit-header')
        <div class="box-body">
                <div class="box-body">
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label for="customerLabel">Customer</label>
                                @if($pengiriman->state == 'draft')
                                    {!! Form::select('customer',$select_customer,$pengiriman->customer_id,['class'=>'form-control init_data','required','data-default'=>$pengiriman->customer_id]) !!}
                                @else
                                    <div class="hide" >
                                        {!! Form::select('customer',$select_customer,$pengiriman->customer_id,['class'=>'form-control init_data','required','data-default'=>$pengiriman->customer_id]) !!}
                                    </div>
                                    <input type="text" name="show_customer" class="form-control" value="{{$pengiriman->customer}}" readonly />
                                @endif
                                <input name="original_id" value="{{$pengiriman->id}}" class="hide" />
                            </div>  
                            <div class="form-group">
                                <label >Pekerjaan </label>
                                @if($pengiriman->state == 'draft')
                                    <select name="pekerjaan" class="form-control init_data" data-default="{{$pengiriman->pekerjaan_id}}" {{$pengiriman->state == 'open' ? 'readonly' : ''}} ></select>
                                @else
                                    <div class="hide" >
                                        <select name="pekerjaan" class="form-control init_data" data-default="{{$pengiriman->pekerjaan_id}}" {{$pengiriman->state == 'open' ? 'readonly' : ''}} ></select>
                                    </div>
                                    <input type="text" name="show_pekerjaan" class="form-control" value="{{$pengiriman->pekerjaan}}" readonly />
                                @endif
                            </div>
                            <div class="form-group">
                                <label >Material</label>
                                @if($pengiriman->state == 'draft')
                                    {!! Form::select('material',$material,null,['class'=>'form-control init_data','required',$pengiriman->state == 'open' ? 'readonly' : '','data-default'=>$pengiriman->material_id]) !!}
                                @else
                                    <div class="hide" >
                                        {!! Form::select('material',$material,null,['class'=>'form-control init_data','required',$pengiriman->state == 'open' ? 'readonly' : '','data-default'=>$pengiriman->material_id]) !!}
                                    </div>
                                    <input type="text" name="show_material" class="form-control" value="{{$pengiriman->material}}" readonly />
                                @endif
                            </div>
                            <div class="form-group">
                                <label >Lokasi Galian</label>
                                @if($pengiriman->state == 'draft')
                                    {!! Form::select('lokasi_galian',$lokasi_galian,$pengiriman->lokasi_galian_id,['class'=>'form-control init_data','required',$pengiriman->state == 'open' ? 'readonly' : '','data-default'=>$pengiriman->lokasi_galian_id]) !!}
                                @else
                                    <div class="hide" >
                                        {!! Form::select('lokasi_galian',$lokasi_galian,$pengiriman->lokasi_galian_id,['class'=>'form-control init_data','required',$pengiriman->state == 'open' ? 'readonly' : '','data-default'=>$pengiriman->lokasi_galian_id]) !!}
                                    </div>
                                    <input type="text" name="show_lokasi_galian" class="form-control" value="{{$pengiriman->lokasi_galian}}" readonly />
                                @endif
                                
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label for="tanggalLabel">Tanggal</label>                                    
                                @if($pengiriman->state == 'draft')
                                    <input type="text" name="tanggal" class="input-tanggal form-control" value="{{$pengiriman->order_date_format}}" required />
                                @else
                                    <div class="hide" >
                                        <input type="text" name="tanggal" class="input-tanggal form-control" value="{{$pengiriman->order_date_format}}" required />
                                    </div>
                                    <input type="text" name="show_tanggal" class="form-control" value="{{$pengiriman->order_date_format}}" readonly />
                                @endif
                            </div>
                            <div class="form-group">
                                <label >Driver</label>
                                @if($pengiriman->state == 'draft')
                                    {!! Form::select('driver',$driver,null,['class'=>'form-control init_data','data-default'=>$pengiriman->karyawan_id,$pengiriman->state == 'open' ? 'readonly' : '']) !!}
                                @else
                                    <div class="hide" >
                                        {!! Form::select('driver',$driver,null,['class'=>'form-control init_data','data-default'=>$pengiriman->karyawan_id,$pengiriman->state == 'open' ? 'readonly' : '']) !!}
                                    </div>
                                    <input type="text" name="show_driver" class="form-control" value="{{$pengiriman->karyawan}}" readonly />
                                @endif
                                
                            </div>
                            <div class="form-group">
                              <label >Nopol</label>
                                @if($pengiriman->state == 'draft')
                                    <input type="text" name="nopol" class="form-control" value="{{$pengiriman->nopol}}" required  />
                                @else
                                    <div class="hide" >
                                        <input type="text" name="nopol" class="form-control" value="{{$pengiriman->nopol}}" required  />    
                                    </div>
                                    <input type="text" name="show_nopol" class="form-control" value="{{$pengiriman->nopol}}" readonly />
                                @endif
                              
                            </div>
                            <div class="form-group">
                                <label >Nota Timbang</label>
                                @if($pengiriman->state == 'done')
                                    <input type="text" name="nota_timbang" class="form-control" value="{{$pengiriman->nota_timbang}}" readonly> 
                                @else
                                    <input type="text" name="nota_timbang" class="form-control" value="{{$pengiriman->nota_timbang}}" > 
                                @endif
                                 
                            </div>

                        </div>


                        <div class="col-xs-12 {{$pengiriman->state == 'draft' ? 'hide':''}} "   >
                            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>KALKULASI</strong></h4> 
                            <table class="table table-bordered table-condensed" >
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2" >KALKULASI</th>
                                        <th class="text-center hide group-kubik" colspan="4" >KUBIKASI</th>
                                        <th class="text-center hide group-ton" colspan="3" >TONASE</th>
                                        <th class="text-center hide group-rit" class="text-center" rowspan="2" >RIT</th>
                                        <th class="text-center col-xs-2" rowspan="2" >HARGA SATUAN</th>
                                        <th class="text-center col-xs-2" rowspan="2" >TOTAL</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center hide group-kubik col-xs-1">P</th>
                                        <th class="text-center hide group-kubik col-xs-1">L</th>
                                        <th class="text-center hide group-kubik col-xs-1">T</th>
                                        <th class="text-center hide group-kubik col-xs-1">VOL</th>
                                        <th class="text-center hide group-ton col-xs-1">G</th>
                                        <th class="text-center hide group-ton col-xs-1">T</th>
                                        <th class="text-center hide group-ton col-xs-1">NET</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($pengiriman->state == 'done')
                                                <select name="kalkulasi" class="form-control" data-default="{{$pengiriman->kalkulasi}}" disabled>
                                                    <option value="rit">Rit</option>
                                                    <option value="kubik">Kubikasi</option>
                                                    <option value="ton">Tonase</option>
                                                </select>
                                            @else
                                                <select name="kalkulasi" class="form-control" data-default="{{$pengiriman->kalkulasi}}" >
                                                    <option value="rit">Rit</option>
                                                    <option value="kubik">Kubikasi</option>
                                                    <option value="ton">Tonase</option>
                                                </select>
                                            @endif
                                        </td>
                                        <td class="hide group-kubik">
                                            <input type="text" name="panjang" class="form-control text-right input-volume" data-default="{{$pengiriman->panjang}}" {{$pengiriman->state == 'done' ? 'readonly':''}} />
                                        </td>
                                        <td class="hide group-kubik">
                                            <input type="text" name="lebar" class="form-control text-right input-volume" data-default="{{$pengiriman->lebar}}" {{$pengiriman->state == 'done' ? 'readonly':''}} />
                                        </td>
                                        <td class="hide group-kubik">
                                            <input type="text" name="tinggi" class="form-control text-right input-volume" data-default="{{$pengiriman->tinggi}}" {{$pengiriman->state == 'done' ? 'readonly':''}} />
                                        </td>
                                        <td class="hide group-kubik">
                                            <input type="text" name="volume" class="form-control  text-right" data-default="{{$pengiriman->volume}}" readonly  />
                                        </td>
                                        <td class="hide group-ton">
                                            <input type="text" name="gross" class="form-control text-right input-ton" data-default="{{$pengiriman->gross}}" {{$pengiriman->state == 'done' ? 'readonly':''}} />
                                        </td>
                                        <td class="hide group-ton">
                                            <input type="text" name="tare" class="form-control text-right input-ton" data-default="{{$pengiriman->tare}}"  {{$pengiriman->state == 'done' ? 'readonly':''}} />
                                        </td>
                                        <td class="hide group-ton">
                                            <input type="text" name="netto" class="form-control  text-right" data-default="{{$pengiriman->netto}}" readonly />
                                        </td>
                                        <td class="hide group-rit">
                                            <input type="text" name="qty" class="form-control  text-right" data-default="{{$pengiriman->qty}}" value="1" readonly />
                                        </td>
                                        <td>
                                            @if($pengiriman->state == 'done')
                                                <input type="text" name="harga_satuan" class="form-control text-right" value="{{$pengiriman->harga_satuan}}" readonly />
                                            @else
                                                <input type="text" name="harga_satuan" class="form-control text-right" value="{{$pengiriman->harga_satuan}}" />
                                            @endif
                                        </td>
                                        <td>
                                            <input type="text" name="harga_total" class="form-control  text-right" value="{{$pengiriman->harga_total}}" readonly />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <!-- <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>DETIL BARANG</strong></h4> -->

            
            <!-- Total -->
            <div class="row hide" >
                <div class="col-lg-8" >
                </div>
                <div class="col-lg-4" >
                    <table class="table table-condensed" >
                        <tbody>
                            <tr>
                                <td class="text-right">
                                    <label>Subtotal :</label>
                                </td>
                                <td class="label-total-subtotal text-right" >

                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Disc :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="disc" class="input-sm form-control text-right input-clear">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Total :
                                </td>
                                <td class="label-total text-right" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- /.box-body -->
        <div class="box-footer" >
            @if($pengiriman->state != 'done')
                <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            @endif
            <a class="btn btn-success"  ><i class="fa fa-print" ></i> Print</a>
            <a class="btn btn-success" target="_blank" href="delivery/topdf/{{$pengiriman->id}}"  ><i class="fa fa-file-pdf-o" ></i> PDF</a>
            <a class="btn btn-danger" id="btn-cancel-save" href="delivery" ><i class="fa fa-close" ></i> Close</a>
            <a class="btn bg-maroon pull-right {{$pengiriman->state == 'draft' ? '' : 'hide'}}" id="btn-confirm"  ><i class="fa fa-check" ></i> Confirm</a>
            <a class="btn bg-purple pull-right {{$pengiriman->state == 'open' ? '' : 'hide'}}" id="btn-validate"  ><i class="fa fa-check" ></i> Validate</a>
            <a class="text-red btn pull-right {{$pengiriman->state == 'done' ? 'hide' : ''}}" id="btn-delete" style="margin-right: 10px;" href="{{url()->current()}}#btn-delete" ><i class="fa fa-close" ></i> Delete</a>
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

    //Initialize Select2 Elements
    $("select.init_data").val([]);
    $("select.init_data").select2();

    // set data default
    $('select[name=customer]').select2('val',$('select[name=customer]').data('default'));
    fillPekerjaan();
    $('select[name=material]').select2('val',$('select[name=material]').data('default'));
    $('select[name=driver]').select2('val',$('select[name=driver]').data('default'));
    $('select[name=lokasi_galian]').select2('val',$('select[name=lokasi_galian]').data('default'));
    // $('select[name=kalkulasi]').select2('val',$('select[name=kalkulasi]').data('default'));
    $('select[name=kalkulasi]').val($('select[name=kalkulasi]').data('default'));
    $('select[name=pekerjaan]').val($('select[name=pekerjaan]').data('default'));
    changeKalkulasi();
    $('input[name=panjang]').val($('input[name=panjang]').data('default'));
    $('input[name=lebar]').val($('input[name=lebar]').data('default'));
    $('input[name=tinggi]').val($('input[name=tinggi]').data('default'));
    $('input[name=volume]').val($('input[name=volume]').data('default'));
    $('input[name=gross]').val($('input[name=gross]').data('default'));
    $('input[name=tare]').val($('input[name=tare]').data('default'));
    $('input[name=netto]').val($('input[name=netto]').data('default'));

    // format uang
    $('input[name=harga_satuan], input[name=harga_total]').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });
    $('input[name=volume], input[name=netto]').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });

    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    function fillPekerjaan(){
        $.get('master/pekerjaan/get-by-customer-id/' + $('select[name=customer]').val(),function(data){
            // data_pekerjaan = JSON.parse(data);
            // $("select[name=pekerjaan]").val([]).trigger('change');
            $('select[name=pekerjaan]').empty();
            $.each(data,function(dt){
                // alert(data_pekerjaan[dt].nama + ' -- ' + data_pekerjaan[dt].id);
                $('select[name=pekerjaan]').append($('<option>', { 
                    value: data[dt].id,
                    text : data[dt].text 
                }));
            });
            // $("select[name=pekerjaan]").reset();
            $("select[name=pekerjaan]").select2('destroy');
            $("select[name=pekerjaan]").val($("select[name=pekerjaan]").data('default'));
            $("select[name=pekerjaan]").select2();
        });
    }

    // Get Data Pekerjaan
    $('select[name=customer]').change(function(){
        fillPekerjaan();
    });

    // Gewt data Nopol
    $('select[name=driver]').change(function(){
        $.get('master/driver/get-by-id/' + $(this).val(),function(data){
            data_karyawan = JSON.parse(data);
            $('input[name=nopol]').val(data_karyawan.nopol);
        });

    });

    // KALKULASI CHANGE
    $('select[name=kalkulasi]').change(function(){
        changeKalkulasi();
    });

    function changeKalkulasi(){
        kalk = $('select[name=kalkulasi]').val();

        // clear input
        $('.group-ton input').val('');
        $('.group-kubik input').val('');

        if (kalk == 'rit'){
            $('.group-rit').removeClass('hide');
            $('.group-kubik').addClass('hide');
            $('.group-ton').addClass('hide');
        }else if(kalk == 'kubik'){
            $('.group-kubik').removeClass('hide');
            $('.group-rit').addClass('hide');
            $('.group-ton').addClass('hide');
        }else if(kalk == 'ton'){
            $('.group-ton').removeClass('hide');
            $('.group-kubik').addClass('hide');
            $('.group-rit').addClass('hide');
        }
    }

    // Perhitungan Volume
    $('.input-volume').keyup(function(){
        hitungKubik();
    });
    function hitungKubik(){
        P = $('input[name=panjang]').val();
        L = $('input[name=lebar]').val();
        T = $('input[name=tinggi]').val();
        V = P*L*T;
        V = V.toFixed(2);
        $('input[name=volume]').val(V);
        // $('input[name=volume]').autoNumeric('set',V);
        // V = 

        // hitung harga_total
        HS = $('input[name=harga_satuan]').autoNumeric('get');
        HT  = V * HS;

        // set harga total
        $('input[name=harga_total]').autoNumeric('set',HT);
    }

    // perhitungan tonase
    $('.input-ton').keyup(function(){
        hitungTon();
    });
    function hitungTon(){
        G = $('input[name=gross]').val();
        T = $('input[name=tare]').val();
        N = G-T;
        N = N.toFixed(2);
        $('input[name=netto]').val(N);

        // hitung harga total
        HS = $('input[name=harga_satuan]').autoNumeric('get');
        HT  = N * HS;

        // set harga total
        $('input[name=harga_total]').autoNumeric('set',HT);
    }

    function hitungRit(){
        // hitung harga total
        HS = $('input[name=harga_satuan]').autoNumeric('get');
        HT  = HS;

        // set harga total
        $('input[name=harga_total]').autoNumeric('set',HT);   
    }

    // harga satuan change
    $('input[name=harga_satuan]').keyup(function(){
        K = $('select[name=kalkulasi]').val();
        if(K=='ton'){
            hitungTon();
        }else if(K=='kubik'){
            hitungKubik();
        }else{
            hitungRit();
        }
    });
   
   // Confirm
   $('#btn-confirm').click(function(){
        // if(confirm('Lanjutkan proses validasi?')){
        location.href="delivery/confirm/"+$('input[name=original_id]').val();
        // }
   });

   // Confirm
   $('#btn-validate').click(function(){
        if(confirm('Anda akan mem-validasi data ini?')){
        location.href="delivery/validate/"+$('input[name=original_id]').val();
        }
   });

   // Delete
   $('#btn-delete').click(function(){
        if(confirm('Anda akan menghapus data ini?')){
            location.href="delivery/delete-single/"+$('input[name=original_id]').val();
        }
   });


})(jQuery);
</script>
@append
