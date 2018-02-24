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

    <section class="content">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active" ><a href="#tab_2" data-toggle="tab">Company Setting</a></li>
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
                            <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-check" ></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="box box-solid hide " id="box-report" >
        <div class="box-body" id="report-detail" >
            
        </div>
        <div class="box-footer" >
            <a class="btn btn-primary" id="btn-print-pdf" ><i class="fa fa-file-pdf-o" ></i> PDF</a>
            <a class="btn btn-success" id="btn-excel" ><i class="fa fa-file-excel-o" ></i> XLS</a>
        </div>
    </div>

</section><!-- /.content -->

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
