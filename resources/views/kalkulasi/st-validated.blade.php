@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/select2.min.css">
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
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="pengiriman" >Kalkulasi Pengiriman</a> <i class="fa fa-angle-double-right" ></i> {{$data_kalkulasi->kode_pengiriman}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data_kalkulasi->kode_pengiriman}}</h3></label>
            
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data_kalkulasi->status == 'DONE' ? 'bg-blue' : 'bg-gray'}}" >DONE</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data_kalkulasi->status == 'VALIDATED' ? 'bg-blue' : 'bg-gray'}}" >VALIDATED</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled {{$data_kalkulasi->status == 'OPEN' ? 'bg-blue' : 'bg-gray'}}" >OPEN</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>

            <a class="btn btn-arrow-right pull-right disabled {{$data_kalkulasi->status == 'DRAFT' ? 'bg-blue' : 'bg-gray'}}" >DRAFT</a>
        </div>
        <form name="formkalkulasi" action="kalkulasi/update" method="POST">
            <div class="box-body">
                <input type="hidden" name="kalkulasi_id" value="{{$data_kalkulasi->id}}">

                <table class="table table-condensed no-border no-padding table-master-header" >
                    <tbody>
                        <tr>
                            <td class="col-lg-1">
                                <label>Customer</label>
                            </td>
                            <td class="col-lg-5" >
                                {{$data_kalkulasi->nama_customer}}
                            </td>
                            <td class="col-lg-2" >
                                <label>Tanggal Order</label>
                            </td>
                            <td class="col-lg-4" id="label-tanggal-order" >
                                {{$data_kalkulasi->tanggal_order_format}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Pekerjaan</label>
                            </td>
                            <td>
                                {{$data_kalkulasi->nama_pekerjaan}}
                            </td>
                            <td  >
                                <label>Tanggal Pengiriman</label>
                            </td>
                            <td  >
                                {{$data_kalkulasi->tanggal_pengiriman_format}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Alamat</label>
                            </td>
                            <td style="vertical-align: top;" >
                                {{
                                    $data_kalkulasi->pekerjaan->alamat 
                                    . ($data_kalkulasi->pekerjaan->desa != '' ? ', ' . $data_kalkulasi->pekerjaan->desa :'') 
                                    . ($data_kalkulasi->pekerjaan->kecamatan != '' ? ', ' . $data_kalkulasi->pekerjaan->kecamatan :'') 
                                    . ($data_kalkulasi->pekerjaan->kabupaten != '' ? ', ' . $data_kalkulasi->pekerjaan->kabupaten :'') 
                                    . ($data_kalkulasi->pekerjaan->provinsi != '' ? ', ' . $data_kalkulasi->pekerjaan->provinsi :'') 
                                    }}
                            </td>
                            <td>
                                <label>Lokasi Galian</label>
                            </td>
                            <td>
                                {{$data_kalkulasi->lokasi_galian}}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <label>Driver</label>
                            </td>
                            <td>
                                {{$data_kalkulasi->karyawan . ' - ' . $data_kalkulasi->nopol}}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

                <table id="table-product" class="table table-bordered table-condensed" >
                    <thead>
                        <tr>
                            <th style="width:25px;" >NO</th>
                            <th>MATERIAL</th>
                            <th>KALKULASI</th>
                            <th>NOTA TIMBANG</th>
                            <th class="col-kubikasi {{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? '' : 'hide'}}">PANJANG</th>
                            <th class="col-kubikasi {{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? '' : 'hide'}}">LEBAR</th>
                            <th class="col-kubikasi {{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? '' : 'hide'}}">TINGGI</th>
                            <th class="col-kubikasi {{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? '' : 'hide'}}">VOLUME</th>
                            <th class="col-tonase {{$data_kalkulasi->kalkulasi == 'TONASE' ? '' : 'hide'}}">GROSS</th>
                            <th class="col-tonase {{$data_kalkulasi->kalkulasi == 'TONASE' ? '' : 'hide'}}">TARE</th>
                            <th class="col-tonase {{$data_kalkulasi->kalkulasi == 'TONASE' ? '' : 'hide'}}">NETTO</th>
                            <th class="col-ritase {{$data_kalkulasi->kalkulasi == 'RITASE' ? '' : 'hide'}}">QUANTITY</th>
                            <th>HARGA SATUAN</th>
                            <th>TOTAL</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr  id="row-add-product"  >
                            <td class="text-right" >1</td>
                            <td>
                                {{$data_kalkulasi->nama_material}}
                            </td>
                            <td>
                                
                                {!! Form::select('kalkulasi',['RITASE'=>'RITASE','KUBIKASI'=>'KUBIKASI','TONASE'=>'TONASE'],$data_kalkulasi->kalkulasi,['class'=>'form-control','disabled']) !!}
                            </td>
                            <td >
                                <input type="text" class="form-control" name="nota_timbang" readonly value="{{$data_kalkulasi->no_nota_timbang}}">
                            </td>
                            <td class="col-kubikasi {{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? '' : 'hide'}} col-xs-1" >
                                <input type="text" name="panjang" class="form-control uang text-right" value="{{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? $data_kalkulasi->panjang : ''}}" readonly >
                            </td>
                            <td class="col-kubikasi {{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? '' : 'hide'}} col-xs-1">
                                <input type="text" name="lebar" class="form-control uang text-right" value="{{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? $data_kalkulasi->lebar : ''}}" readonly>
                            </td>
                            <td class="col-kubikasi {{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? '' : 'hide'}} col-xs-1">
                                <input type="text" name="tinggi" class="form-control uang text-right" value="{{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? $data_kalkulasi->tinggi : ''}}" readonly>
                            </td>
                            <td class="col-kubikasi {{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? '' : 'hide'}} col-xs-1">
                                <input type="text" name="volume" class="form-control uang text-right" value="{{$data_kalkulasi->kalkulasi == 'KUBIKASI' ? $data_kalkulasi->volume : ''}}" readonly>
                            </td>
                            <td class="col-tonase {{$data_kalkulasi->kalkulasi == 'TONASE' ? '' : 'hide'}} col-xs-1" >
                                <input type="text" name="gross" class="form-control uang text-right" value="{{$data_kalkulasi->kalkulasi == 'TONASE' ? $data_kalkulasi->gross : ''}}" readonly>
                            </td>
                            <td class="col-tonase {{$data_kalkulasi->kalkulasi == 'TONASE' ? '' : 'hide'}} col-xs-1">
                                <input type="text" name="tare" class="form-control uang text-right" value="{{$data_kalkulasi->kalkulasi == 'TONASE' ? $data_kalkulasi->tare : ''}}" readonly>
                            </td>
                            <td class="col-tonase {{$data_kalkulasi->kalkulasi == 'TONASE' ? '' : 'hide'}} col-xs-1">
                                <input type="text" name="netto" class="form-control uang text-right" value="{{$data_kalkulasi->kalkulasi == 'TONASE' ? $data_kalkulasi->netto : ''}}" readonly>
                            </td>
                            <td class="col-ritase {{$data_kalkulasi->kalkulasi == 'RITASE' ? '' : 'hide'}} col-xs-1">
                                <input type="text" name="quantity" class="form-control text-right" value="{{$data_kalkulasi->kalkulasi == 'RITASE' ? $data_kalkulasi->qty : ''}}" readonly>
                            </td>
                            <td class="col-xs-2">
                                <input type="text" name="harga_satuan" required class="form-control text-right uang" value="{{$data_kalkulasi->unit_price}}" readonly>
                            </td>
                            <td class="col-xs-2">
                                <input type="text" name="total" class="form-control text-right uang" value="{{$data_kalkulasi->total}}" readonly>
                            </td>
                        </tr>                   
                        
                    </tbody>
                </table>

            </div><!-- /.box-body -->
            <div class="box-footer" >
                {{-- <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button> --}}
                <a class="btn btn-danger" id="btn-cancel-save" href="kalkulasi" ><i class="fa fa-close" ></i> Close</a>
                {{-- <a class="btn btn-success pull-right"  id="btn-validate" href="kalkulasi/validate/{{$data_kalkulasi->id}}" ><i class="fa fa-check" ></i> Validate</a> --}}
            </div>        
        </form>

        

    </div><!-- /.box -->

<!-- /.modal -->
</div>

</section><!-- /.content -->


@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/select2/select2.full.min.js"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {
  // UANG FORMAT
    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.99'
        });
    // END OF SET DATEPICKER
})(jQuery);
</script>

@append