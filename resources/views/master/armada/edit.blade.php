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
        <a href="master/armada" >Data Armada</a> 
        <i class="fa fa-angle-double-right" ></i> 
        Edit
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid" >
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->nopol}}</h3></label>

            <div class="pull-right" >
                <a class="btn btn-default btn-sm {{$prev?'':'disabled'}}" href="{{$prev ? 'master/armada/edit/'.$prev->id : url()->current().'#'}}" ><i class="fa fa-angle-double-left" ></i></a>
                <a class="btn btn-default btn-sm {{$next?'':'disabled'}}" " href="{{$next ? 'master/armada/edit/'.$next->id : url()->current().'#'}}" ><i class="fa fa-angle-double-right" ></i></a>
            </div>
        </div>
        <div class="box-body" >
            <div class="row" >
                <div class="col-xs-6" >
                    <div class="form-group">
                        <label>Nopol</label>
                        <input type="text" name="nopol" class="form-control " data-id="" autofocus required value="{{$data->nopol}}" />
                        <input type="text" name="kode" class="form-control hide" required autocomplete="off" value="{{$data->kode}}" readonly />
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="nama" class="form-control" autocomplete="off" value="{{$data->nama}}" />
                        <input type="hidden" name="id" value="{{$data->id}}" />   
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" href="master/armada" ><i class="fa fa-close" ></i> Close</a>
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
        var kode = $('input[name=kode]').val();
        var nopol = $('input[name=nopol]').val();
        var driver = $('select[name=driver]').val();

        if( nopol != "" ){
            var formdata = $('<form>').attr('method','POST').attr('action','master/armada/update');
            formdata.append($('<input>').attr('type','hidden').attr('name','id').val(id));
            formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
            formdata.append($('<input>').attr('type','hidden').attr('name','kode').val(kode));
            formdata.append($('<input>').attr('type','hidden').attr('name','nopol').val(nopol));
            formdata.append($('<input>').attr('type','hidden').attr('name','driver').val(driver));
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