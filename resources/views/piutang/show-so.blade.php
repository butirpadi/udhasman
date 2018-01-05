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
        <a href="finance/piutang" >Piutang</a> <i class="fa fa-angle-double-right" ></i> 
        <a href="finance/piutang/edit/{{$data->id}}" >{{$data->name}} </a> <i class="fa fa-angle-double-right" ></i> {{$data->so->name}} 
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <form role="form" method="POST" action="delivery/update" >
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->so->name}}</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data->so->state == 'done' ? 'bg-blue' : 'bg-gray'}}" >DONE</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->so->state == 'open' ? 'bg-blue' : 'bg-gray'}}  " >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->so->state == 'draft' ? 'bg-blue' : 'bg-gray'}}" >DRAFT</a>
        </div>
        <div class="box-body">               
            <div class="row" >
                <div class="col-xs-6" >
                    <div class="form-group">
                        <label for="customerLabel">Customer</label>
                        <input type="text" name="customer" class="form-control" value="{{$data->so->customer}}" readonly/>
                        <input name="original_id" value="{{$data->so->id}}" class="hide" />
                    </div>  
                    <div class="form-group">
                        <label >Pekerjaan</label>
                        <input type="text" name="pekerjann" class="form-control" value="{{$data->so->pekerjaan}}" readonly/>
                    </div>
                    <div class="form-group">
                        <label >Material</label>
                        <input type="text" name="material" class="form-control" value="{{$data->so->material}}" readonly/>
                    </div>
                    <div class="form-group">
                        <label >Lokasi Galian</label>
                        <input type="text" name="lokasi_galian" class="form-control" value="{{$data->so->lokasi_galian}}" readonly/>
                    </div>
                </div>
                <div class="col-xs-6" >
                    <div class="form-group">
                        <label for="tanggalLabel">Tanggal</label>
                        <input type="text" name="tanggal" class="input-tanggal form-control" value="{{$data->so->order_date_format}}" readonly>    
                    </div>
                    <div class="form-group">
                        <label >Driver</label>
                        <input type="text" name="driver" class="form-control" value="{{$data->so->karyawan}}" readonly/>
                    </div>
                    <div class="form-group">
                      <label >Nopol</label>
                      <input type="text" name="nopol" class="form-control" value="{{$data->so->nopol}}" readonly/>
                    </div>
                    <div class="form-group">
                      <label >Nota Timbang</label>
                      <input type="text" name="nota_timbang" class="form-control" value="{{$data->so->nota_timbang}}" readonly>    
                    </div>
                    
                </div>

                 <div class="col-xs-12 {{$data->so->state == 'draft' ? 'hide':''}} " >
                    <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>KALKULASI</strong></h4> 
                    <table class="table table-bordered table-condensed" >
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2" >KALKULASI</th>
                                <th class="text-center {{$data->so->kalkulasi != 'kubik' ? 'hide' : '' }} group-kubik" colspan="4" >KUBIKASI</th>
                                <th class="text-center {{$data->so->kalkulasi != 'ton' ? 'hide' : '' }} group-ton" colspan="3" >TONASE</th>
                                <th class="text-center {{$data->so->kalkulasi != 'rit' ? 'hide' : '' }} group-rit" class="text-center" rowspan="2" >RIT</th>
                                <th class="text-center col-xs-2" rowspan="2" >HARGA SATUAN</th>
                                <th class="text-center col-xs-2" rowspan="2" >TOTAL</th>
                            </tr>
                            <tr>
                                <th class="text-center {{$data->so->kalkulasi != 'kubik' ? 'hide' : '' }} group-kubik col-xs-1">P</th>
                                <th class="text-center {{$data->so->kalkulasi != 'kubik' ? 'hide' : '' }} group-kubik col-xs-1">L</th>
                                <th class="text-center {{$data->so->kalkulasi != 'kubik' ? 'hide' : '' }} group-kubik col-xs-1">T</th>
                                <th class="text-center {{$data->so->kalkulasi != 'kubik' ? 'hide' : '' }} group-kubik col-xs-1">VOL</th>
                                <th class="text-center {{$data->so->kalkulasi != 'ton' ? 'hide' : '' }} group-ton col-xs-1">G</th>
                                <th class="text-center {{$data->so->kalkulasi != 'ton' ? 'hide' : '' }} group-ton col-xs-1">T</th>
                                <th class="text-center {{$data->so->kalkulasi != 'ton' ? 'hide' : '' }} group-ton col-xs-1">NET</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{Form::select('kalkulasi',['rit'=>'Rit','kubik'=>'Kubikasi','ton'=>'Tonase'],$data->so->kalkulasi,['class'=>'form-control','disabled'])}}
                                </td>
                                <td class="{{$data->so->kalkulasi != 'kubik' ? 'hide' : '' }} group-kubik">
                                    <input type="text" name="panjang" class="form-control text-right" value="{{$data->so->panjang}}" readonly />
                                </td>
                                <td class="{{$data->so->kalkulasi != 'kubik' ? 'hide' : '' }} group-kubik">
                                    <input type="text" name="lebar" class="form-control  text-right" value="{{$data->so->lebar}}" readonly />
                                </td>
                                <td class="{{$data->so->kalkulasi != 'kubik' ? 'hide' : '' }} group-kubik">
                                    <input type="text" name="tinggi" class="form-control  text-right" value="{{$data->so->tinggi}}" readonly />
                                </td>
                                <td class="{{$data->so->kalkulasi != 'kubik' ? 'hide' : '' }} group-kubik">
                                    <input type="text" name="volume" class="form-control  text-right" value="{{$data->so->volume}}" readonly />
                                </td>
                                <td class="{{$data->so->kalkulasi != 'ton' ? 'hide' : '' }} group-ton">
                                    <input type="text" name="gross" class="form-control  text-right" value="{{$data->so->gross}}" readonly />
                                </td>
                                <td class="{{$data->so->kalkulasi != 'ton' ? 'hide' : '' }} group-ton">
                                    <input type="text" name="tare" class="form-control  text-right" value="{{$data->so->tare}}" readonly />
                                </td>
                                <td class="{{$data->so->kalkulasi != 'ton' ? 'hide' : '' }} group-ton">
                                    <input type="text" name="netto" class="form-control  text-right" value="{{$data->so->netto}}" readonly />
                                </td>
                                <td class="{{$data->so->kalkulasi != 'rit' ? 'hide' : '' }} group-rit">
                                    <input type="text" name="qty" class="form-control  text-right" value="{{$data->so->qty}}" value="1" readonly />
                                </td>
                                <td>
                                    <input type="text" name="harga_satuan" class="form-control  text-right" value="{{number_format($data->so->harga_satuan,2,'.',',')}}" readonly />
                                </td>
                                <td>
                                    <input type="text" name="harga_total" class="form-control  text-right" value="{{number_format($data->so->harga_total,2,'.',',')}}" readonly />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
               

        </div><!-- /.box-body -->
        <div class="box-footer" >            
            <a class="btn btn-danger" id="btn-cancel-save" href="finance/piutang/edit/{{$data->id}}" ><i class="fa fa-close" ></i> Close</a>
        </div>
        </form>
    </div><!-- /.box -->

</section><!-- /.content -->

<!-- /.modal -->
</div>

@stop

@section('scripts')
<script type="text/javascript">
(function ($) {

   // Delete
   $('#btn-delete').click(function(){
        if(confirm('Anda akan menghapus data ini?')){
            location.href="delivery/delete-single/"+$('input[name=original_id]').val();
        }
   });

})(jQuery);
</script>
@append

