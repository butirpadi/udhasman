@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
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
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="invoice/customer" >Customer Invoices</a> 
        <i class="fa fa-angle-double-right" ></i> 
        <a href="invoice/customer/show-one-invoice/{{$data->id}}" >{{$data->inv_number}}</a> 
        <i class="fa fa-angle-double-right" ></i> 
        Register Payment
    </h1>
</section>

<!-- Main content -->
<section class="content">
    {{-- data hidden  --}}
    <input type="hidden" name="customer_invoice_id" value="{{$data->id}}">
    {{-- <input type="hidden" name="so_master_id" value="{{$so_master->id}}"> --}}
    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <a class="btn btn-primary" style="margin-top:0;" id="btn-reg-payment" href="sales/order/reg-payment/{{$so_master->id}}" ><i class="fa fa-download" ></i> Register Payment</a> --}}

            <label> 
                <small>Register Payment</small> 
                <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >{{$data->inv_number}}</h4>
            </label>

            {{-- <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label> --}}
            {{-- <a class="btn  btn-arrow-right pull-right disabled {{$data->status == 'P' ? 'bg-blue' : 'bg-gray'}}" >Paid</a> --}}

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Posted</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Draft</a>
        </div>
        <div class="box-body">
            {{-- <form method="POST" action="sales/order/save-payment" > --}}
                
                <table class="table" >
                    <tbody>
                        <tr>
                            <td class="col-lg-2">
                                <label>Source Document</label>
                            </td>
                            <td class="col-lg-3" >
                                {{$data->inv_number}}
                            </td>
                            <td class="col-lg-2" ></td>
                            <td class="col-lg-2">
                                <label>Payment Date</label>
                            </td>
                            <td class="col-lg-3" >
                                <input type="text" name="payment_date" class="form-control input-tanggal" value="{{date('d-m-Y')}}" required>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <label>Amount Due</label>
                            </td>
                            <td  >
                                <input type="text" name="amount_due" class="form-control text-right" value="{{$data->amount_due}}" readonly>
                            </td>
                            <td  ></td>
                            <td >
                                <label>Payment Amount</label>
                            </td>
                            <td >
                                <input type="text" name="payment_amount" class="form-control text-right" value="{{$data->amount_due}}" autofocus required>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5" >
                                <button type="submit" class="btn btn-primary" id="btn-save" >Save</button>
                                <a class="btn btn-danger" id="btn-cancel" href="invoice/customer/show-one-invoice/{{$data->id}}" >Cancel</a>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            {{-- </form> --}}


        </div><!-- /.box-body -->
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
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

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('input[name=payment_amount], input[name=amount_due]').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    // END OF AUTONUMERIC

    // CEK PAYMENT AMOUNT APAKAH LEBIH BESAR DARI AMOUNT DUE
    $('#btn-save').click(function(){
        var amount_due = $('input[name=amount_due]').autoNumeric('get');
        var payment_amount = $('input[name=payment_amount]').autoNumeric('get');
        // var so_master_id = $('input[name=so_master_id]').val();
        var payment_date = $('input[name=payment_date]').val();
        var customer_invoice_id = $('input[name=customer_invoice_id]').val();
        
        if(Number(payment_amount) > Number(amount_due)){
            alert('Payment amount lebih besar dari amount due.');
            // fokuskan
            $('input[name=payment_amount]').select();
        }else{
            var newform = $('<form>').attr('method','POST').attr('action','invoice/customer/save-register-payment');
            newform.append($('<input>').attr('type','hidden').attr('name','customer_invoice_id').val(customer_invoice_id));
            newform.append($('<input>').attr('type','hidden').attr('name','payment_amount').val(payment_amount));
            // newform.append($('<input>').attr('type','hidden').attr('name','so_master_id').val(so_master_id));
            newform.append($('<input>').attr('type','hidden').attr('name','payment_date').val(payment_date));
            // newform.append($('<input>').attr('type','hidden').attr('name','customer_invoice_id').val(customer_invoice_id));
            newform.submit();

            //$.post('invoice/customer/save-register-payment',{
            //    'payment_amount' : payment_amount,
            //    'payment_date' : payment_date,
            //    'customer_invoice_id' : customer_invoice_id
            //},function(){
            //    location.href = "invoice/customer/show/" + customer_invoice_id;
            //});
        }

        return false;
    });
    // END OF CEK PAYMENT AMOUNT APAKAH LEBIH BESAR DARI AMOUNT DUE


})(jQuery);
</script>
@append