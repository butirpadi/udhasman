@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Gaji Driver
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        @include('gaji.gaji_driver.box-header')
        <div class="box-body">
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed table-striped " id="table-data" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode</th>
                        <th>Driver</th>
                        <th>Jumlah</th>
                        <th>Amount Due</th>
                        <th>State</th>
                        <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt)
                    <tr>
                        <td align="center" >
                            {{$dt->name}}
                        </td>
                        <td align="center" >
                            {{$dt->tanggal_format}}
                        </td>
                        <td>
                            {{$dt->kode_partner . ' - ' . $dt->partner}}
                        </td>
                        <td align="right" >{{number_format($dt->gaji_nett,2,'.',',')}}</td>
                        <td align="right"  >{{number_format($dt->amount_due,2,'.',',')}}</td>
                        <td class="text-center" >
                            @if($dt->state == 'draft')
                                <label class="label label-danger" >DRAFT</label>
                            @elseif($dt->state == 'open')
                                <label class="label label-warning" >OPEN</label>
                            @else
                                <label class="label label-success" >PAID</label>
                            @endif
                        </td>
                        <td class="text-center" >
                            <a class="btn btn-xs btn-primary" href="gaji/gaji-driver/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
        @include('gaji.gaji_driver.box-footer')
    </div><!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // var TBL_KATEGORI = $('#table-data').DataTable({
    //     sort:false
    // });

    // add class to pagination
    $('ul.pagination').addClass('pagination-sm no-margin');

   

    

})(jQuery);
</script>
@append