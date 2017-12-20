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
        <a href="master/customer" >Data Customer</a> <i class="fa fa-angle-double-right" ></i> 
        <a href="master/customer/edit/{{$customer->id}}" >{{$customer->nama}}</a> 
        <i class="fa fa-angle-double-right" ></i> {{$data->nama}}
    </h1>
</section>

<!-- Main content -->   
<section class="content">
    <div class="box box-solid" >
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->nama}}</h3></label>
        </div>
        <div class="box-body" >
            <div class="row" >
                <div class="col-xs-6" >
                    <div class="form-group">
                        <label>Customer</label>
                        <input type="text" name="customer" class="form-control" required autocomplete="off" value="{{$data->customer}}" readonly />
                        
                    </div>  
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required autofocus autocomplete="off" value="{{$data->nama}}" />
                        <input type="hidden" name="id" value="{{$data->id}}" />
                        
                    </div>  
                    <div class="form-group">
                        <label>Tahun Pekerjaan</label>
                        <input type="text" name="tahun" class="form-control" required  autocomplete="off" value="{{$data->tahun}}" />
                        
                    </div>  
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" required autocomplete="off" value="{{$data->alamat}}" />
                        
                    </div>  
                    
                </div>

                <div class="col-xs-6" >
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" required autocomplete="off" value="{{$data->provinsi}}" />
                        <input type="hidden" name="provinsi_id" class="form-control" required autocomplete="off" value="{{$data->provinsi_id}}" />                     
                    </div>  
                    <div class="form-group">
                        <label>Kota/Kabupaten</label>
                         <input type="text" name="kabupaten" class="form-control" required autocomplete="off" value="{{$data->kabupaten}}" />
                        <input type="hidden" name="kabupaten_id" class="form-control" required autocomplete="off" value="{{$data->kabupaten_id}}" />
                    </div>  
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" required autocomplete="off" value="{{$data->kecamatan}}" />
                        <input type="hidden" name="kecamatan_id" class="form-control" required autocomplete="off" value="{{$data->kecamatan_id}}" />
                        
                    </div> 
                    <div class="form-group">
                        <label>Desa</label>
                        <input type="text" name="desa" class="form-control" required autocomplete="off" value="{{$data->desa}}" />
                        <input type="hidden" name="desa_id" class="form-control" required autocomplete="off" value="{{$data->desa_id}}" />
                        
                    </div>
                </div>
            </div>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" href="master/customer/edit/{{$customer->id}}" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div>


</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {
     // SAVE LOKASI GALIAN   
    $('#btn-save').click(function(){
        // cek kelengkapan data
        var id = $('input[name=id]').val();
        var nama = $('input[name=nama]').val();
        var alamat = $('input[name=alamat]').val();
        var desa_id = $('input[name=desa_id]').val();
        var tahun = $('input[name=tahun]').val();

        if(nama != ""  ){
            var formdata = $('<form>').attr('method','POST').attr('action','master/customer/update-pekerjaan');
            formdata.append($('<input>').attr('type','hidden').attr('name','id').val(id));
            formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
            formdata.append($('<input>').attr('type','hidden').attr('name','alamat').val(alamat));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa_id').val(desa_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','tahun').val(tahun));
            $('body').append(formdata);
            formdata.submit();
        }else{
            alert('Lengkapi data yang kosong.');
        }
    });
    // END OF LOKASI GALIAN

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

// alert('pret');
})(jQuery);
</script>
@append