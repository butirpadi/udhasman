@extends('layouts.master')

@section('styles')
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
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
        <a href="master/customer" >Data Customer</a> 
        <i class="fa fa-angle-double-right" ></i> 
        {{$data->nama}}
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
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required autofocus autocomplete="off" value="{{$data->nama}}" />
                        <input type="hidden" name="id" value="{{$data->id}}" />
                        <input type="hidden" name="kode" class="form-control "  autocomplete="off" value="{{$data->kode}}" readonly >
                        
                    </div>  
                    <div class="form-group">
                        <label>NPWP</label>
                        <input type="text" name="npwp" class="form-control " autocomplete="off" value="{{$data->npwp}}" />
                        
                        
                    </div>  
                    <div class="form-group">
                        <label>Owner</label>
                        <input type="text" name="owner" class="form-control " autocomplete="off" value="{{$data->owner}}" />
                        
                        
                    </div>  
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control " value="{{$data->alamat}}" />
                        
                        
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
                    <div class="form-group">
                        <label>Telp</label>
                        <input type="text" class="form-control" name="telp" value="{{$data->telp}}" />
                        
                        
                    </div> 
                </div>

                <div class="col-xs-12" style="min-height: 600px;" >
                    <h4 id="section-pekerjaan" class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PEKERJAAN</strong></h4>
                    <a class="btn btn-primary" href="master/customer/create-pekerjaan/{{$data->id}}" ><i class="fa fa-plus-circle" ></i> Create Pekerjaan</a>
                    <br/>
                    <br/>
                    <table class="table table-bordered table-condensed datatable" >
                        <thead>
                            <tr>
                                <th class="col-xs-" >NAMA</th>
                                <th class="col-xs-1 text-center" >TAHUN</th>
                                <th class="col-xs-1" ></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pekerjaan as $dt)
                                <tr>
                                    <td>
                                        {{$dt->nama}}
                                    </td>
                                    <td  class="text-center">
                                        {{$dt->tahun}}
                                    </td>
                                    <td class="text-center" style="font-size: 20px;" >
                                        <a href="master/customer/edit-pekerjaan/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                        &nbsp;
                                        <a href="{{url()->current()}}#section-pekerjaan" data-originalid="{{$dt->id}}" class="text-red btn-del-pekerjaan"><i class="fa fa-trash" ></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" href="master/customer" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div>
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {

    var table_pekerjaan = $('.datatable').DataTable({
        sort:false
    });
     
    // SAVE 
    $('#btn-save').click(function(){
        // cek kelengkapan data
        var id = $('input[name=id]').val();
        var nama = $('input[name=nama]').val();
        var kode = $('input[name=kode]').val();
        var npwp = $('input[name=npwp]').val();
        var owner = $('input[name=owner]').val();
        var nopol = $('input[name=nopol]').val();
        var alamat = $('input[name=alamat]').val();
        var provinsi = $('input[name=provinsi]').val();
        var provinsi_id = $('input[name=provinsi]').data('id');
        var kabupaten = $('input[name=kabupaten]').val();
        var kabupaten_id = $('input[name=kabupaten]').data('id');
        var kecamatan = $('input[name=kecamatan]').val();
        var kecamatan_id = $('input[name=kecamatan]').data('id');
        var desa = $('input[name=desa]').val();
        var desa_id = $('input[name=desa]').data('id');
        var telp = $('input[name=telp]').val();
        var telp2 = $('input[name=telp2]').val();
        var telp3 = $('input[name=telp3]').val();

        if(nama != ""  && nopol != "" ){
            var formdata = $('<form>').attr('method','POST').attr('action','master/customer/update');
            formdata.append($('<input>').attr('type','hidden').attr('name','id').val(id));
            formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
            formdata.append($('<input>').attr('type','hidden').attr('name','kode').val(kode));
            formdata.append($('<input>').attr('type','hidden').attr('name','npwp').val(npwp));
            formdata.append($('<input>').attr('type','hidden').attr('name','owner').val(owner));
            formdata.append($('<input>').attr('type','hidden').attr('name','alamat').val(alamat));
            formdata.append($('<input>').attr('type','hidden').attr('name','provinsi').val(provinsi));
            formdata.append($('<input>').attr('type','hidden').attr('name','provinsi_id').val(provinsi_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kabupaten').val(kabupaten));
            formdata.append($('<input>').attr('type','hidden').attr('name','kabupaten_id').val(kabupaten_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','kecamatan').val(kecamatan));
            formdata.append($('<input>').attr('type','hidden').attr('name','kecamatan_id').val(kecamatan_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa').val(desa));
            formdata.append($('<input>').attr('type','hidden').attr('name','desa_id').val(desa_id));
            formdata.append($('<input>').attr('type','hidden').attr('name','telp').val(telp));
            formdata.append($('<input>').attr('type','hidden').attr('name','telp2').val(telp2));
            formdata.append($('<input>').attr('type','hidden').attr('name','telp3').val(telp3));
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
            // // set data customer
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
            // // set data customer
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
            // // set data customer
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
            // // set data customer
            $('input[name=desa]').data('id',suggestions.data);
            // alert($('input[name=desa]').data('id'));

        }

    });
    // END OF SET AUTOCOMPLETE KECAMATAN

    // DELETTE PEKERJAAN
    $(document).on('click','.btn-del-pekerjaan',function(){
        if(confirm('Anda akan menghapus data pekerjaan ini?')){
            $row = $(this).parent().parent();
            $data_id = $(this).data('originalid');
            $.get('master/customer/del-pekerjaan/'+$data_id,function(){
                $row.fadeOut();
            }).fail(function(){
                alert('Data pekerjaan tidak dapat dihapus.');
            });
        }
    });

// alert('pret');
})(jQuery);
</script>
@append