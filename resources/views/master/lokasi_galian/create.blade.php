@extends('layouts.master')

@section('styles')
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
        <a href="master/lokasi" >Data Lokasi Galian</a> 
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
                        <input type="text" name="nama" class="form-control" required autofocus autocomplete="off" />
                        <input type="text" name="kode" class="form-control hide"  autocomplete="off" />
                    </div>  
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control " />
                    </div>  
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="provinsi" class="form-control " data-id="" />
                    </div>  
                </div>
                <div class="col-xs-6" >
                    <div class="form-group">
                        <label>Kota/Kabupaten</label>
                        <input type="text" name="kabupaten" class="form-control " data-id="" />

                    </div>  
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control " data-id="" />

                    </div>  
                    <div class="form-group">
                        <label>Desa</label>
                        <input type="text" name="desa" class="form-control " data-id="" />

                    </div>  
                </div>
            </div>
        </div>
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" href="master/lokasi" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div>
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {

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

    // SET AUTOCOMPLETE DESA
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
    // END OF SET AUTOCOMPLETE DESA

    // SAVE LOKASI GALIAN
    // $('form').ajaxForm({
    //     beforeSubmit:function(){
            
    //     },

    // });
    $('#btn-save').click(function(){
        // cek kelengkapan data
        var nama = $('input[name=nama]').val();
        var kode = $('input[name=kode]').val();
        var prov = $('input[name=provinsi]').val();
        var prov_id = $('input[name=provinsi]').data('id');
        var kab = $('input[name=kabupaten]').val();
        var kab_id = $('input[name=kabupaten]').data('id');
        var kec = $('input[name=kecamatan]').val();
        var kec_id = $('input[name=kecamatan]').data('id');
        var desa = $('input[name=desa]').val();
        var desa_id = $('input[name=desa]').data('id');
        var alamat = $('input[name=alamat]').val();

        if(nama != "" ){
            var formdata = $('<form>').attr('method','POST').attr('action','master/lokasi/insert');
            formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
            formdata.append($('<input>').attr('type','hidden').attr('name','kode').val(kode));
            formdata.append($('<input>').attr('type','hidden').attr('name','prov').val(prov));
            formdata.append($('<input>').attr('type','hidden').attr('name','prov_id').val(prov_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kab').val(kab));
            formdata.append($('<input>').attr('type','hidden').attr('name','kab_id').val(kab_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kec').val(kec));
            formdata.append($('<input>').attr('type','hidden').attr('name','kec_id').val(kec_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa').val(desa));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa_id').val(desa_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','alamat').val(alamat));
            $('body').append(formdata);
            formdata.submit();
        }else{
            alert('Lengkapi data yang kosong.');
        }
    });

    // END OF LOKASI GALIAN

// alert('pret');
})(jQuery);
</script>
@append