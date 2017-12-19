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
        <a href="master/product" >Data Product</a> 
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
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" required autofocus autocomplete="off" />
                    <input type="text" name="kode" class="form-control hide" required autocomplete="off" >                    
                </div>
                <div class="col-xs-6" >
                    <label>Product Unit</label>
                    {!! Form::select('product_unit',$select_unit,null,['class'=>'form-control']) !!}

                </div>
            </div>
        </div>
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
            <a class="btn btn-danger" href="master/product" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div>

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {

    // unselect select element
    $('select[name=product_unit]').val([]);
   
    // SAVE LOKASI GALIAN
   
    $('#btn-save').click(function(){
        // cek kelengkapan data
        var nama = $('input[name=nama]').val();
        var kode = $('input[name=kode]').val();
        var product_unit = $('select[name=product_unit]').val();
        

        if(nama != "" ){
            var formdata = $('<form>').attr('method','POST').attr('action','master/product/insert');
            formdata.append($('<input>').attr('type','hidden').attr('name','kode').val(kode));
            formdata.append($('<input>').attr('type','hidden').attr('name','nama').val(nama));
            formdata.append($('<input>').attr('type','hidden').attr('name','product_unit').val(product_unit));
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