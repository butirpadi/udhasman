@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
         <a href="invoice/customer" >Customer Invoices</a> 
         <i class="fa fa-angle-double-right" ></i> 
         <a href="invoice/customer/edit/{{$data->id}}" >{{$data->inv_number}}</a> 
         <i class="fa fa-angle-double-right" ></i> 
         Payments
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label>
                <small>Payments of</small>
                <h3 style="margin:0;padding:0;font-weight:bold;font-size: 1.3em" >{{$data->inv_number}}</h3>
            </label>
        </div>
        <div class="box-body">
            
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        {{-- <th style="width:25px;">No</th> --}}
                        <th>Payment Date</th>
                        <th>Ref#</th>
                        {{-- <th>Invoice Ref #</th> --}}
                        <th>Status</th>
                        <th>Payment Amount</th>                        
                        <th ></th>
                    </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                   @foreach($payments as $dt)
                        <tr>
                            {{-- <td>{{$rownum++}}</td> --}}
                            <td>
                                {{$dt->date_formatted}}
                            </td>
                            <td>
                                {{$dt->payment_number}}
                            </td>
                            {{-- <td>
                                {{$dt->inv_number}}
                            </td> --}}
                            <td>
                                @if($dt->status == 'P')
                                    Posted
                                @else
                                    Draft
                                @endif
                            </td>
                            <td class="uang text-right" >
                                {{$dt->payment_amount}}
                            </td>
                            
                            <td class="text-center col-sm-1" >
                                {{-- <a class="btn btn-primary btn-xs" href="invoice/customer/payment/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a> --}}
                                <a class="btn btn-success btn-xs btn-print-payment" href="#" ><i class="fa fa-print" ></i></a>
                                <a class="btn btn-danger btn-xs btn-delete-payment" href="invoice/customer/payments/delete/{{$dt->id}}" ><i class="fa fa-trash" ></i></a>
                            </td>
                        </tr>
                        <?php $total+=$dt->payment_amount; ?>
                   @endforeach
                    <tr style="background-color: #EFEFF7;border-top: 2px solid #CACACA;" >
                        <td colspan="3" ></td>
                        <td class=" text-right" >
                            <label class="uang" >{{$total}}</label>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div><!-- /.box-body -->
        <div class="box-footer" >
            {{-- <a class="btn btn-danger" href="invoice/customer/edit/{{$data->id}}" ><i class="fa fa-close" ></i> Close</a> --}}
            <a class="btn btn-danger" href="invoice/customer/show-one-invoice/{{$data->id}}" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {   
    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });

    // normalize
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });
    // END OF AUTONUMERIC

    // DELETE PAYMENT
    $('.btn-delete-payment').click(function(){
        if(confirm('Anda akan menghapus data ini?')){

        }else{
            return false;
        }
    });

})(jQuery);
</script>
@append