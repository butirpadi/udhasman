@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/dist/css/select2.min.css">
<style>
    .col-top-item{
        /*cursor:pointer;*/
        border: thin solid #CCCCCC;

    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
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
        <a href="master/supplier" >Data Supplier</a>
        <i class="fa fa-angle-double-right" ></i> Create
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid" >
        <form enctype="multipart/form-data" method="POST" action="master/supplier/insert" >
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>
            </div>
            <div class="box-body" >
                <div class="row" >
                    <div class="col-xs-5" >
                        <div class="radio hide">
                            <label class="radio-inline" >
                                <input type="radio" name="partner_type" value="partner" >
                                Supplier
                            </label>
                            <label class="radio-inline" >
                                <input type="radio" name="partner_type" value="supplier" >
                                Supplier
                            </label>
                            <label class="radio-inline" >
                                <input type="radio" name="partner_type" value="supplier" >
                                Supplier
                            </label>
                            <label class="radio-inline" >
                                <input type="radio" name="partner_type" value="supplier" >
                                Supplier
                            </label>
                            <label class="radio-inline" >
                                <input type="radio" name="partner_type" value="supplier" >
                                Supplier
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control"  autofocus autocomplete="off" required />
                        </div>  
                        <div class="form-group" id="input-panggilan" >
                            <label>Panggilan</label>
                            <input type="text" name="panggilan" class="form-control"  autocomplete="off"  />
                        </div>  
                        <div class="form-group" id="input-owner" >
                            <label>Owner</label>
                            <input type="text" name="owner" class="form-control"  autocomplete="off"  />
                        </div>  
                        <div class="form-group" id="input-npwp" >
                            <label>NPWP</label>
                            <input type="text" name="npwp" class="form-control"  autocomplete="off"  />
                        </div>  
                        <div class="form-group" id="input-gaji" >
                            <label>Gaji/Harian</label>
                            <input name="gaji_pokok" class="form-control input-uang" />
                        </div>  
                        <div class="form-group" id="input-armada" >
                            <label>Armada</label>
                            {!! Form::select('armada',$armada,null,['class'=>'form-control']) !!}
                        </div>  
                        <div class="form-group" id="input-ktp" >
                            <label>KTP</label>
                            <input type="text" name="ktp" class="form-control"  autocomplete="off"  />
                        </div>  
                        <div class="form-group" id="input-ttl" >
                            <label>Tempat/Tanggal Lahir</label>
                            <div class="input-group">
                                <input type="text" name="tempat_lahir" class="form-control"/>
                                <div class="input-group-addon" style="border:none;" >
                                    <label>Tanggal</label>
                                </div>
                                <div class="input-group-btn" style="width: 150px;" >
                                    <input type="text" name="tanggal" class="input-tanggal form-control" value="{{date('d-m-Y')}}" />    
                                </div>
                            </div>
                        </div>  
                    </div>

                    <div class="col-xs-5" >
                        <div class="form-group" id="input-alamat" >
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control " />
                        </div>  
                        <div class="form-group"  >
                            <label>Provinsi</label>
                            <input type="text" name="provinsi" class="form-control " data-id="" />
                        </div>  
                        <div class="form-group">
                            <label>Kota/Kabupaten</label>
                            <input type="text" name="kabupaten" class="form-control " data-id="" />

                        </div>  
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control " data-id="" />
                        </div> 
                        <div class="form-group" id="input-desa" >
                            <label>Desa</label>
                            <input type="text" name="desa" class="form-control " data-id="" />
                            <input type="hidden" name="desa_id" class="form-control "  />
                        </div>  
                        <div class="form-group" >
                            <label>Telp</label>
                            <input type="text" name="telp" class="form-control " data-id="" />
                        </div> 

                    </div>
                    <div class="col-xs-2 text-center" >
                        <img style="max-width:125px;border:solid thin whitesmoke;padding:10px;" id="foto-partner" class="" ><br/>
                        <input type="button" value="Choose File" onclick="document.getElementById('foto-input').click();" style="margin-top: 10px;" />
                        <input type="file" id="foto-input" name="foto" style="display: none;" class="text-center" accept="image/*" >

                    </div>

                    <div class="col-xs-6" >                   
                         
                         
                    </div>
                </div>

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
                <a class="btn btn-danger" href="master/supplier" ><i class="fa fa-close" ></i> Close</a>
            </div>
        </form>
    </div>
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/select2/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
    (function ($) {

        $('select[name=jabatan]').val([]);
        $("select[name=armada]").select2();

        $('input[name=gaji_pokok]').autoNumeric('init',{
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
                $('input[name=provinsi]').data('id',suggestions.data);
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
                            return $('input[name=provinsi]').data('id');
                        },

                    },
            onSelect:function(suggestions){
                // // set data supplier
                $('input[name=kabupaten]').data('id',suggestions.data);
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
                            return $('input[name=kabupaten]').data('id');
                        },

                    },
            onSelect:function(suggestions){
                // // set data supplier
                $('input[name=kecamatan]').data('id',suggestions.data);
            }

        });
        // END OF SET AUTOCOMPLETE KECAMATAN

        // SET AUTOCOMPLETE KECAMATAN
        $('input[name=desa]').autocomplete({
            serviceUrl: 'api/get-auto-complete-desa',
            params: {
                        'nama': function() {
                            return $('input[name=desa]').val();
                        },
                        'kecamatan_id': function() {
                            return $('input[name=kecamatan]').data('id');
                        },

                    },
            onSelect:function(suggestions){
                // // set data supplier
                $('input[name=desa]').data('id',suggestions.data);
                $('input[name=desa_id]').val(suggestions.data);
                // alert($('input[name=desa]').data('id'));

            }

        });
        // END OF SET AUTOCOMPLETE KECAMATAN

        // TAMPILKAN PREVIEW FOTO
         $(document).on('change','input[name=foto]',function(){
            var imgPrev = $('#foto-partner');

            var reader = new FileReader();
            reader.onload = function (e) {
                imgPrev.attr('src',e.target.result);
             }

            reader.readAsDataURL($(this)[0].files[0]);
        });
        // END OF TAMPIKLKAN PREVIEW FOTO

        $('input[name=partner_type]').change(function(){
            var type = $(this).val();
            $('#input-panggilan').show();
            $('#input-gaji').show();
            $('#input-ktp').show();
            $('#input-ttl').show();
            $('#input-owner').show();
            $('#input-npwp').show();
            $('#input-armada').show();

            if(type == 'supplier'){
                $('#input-panggilan').hide();
                $('#input-gaji').hide();
                $('#input-ktp').hide();
                $('#input-ttl').hide();
                $('#input-armada').hide();
            }else if(type == 'supplier'){
                $('#input-panggilan').hide();
                $('#input-gaji').hide();
                $('#input-ktp').hide();
                $('#input-ttl').hide();
                $('#input-owner').hide();
                $('#input-npwp').hide();
                $('#input-armada').hide();
            }else if(type == 'supplier'){
                $('#input-gaji').hide();
                $('#input-owner').hide();
                $('#input-npwp').hide();
            }else if(type == 'supplier'){
                $('#input-owner').hide();
                $('#input-npwp').hide();
                $('#input-armada').hide();
            }else if(type == 'partner'){
                $('#input-panggilan').hide();
                $('#input-owner').hide();
                $('#input-npwp').hide();
                $('#input-gaji').hide();
                $('#input-armada').hide();
                $('#input-ktp').hide();
                $('#input-ttl').hide();
                $('#input-armada').hide();
            }

        });

        $(":radio[value=supplier]").prop('checked',true).trigger('change');

    })(jQuery);
</script>
@append
