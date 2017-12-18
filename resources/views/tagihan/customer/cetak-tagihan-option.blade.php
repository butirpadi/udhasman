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

    #table-kubikasi thead tr th{
        text-align: center;
    }

</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="tagihan-customer" >Data Tagihan Customer</a> <i class="fa fa-angle-double-right" ></i> Cetak Daftar Tagihan
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >Cetak Daftar Tagihan</h3></label>

            <!-- <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >DONE</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >VALIDATED</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-gray" >OPEN</a>
 -->
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled bg-blue" >DRAFT</a>
        </div>
        <div class="box-body">
            <form name="form_option" action="tagihan-customer/get-data-tagihan" method="POST">
                <table class="table table-condensed no-border table-master-header" >
                    <tbody>
                        <tr  >
                            <td class="col-xs-2" >
                                <label>Customer</label>
                            </td>
                            <td class="col-xs-4" >
                                {!! Form::select('customer',$selectCustomer,null,['class'=>'form-control','required']) !!}
                            </td>
                            <td class="col-xs-2">
                                <label>Pekerjaan</label>
                            </td>
                            <td  class="col-xs-4"  >
                                
                                <select name="pekerjaan" class="form-control select2" required="required" disabled="disabled">
                                    </select>
                            </td>
                        </tr>
                        <tr>
                            <td  >
                                <label>Tanggal Pengiriman </label>
                            </td>
                            <td >
                                <div class="row" >
                                    <div class="col-xs-6" >
                                        <input placeholder="Awal" type="text" name="tanggal_awal" class="input-tanggal form-control" value="" required>
                                    </div>
                                    <div class="col-xs-6" >
                                        <input placeholder="Akhir" type="text" name="tanggal_akhir" class="input-tanggal form-control" value="" required readonly>
                                    </div>
                                </div>
                            </td>
                            <td >
                                {{-- <label>Material</label> --}}
                            </td>
                            <td>
                                {{-- {!! Form::select('material',$selectMaterial,null,['class'=>'form-control','required']) !!} --}}
                            </td>                        
                        </tr>     
                        <tr>
                            <td></td>
                            <td class="no-border" >
                                <button class="btn btn-primary" >Submit</button>
                            </td>
                            <td></td>
                            <td class="no-border" ></td>
                        </tr>               
                    </tbody>
                </table>                
            </form>


            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            <div class="nav-tabs-custom" id="nav-tab-data-tagihan">
                        <ul class="nav nav-tabs">
                          
                        </ul>
                        <div class="tab-content">
                          
                        </div><!-- /.tab-content -->
                      </div><!-- nav-tabs-custom -->
            

        </div><!-- /.box-body -->
        <div class="box-footer" >
            {{-- <button type="submit" class="btn btn-primary normal_sales_input" id="btn-save" ><i class="fa fa-save" ></i> Save</button> --}}
            {{-- <button type="submit" class="btn btn-success direct_sales_input hide" id="btn-direct-sales-save" ><i class="fa fa-save" ></i> Save</button> --}}
            <a class="btn btn-danger" href="penjualan" id="btn-cancel-save" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

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
    // bootstrap swithc
    $("input[name=is_direct_sales]").bootstrapSwitch({
            size:'small',
            onText:'YES',
            offText:'NO'
          });

    //Initialize Select2 Elements
    $("select[name=pekerjaan]").select2();
    $("select[name=material]").val([]);
    $("select[name=material]").select2();

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
    $('input[name=tanggal_awal]').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    }).on('changeDate',function(){
        // set minimal inpu=t tanggal akhir
        // alert($(this).datepicker('getDate'));
        var tanggal_awal = $(this).datepicker('getDate');
        $('input[name=tanggal_akhir]').removeAttr('readonly');
        // $('input[name=tanggal_akhir]').datepicker('destroy');
        $('input[name=tanggal_akhir]').datepicker('setStartDate',tanggal_awal);
    });

    $('input[name=tanggal_akhir]').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
    });

    // $('.input-tanggal').datepicker({
    //     format: 'dd-mm-yyyy',
    //     todayHighlight: true,
    //     autoclose: true
    // });
    // END OF SET DATEPICKER

    // FILL SELECT PEKERJAAN
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

   // //SUBMIT FILTER OPTION
   // $('form[name=form_option]').ajaxForm({
   //      beforeSubmit:function(){},
   //      success:function(res){
   //          // GET DATA TAGIHAN
   //          var dataTagihan = JSON.parse(res);
   //          var dataKubikasi = dataTagihan.kubikasi;
   //          var dataTonase = dataTagihan.tonase;
   //          var dataRitase = dataTagihan.ritase;

   //          var tableKubikasi = $('#table-kubikasi');

   //          // insert table kubikasi
   //          var rownum=1;
   //          // clear table
   //          tableKubikasi.children('tbody').empty();

   //          $.each(dataKubikasi,function(){
   //              // alert(JSON.stringify($(this)[0]));

   //              var colNum = $('<td>').text(rownum++).attr('class','text-center');
   //              var colTanggal = $('<td>').text($(this)[0].tanggal_pengiriman_format).attr('class','text-center');
   //              var colNopol = $('<td>').text($(this)[0].nopol).attr('class','text-center');
   //              var colMaterial = $('<td>').text($(this)[0].material);
   //              var colP = $('<td>').text($(this)[0].panjang).attr('class','text-right uang');
   //              var colL = $('<td>').text($(this)[0].lebar).attr('class','text-right uang');
   //              var colT = $('<td>').text($(this)[0].tinggi).attr('class','text-right uang');
   //              var colVol = $('<td>').text($(this)[0].volume).attr('class','text-right uang');
   //              var colHarga = $('<td>').text($(this)[0].unit_price).attr('class','text-right uang');
   //              var colJumlah = $('<td>').text($(this)[0].total).attr('class','text-right uang');

   //              var newrow = $('<tr>')
   //                              .append(colNum)
   //                              .append(colTanggal)
   //                              .append(colNopol)
   //                              .append(colMaterial)
   //                              .append(colP)
   //                              .append(colL)
   //                              .append(colT)
   //                              .append(colVol)
   //                              .append(colHarga)
   //                              .append(colJumlah);
   //              tableKubikasi.append(newrow);
   //          });

   //          // format auto numeric
   //          $('.uang').autoNumeric('init',{
   //              vMin:'0.00',
   //              vMax:'9999999999.99'
   //          });
   //      },
   //      error:function(res){
   //          alert('Error Submit');
   //      }
   // });


   //SUBMIT FILTER OPTION
   $('form[name=form_option]').ajaxForm({
        beforeSubmit:function(){},
        success:function(res){
            var data_material = JSON.parse(res);

            // clear data sebelumnya
            $('#nav-tab-data-tagihan').children('ul.nav').empty();
            $('#nav-tab-data-tagihan').children('div.tab-content').empty();

            var mat_idx = 1;
            $.each(data_material,function(){
                // GENERATE TAB & CONTENT
                var mat = $(this)[0];
                // alert(JSON.stringify(mat));
                var li_tab_mat = $('<li>');
                var tab_mat = $('<a>').attr('href','#tab_'+mat.material_id).attr('data-toggle','tab').text(mat.material);
                $('#nav-tab-data-tagihan').children('ul.nav').append(
                        li_tab_mat.append(
                                  tab_mat
                                )
                        );

                if(mat_idx == 1){
                  // jika tab yang pertama maka tambahkan class active
                  li_tab_mat.addClass('active');
                  // li_tab_mat.children('a').attr('aria-expanded','true');
                }

                // tab content
                var newTabContent = $('<div>').addClass('tab-pane').attr('id','tab_'+mat.material_id);
                if(mat_idx == 1){
                  newTabContent.addClass('active');
                }                
                $('#nav-tab-data-tagihan').children('div.tab-content').append(
                        newTabContent
                    );

                // GET DATA PER MATERIAL
                var getDataByMaterialUrl = "tagihan-customer/get-data-tagihan-by-material";
                $.post(getDataByMaterialUrl,{
                  'customer':$('select[name=customer]').val(),
                  'tanggal_awal':$('input[name=tanggal_awal]').val(),
                  'tanggal_akhir':$('input[name=tanggal_akhir]').val(),
                  'material':mat.material_id,
                  'pekerjaan':$('select[name=pekerjaan]').val()
                },function(res){
                  var data_tagihan = JSON.parse(res);

                  // $.each(data_tagihan,function(){
                  //   // JSON.parse($(this));
                  //   alert('ok');
                  // });

                  // alert(res);

                  // $('#tab_'+mat.material_id).html(res);

                  var data_kubikasi = data_tagihan.kubikasi;
                  var data_tonase = data_tagihan.tonase;
                  var data_ritase = data_tagihan.ritase;

                  if(data_ritase.length > 0){
                    // GENERATE TABLE RITASE
                    var aTableRitase = $('<table>').addClass('table table-bordered table-condensed').attr('id','table-rit-'+mat.material_id);
                    // GENERATE HEADER
                    aTableRitase.append(
                                $('<thead>').append(
                                    $('<tr>').append(
                                        $('<th>').addClass('text-center').text('NO')
                                      ).append(
                                        $('<th>').addClass('text-center').text('TANGGAL')
                                      ).append(
                                        $('<th>').addClass('text-center').text('NOPOL')
                                      ).append(
                                        $('<th>').addClass('text-center').text('MATERIAL')
                                      ).append(
                                        $('<th>').addClass('text-center').text('RIT')
                                      ).append(
                                        $('<th>').addClass('text-center').text('HARGA')
                                      ).append(
                                        $('<th>').addClass('text-center').text('JUMLAH')
                                      )
                                  )
                              );
                    // GENERATE TABLE CONTENT
                    var data_row = 1;
                    aTableRitase.append($('<tbody>'));
                    var totalRit = 0;

                    $.each(data_ritase,function(){
                      var dt = $(this)[0];
                      totalRit += Number(dt.total);

                      var newrow = $('<tr>').append(
                                      $('<td>').addClass('text-center').text(data_row++)
                                    ).append(
                                      $('<td>').addClass('text-center').text(dt.tanggal_pengiriman_format)
                                    ).append(
                                      $('<td>').addClass('text-center').text(dt.nopol)
                                    ).append(
                                      $('<td>').addClass('text-left').text(dt.material)
                                    ).append(
                                      $('<td>').addClass('text-right').text(1)
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').text(dt.unit_price)
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').text(dt.total)
                                    );
                        // tambabhkan row baru ke table
                        aTableRitase.children('tbody').append(newrow);
                    });

                    // add total row
                    var rowTotal = $('<tr>').css('border-top','solid 2px darkgrey').append(
                                      $('<td>').attr('colspan',6).addClass('text-center').html('<label>TOTAL</label>')
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').css('font-weight','bold').text(totalRit)
                                    );
                    aTableRitase.children('tbody').append(rowTotal);

                    // add table to tab content
                    $('#tab_'+mat.material_id).append(aTableRitase);

                    // END LOOP OF DATA_RITASE
                  }

                  if(data_kubikasi.length > 0){
                    // GENERATE TABLE KUBIKASI
                    var aDatatable = $('<table>').addClass('table table-bordered table-condensed').attr('id','table-kub-'+mat.material_id);
                    
                    // GENERATE HEADER
                    aDatatable.append(
                          $('<thead>').append(
                              $('<tr>').append(
                                  $('<th>').attr('rowspan',2).addClass('text-center').text('NO')
                                ).append(
                                  $('<th>').attr('rowspan',2).addClass('text-center').text('TANGGAL')
                                ).append(
                                  $('<th>').attr('rowspan',2).addClass('text-center').text('NOPOL')
                                ).append(
                                  $('<th>').attr('rowspan',2).addClass('text-center').text('MATERIAL')
                                ).append(
                                  $('<th>').attr('colspan',3).addClass('text-center').text('UKURAN')
                                ).append(
                                  $('<th>').attr('rowspan',2).addClass('text-center').text('VOL')
                                ).append(
                                  $('<th>').attr('rowspan',2).addClass('text-center').text('HARGA')
                                ).append(
                                  $('<th>').attr('rowspan',2).addClass('text-center').text('JUMLAH')
                                )
                            ).append(
                                $('<tr>').append(
                                    $('<th>').addClass('text-center').text('P')
                                  ).append(
                                    $('<th>').addClass('text-center').text('L')
                                  ).append(
                                    $('<th>').addClass('text-center').text('T')
                                  )
                            )
                        );
                    // GENERATE TABLE CONTENT
                    var data_row = 1;
                    aDatatable.append($('<tbody>'));
                    var totalKubikasi = 0;

                    $.each(data_kubikasi,function(){
                      var dt = $(this)[0];
                      totalKubikasi += Number(dt.total);

                      var newrow = $('<tr>').append(
                                      $('<td>').addClass('text-center').text(data_row++)
                                    ).append(
                                      $('<td>').addClass('text-center').text(dt.tanggal_pengiriman_format)
                                    ).append(
                                      $('<td>').addClass('text-center').text(dt.nopol)
                                    ).append(
                                      $('<td>').addClass('text-left').text(dt.material)
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').text(dt.panjang)
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').text(dt.lebar)
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').text(dt.tinggi)
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').text(dt.volume)
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').text(dt.unit_price)
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').text(dt.total)
                                    );
                        // tambabhkan row baru ke table
                        aDatatable.children('tbody').append(newrow);
                    });

                    // add total row
                    var rowTotal = $('<tr>').css('border-top','solid 2px darkgrey').append(
                                      $('<td>').attr('colspan',9).addClass('text-center').html('<label>TOTAL</label>')
                                    ).append(
                                      $('<td>').addClass('text-right bilangan').css('font-weight','bold').text(totalKubikasi)
                                    );
                    aDatatable.children('tbody').append(rowTotal);

                    // add table to tab content
                    $('#tab_'+mat.material_id).append(aDatatable);

                    // END OF TABLE KUBIKASI                    
                  }



                   // auto format bilangann
                    $('.bilangan').autoNumeric('init',{
                        vMin:'0.00',
                        vMax:'9999999999.99'
                    });




                  // END POST GET DATA TAGIHAN BY MATERIAL
                });


                mat_idx++;
                // END OF LOOPING NAV-TAB
            });
        },
        error:function(res){
            alert('Error Submit');
        }
   });

    
    // END OF DIRECT SALES
})(jQuery);
</script>
@append