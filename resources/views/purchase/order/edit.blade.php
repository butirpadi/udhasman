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
        <a href="purchase/order" >Purchase Orders</a> <i class="fa fa-angle-double-right" ></i> {{$data_master->order_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >New</h4></label> --}}
            <button class="btn btn-primary" id="btn-validate-po" data-href="purchase/order/validate/{{$data_master->id}}" >Validate</button>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >Done</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Draft</a>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-10 col-md-10 col-lg-10" >
                    <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data_master->order_number}}</h3></label>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2" >
                    {{-- INVOICE SHORTCUT --}}
                    {{-- <a class="btn btn-app pull-right" href="sales/order/delivery/{{$data_master->id}}" >
                        <span class="badge bg-green">0</span>
                        <i class="fa fa-truck"></i> Delivery
                    </a> --}}
                </div>
            </div>

            {{-- hidden field --}}
            <input type="hidden" name="purchase_order_id" value="{{$data_master->id}}">

            <table class="table" >
                <tbody>
                    <tr>
                        <td class="col-lg-2">
                            <label>Supplier</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="supplier" autofocus class="form-control " data-supplierid="{{$data_master->supplier_id}}" value="{{'[' . $data_master->kode_supplier . '] ' . $data_master->supplier}}" readonly>
                        </td>
                        <td class="col-lg-2" ></td>
                        <td class="col-lg-2" >
                            <label>Order Date</label>
                        </td>
                        <td class="col-lg-2" >
                            <input type="text" name="tanggal" class="input-tanggal form-control" value="{{$data_master->order_date_formatted}}" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Supplier Ref#</label>
                        </td>
                        <td>
                            <input type="text" name="supplier_ref" class="form-control" value="{{$data_master->supplier_ref}}">
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    {{-- <tr>
                        <td class="col-lg-2">
                            <label>Salesperson</label>
                        </td>
                        <td class="col-lg-4" >
                            <input type="text" name="purchaseperson" class="form-control " data-purchasepersonid="" required >
                        </td>
                        <td class="col-lg-2" ></td>
                        <td class="col-lg-2 hide" >
                            <label>Jatuh Tempo</label>
                        </td>
                        <td class="col-lg-2 hide" >
                            <input type="text" name="jatuh_tempo"  class="input-tanggal form-control" value="" >
                        </td>
                    </tr> --}}
                </tbody>
            </table>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <table id="table-product" class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th style="width:25px;" >NO</th>
                        <th  >PRODUCT</th>
                        <th class="col-lg-1" >SATUAN</th>
                        <th class="col-lg-1" >QUANTITY</th>
                        <th class="col-lg-2" >UNIT PRICE</th>
                        {{-- <th class="col-lg-2" >S.U.P</th> --}}
                        <th class="col-lg-2" >SUBTOTAL</th>
                        <th style="width:50px;" ></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hide" id="row-add-product"  >
                        <td class="text-right" ></td>
                        <td>
                            <input autocomplete="off" type="text"  data-productid="" data-kode="" class=" form-control input-product input-sm input-clear">
                        </td>
                        <td class="label-satuan" ></td>
                        {{-- <td>
                            <input type="text" readonly autocomplete="off" class="form-control text-right input-quantity-on-hand input-sm input-clear">
                        </td> --}}
                        <td>
                            <input type="number" autocomplete="off" min="1" class="form-control text-right input-quantity input-sm input-clear">
                        </td>
                        {{-- <td>
                            <input autocomplete="off" type="text" class="text-right form-control input-unit-price input-sm input-clear" readonly="">
                        </td> --}}
                        <td>
                            <input autocomplete="off" type="text" class="text-right form-control input-unit-price input-sm input-clear" name="unit_price" >
                        </td>
                        <td>
                            <input autocomplete="off" type="text" readonly  class="text-right form-control input-subtotal input-sm input-clear" name="subtotal" >
                        </td>
                        <td class="text-center" >
                            <a href="#" class="btn-delete-row-product" ><i class="fa fa-trash" ></i></a>
                        </td>
                    </tr>
                    <?php $rownum=1; ?>
                    @foreach($data_detail as $dt)
                    <tr class="row-product">
                        <td class="text-right" >{{$rownum++}}</td>
                        <td>
                            {{-- <input autocomplete="off" type="text"  data-productid="{{$dt->product_id}}" data-kode="{{$dt->kode_product}}" class=" form-control input-product input-sm input-clear" value="{{'[' . $dt->kode_product .'] ' . $dt->product}}" readonly > --}}

                            <select name="product" class="form-control" required style="width: 100%;" disabled >
                                @foreach($product as $pd)
                                    <option {{$pd->id == $dt->product_id ? 'selected':'' }} value="{{$pd->id}}" data-unit="{{$pd->product_unit}}">{{$pd->nama}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="label-satuan" >{{$dt->product_unit}}</td>
                        <td>
                            <input name="input_quantity" type="number" autocomplete="off" min="1" class="form-control text-right input-quantity input-sm input-clear" value="{{$dt->qty}}" >
                        </td>
                        <td>
                            <input name="unit_price" autocomplete="off" type="text" class="text-right form-control input-unit-price input-sm input-clear" value="{{$dt->unit_price}}" >
                        </td>
                        <td>
                            <input name="subtotal" autocomplete="off" type="text" readonly  class="text-right form-control input-subtotal input-sm input-clear" value="{{$dt->unit_price *  $dt->qty}}" >
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
                    
                    
                </tbody>
            </table>

            <div class="row" >
                <div class="col-lg-8" >
                    {{-- <textarea name="note" class="form-control" rows="3" style="margin-top:5px;" placeholder="Note" ></textarea>
                    <i>* <span>Q.O.H : Quantity on Hand</span></i>
                    <i>&nbsp;|&nbsp;</i>
                    <i><span>S.U.P : Salesperson Unit Price</span></i> --}}
                </div>
                <div class="col-lg-4" >
                    <table class="table table-condensed" >
                        <tbody>
                            <tr>
                                <td class="text-right">
                                    <label>Subtotal :</label>
                                </td>
                                <td class="label-total-subtotal uang text-right" >
                                    {{$data_master->subtotal}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" >
                                    <label>Disc :</label>
                                </td>
                                <td >
                                   <input style="font-size:14px;" type="text" name="disc" class="input-sm form-control text-right input-clear" value="{{$data_master->disc}}" > 
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" style="border-top:solid darkgray 1px;" >
                                    Total :
                                </td>
                                <td class="label-total text-right uang" style="font-size:18px;font-weight:bold;border-top:solid darkgray 1px;" >
                                    {{$data_master->total}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- <div class="col-lg-12" >
                    <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
                            <a class="btn btn-danger" id="btn-cancel-save" ><i class="fa fa-close" ></i> Cancel</a>
                </div> --}}
            </div>



            {{-- <a id="btn-test" href="#" >TEST</a> --}}


        </div><!-- /.box-body -->
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

<!-- /.modal -->
</div>

<div class="hide">
    <div id="select-product-for-clone" >
        <select name="product" class="form-control" required style="width: 100%;" >
            @foreach($product as $dt)
                <option value="{{$dt->id}}" data-unit="{{$dt->product_unit}}">{{$dt->nama}}</option>
            @endforeach
        </select>
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
    <script src="plugins/select2/dist/js/select2.full.min.js"></script>



<script type="text/javascript">
(function ($) {

    //Initialize Select2 Elements
    $("select[name=product]").select2({ containerCssClass : "select-clear" });


    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // SET AUTOCOMPLETE SUPPLIER
    $('input[name=supplier]').autocomplete({
        serviceUrl: 'api/get-auto-complete-supplier',
        params: {  'nama': function() {
                        return $('input[name=supplier]').val();
                    }
                },
        onSelect:function(suggestions){
            // set data supplier
            $('input[name=supplier]').data('supplierid',suggestions.data);

            // get data pekerjaan
            // fillSelectPekerjaan(suggestions.data);

            // // enablekan select pekerjaan
            // $('select[name=pekerjaan]').removeAttr('disabled');
            // $('#btn-add-pekerjaan').removeAttr('disabled');

            //set data pekerjaan id
            $('form[name=form_create_pekerjaan] input[name=supplier_id]').val(suggestions.data);
        }

    });

    function fillSelectPekerjaan(supplier_id){
        $.get('api/get-select-pekerjaan/' + supplier_id,null,function(datares){
                var data_pekerjaan = JSON.parse(datares);
                // insert select option
                $('select[name=pekerjaan]').empty();
                $.each(data_pekerjaan,function(i,data){
                    $('select[name=pekerjaan]').append($('<option>').val(i).text(data));
                });
                $('select[name=pekerjaan]').val([]);

                //Initialize Select2 Elements
                $(".select2").select2();
            });
    }
    // END OF SET AUTOCOMPLETE CUSTOMER

    // // SET AUTOCOMPLETE MATERIAL
    // $('input[name=purchaseperson]').autocomplete({
    //     serviceUrl: 'purchase/order/get-purchaseperson',
    //     params: {  'nama': function() {
    //                     return $('input[name=purchaseperson]').val();
    //                 }
    //             },
    //     onSelect:function(suggestions){
    //         // set data supplier
    //         $('input[name=purchaseperson]').data('purchasepersonid',suggestions.data);
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
    // $('input[name=unit_price], input[name=subtotal], input[name=disc], .label-total, .label-total-subtotal').autoNumeric('init',{
    //     vMin:'0',
    //     vMax:'9999999999'
    // });
    $('.input-unit-price, .input-subtotal, .uang, input[name=disc]').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });

    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });
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
    var label_satuan;
    
    $('#btn-add-item').click(function(){
        // tampilkan form add new item
        var newrow = $('#row-add-product').clone();

        // revisi ganti input material dengan select product
            newrow.children('td:first').next().find('input').remove();
            var selectProduct = $('#select-product-for-clone').find('select[name=product]').clone();
            newrow.children('td:first').next().append(selectProduct);
            // format select 2 
            selectProduct.val([]);
            selectProduct.select2({ containerCssClass : "select-clear" });
            selectProduct.on('select2:select', function (evt) {
               // label_satuan.text(suggestions.unit);
               var unit  = selectProduct.find(':selected').data('unit');
               selectProduct.parent().next().text(unit);
            });
        // ---- end revisi ganti input material dengan select material

        newrow.addClass('row-product');
        newrow.removeClass('hide');
        newrow.removeAttr('id');
        // first_col = newrow.children('td:first');
        // input_product = first_col.next().children('input');
        // input_qty_on_hand = first_col.next().next().children('input');
        // input_qty = first_col.next().next().next().children('input');
        // label_satuan = first_col.next().next();
        // input_unit_price = first_col.next().next().next().next().children('input');
        // input_sup = first_col.next().next().next().next().next().children('input');
        // input_subtotal = first_col.next().next().next().next().next().children('input');

        // tambahkan newrow ke table
        // $(this).parent().parent().prev().after(newrow);
        $('#table-product tbody').children('tr:last').before(newrow);

        // newrow.find('input[name=unit_price]').fadeOut(500);

        // format numeric
        newrow.find('input[name=unit_price]').autoNumeric('init',{
            vMin:'0',
            vMax:'9999999999'
        });
        newrow.find('input[name=subtotal]').autoNumeric('init',{
            vMin:'0',
            vMax:'9999999999'
        });

        // // format auto numeric
        // input_unit_price.autoNumeric('init',{
        // // $('.input-unit-price').autoNumeric('init',{
        //     vMin:'0',
        //     vMax:'9999999999'
        // });
        // input_sup.autoNumeric('init',{
        // // $('.input-unit-price').autoNumeric('init',{
        //     vMin:'0',
        //     vMax:'9999999999'
        // });
        // input_subtotal.autoNumeric('init',{
        // // $('.input-subtotal').autoNumeric('init',{
        //     vMin:'0',
        //     vMax:'9999999999'
        // });       

        // Tampilkan & Reorder Row Number
        rownumReorder();
       
        // format autocomplete
        // input_product.autocomplete({
        //     serviceUrl: 'api/get-auto-complete-product',
        //     params: {  
        //                 'nama' : function() {
        //                             return input_product.val();
        //                         },
        //                 // 'exceptdata':JSON.stringify(getExceptionData())
        //             },
        //     onSelect:function(suggestions){
        //         input_product.data('productid',suggestions.data);
        //         input_product.data('kode',suggestions.kode);
                
        //         // disable input_product
        //         input_product.attr('readonly','readonly');

        //         // tampilkan satuan
        //         label_satuan.text(suggestions.unit);

        //         // get quantity on hand dan tampilkan ke input-quantity-on-hand
        //         // input_product.parent().next().children('input').val(suggestions.stok);
        //         // input_qty_on_hand.val(suggestions.stok);

        //         // set maks input-quanity
        //         // input_product.parent().next().next().children('input').attr('max',suggestions.stok);
        //         // input_qty.attr('max',suggestions.stok);

        //         // get unit_price & tampikan ke input-unit-price
        //         // input_product.parent().next().next().children('input').autoNumeric('set',suggestions.harga_jual);
        //         // input_unit_price.autoNumeric('set',suggestions.harga_jual);

        //         //set SUP default unit price
        //         // input_sup.autoNumeric('set',suggestions.harga_jual);

        //          // input_unit_price.autoNumeric('init',{
        //         input_unit_price.autoNumeric('init',{
        //             vMin:'0',
        //             vMax:'9999999999'
        //         });
        //         input_subtotal.autoNumeric('init',{
        //             vMin:'0',
        //             vMax:'9999999999'
        //         });

        //         // fokuskan ke input quantity
        //         // input_product.parent().next().children('input').focus();
        //         // alert('ok');
        //         input_qty.focus();
        //         // alert('done');

        //     }
        // });

        

        // // fokuskan ke input product
        // input_product.focus();

        return false;
    });
    // END OF ~BTN ADD ITEM

    // // HITUNG SUBTOTAL
    $(document).on('keyup','.input-unit-price, .input-quantity',function(){
        // generateInput($(this));

        // cek qty apakah melebihi batas QOH
        // alert(input_qty.val() +' ' + input_qty_on_hand.val());
        // if(Number(input_qty.val()) > Number(input_qty_on_hand.val())){
        //     alert('Quantity melebihi QOH.');
        //     input_qty.val('');
        //     input_qty.focus();
        // }else{
            calcSubtotal($(this));
        // }
        
    });
    // $(document).on('input','.input-quantity',function(){
    //     calcSubtotal($(this));
    // });

    function generateInput(inputElm){
        first_col = inputElm.parent().parent().children('td:first');
        input_product = first_col.next().children('input');
        // input_qty_on_hand = first_col.next().next().children('input');
        input_qty = first_col.next().next().next().children('input');
        input_unit_price = first_col.next().next().next().next().children('input');
        // input_sup = first_col.next().next().next().next().next().children('input');
        input_subtotal = first_col.next().next().next().next().next().children('input');
    }

    function calcSubtotal(inputElm){
        generateInput(inputElm);

        // hitung sub total
        var subtotal = Number(input_qty.val()) * Number(input_unit_price.autoNumeric('get'));
        // alert(subtotal);

        // tampilkan sub total
        input_subtotal.autoNumeric('set',subtotal);

        // hitung TOTAL
        hitungTotal();
    }
    // END HITUNG SUBTOTAL

    // FUNGSI HITUNG TOTAL KESELURUHAN
    function hitungTotal(){
        var disc = $('input[name=disc]').autoNumeric('get');
        var subtotal = 0;
        $('input.input-subtotal').each(function(){
            if($(this).parent().parent().hasClass('row-product')){
                subtotal += Number($(this).autoNumeric('get'));
            }
        });        
        // tampilkan subtotal dan total
        $('.label-total-subtotal').autoNumeric('set',subtotal);
        $('.label-total').autoNumeric('set',Number(subtotal) - Number(disc));
    }
    // END OF FUNGSI HITUNG TOTAL KESELURUHAN

    // INPUT DISC ON KEYUP
    $(document).on('keyup','input[name=disc]',function(){
        hitungTotal();
    });
    // END OF INPUT DISC ON KEYUP

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
    $('#btn-cancel-save').click(function(){
        // if(confirm('Anda akan membabtalkan transaksi ini?')){
            location.href = "purchase/order";
        // }else
        // {

        // return false
        // }
    });
    // END OF BTN CANCEL SAVE


    // BTN SAVE TRANSACTION
    $('#btn-save').click(function(){
        // cek kelengkapan data
        var po_master = {"supplier_id":"",
                         // "purchaseperson_id":"",
                         "purchase_order_id":"",
                         "supplier_ref":"",
                         "order_date":"",
                         // "pekerjaan_id":"",
                         // "note":"",
                         "subtotal":"",
                         "disc":"",
                         "total":""
                     };
        // set po_master data
        po_master.purchase_order_id = $('input[name=purchase_order_id]').val();
        po_master.supplier_id = $('input[name=supplier]').data('supplierid');
        // po_master.supplier_id = $('select[name=supplier]').val();

        // po_master.purchaseperson_id = $('input[name=purchaseperson]').data('purchasepersonid');
        // po_master.no_inv = $('input[name=no_inv]').val();
        po_master.order_date = $('input[name=tanggal]').val();
        po_master.supplier_ref = $('input[name=supplier_ref]').val();
        // po_master.pekerjaan_id = $('select[name=pekerjaan]').val();
        // po_master.jatuh_tempo = $('input[name=jatuh_tempo]').val();
        // po_master.note = $('textarea[name=note]').val();
        po_master.subtotal = $('.label-total-subtotal').autoNumeric('get');
        po_master.total = $('.label-total').autoNumeric('get');
        po_master.disc = $('input[name=disc]').autoNumeric('get');

        // get data product;
        var po_product = JSON.parse('{"product" : [] }');

        // // set data barang
        // $('input.input-product').each(function(){
        //     if($(this).parent().parent().hasClass('row-product')){
        //         generateInput($(this));

        //         if(input_product.data('productid') != "" 
        //             // && input_qty_on_hand.val() != "" 
        //             // && Number(input_qty_on_hand.val()) > 0 
        //             && input_qty.val() != "" 
        //             && Number(input_qty.val()) > 0 
        //             // &&input_unit_price.val() != "" 
        //             // && Number(input_unit_price.autoNumeric('get')) > 0 
        //             // && input_sup.val() != "" 
        //             // && Number(input_sup.autoNumeric('get')) > 0 
        //             // && input_subtotal.val() != "" 
        //             // && Number(input_subtotal.autoNumeric('get')) > 0 
        //             ){

        //             po_product.product.push({
        //                 id:input_product.data('productid'),
        //                 // qoh:input_qty_on_hand.val(),
        //                 qty:input_qty.val(),
        //                 unit_price : input_unit_price.autoNumeric('get'),
        //                 // sup_price:input_sup.autoNumeric('get'),
        //                 // subtotal:input_subtotal.autoNumeric('get')
        //             });

        //         }
                
        //     }
        // });

        // set data barang
        $('.row-product').each(function(){
        // $('input.input-product').each(function(){
            // if($(this).parent().parent().hasClass('row-product')){
                // generateInput($(this));

                var row = $(this);
                var product_id = row.find('select[name=product]').val();
                var qty = row.find('input.input-quantity').val();
                var unit_price = row.find('input[name=unit_price]').autoNumeric('get');
                var subtotal = row.find('input[name=subtotal]').autoNumeric('get');

                // alert(product_id);
                // alert(qty);
                // alert(unit_price);
                // alert(subtotal);

                if(product_id != ""
                    // && input_qty_on_hand.val() != ""
                    // && Number(input_qty_on_hand.val()) > 0
                    && qty != ""
                    && qty > 0
                    // &&input_unit_price.val() != ""
                    // && Number(input_unit_price.autoNumeric('get')) > 0
                    // && input_sup.val() != ""
                    // && Number(input_sup.autoNumeric('get')) > 0
                    // && input_subtotal.val() != ""
                    // && Number(input_subtotal.autoNumeric('get')) > 0
                    ){

                    po_product.product.push({
                        id:product_id,
                        // qoh:input_qty_on_hand.val(),
                        qty:qty,
                        unit_price : unit_price,
                        // sup_price:input_sup.autoNumeric('get'),
                        // subtotal:input_subtotal.autoNumeric('get')
                    });

                }

            // }
        });

        // save ke database
        // alert(po_product.product.length);
        // alert('Pekerjaan id : ' + po_master.pekerjaan_id);
        if(po_master.supplier_id != "" 
            // && $('input[name=supplier]').val() != "" 
            // && $('input[name=supplier]').val() != null 
            && po_master.order_date != "" 
            && po_master.order_date != null 
            // && po_master.pekerjaan_id != "" 
            // && po_master.pekerjaan_id != null 
            && po_product.product.length > 0){

            $(this).attr('disabled','disabled');

            var newform = $('<form>').attr('method','POST').attr('action','purchase/order/update');
                newform.append($('<input>').attr('type','hidden').attr('name','po_master').val(JSON.stringify(po_master)));
                newform.append($('<input>').attr('type','hidden').attr('name','po_product').val(JSON.stringify(po_product)));
                newform.submit();

        }else{
            alert('Lengkapi data yang kosong');
        }


        return false;
    });
    // END OF BTN SAVE TRANSACTION


    // CEK INPUT CUSTOMER APAKAH KOSONG ATAU TIDAK
    $('input[name=supplier]').keyup(function(){
        if($(this).val() == ""){
            // disable input pekerjaan
            $('select[name=pekerjaan]').empty();
            $('select[name=pekerjaan]').attr('disabled','disabled');
            $('$btn-add-pekerjaan').addClass('disabled');
        }
    });
    // END OF CEK INPUT CUSTOMER APAKAH KOSONG ATAU TIDAK

    // SAVE ADD PEKERJAAN
    $('form[name=form_create_pekerjaan]').ajaxForm(function(res){
        fillSelectPekerjaan($('form[name=form_create_pekerjaan] input[name=supplier_id]').val());
        // close modal
        $('#modal-pekerjaan').modal('hide');
    });
    // END OF SAVE ADD PEKERJAAN

    // VALIDATE PURCHASE ORDER
    $('#btn-validate-po').click(function(){
        location.href = $(this).data('href');
    });
    // END OF VALIDATE PURCHASE ORDER
    


    

    // // $('#btn-test').click(function(){
    // //     hitungTotal();
    // //     return false;
    // // });
    // // END OF FUNGSI HITUNG TOTAL KESELURUHAN

})(jQuery);
</script>
@append