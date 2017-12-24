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
        <i class="fa fa-angle-double-right" ></i> 
        Edit
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid" >
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->nama}}</h3></label>

            <div class="pull-right" >
                <a class="btn btn-default btn-sm {{$prev?'':'disabled'}}" href="{{$prev ? 'master/lokasi/edit/'.$prev->id : url()->current().'#'}}" ><i class="fa fa-angle-double-left" ></i></a>
                <a class="btn btn-default btn-sm {{$next?'':'disabled'}}" " href="{{$next ? 'master/lokasi/edit/'.$next->id : url()->current().'#'}}" ><i class="fa fa-angle-double-right" ></i></a>
            </div>
        </div>
        <div class="box-body" >
            <div class="row" >
                <div class="col-xs-6" >
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required autofocus autocomplete="off" value="{{$data->nama}}" />
                        <input type="hidden" name="id" value="{{$data->id}}" />
                        <input type="text" name="kode" class="form-control hide"  autocomplete="off" value="{{$data->kode}}" readonly />

                    </div>  
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control " value="{{$data->alamat}}"/>
                        
                    </div>  
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="provinsi" class="form-control " data-id="{{$data->provinsi_id}}" value="{{$data->provinsi}}" />
                        
                    </div>  
                </div>
                <div class="col-xs-6" >
                    <div class="form-group">
                        <label>Kota/Kabupaten</label>
                        <input type="text" name="kabupaten" class="form-control " data-id="{{$data->kabupaten_id}}" value="{{$data->kabupaten}}" />
                        
                    </div>  
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control " data-id="{{$data->kecamatan_id}}" value="{{$data->kecamatan}}" />

                    </div>  
                    <div class="form-group">
                        <label>Desa</label>
                        <input type="text" name="desa" class="form-control " data-id="{{$data->desa_id}}" value="{{$data->desa}}" />

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
    // alert('ok');
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

    // SAVE LOKASI GALIAN
    $('#btn-save').click(function(){
        // cek kelengkapan data
        var id = $('input[name=id]').val();
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

        if(nama != "" && prov != "" && prov_id != "" && kab != "" && kab_id != "" && kec != "" && kec_id != "" && desa != "" && desa_id != ""){
            var formdata = $('<form>').attr('method','POST').attr('action','master/lokasi/update');
            formdata.append($('<input>').attr('type','hidden').attr('name','id').val(id));
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