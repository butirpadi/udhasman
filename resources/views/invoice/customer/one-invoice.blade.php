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

    #table-master-so tr td{
        vertical-align: top;
    }

    #table-invoice-detail thead tr th{
        text-align: center;
    }

    tr.row-total > td{
        /*margin:0!important ;*/
        padding-left: 0!important;
        padding-top: 0!important;
        padding-bottom: 0!important;
        border:none!important;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="invoice/customer" >Customer Invoices</a> <i class="fa fa-angle-double-right" ></i> {{$data->inv_number}}
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->inv_number}}</h3></label>
            
             
            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data->status == 'P' ? 'bg-blue' : 'bg-gray'}}" >Paid</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled {{$data->status == 'V' ? 'bg-blue' : 'bg-gray'}}" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->status == 'O' ? 'bg-blue' : 'bg-gray'}}"" >Open</a>

            {{-- <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled {{$data->status == 'D' ? 'bg-blue' : 'bg-gray'}}"" >Draft</a> --}}

        </div>
        <div class="box-body">

            <input type="hidden" name="customer_invoice_id" value="{{$data->id}}">
            
            <div class="row" >
                <div class="col-sm-10 col-md-10 col-lg-10" >
                    <table class="table table-condensed no-border" id="table-master-so" >
                        <tbody>
                            <tr>
                                <td class="col-lg-2">
                                    <label>Customer</label>
                                </td>
                                <td class="col-lg-4" >
                                    {{'['.$data->kode_customer .'] ' .$data->customer}}
                                </td>
                                <td class="col-lg-2" >
                                    <label>SO Ref#</label>
                                </td>
                                <td class="col-lg-2" >
                                    {{$data->order_number}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @if($sales_order->is_direct_sales == 'Y')
                                        <label>Nopol</label>
                                    @else
                                        <label>Pekerjaan</label>
                                    @endif
                                </td>
                                <td>
                                    @if($sales_order->is_direct_sales == 'Y')
                                        {{$sales_order->nopol}}
                                    @else
                                        @if($data->pekerjaan)
                                            {{$data->pekerjaan}}<br/>
                                            @if($data->alamat_pekerjaan != '')
                                                {{$data->alamat_pekerjaan}}
                                            @endif
                                            @if($data->desa != "" )
                                             {{', ' . $data->desa . ', ' . $data->kecamatan}} <br/>
                                            {{$data->kabupaten . ', ' . $data->provinsi }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    @endif
                                </td>
                                <td  >
                                    <label>SO Date</label>
                                </td>
                                <td  >
                                    {{$data->order_date_formatted}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2" >
                    @if($data->status != 'O')
                        <a class="btn btn-app pull-right" href="invoice/customer/payments/{{$data->id}}" >
                            <span class="badge bg-green">{{count($payments)}}</span>
                            <i class="fa fa-money"></i> Payments
                        </a>
                    @endif
                </div>
            </div>

            <h4 class="page-header" style="font-size:14px;color:#3C8DBC"><strong>PRODUCT DETAILS</strong></h4>

            {{-- DEFINED TOTAL VARIABLE --}}
            <?php $total_kubikasi = 0;?>
            <?php $total_ritase=0; ?>
            <?php $total_tonase=0; ?>

            @if($data_kubikasi)

                <table  class="table table-bordered table-condensed" id="table-invoice-detail" >
                    <thead>
                        <tr>
                            <th colspan="10" class="label-primary" >
                                <label>DATA KALKULASI : KUBIKASI</label>
                            </th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="width:40px;" >NO</th>
                            <th rowspan="2" >DELIVERY DATE</th>
                            <th rowspan="2" >NOPOL</th>
                            <th rowspan="2" >MATERIAL</th>
                            <th colspan="3" >KUBIKASI</th>
                            <th rowspan="2" >VOLUME</th>
                            <th rowspan="2" >HARGA</th>
                            <th rowspan="2" >TOTAL</th>
                        </tr>
                        <tr>
                            <th>P</th>
                            <th>L</th>
                            <th>T</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rownum=1; ?>
                        <?php $vol=0; ?>
                       @foreach($data_kubikasi as $dt)
                        <tr>
                            <td class="text-right">
                                {{$rownum++}}
                            </td>
                            <td>
                                {{$dt->delivery_date_formatted}}
                            </td>
                            <td>
                                @if($dt->nopol)
                                {{$dt->nopol}}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                {{$dt->material}}
                            </td>
                            <td class="text-right" >                                
                                    {{$dt->panjang}}                                
                            </td>
                            <td class="text-right" >                               
                                    {{$dt->lebar}}                                
                            </td>
                            <td class="text-right" >                                
                                    {{$dt->tinggi}}                            
                            </td>
                            <td class="text-right" style="background-color:whitesmoke;" >                                
                                    {{$dt->volume}}                                
                            </td>
                            
                            <td class="text-right uang" >{{$dt->unit_price}}</td>
                            <td class="text-right uang" style="background-color:whitesmoke;" >{{$dt->total}}</td>
                        </tr>
                        <?php $vol+= $dt->volume; ?>
                        <?php $total_kubikasi += $dt->total;?>
                       @endforeach                   
                        <tr style="border-top: solid 2px gray;" >
                            <td colspan="7" class="text-right" >
                                <label>TOTAL</label>
                            </td>
                            <td class="text-right" style="background-color:whitesmoke;" >
                                <label>{{$vol}}</label>
                            </td>
                            <td colspan="2" class="text-right " style="background-color:whitesmoke;" >
                                <label class="uang" >{{$total_kubikasi}}</label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br/>
            @endif

            @if($data_tonase)
                <table  class="table table-bordered table-condensed" id="table-invoice-detail" >
                    <thead>
                        <tr>
                            <th colspan="9" class="label-success" >
                                <label>DATA KALKULASI : TONASE</label>
                            </th>
                        </tr>
                        <tr>
                            <th rowspan="2" style="width:40px;" >NO</th>
                            <th rowspan="2" >DELIVERY DATE</th>
                            <th rowspan="2" >NOPOL</th>
                            <th rowspan="2" >MATERIAL</th>
                            <th colspan="2" >TONASE</th>
                            <th rowspan="2" >NETTO</th>
                            <th rowspan="2" >HARGA</th>
                            <th rowspan="2" >TOTAL</th>
                        </tr>
                        <tr>
                            <th>GROSS</th>
                            <th>TARE</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rownum=1; ?>
                        <?php $netto=0; ?>
                       @foreach($data_tonase as $dt)
                        <tr>
                            <td class="text-right">
                                {{$rownum++}}
                            </td>
                            <td>
                                {{$dt->delivery_date_formatted}}
                            </td>
                            <td>
                                @if($dt->nopol)
                                {{$dt->nopol}}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                {{$dt->material}}
                            </td>
                            <td class="text-right" >                                
                                    {{$dt->gross}}                                
                            </td>
                            <td class="text-right" >                               
                                    {{$dt->tarre}}                                
                            </td>
                            <td class="text-right" style="background-color:whitesmoke;" >
                                    {{$dt->netto}}                                
                            </td>                            
                            <td class="text-right uang" >{{$dt->unit_price}}</td>
                            <td class="text-right uang" style="background-color:whitesmoke;" >{{$dt->total}}</td>
                        </tr>
                        <?php $netto+= $dt->netto; ?>
                        <?php $total_tonase += $dt->total; ?>
                       @endforeach                   
                        <tr style="border-top: solid 2px gray;" >
                            <td colspan="6" class="text-right" >
                                
                            </td>
                            <td class="text-right" style="background-color:whitesmoke;" >
                                <label>{{$netto}}</label>
                            </td>
                            <td colspan="2" class="text-right " style="background-color:whitesmoke;" >
                                <label class="uang" >{{$total_tonase}}</label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <br/>
            @endif

            @if($data_ritase)
                <table  class="table table-bordered table-condensed" id="table-invoice-detail" >
                    <thead>
                        <tr>
                            <th colspan="7" class="bg-orange"  >
                                <label>DATA KALKULASI : RITASE</label>
                            </th>
                        </tr>
                        <tr>
                            <th  style="width:40px;" >NO</th>
                            {{-- @if($sales_order->is_direct_sales == 'N') --}}
                            <th  >DELIVERY DATE</th>
                            <th  >NOPOL</th>
                            {{-- @endif --}}
                            <th  >MATERIAL</th>
                            <th  >QUANTITY</th>
                            <th  >HARGA</th>
                            <th  >TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rownum=1; ?>
                        <?php $qty=0; ?>

                       @foreach($data_ritase as $dt)
                        <tr>
                            <td class="text-right">
                                {{$rownum++}}
                            </td>
                            {{-- @if($sales_order->is_direct_sales == 'N') --}}
                            <td>
                                @if($sales_order->is_direct_sales == 'Y')
                                    -
                                @else
                                    {{$dt->delivery_date_formatted}}
                                @endif
                            </td>
                            <td>
                                @if($dt->nopol)
                                {{$dt->nopol}}
                                @else
                                    @if($sales_order->is_direct_sales == 'Y')
                                        {{$sales_order->nopol}}
                                    @else
                                        -
                                    @endif
                                @endif
                            </td>
                            {{-- @endif --}}
                            <td>
                                @if($sales_order->is_direct_sales == 'Y')
                                    {{ '['.$dt->direct_kode_material.'] ' . $dt->direct_material }}
                                @else
                                    {{ '['.$dt->kode_material.'] ' . $dt->material }}
                                @endif
                            </td>
                            <td class="text-right" >                                
                                    {{$dt->qty}}                                
                            </td>                            
                            <td class="text-right uang" >{{$dt->unit_price}}</td>
                            <td class="text-right uang" style="background-color:whitesmoke;" >{{$dt->total}}</td>
                        </tr>
                        <?php $qty+= $dt->qty; ?>
                        <?php $total_ritase += $dt->total; ?>
                       @endforeach                   
                        <tr style="border-top: solid 2px gray;" >
                            <td colspan="4" class="text-right" >
                                <label>TOTAL</label>
                            </td>
                            <td class="text-right" style="background-color:whitesmoke;" >
                                <label>{{$qty}}</label>
                            </td>
                            {{-- <td class="text-right" style="background-color:whitesmoke;" >
                                <label>{{$qty}}</label>
                            </td> --}}
                            <td colspan="2"  class="text-right " style="background-color:whitesmoke;" >
                                <label class="uang" >{{$total_ritase}}</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
           
            <br/>
            <div class="row" >
                <div class="col-sm-8 col-md-8 col-lg-8" ></div>                
                <div class="col-sm-4 col-md-4 col-lg-4" >
                    <table class="table " >
                        <tbody>
                            <tr class="row-total" >
                                <td class="text-right" >Kubikasi</td>
                                <td>:</td>
                                <td class="text-right" >
                                    <label class="uang" >{{$total_kubikasi}}</label>
                                </td>                                
                            </tr>
                            <tr class="row-total" >
                                <td class="text-right" >Tonase</td>
                                <td>:</td>
                                <td class="text-right" >
                                    <label class="uang" >{{$total_tonase}}</label>
                                </td>                                
                            </tr>
                            <tr class="row-total" >
                                <td class="text-right" >Ritase</td>
                                <td>:</td>
                                <td class="text-right" >
                                    <label class="uang" >{{$total_ritase}}</label>
                                </td>                                
                            </tr>
                            <tr style="border-top:solid #CACACA 2px;" >
                                <td class="text-right" >Total</td>
                                <td>:</td>
                                <td class="text-right" >
                                    <label class="uang" >{{$data->total}}</label>
                                </td>                                
                            </tr>
                            @foreach($payments as $pay)
                                <tr style="background-color:#EEF0F0;">
                                    <td class="text-right">
                                        <i>Paid on {{$pay->date_formatted}}</i>
                                    </td>
                                    <td>:</td>
                                    <td class="text-right">
                                        <i><span class="uang" >{{$pay->payment_amount}}</span></i>
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="border-top:solid #CACACA 2px;" >
                                <td class="text-right" >Amount Due</td>
                                <td>:</td>
                                <td class="text-right" >
                                    <label class="uang" >{{$data->amount_due}}</label>
                                </td>                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            

        </div><!-- /.box-body -->
        <div class="box-footer" >
            @if($data->status == 'O') 
                <button class="btn btn-primary" id="btn-validate" ><i class="fa fa-check" ></i> Validate</button>
            @else
                @if($data->status != 'P')
                    <a class="btn btn-primary" id="btn-register-payment" href="invoice/customer/register-payment/{{$data->id}}" ><i class="fa fa-download" ></i> Register Payment</a>
                @endif
            @endif
            
            @if($data->status != "O")
                <div class="btn-group">
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Print <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="#">Direct Print</a></li>
                    <li><a href="#">PDF</a></li>
                  </ul>
                </div>
            @endif

            <a class="btn btn-danger" href="invoice/customer" ><i class="fa fa-close" ></i> Close</a>
        </div>
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {
    // Reconcile
    $('#btn-reconcile').click(function(){
        if(confirm('Anda akan membatalkan data ini? \nData yang telah tersimpan akan dihapus & tidak dapat dikembalikan.')){
            // alert('reconcile');
            location.href = $(this).data('href');
        }
    });

    // VALIDATE INVOICE 
    $('#btn-validate').click(function(){
        var invoice_id = $('input[name=customer_invoice_id]').val();
        // if(confirm("Anda akan mem-validasi data ini? pastikan data sudah benar & valid, data tidak dapat dirubah setelah proses ini.")){
            location.href = "invoice/customer/validate/" + invoice_id;
        // }
    });

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

})(jQuery);
</script>
@append