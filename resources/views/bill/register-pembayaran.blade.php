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
        <a href="billinvoice/bill-pembelian" >Bill Pembelian</a> <i class="fa fa-angle-double-right" ></i> 
        <a href="billinvoice/bill-pembelian/edit/{{$data_bill->id}}" >{{$data_bill->ref}}</a> <i class="fa fa-angle-double-right" ></i> Register Pembayaran
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >Register Pembayaran</h3></label>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray " >POSTED</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue " >DRAFT</a>
        </div>
        <form name="form_bill_pembayaran" method="POST" action="billinvoice/bill-pembelian/save-register-pembayaran"  >
            <input name="bill_id" type="hidden" value="{{$data_bill->id}}">
            <div class="box-body">
                <table class="table table-condensed no-border" >
                    <tbody>
                        <tr>
                            <td>
                                <label>Nomor Pembelian</label>
                            </td>
                            <td>
                                <input type="text" name="ref" class="form-control" value="{{$data_bill->ref}}" disabled>
                            </td>
                            <td class="col-lg-2" >
                                <label>Tanggal</label>
                            </td>
                            <td class="col-lg-4" >
                                <input type="text" name="tanggal" class="input-tanggal form-control" value="{{$data_bill->tanggal_format}}" required>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label>Amount Due</label>
                            </td>
                            <td>
                                <input type="text" name="amount_due" class="form-control uang text-right" value="{{$data_bill->amount_due}}" disabled>
                            </td>
                            <td class="col-lg-2" >
                                <label>Jumlah Bayar</label>
                            </td>
                            <td class="col-lg-4" >
                                <input type="text" name="jumlah_bayar" class="text-right form-control uang" value="{{$data_bill->amount_due}}" required>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
            <div class="box-footer" >
                <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
                
                <a class="btn btn-danger" id="btn-cancel-save" href="billinvoice/bill-pembelian/edit/{{$data_bill->id}}" ><i class="fa fa-close" ></i> Close</a>
            </div>
        </form>
    </div><!-- /.box -->

</section><!-- /.content -->

<!-- /.modal -->
</div>


@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<!-- Select2 -->
<script src="plugins/select2/dist/js/select2.full.min.js"></script>



<script type="text/javascript">
(function ($) {
    $('.uang').autoNumeric('init',{
            vMin:'0.00',
            vMax:'9999999999.00',
            aSep: ',',
            aDec: '.'
        });

    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });

    // CANCEL PEMBELIAN
    $('#btn-cancel-pembelian').click(function(){
        if(confirm('Anda akan membatalan transaksi pembelian ini?')){

        }else{
            return false;
        }
    });

    // SUBMIT FORM PEMBAYARAN
    $('form[name=form_bill_pembayaran]').submit(function(){
        var amount_due = $('input[name=amount_due]').autoNumeric('get');
        var jumlah_bayar = $('input[name=jumlah_bayar]').autoNumeric('get');

        // ganti format jumlah_bayar
        $('input[name=jumlah_bayar]').autoNumeric('destroy');
        $('input[name=jumlah_bayar]').val(jumlah_bayar);

        if(Number(jumlah_bayar) > Number(amount_due) || jumlah_bayar == "" || Number(jumlah_bayar) == 0){
            alert('Jumlah bayar tidak sesuai.');
            // focuskan ke input jumlah bayar
            $('input[name=jumlah_bayar]').focus();
            return false;
        }
    });

})(jQuery);
</script>
@append
