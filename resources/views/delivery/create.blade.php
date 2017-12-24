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
        <a href="delivery" >Pengiriman</a> <i class="fa fa-angle-double-right" ></i> Create
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <form role="form" method="POST" action="delivery/insert" >
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >New</h4></label> --}}
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >DONE</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >DRAFT</a>
        </div>
        <div class="box-body">
                <div class="box-body">
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label for="customerLabel">Customer</label>
                                {!! Form::select('customer',$select_customer,null,['class'=>'form-control init_data','required']) !!}
                            </div>  
                            <div class="form-group">
                                <label >Pekerjaan</label>
                                <select name="pekerjaan" class="form-control init_data"  ></select>
                            </div>
                            <div class="form-group">
                                <label >Material</label>
                                {!! Form::select('material',$material,null,['class'=>'form-control init_data','required']) !!}
                            </div>
                            <div class="form-group">
                                <label >Lokasi Galian</label>
                                {!! Form::select('lokasi_galian',$lokasi_galian,null,['class'=>'form-control init_data','required']) !!}
                            </div>
                        </div>
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label for="tanggalLabel">Tanggal</label>
                                <input type="text" name="tanggal" class="input-tanggal form-control" value="{{date('d-m-Y')}}" required>    
                            </div>
                            <div class="form-group">
                                <label >Driver</label>
                                {!! Form::select('driver',$driver,null,['class'=>'form-control init_data']) !!}
                            </div>
                            <div class="form-group">
                              <label >Nopol</label>
                              <input type="text" name="nopol" class="form-control" required>    
                            </div>
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
            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" id="btn-cancel-save" href="delivery" ><i class="fa fa-close" ></i> Close</a>
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
    // $("select[name=driver]").val([]);
    // $("select[name=material]").val([]);
    $("select.init_data").select2();
    // $("select[name=customer]").select2();
    // $("select[name=driver]").select2();
    // $("select[name=material]").select2();



    // SET DATEPICKER
    $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // // SET AUTOCOMPLETE SUPPLIER
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
    //         // fillSelectPekerjaan(suggestions.data);

    //         // // enablekan select pekerjaan
    //         // $('select[name=pekerjaan]').removeAttr('disabled');
    //         // $('#btn-add-pekerjaan').removeAttr('disabled');

    //         //set data pekerjaan id
    //         $('form[name=form_create_pekerjaan] input[name=customer_id]').val(suggestions.data);
    //     }

    // });

    // function fillSelectPekerjaan(customer_id){
    //     $.get('api/get-select-pekerjaan/' + customer_id,null,function(datares){
    //             var data_pekerjaan = JSON.parse(datares);
    //             // insert select option
    //             $('select[name=pekerjaan]').empty();
    //             $.each(data_pekerjaan,function(i,data){
    //                 $('select[name=pekerjaan]').append($('<option>').val(i).text(data));
    //             });
    //             $('select[name=pekerjaan]').val([]);

    //             //Initialize Select2 Elements
    //             $(".select2").select2();
    //         });
    // }
    // END OF SET AUTOCOMPLETE CUSTOMER

    // // SET AUTOCOMPLETE MATERIAL
    // $('input[name=purchaseperson]').autocomplete({
    //     serviceUrl: 'pengiriman/get-purchaseperson',
    //     params: {  'nama': function() {
    //                     return $('input[name=purchaseperson]').val();
    //                 }
    //             },
    //     onSelect:function(suggestions){
    //         // set data customer
    //         $('input[name=purchaseperson]').data('purchasepersonid',suggestions.data);
    //     }

    // });
    // END OF SET AUTOCOMPLETE MATERIAL

    // SET AUTOCOMPLETE PROVINSI
    // $('input[name=provinsi]').autocomplete({
    //     serviceUrl: 'api/get-auto-complete-provinsi',
    //     params: {
    //                 'nama': function() {
    //                     return $('input[name=provinsi]').val();
    //                 }
    //             },
    //     onSelect:function(suggestions){
    //         // // set data customer
    //         $('input[name=provinsi_id]').val(suggestions.data);
    //     }

    // });
    // END OF SET AUTOCOMPLETE PROVINSI

    // SET AUTOCOMPLETE KABUPATEN
    // $('input[name=kabupaten]').autocomplete({
    //     serviceUrl: 'api/get-auto-complete-kabupaten',
    //     params: {
    //                 'nama': function() {
    //                     return $('input[name=kabupaten]').val();
    //                 },
    //                 'provinsi_id': function() {
    //                     return $('input[name=provinsi_id]').val();
    //                 },

    //             },
    //     onSelect:function(suggestions){
    //         // // set data customer
    //         $('input[name=kabupaten_id]').val(suggestions.data);
    //     }

    // });
    // END OF SET AUTOCOMPLETE KABUPATEN

    // SET AUTOCOMPLETE KECAMATAN
    // $('input[name=kecamatan]').autocomplete({
    //     serviceUrl: 'api/get-auto-complete-kecamatan',
    //     params: {
    //                 'nama': function() {
    //                     return $('input[name=kecamatan]').val();
    //                 },
    //                 'kabupaten_id': function() {
    //                     return $('input[name=kabupaten_id]').val();
    //                 },

    //             },
    //     onSelect:function(suggestions){
    //         // // set data customer
    //         $('input[name=kecamatan_id]').val(suggestions.data);
    //     }

    // });
    // END OF SET AUTOCOMPLETE KECAMATAN

    // SET AUTOCOMPLETE DESA
    // $('input[name=desa]').autocomplete({
    //     serviceUrl: 'api/get-auto-complete-desa',
    //     params: {
    //                 'nama': function() {
    //                     return $('input[name=desa]').val();
    //                 },
    //                 'kecamatan_id': function() {
    //                     return $('input[name=kecamatan_id]').val();
    //                 },

    //             },
    //     onSelect:function(suggestions){
    //         // // set data customer
    //         $('input[name=desa_id]').val(suggestions.data);
    //         // alert($('input[name=desa]').data('id'));

    //     }

    // });
    // END OF SET AUTOCOMPLETE DESA

    // // -----------------------------------------------------
    // // SET AUTO NUMERIC
    // // =====================================================
    // $('input[name=unit_price], input[name=subtotal], input[name=disc], .label-total, .label-total-subtotal').autoNumeric('init',{
    //     vMin:'0',
    //     vMax:'9999999999'
    // });
    // // END OF AUTONUMERIC

    // // FUNGSI REORDER ROWNUMBER
    // function rownumReorder(){
    //     var rownum=1;
    //     $('#table-product > tbody > tr.row-product').each(function(){
    //         $(this).children('td:first').text(rownum++);
    //     });
    // }
    // // END OF FUNGSI REORDER ROWNUMBER

    // // ~BTN ADD ITEM
    // var first_col;
    // var input_product;
    // var input_qty_on_hand;
    // var input_qty;
    // var input_unit_price;
    // var input_sup;
    // var input_subtotal;
    // var label_satuan;

    // $('#btn-add-item').click(function(){
    //     // tampilkan form add new item
    //     var newrow = $('#row-add-product').clone();

    //         // revisi ganti input material dengan select product
    //         newrow.children('td:first').next().find('input').remove();
    //         var selectProduct = $('#select-product-for-clone').find('select[name=product]').clone();
    //         newrow.children('td:first').next().append(selectProduct);
    //         // format select 2 
    //         selectProduct.val([]);
    //         selectProduct.select2({ containerCssClass : "select-clear" });
    //         selectProduct.on('select2:select', function (evt) {
    //            // label_satuan.text(suggestions.unit);
    //            var unit  = selectProduct.find(':selected').data('unit');
    //            selectProduct.parent().next().text(unit);
    //         });
    //     // ---- end revisi ganti input material dengan select material

    //     newrow.addClass('row-product');
    //     newrow.removeClass('hide');
    //     newrow.removeAttr('id');
    //     // first_col = newrow.children('td:first');
    //     // input_product = first_col.next().children('input');
    //     // input_qty_on_hand = first_col.next().next().children('input');
    //     // input_qty = first_col.next().next().next().children('input');
    //     // label_satuan = first_col.next().next();
    //     // input_unit_price = first_col.next().next().next().next().children('input');
    //     // // input_sup = first_col.next().next().next().next().next().children('input');
    //     // input_subtotal = first_col.next().next().next().next().next().children('input');

    //     // tambahkan newrow ke table
    //     $(this).parent().parent().prev().after(newrow);

    //     // format numeric
    //     newrow.find('input[name=unit_price]').autoNumeric('init',{
    //         vMin:'0',
    //         vMax:'9999999999'
    //     });
    //     newrow.find('input[name=subtotal]').autoNumeric('init',{
    //         vMin:'0',
    //         vMax:'9999999999'
    //     });


       
    //     rownumReorder();

      

    //     return false;
    // });
    // // END OF ~BTN ADD ITEM

    // // // HITUNG SUBTOTAL
    // $(document).on('keyup','.input-unit-price, .input-quantity',function(){
    
    //         calcSubtotal($(this));
        

    // });
    
    // function generateInput(inputElm){
    //     first_col = inputElm.parent().parent().children('td:first');
    //     input_product = first_col.next().children('input');
    //     // input_qty_on_hand = first_col.next().next().children('input');
    //     input_qty = first_col.next().next().next().children('input');
    //     input_unit_price = first_col.next().next().next().next().children('input');
    //     // input_sup = first_col.next().next().next().next().next().children('input');
    //     input_subtotal = first_col.next().next().next().next().next().children('input');
    // }

    // function calcSubtotal(inputElm){
    //     generateInput(inputElm);

    //     // hitung sub total
    //     var subtotal = Number(input_qty.val()) * Number(input_unit_price.autoNumeric('get'));
    //     // alert(subtotal);

    //     // tampilkan sub total
    //     input_subtotal.autoNumeric('set',subtotal);

    //     // hitung TOTAL
    //     hitungTotal();
    // }
    // // END HITUNG SUBTOTAL

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

    // // DELETE ROW PRODUCT
    // $(document).on('click','.btn-delete-row-product',function(){
    //     var row = $(this).parent().parent();
    //     row.fadeOut(250,null,function(){
    //         row.remove();
    //         // reorder row number
    //         rownumReorder();
    //         // hitung total
    //         hitungTotal();
    //     });

    //     return false;
    // });
    // // END OF DELETE ROW PRODUCT


    // // BTN CANCEL SAVE
    // $('#btn-cancel-save').click(function(){
    //         location.href = "pengiriman";
       
    // });
    // // END OF BTN CANCEL SAVE


    // // BTN SAVE TRANSACTION
    // $('#btn-save').click(function(){

    //     // cek kelengkapan data
    //     var po_master = {"customer_id":"",
    //                      // "purchaseperson_id":"",
    //                      "customer_ref":"",
    //                      "tanggal":"",
    //                      // "pekerjaan_id":"",
    //                      // "note":"",
    //                      "subtotal":"",
    //                      "disc":"",
    //                      "total":""
    //                  };
    //     // set po_master data
    //     // po_master.customer_id = $('input[name=customer]').data('customerid');
    //     po_master.customer_id = $('select[name=customer]').val();
    //     // po_master.purchaseperson_id = $('input[name=purchaseperson]').data('purchasepersonid');
    //     // po_master.no_inv = $('input[name=no_inv]').val();
    //     po_master.tanggal = $('input[name=tanggal]').val();
    //     po_master.nomor_nota = $('input[name=customer_ref]').val();
    //     // po_master.pekerjaan_id = $('select[name=pekerjaan]').val();
    //     // po_master.jatuh_tempo = $('input[name=jatuh_tempo]').val();
    //     // po_master.note = $('textarea[name=note]').val();
    //     po_master.subtotal = $('.label-total-subtotal').autoNumeric('get');
    //     po_master.total = $('.label-total').autoNumeric('get');
    //     po_master.disc = $('input[name=disc]').autoNumeric('get');

    //     // alert(JSON.stringify(po_master));

    //     // get data product;
    //     var po_product = JSON.parse('{"product" : [] }');

    //     // set data barang
    //     $('.row-product').each(function(){
    //     // $('input.input-product').each(function(){
    //         // if($(this).parent().parent().hasClass('row-product')){
    //             // generateInput($(this));

    //             var row = $(this);
    //             var product_id = row.find('select[name=product]').val();
    //             var qty = row.find('input.input-quantity').val();
    //             var unit_price = row.find('input[name=unit_price]').autoNumeric('get');
    //             var subtotal = row.find('input[name=subtotal]').autoNumeric('get');

               

    //             if(product_id != ""
    //                 && qty != ""
    //                 && qty > 0
    //                 ){

    //                 po_product.product.push({
    //                     id:product_id,
    //                     qty:qty,
    //                     unit_price : unit_price,
    //                 });

    //             }

    //         // }
    //     });

       
    //     if(po_master.customer_id != ""
    //         && po_master.tanggal != ""
    //         && po_master.tanggal != null
    //         && po_product.product.length > 0){

    //         $(this).attr('disabled','disabled');

    //         var newform = $('<form>').attr('method','POST').attr('action','pengiriman/insert');
    //             newform.append($('<input>').attr('type','hidden').attr('name','po_master').val(JSON.stringify(po_master)));
    //             newform.append($('<input>').attr('type','hidden').attr('name','po_product').val(JSON.stringify(po_product)));
    //             newform.submit();

    //     }else{
    //         alert('Lengkapi data yang kosong');
    //     }


    //     return false;
    // });
    // // END OF BTN SAVE TRANSACTION


    // // CEK INPUT CUSTOMER APAKAH KOSONG ATAU TIDAK
    // $('input[name=customer]').keyup(function(){
    //     if($(this).val() == ""){
    //         // disable input pekerjaan
    //         $('select[name=pekerjaan]').empty();
    //         $('select[name=pekerjaan]').attr('disabled','disabled');
    //         $('$btn-add-pekerjaan').addClass('disabled');
    //     }
    // });
    // // END OF CEK INPUT CUSTOMER APAKAH KOSONG ATAU TIDAK


    // Get Data Pekerjaan
    $('select[name=customer]').change(function(){
        $.get('master/pekerjaan-get-by-id/'+$(this).val(),function(data){
            data_pekerjaan = JSON.parse(data);
            // $("select[name=pekerjaan]").val([]).trigger('change');
            $('select[name=pekerjaan]').empty();
            $.each(data_pekerjaan,function(dt){
                // alert(data_pekerjaan[dt].nama + ' -- ' + data_pekerjaan[dt].id);
                $('select[name=pekerjaan]').append($('<option>', { 
                    value: data_pekerjaan[dt].id,
                    text : data_pekerjaan[dt].text 
                }));
            });
            // $("select[name=pekerjaan]").reset();
            $("select[name=pekerjaan]").select2('destroy');
            $("select[name=pekerjaan]").val([]);
            $("select[name=pekerjaan]").select2();
        });
        // set data pekerjaan
        // $pekerjaan = $.get('pekerjaan-get-by-id/'+$(this).val());
    });

    // Gewt data Nopol
    $('select[name=driver]').change(function(){
        $.get('master/karyawan-get-driver-by-id/'+$(this).val(),function(data){
            data_karyawan = JSON.parse(data);
            $('input[name=nopol]').val(data_karyawan.nopol);
        });

    });

   


})(jQuery);
</script>
@append
