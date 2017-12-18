@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/select2.min.css">
<link href="plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>

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

     /*clear border select2*/
     /*span.select2-selection.select2-selection--single {
        outline: none;
    }*/

    span.select2-selection.select2-selection--single.select-clear {
        outline: none;
        border: none;
        /*padding: 0; 
        margin: 0; 
        border: 0; */
        /*width: 100%;*/
        background-color:#EEF0F0;
        /*float:right;*/
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
        <a href="penjualan" >Pesanan Penjualan</a> <i class="fa fa-angle-double-right" ></i> Edit
    </h1>
</section>

{{-- DATA MASTER ID --}}
<input type="hidden" name="penjualan_id" value="{{$data_master->id}}">

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data_master->order_number}}</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >DONE</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >VALIDATED</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-blue" >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-gray" >DRAFT</a>
        </div>
        <div class="box-body">
            <table class="table table-condensed no-border table-master-header" >
                <tbody>
                    <tr>
                        <td class="col-lg-2" >
                            <label>Tanggal</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="tanggal" class="input-tanggal form-control" value="{{$data_master->tanggal_format}}" required>
                        </td>
                        <td class="col-lg-2">
                            <label>Direct Sales</label>
                        </td>
                        <td class="col-lg-4" >
                            <label class="label label-success" >Direct Sales</label>
                        </td>
                        
                    </tr>
                    <tr  >
                        <td >
                            <label>Customer</label>
                        </td>
                        <td >
                            <input type="text" name="customer" autofocus class="form-control " data-customerid="{{$data_master->customer_id}}" value="{{$data_master->nama_customer}}" readonly>
                            
                        </td>

                        <td class="direct_sales_input" >
                            <label>No. Kendaraan</label>
                        </td>
                        <td class="direct_sales_input ">
                            <input type="text" name="nopol" class="form-control" value="{{$data_master->nopol_direct_sales}}">
                        </td>
                    </tr>
                </tbody>
            </table>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th  >MATERIAL</th>
                        {{-- <th class="col-lg-1" >SATUAN</th> --}}
                        <th class="col-sm-1" >QUANTITY</th>
                        <th class="col-sm-2 direct_sales_input" >UNIT PRICE</th>
                        {{-- <th class="col-lg-2" >S.U.P</th> --}}
                        <th class="col-lg-2 direct_sales_input" >TOTAL</th>
                        <th style="width:50px;" ></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hide" id="row-add-product"  >
                        <td class="text-right" ></td>
                        <td>
                            <input autocomplete="off" type="text"  data-materialid="" data-kode="" class=" form-control input-product input-sm input-clear">
                        </td>
                        <td>
                            <input type="number" autocomplete="off" min="1" class="form-control text-right input-quantity input-sm input-clear">
                        </td>
                        <td class="direct_sales_input" >
                            <input type="text" class="uang form-control input-clear text-right input-harga-satuan-on-row uang" name="harga_satuan" >
                        </td>
                        <td class="direct_sales_input text-right uang" ></td>
                        <td class="text-center" >
                            <a href="#" class="btn-delete-row-product" ><i class="fa fa-trash" ></i></a>
                        </td>
                    </tr>
                    <?php $rownum=1; ?>
                    @foreach($data_master->detail as $dt)
                        <tr class="row-product"   >
                            <td class="text-right" >
                                {{$rownum++}}
                            </td>
                            <td>
                                {!! Form::select('material',$selectMaterial,$dt->material_id,['class'=>'form-control input-sm disabled','style'=>'width:100%;']) !!}
                            </td>
                            <td>
                                <input type="number" autocomplete="off" min="1" class="form-control text-right input-quantity  input-clear" value="{{$dt->qty}}" >
                            </td>
                            <td class="direct_sales_input" >
                                <input type="text" class="uang form-control input-clear text-right input-harga-satuan-on-row uang" name="harga_satuan" value="{{$dt->unit_price}}" >
                            </td>
                            <td class="direct_sales_input text-right uang" >
                                {{$dt->total}}
                            </td>
                            <td class="text-center" >
                                <a href="#" class="btn-delete-row-product" ><i class="fa fa-trash" ></i></a>
                            </td>
                        </tr>
                    @endforeach

                    <tr id="row-btn-add-item">
                        <td></td>
                        <td colspan="7" >
                            <a id="btn-add-item" href="#">Add an item</a>
                        </td>
                    </tr>
                    <tr class="direct_sales_input" >
                        <td colspan="4" class="text-right">
                            <label>TOTAL</label>
                        </td>
                        <td class=" text-right" >
                            <label class="label-total uang" >
                                {{$data_master->total}}
                            </label>
                        </td>
                        <td></td>
                    </tr>
                    
                    
                </tbody>
            </table>

            


        </div><!-- /.box-body -->
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary normal_sales_input" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" href="penjualan" id="btn-cancel-save" ><i class="fa fa-close" ></i> Close</a>
            <a class="btn btn-success pull-right" href="penjualan/validate-direct-sales/{{$data_master->id}}"  ><i class="fa fa-check" ></i> Validate</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->


<div class="hide" >
    {{-- data element untuk di cloning --}}
    <div id="select-material-for-clone" >
        {!! Form::select('material',$selectMaterial,null,['class'=>'form-control input-sm','style'=>'width:100%;']) !!}
    </div>
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
<script src="plugins/select2/select2.full.min.js"></script>
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>



<script type="text/javascript">
(function ($) {
    // UANG FORMAT
    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.99'
        });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

    // bootstrap swithc
    $("input[name=is_direct_sales]").bootstrapSwitch({
            size:'small',
            onText:'YES',
            offText:'NO'
          });

    //Initialize Select2 Elements
    $("select[name=pekerjaan]").select2();
    $("select[name=material]").select2({ containerCssClass : "select-clear" });

    $('select[name=customer]').val([]);
    $('select[name=customer]').select2();
    $('select[name=customer]').on('select2:select', function (evt) {
        var customer_id = $(this).val();
        // get data pekerjaan
        fillSelectPekerjaan(customer_id);

        // enablekan select pekerjaan
        $('select[name=pekerjaan]').removeAttr('disabled');
    });


    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // // SET AUTOCOMPLETE CUSTOMER
    // $('input[name=customer]').autocomplete({
    //     serviceUrl: 'api/get-auto-complete-customer',
    //     params: {  'nama': function() {
    //                     return $('input[name=customer]').val();
    //                 }
    //             },
    //     onSelect:function(suggestions){
    //         // set data customer
    //         $('input[name=customer]').data('customerid',suggestions.data);

    //         // get data pekerjaan
    //         fillSelectPekerjaan(suggestions.data);

    //         // enablekan select pekerjaan
    //         $('select[name=pekerjaan]').removeAttr('disabled');
    //         $('#btn-add-pekerjaan').removeAttr('disabled');

    //         //set data pekerjaan id
    //         $('form[name=form_create_pekerjaan] input[name=customer_id]').val(suggestions.data);
    //     }

    // });

    function fillSelectPekerjaan(customer_id){
        $.get('api/get-select-pekerjaan/' + customer_id,null,function(datares){
                var data_pekerjaan = JSON.parse(datares);
                // insert select option
                $('select[name=pekerjaan]').empty();
                $.each(data_pekerjaan,function(i,data){
                    $('select[name=pekerjaan]').append($('<option>').val(i).text(data));
                });
                $('select[name=pekerjaan]').val([]);

                //Initialize Select2 Elements
                $("select[name=pekerjaan]").select2();
            });
    }
    // END OF SET AUTOCOMPLETE CUSTOMER

    // // SET AUTOCOMPLETE MATERIAL
    // $('input[name=salesperson]').autocomplete({
    //     serviceUrl: 'penjualan/get-salesperson',
    //     params: {  'nama': function() {
    //                     return $('input[name=salesperson]').val();
    //                 }
    //             },
    //     onSelect:function(suggestions){
    //         // set data customer
    //         $('input[name=salesperson]').data('salespersonid',suggestions.data);
    //     }

    // });
    // END OF SET AUTOCOMPLETE MATERIAL

    // SET AUTOCOMPLETE PROVINSI
    $('input[name=provinsi]').autocomplete({
        serviceUrl: 'api/get-auto-complete-provinsi',
        params: {  
                    'nama': function() {
                        return $('input[name=provinsi]').val();
                    }
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=provinsi_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE PROVINSI

    // SET AUTOCOMPLETE KABUPATEN
    $('input[name=kabupaten]').autocomplete({
        serviceUrl: 'api/get-auto-complete-kabupaten',
        params: {  
                    'nama': function() {
                        return $('input[name=kabupaten]').val();
                    },
                    'provinsi_id': function() {
                        return $('input[name=provinsi_id]').val();
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=kabupaten_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE KABUPATEN

    // SET AUTOCOMPLETE KECAMATAN
    $('input[name=kecamatan]').autocomplete({
        serviceUrl: 'api/get-auto-complete-kecamatan',
        params: {  
                    'nama': function() {
                        return $('input[name=kecamatan]').val();
                    },
                    'kabupaten_id': function() {
                        return $('input[name=kabupaten_id]').val();
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=kecamatan_id]').val(suggestions.data);
        }

    });
    // END OF SET AUTOCOMPLETE KECAMATAN

    // SET AUTOCOMPLETE DESA
    $('input[name=desa]').autocomplete({
        serviceUrl: 'api/get-auto-complete-desa',
        params: {  
                    'nama': function() {
                        return $('input[name=desa]').val();
                    },
                    'kecamatan_id': function() {
                        return $('input[name=kecamatan_id]').val();
                    },
                    
                },
        onSelect:function(suggestions){
            // // set data supplier
            $('input[name=desa_id]').val(suggestions.data);
            // alert($('input[name=desa]').data('id'));

        }

    });
    // END OF SET AUTOCOMPLETE DESA

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================

    // END OF AUTONUMERIC

    // FUNGSI REORDER ROWNUMBER
    function rownumReorder(){
        var rownum=1;
        $('#table-product > tbody > tr.row-product').each(function(){
            $(this).children('td:first').text(rownum++);
        });
    }
    // END OF FUNGSI REORDER ROWNUMBER

    // ~BTN ADD ITEM
    var first_col;
    var input_product;
    var input_qty_on_hand;
    var input_qty;
    var input_unit_price;
    var input_sup;
    var input_subtotal;
    $('#btn-add-item').click(function(){
        // tampilkan form add new item
        var newrow = $('#row-add-product').clone();

        // revisi ganti input material dengan select material
            newrow.children('td:first').next().find('input').remove();
            var selectMaterial = $('#select-material-for-clone').find('select[name=material]').clone();
            newrow.children('td:first').next().append(selectMaterial);
            // format select 2 
            selectMaterial.val([]);
            selectMaterial.select2({ containerCssClass : "select-clear" });
        // ---- end revisi ganti input material dengan select material

        newrow.addClass('row-product');
        newrow.removeClass('hide');
        newrow.removeAttr('id');
        first_col = newrow.children('td:first');
        input_product = first_col.next().children('input');
        // input_qty_on_hand = first_col.next().next().children('input');
        input_qty = first_col.next().next().children('input');
        // input_unit_price = first_col.next().next().next().next().children('input');
        // input_sup = first_col.next().next().next().next().next().children('input');
        // input_subtotal = first_col.next().next().next().next().next().next().children('input');

        // tambahkan newrow ke table
        $(this).parent().parent().prev().after(newrow);

        $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.99'
        });

        // // format auto numeric
        // input_unit_price.autoNumeric('init',{
        // // $('.input-unit-price').autoNumeric('init',{
        //     vMin:'0.00',
        //     vMax:'9999999999.99'
        // });
        // input_sup.autoNumeric('init',{
        // // $('.input-salesperson-unit-price').autoNumeric('init',{
        //     vMin:'0.00',
        //     vMax:'9999999999.99'
        // });
        // input_subtotal.autoNumeric('init',{
        // // $('.input-subtotal').autoNumeric('init',{
        //     vMin:'0.00',
        //     vMax:'9999999999.99'
        // });       

        // Tampilkan & Reorder Row Number
        rownumReorder();
       
        // format autocomplete
        input_product.autocomplete({
            serviceUrl: 'api/get-auto-complete-material',
            params: {  
                        'nama' : function() {
                                    return input_product.val();
                                },
                        // 'exceptdata':JSON.stringify(getExceptionData())
                    },
            onSelect:function(suggestions){
                input_product.data('materialid',suggestions.data);
                input_product.data('kode',suggestions.kode);
                
                // disable input_product
                input_product.attr('readonly','readonly');

                // get quantity on hand dan tampilkan ke input-quantity-on-hand
                // input_product.parent().next().children('input').val(suggestions.stok);
                // input_qty_on_hand.val(suggestions.stok);

                // set maks input-quanity
                // input_product.parent().next().next().children('input').attr('max',suggestions.stok);
                // input_qty.attr('max',suggestions.stok);

                // get unit_price & tampikan ke input-unit-price
                // input_product.parent().next().next().children('input').autoNumeric('set',suggestions.harga_jual);
                // input_unit_price.autoNumeric('set',suggestions.harga_jual);

                //set SUP default unit price
                // input_sup.autoNumeric('set',suggestions.harga_jual);

                // fokuskan ke input quantity
                // input_product.parent().next().children('input').focus();
                // alert('ok');
                input_qty.focus();
                // alert('done');

            }
        });

        

        // fokuskan ke input product
        input_product.focus();

        return false;
    });
    // END OF ~BTN ADD ITEM

    // // // HITUNG SUBTOTAL
    // $(document).on('keyup','.input-salesperson-unit-price, .input-quantity',function(){
    //     generateInput($(this));

    //     // cek qty apakah melebihi batas QOH
    //     // alert(input_qty.val() +' ' + input_qty_on_hand.val());
    //     if(Number(input_qty.val()) > Number(input_qty_on_hand.val())){
    //         alert('Quantity melebihi QOH.');
    //         input_qty.val('');
    //         input_qty.focus();
    //     }else{
    //         calcSubtotal($(this));
    //     }
        
    // });
    // $(document).on('input','.input-quantity',function(){
    //     calcSubtotal($(this));
    // });

    function generateInput(inputElm){
        first_col = inputElm.parent().parent().children('td:first');
        input_product = first_col.next().children('select');
        // input_qty_on_hand = first_col.next().next().children('input');
        input_qty = first_col.next().next().children('input');
        input_unit_price = first_col.next().next().next().children('input');
        // input_sup = first_col.next().next().next().next().next().children('input');
        // input_subtotal = first_col.next().next().next().next().next().next().children('input');
    }

    function calcSubtotal(inputElm){
        generateInput(inputElm);

        // hitung sub total
        var subtotal = Number(input_qty.val()) * Number(input_sup.autoNumeric('get'));

        // tampilkan sub total
        input_subtotal.autoNumeric('set',subtotal);

        // hitung TOTAL
        hitungTotal();
    }
    // END HITUNG SUBTOTAL

    // // FUNGSI HITUNG TOTAL KESELURUHAN
    // function hitungTotal(){
    //     var disc = $('input[name=disc]').autoNumeric('get');
    //     var subtotal = 0;
    //     $('input.input-subtotal').each(function(){
    //         if($(this).parent().parent().hasClass('row-product')){
    //             subtotal += Number($(this).autoNumeric('get'));
    //         }
    //     });        
    //     // tampilkan subtotal dan total
    //     $('.label-total-subtotal').autoNumeric('set',subtotal);
    //     $('.label-total').autoNumeric('set',Number(subtotal) - Number(disc));
    // }
    // // END OF FUNGSI HITUNG TOTAL KESELURUHAN

    // // INPUT DISC ON KEYUP
    // $(document).on('keyup','input[name=disc]',function(){
    //     hitungTotal();
    // });
    // // END OF INPUT DISC ON KEYUP

    // DELETE ROW PRODUCT
    $(document).on('click','.btn-delete-row-product',function(){
        var row = $(this).parent().parent();
        row.fadeOut(250,null,function(){
            row.remove();
            // reorder row number
            rownumReorder();
            // hitung total
            hitungTotal();
        });

        return false;
    });
    // END OF DELETE ROW PRODUCT

    
    // BTN CANCEL SAVE
    // $('#btn-cancel-save').click(function(){
    //     if(confirm('Anda akan membabtalkan transaksi ini?')){
    //         location.href = "penjualan";
    //     }else
    //     {

    //     return false
    //     }
    // });
    // END OF BTN CANCEL SAVE


    // BTN SAVE TRANSACTION
    // $('#btn-save').click(function(){
    //     // cek kelengkapan data
    //         var so_master = {"customer_id":"",
    //                          // "salesperson_id":"",
    //                          "order_date":"",
    //                          "pekerjaan_id":"",
    //                          // "note":"",
    //                          // "subtotal":"",
    //                          // "disc":"",
    //                          // "total":""
    //                      };
    //         // set so_master data
    //         // so_master.customer_id = $('input[name=customer]').data('customerid');
    //         so_master.customer_id = $('select[name=customer]').val();
    //         // so_master.salesperson_id = $('input[name=salesperson]').data('salespersonid');
    //         // so_master.no_inv = $('input[name=no_inv]').val();
    //         so_master.order_date = $('input[name=tanggal]').val();
    //         so_master.pekerjaan_id = $('select[name=pekerjaan]').val();
    //         // so_master.jatuh_tempo = $('input[name=jatuh_tempo]').val();
    //         // so_master.note = $('textarea[name=note]').val();
    //         // so_master.subtotal = $('.label-total-subtotal').autoNumeric('get');
    //         // so_master.total = $('.label-total').autoNumeric('get');
    //         // so_master.disc = $('input[name=disc]').autoNumeric('get');

    //         // get data material;
    //         var so_material = JSON.parse('{"material" : [] }');

    //         // set data barant
    //         // $('input.input-product').each(function(){
    //         $('#table-product').find('select[name=material]').each(function(){
    //             if($(this).parent().parent().hasClass('row-product')){
    //                 generateInput($(this));

    //                 if(input_product.val() != "" 
    //                     // && input_qty_on_hand.val() != "" 
    //                     // && Number(input_qty_on_hand.val()) > 0 
    //                     && input_qty.val() != "" 
    //                     && Number(input_qty.val()) > 0 
    //                     // &&input_unit_price.val() != "" 
    //                     // && Number(input_unit_price.autoNumeric('get')) > 0 
    //                     // && input_sup.val() != "" 
    //                     // && Number(input_sup.autoNumeric('get')) > 0 
    //                     // && input_subtotal.val() != "" 
    //                     // && Number(input_subtotal.autoNumeric('get')) > 0 
    //                     ){

    //                     so_material.material.push({
    //                         id:input_product.val(),
    //                         // qoh:input_qty_on_hand.val(),
    //                         qty:input_qty.val(),
    //                         // unit_price : input_unit_price.autoNumeric('get'),
    //                         // sup_price:input_sup.autoNumeric('get'),
    //                         // subtotal:input_subtotal.autoNumeric('get')
    //                     });

    //                 }
                    
    //             }
    //         });

    //         // save ke database
    //         // alert(so_material.material.length);
    //         // alert('Pekerjaan id : ' + so_master.pekerjaan_id);
    //         if(so_master.customer_id != "" 
    //             // && $('input[name=customer]').val() != "" 
    //             // && $('input[name=customer]').val() != null 
    //             && so_master.order_date != "" 
    //             && so_master.order_date != null 
    //             // && so_master.pekerjaan_id != "" 
    //             // && so_master.pekerjaan_id != null 
    //             && so_material.material.length > 0){

    //             $(this).attr('disabled','disabled');

    //             var newform = $('<form>').attr('method','POST').attr('action','penjualan/insert');
    //                 newform.append($('<input>').attr('type','hidden').attr('name','so_master').val(JSON.stringify(so_master)));
    //                 newform.append($('<input>').attr('type','hidden').attr('name','so_material').val(JSON.stringify(so_material)));
    //                 newform.submit();

    //                 // alert(JSON.stringify(so_material));

    //         }else{
    //             alert('Lengkapi data yang kosong');
    //         }        


    //     return false;
    // });
    // END OF BTN SAVE TRANSACTION


    // CEK INPUT CUSTOMER APAKAH KOSONG ATAU TIDAK
    $('input[name=customer]').keyup(function(){
        if($(this).val() == ""){
            // disable input pekerjaan
            $('select[name=pekerjaan]').empty();
            $('select[name=pekerjaan]').attr('disabled','disabled');
            $('$btn-add-pekerjaan').addClass('disabled');
        }
    });
    // END OF CEK INPUT CUSTOMER APAKAH KOSONG ATAU TIDAK

    // TAMPILKAN MODAL ADD PEKERJAAN
    $('#btn-add-pekerjaan').click(function(){
        $('#modal-pekerjaan').modal({
            backdrop: 'static',
            keyboard: false
        });
        // focuskan ke input nama
        $('form[name=form_create_pekerjaan] input[name=nama]').focus();
    });
    // END OF TAMPILKAN MODAL ADD PEKERJAAN

    // SAVE ADD PEKERJAAN
    $('form[name=form_create_pekerjaan]').ajaxForm(function(res){
        fillSelectPekerjaan($('form[name=form_create_pekerjaan] input[name=customer_id]').val());
        // close modal
        $('#modal-pekerjaan').modal('hide');
    });
    // END OF SAVE ADD PEKERJAAN

    // DIRECT SALES
    
    // alert('show');
    $('input[name=is_direct_sales]').on('switchChange.bootstrapSwitch', function(event, state) {
        // alert('ok');
        // $('#direct_sales_input').removeClass('hide');    
        // $('.direct_sales_input').addClass('hide');    
        // alert('done');
        // $('#direct_sales_input').show();    
        if(state){
            // tampilkan input direct sales            
            $('.normal_sales_input').fadeOut(250,function(){
                $('.direct_sales_input').removeClass('hide');    
                $('.direct_sales_input').show();    
            });
        }else{
            // sembunyikan input
            $('.direct_sales_input').fadeOut(250,function(){
                $('.normal_sales_input').removeClass('hide');    
                $('.normal_sales_input').show();     
            });

        }
    });

    // SIMPAN DATA DIRECT SALES
    $('#btn-save').click(function(){
        // cek kelengkapan data
            var so_master = {"penjualan_id":"",
                             "customer_id":"",
                             "order_date":"",
                             "nopol":"",
                             "total":"",
                         };
            // set so_master data
            so_master.penjualan_id = $('input[name=penjualan_id]').val();
            so_master.customer_id = $('input[name=customer]').data('customerid');
            so_master.order_date = $('input[name=tanggal]').val();
            so_master.nopol = $('input[name=nopol]').val();
            so_master.total = $('.label-total').autoNumeric('get');
            // alert(JSON.stringify(so_master));
            // get data material;
            var so_material = JSON.parse('{"material" : [] }');

           

            $('.row-product').each(function(){
                // if($(this).parent().parent().hasClass('row-product')){
                    // generateInput($(this));

                    first_col = $(this).children('td:first');
                    input_product = first_col.next().children('select');
                    input_qty = first_col.next().next().children('input');
                    input_unit_price = first_col.next().next().next().children('input');

                    if(input_product.val() != "" 
                        && input_qty.val() != "" 
                        && Number(input_qty.val()) > 0 
                        ){

                        so_material.material.push({
                            id:input_product.val(),
                            // qoh:input_qty_on_hand.val(),
                            qty:input_qty.val(),
                            unit_price : input_unit_price.autoNumeric('get'),
                            // sup_price:input_sup.autoNumeric('get'),
                            // subtotal:input_subtotal.autoNumeric('get')
                        });

                    }
                    
                // }
            });

            // save ke database
            // alert(JSON.stringify(so_material));

            if(so_master.customer_id != "" 
                && $('input[name=customer]').val() != "" 
                && so_master.order_date != "" 
                && so_master.order_date != null 
                && so_material.material.length > 0){

                $(this).attr('disabled','disabled');

                var newform = $('<form>').attr('method','POST').attr('action','penjualan/update-direct-sales');
                    newform.append($('<input>').attr('type','hidden').attr('name','so_master').val(JSON.stringify(so_master)));
                    newform.append($('<input>').attr('type','hidden').attr('name','so_material').val(JSON.stringify(so_material)));
                    $('body').append(newform);
                    newform.submit();
                    // alert(newform.html());

            }else{
                alert('Lengkapi data yang kosong');
            }
    });
    // END OF SIMPAN DATA DIRECT SALES

    // HITUNG TOTAL
    $(document).on('keyup','.input-quantity, .input-harga-satuan-on-row',function(){
        
            var subtotal = 0;

            $('.row-product').each(function(){
                var row = $(this);
                var qty  = row.children('td:first').next().next().children('input').val();
                // alert(qty);
                var harga_satuan  = row.children('td:first').next().next().next().children('input').autoNumeric('get');
                var total = Number(qty) * Number(harga_satuan);
                row.children('td:last').prev().autoNumeric('set',total);            
                subtotal += Number(total);
            });

            // tampilkan subtotal
            $('.label-total').autoNumeric('set',subtotal);
        
    });
    
    // END OF DIRECT SALES
    


    

    // // $('#btn-test').click(function(){
    // //     hitungTotal();
    // //     return false;
    // // });
    // // END OF FUNGSI HITUNG TOTAL KESELURUHAN

})(jQuery);
</script>
@append