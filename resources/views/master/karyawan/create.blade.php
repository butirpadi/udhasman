@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<style>
    .col-top-item{
        /*cursor:pointer;*/
        border: thin solid #CCCCCC;

    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
    }
</style>
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="master/karyawan" >Data Karyawan</a>
        <i class="fa fa-angle-double-right" ></i> Create
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid" >
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>
        </div>
        <div class="box-body" >
            <div class="row" >
                <div class="col-xs-6" >
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control"  autofocus autocomplete="off" required />
                        <input type="text" name="kode" class="form-control hide"  autocomplete="off" required />
                    </div>  
                    <div class="form-group">
                        <label>Panggilan</label>
                        <input type="text" name="panggilan" class="form-control"  autocomplete="off"  />
                    </div>  
                    <div class="form-group">
                        <label>Jabatan</label>
                        {!! Form::select('jabatan',$selectJabatan,null,['class'=>'form-control']) !!}

                    </div>  
                    <div class="form-group">
                        <label>Gaji/Harian</label>
                        <input name="gaji_pokok" class="form-control input-uang" />

                    </div>  
                    <div class="form-group">
                        <label>KTP</label>
                        <input type="text" name="ktp" class="form-control"  autocomplete="off"  />

                    </div>  
                    <div class="form-group">
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

                <div class="col-xs-3" >
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control " />
                    </div>  
                    <div class="form-group">
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
                </div>
                <div class="col-xs-3 text-center" >
                    <img style="max-height: 230px;max-width:200px;border:solid thin whitesmoke;padding:10px;" id="foto-karyawan" class="" ><br/>
                    <input type="button" value="Choose File" onclick="document.getElementById('foto-input').click();" style="margin-top: 10px;" />
                    <input type="file" id="foto-input" name="foto" style="display: none;" class="text-center" accept="image/*" >

                </div>

                <div class="col-xs-6" >                   
                     
                    <div class="form-group">
                        <label>Desa</label>
                        <input type="text" name="desa" class="form-control " data-id="" />
                        
                    </div>  
                    <div class="form-group">
                        <label>Telp</label>
                        <input type="text" name="telp" class="form-control " data-id="" />
                    </div>  
                </div>
            </div>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" href="master/karyawan" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div>
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {

    // SET DATEPICKER
     $('.input-tanggal').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // SET AUTONUMERIC GAJI POKOK
    $('.input-uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });

    // SAVE

    $('#btn-save').click(function(){
        // cek kelengkapan data
        var nama = $('input[name=nama]').val();
        var panggilan = $('input[name=panggilan]').val();
        var kode = $('input[name=kode]').val();
        var ktp = $('input[name=ktp]').val();
        var alamat = $('input[name=alamat]').val();
        var provinsi = $('input[name=provinsi]').val();
        var provinsi_id = $('input[name=provinsi]').data('id');
        var kabupaten = $('input[name=kabupaten]').val();
        var kabupaten_id = $('input[name=kabupaten]').data('id');
        var kecamatan = $('input[name=kecamatan]').val();
        var kecamatan_id = $('input[name=kecamatan]').data('id');
        var desa = $('input[name=desa]').val();
        var desa_id = $('input[name=desa]').data('id');
        var jabatan = $('select[name=jabatan]').val();
        var telp = $('input[name=telp]').val();
        var tgl_lahir = $('input[name=tgl_lahir]').val();
        var tempat_lahir = $('input[name=tempat_lahir]').val();
        var gaji_pokok = $('input[name=gaji_pokok]').autoNumeric('get');
        var tanggal = $('input[name=tanggal]').val();
        var bulan = $('input[name=bulan]').val();
        var tahun = $('input[name=tahun]').val();

        if(nama != "" ){

        // alert('masuk kondisi');
            var formdata = $('<form>').attr('method','POST').attr('action','master/karyawan/insert').attr('enctype','multipart/form-data');
            formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
            formdata.append($('<input>').attr('type','hidden').attr('name','panggilan').val(panggilan));
            formdata.append($('<input>').attr('type','hidden').attr('name','kode').val(kode));
            formdata.append($('<input>').attr('type','hidden').attr('name','ktp').val(ktp));
            formdata.append($('<input>').attr('type','hidden').attr('name','alamat').val(alamat));
            formdata.append($('<input>').attr('type','hidden').attr('name','provinsi').val(provinsi));
            formdata.append($('<input>').attr('type','hidden').attr('name','provinsi_id').val(provinsi_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kabupaten').val(kabupaten));
            formdata.append($('<input>').attr('type','hidden').attr('name','kabupaten_id').val(kabupaten_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kecamatan').val(kecamatan));
            formdata.append($('<input>').attr('type','hidden').attr('name','kecamatan_id').val(kecamatan_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa').val(desa));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa_id').val(desa_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','jabatan').val(jabatan));
            formdata.append($('<input>').attr('type','hidden').attr('name','telp').val(telp));
            formdata.append($('<input>').attr('type','hidden').attr('name','tgl_lahir').val(tgl_lahir));
            formdata.append($('<input>').attr('type','hidden').attr('name','tanggal').val(tanggal));
            formdata.append($('<input>').attr('type','hidden').attr('name','bulan').val(bulan));
            formdata.append($('<input>').attr('type','hidden').attr('name','tahun').val(tahun));
            formdata.append($('<input>').attr('type','hidden').attr('name','tempat_lahir').val(tempat_lahir));
            formdata.append($('<input>').attr('type','hidden').attr('name','gaji_pokok').val(gaji_pokok));
            formdata.append($('input[name=foto]'));
            $('body').append(formdata);
            formdata.submit();
        }else{
            alert('Lengkapi data yang kosong.');
        }
    });

    // END OF SAVE

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
            // alert($('input[name=desa]').data('id'));

        }

    });
    // END OF SET AUTOCOMPLETE KECAMATAN

    // TAMPILKAN PREVIEW FOTO
     $(document).on('change','input[name=foto]',function(){
        var imgPrev = $('#foto-karyawan');

        var reader = new FileReader();
        reader.onload = function (e) {
            imgPrev.attr('src',e.target.result);
         }

        reader.readAsDataURL($(this)[0].files[0]);
    });
    // END OF TAMPIKLKAN PREVIEW FOTO
})(jQuery);
</script>
@append
