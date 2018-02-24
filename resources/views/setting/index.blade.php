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
        System Setting
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active" ><a href="#tab_2" data-toggle="tab">Company Setting</a></li>
          <li  ><a href="#tab_3" data-toggle="tab">Data Prefix</a></li>
          <li  ><a href="#tab_4" data-toggle="tab">System Setting</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_2">
                <form name="form-group-report" method="POST" action="setting/system/update" enctype="multipart/form-data" >
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Company Name</label>
                                <input type="text" name="company_name" value="{{Appsetting('company_name')}}" class="form-control"  placeholder="Company Name" >
                            </div>
                            <div class="form-group">
                                <label >Address</label>
                                <input type="text" name="alamat_1" value="{{Appsetting('alamat_1')}}" class="form-control" placeholder="Address" >
                            </div>
                            <div class="form-group">
                                <input type="text" name="alamat_2" value="{{Appsetting('alamat_2')}}" class="form-control" placeholder="Address" >
                            </div>
                            
                            
                        </div>
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Telp</label>
                                <input type="text" name="telp" value="{{Appsetting('telp')}}" class="form-control" placeholder="Telp" >
                            </div>

                            <div class="form-group"  >
                                <label >Logo</label>
                                <input type="file" name="company_logo">
                            </div>

                            <div class="form-group text-center"  >
                                <img name="logo" style="text-align: center;max-width: 200px;border: thin solid whitesmoke;padding: 10px;" src="{{url('img/' . Appsetting('company_logo'))}}" >
                            </div>
                            
                        </div> 
                        <div class="col-xs-12" >
                            <button type="submit" class="btn btn-primary"  ><i class="fa fa-check" ></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="tab_3">
                <form name="form-group-report" method="POST" action="setting/system/update" enctype="multipart/form-data" >
                    <div class="row" >
                        <div class="col-xs-4" >
                            <div class="form-group">
                                <label >Partner Prefix</label>
                                <input type="text" name="partner_prefix" value="{{Appsetting('partner_prefix')}}" class="form-control"  placeholder="Partner Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Staff Prefix</label>
                                <input type="text" name="alat_prefix" value="{{Appsetting('staff_prefix')}}" class="form-control"  placeholder="Staff Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Driver Prefix</label>
                                <input type="text" name="driver_prefix" value="{{Appsetting('driver_prefix')}}" class="form-control"  placeholder="Driver Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Customer Prefix</label>
                                <input type="text" name="customer_prefix" value="{{Appsetting('customer_prefix')}}" class="form-control"  placeholder="Customer Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Supplier Prefix</label>
                                <input type="text" name="supplier_prefix" value="{{Appsetting('supplier_prefix')}}" class="form-control"  placeholder="Supplier Prefix" >
                            </div>                     
                        </div>
                        <div class="col-xs-4" >
                            <div class="form-group">
                                <label >Pembelian Prefix</label>
                                <input type="text" name="pembelian_prefix" value="{{Appsetting('pembelian_prefix')}}" class="form-control"  placeholder="Pembelian Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Pengiriman Prefix</label>
                                <input type="text" name="pengiriman_prefix" value="{{Appsetting('pengiriman_prefix')}}" class="form-control"  placeholder="Pengiriman Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Operasional Alat Berat Prefix</label>
                                <input type="text" name="operasional_alat_prefix" value="{{Appsetting('operasional_alat_prefix')}}" class="form-control"  placeholder="Operasional Alat Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Payroll Prefix</label>
                                <input type="text" name="payroll_prefix" value="{{Appsetting('payroll_prefix')}}" class="form-control"  placeholder="Payroll Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Hutang Prefix</label>
                                <input type="text" name="hutang_prefix" value="{{Appsetting('hutang_prefix')}}" class="form-control"  placeholder="Hutang Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Piutang Prefix</label>
                                <input type="text" name="piutang_prefix" value="{{Appsetting('piutang_prefix')}}" class="form-control"  placeholder="Piutang Prefix" >
                            </div>
                        </div> 
                        <div class="col-xs-4" >
                            <div class="form-group">
                                <label >Kas Masuk Prefix</label>
                                <input type="text" name="cashbook_debit_prefix" value="{{Appsetting('cashbook_debit_prefix')}}" class="form-control"  placeholder="Kas Masuk Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Kas Keluar Prefix</label>
                                <input type="text" name="alat_prefix" value="{{Appsetting('cashbook_credit_prefix')}}" class="form-control"  placeholder="Kas Keluar Prefix" >
                            </div>
                            <div class="form-group">
                                <label >Multi Payment Prefix</label>
                                <input type="text" name="master_payment_in_prefix" value="{{Appsetting('master_payment_in_prefix')}}" class="form-control"  placeholder="Multi Payment Prefix" >
                            </div>
                        </div> 
                        <div class="col-xs-12" >
                            <button type="submit" class="btn btn-primary"  ><i class="fa fa-check" ></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane" id="tab_4">
                <form name="form-group-report" method="POST" action="setting/system/update" enctype="multipart/form-data" >
                    <div class="row" >
                        <div class="col-xs-6" >
                            <div class="form-group">
                                <label >Paging Item Number</label>
                                <input type="text" name="paging_item_number" value="{{Appsetting('paging_item_number')}}" class="form-control"  placeholder="Company Name" >
                            </div>
                            <div class="form-group">
                                <label >Login Background</label>
                                <input type="file" name="login_background">
                            </div>
                            <div class="form-group text-center"  >
                                <img style="text-align: center;max-width: 300px;border: thin solid whitesmoke;padding: 10px;" src="{{url('img/' . Appsetting('login_background'))}}" >
                            </div>
                            
                        </div>
                        <div class="col-xs-6" >
                            
                            
                        </div> 
                        <div class="col-xs-12" >
                            <button type="submit" class="btn btn-primary"  ><i class="fa fa-check" ></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {


})(jQuery);
</script>
@append
