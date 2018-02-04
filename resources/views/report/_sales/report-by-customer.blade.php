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
         <a href="report/sales" >Sales Reports</a> <i class="fa fa-angle-double-right" ></i> 
         Show Data Reports 
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box box-solid">
        <div class="box-header with-border"  >
            <h3 class="box-title">Sales Report</h3>
              <div class="box-tools pull-right">
                <div class="btn-group pull-right">
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Print <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    {{-- <li><a href="#">Direct Print</a></li> --}}
                    <li><a target="_blank" href="report/purchase/filter-by-date/pdf/{{$start_date}}/{{$end_date}}">Pdf</a></li>
                    <li><a href="#">Xls</a></li>
                  </ul>
                </div>
              </div>
        </div>
        <div class="box-body">
            
            <table class="table table-condensed" >
                <tbody>
                    <tr>
                        <td class="col-sm-1 col-md-1 col-lg-1">
                            <label>Tanggal</label>
                        </td>
                        <td>:</td>
                        <td  >
                            {{ '['. $start_date .'] - [' . $end_date .']'}}
                        </td>

                        <td>
                            <label>Customer</label>
                        </td>
                        <td>:</td>
                        <td>{{$customer->nama}}</td>
                        
                    </tr>
                    @if($pekerjaan_id > 0)
                        <tr>
                            <td class="col-sm-1 col-md-1 col-lg-1" >
                                <label>Pekerjaan</label>
                            </td>
                            <td>
                                :
                            <td  >
                                {{$pekerjaan->nama}}
                            </td>
                            
                            <td>
                               <label>Alamat</label>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {!!$pekerjaan->alamat .', ' . $pekerjaan->desa.', <br/>' . $pekerjaan->kecamatan .', ' . $pekerjaan->kabupaten . ', ' . $pekerjaan->provinsi!!}
                            </td>
                        </tr>
                    @endif
                    @if($sales_type > 0)
                        <tr>
                            <td class="col-sm-1 col-md-1 col-lg-1" >
                                    <label>Sales Type</label>
                            </td>
                            <td>
                                    :
                            <td  >
                                    @if($sales_type == 1)
                                        Direct Sales
                                    @elseif($sales_type == 2)
                                        Non Direct Sales / Mitra
                                    @endif
                            </td>
                            
                            <td>
                                
                            </td>
                            <td>
                                
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="5" ></td>
                    </tr>
                </tbody>
            </table>
            
            <?php $rownum=1; ?>
            <table class="table table-bordered table-condensed" >
                <thead>
                    <tr>
                        <th>No</th>
                        <th>SO Ref#</th>
                        <th>SO Date</th>
                        <th>Status</th>
                        {{-- <th>Customer</th> --}}
                        <th>Nopol</th>
                        @if($pekerjaan_id == 0)
                            <th >Pekerjaan</th>
                            <th >Alamat</th>
                        @endif
                        <th class="col-sm-2 col-md-2 col-lg-2" >Total</th>
                        <th class="col-sm-2 col-md-2 col-lg-2" >Amount Due</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dt )
                        <tr>
                            <td>{{$rownum++}}</td>
                            <td>
                                {{$dt->order_number}}
                            </td>
                            <td>
                                {{$dt->order_date_formatted}}
                            </td>
                            <td>
                                @if($dt->status =='O')
                                    OPEN
                                @elseif($dt->status =='V')
                                    VALIDATED
                                @elseif($dt->status =='D')
                                    DONE
                                @endif
                            </td>
                            {{-- <td>
                                {{$dt->customer}}
                            </td> --}}
                            <td>
                                @if($dt->nopol != "")
                                    {{$dt->nopol}}
                                @else
                                    -
                                @endif
                            </td>
                            @if($pekerjaan_id == 0)
                                <td>
                                    @if($dt->pekerjaan != '')
                                        {{$dt->pekerjaan}}
                                    @else 
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($dt->alamat_pekerjaan != "")
                                        {!! $dt->alamat_pekerjaan .', ' . $dt->desa . ', <br/>'  . $dt->kecamatan . ', ' . $dt->kabupaten !!}
                                    @else 
                                    -
                                    @endif
                                </td>
                            @endif
                            <td class="text-right uang">
                                {{$dt->total}}
                            </td>
                            <td class="text-right uang">
                                {{$dt->amount_due}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="{{$pekerjaan_id == 0 ? 7 : 5}}" class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;" ><label>TOTAL</label></td>
                        <td class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;" ><label class="uang" >{{$total != "" ? $total : 0}}</label></td>
                        <td class="text-right" style="background-color: #ecf0f5;border-color: #DDDDDD!important;" ><label class="uang" >{{$total_amount_due != "" ? $total_amount_due : 0}}</label></td>
                        
                    </tr>
                </tfoot>
            </table>
            
        </div><!-- /.box-body -->
        <div class="box-footer" >
            <a class="btn btn-danger" href="report/sales" >Close</a>
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

    var TBL_KATEGORI = $('#table-data').DataTable({
        sort:false
    });

    // -----------------------------------------------------
    // SET AUTO NUMERIC
    // =====================================================
    $('.uang').autoNumeric('init',{
        vMin:'0',
        vMax:'9999999999'
    });
    $('.uang').each(function(){
        $(this).autoNumeric('set',$(this).autoNumeric('get'));
    });
    // END OF AUTONUMERIC
    

})(jQuery);
</script>
@append