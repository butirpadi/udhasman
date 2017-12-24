@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/dist/css/select2.min.css">
<link type="text/css" href="plugins/timepicker/timepicker.less" />
<style>
    .col-top-item{
        /*cursor:pointer;*/
        border: thin solid #CCCCCC;
        
    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
    }
</style>
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <a href="dailyhd" >Harian Alat Berat</a> 
        <i class="fa fa-angle-double-right" ></i> Create
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid" >
        <form name="form-create-dailyhd" method="POST" action="dailyhd/insert" >
        <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
            {{-- <label> <small>Sales Order</small> <h4 style="font-weight: bolder;margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;" >New</h4></label> --}}
            <label><h3 style="margin:0;padding:0;font-weight:bold;" >New</h3></label>

            <!-- <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn  btn-arrow-right pull-right disabled bg-gray" >Validated</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-gray" >Open</a>

            <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
            <a class="btn btn-arrow-right pull-right disabled bg-blue" >Draft</a> -->

        </div>
        <div class="box-body" >
            <div class="row" >
                <div class="col-xs-4" >
                    <div class="form-group">
                        <label >Tanggal</label>
                        <input type="text" name="tanggal" class="form-control input-date" value="{{date('d-m-Y')}}" required />                        
                    </div>
                    <div class="form-group">
                        <label >Pengawas</label>
                        {!! Form::select('pengawas_id',$selectStaff,null,['class'=>'form-control','required']) !!}
                    </div>
                    <div class="form-group">
                        <label >Jam Kerja</label>
                        <div class='input-group'>
                            <input type='text'  placeholder="Jam Mulai" class="form-control input-time" name="mulai" required />
                            <div class='input-group-field' >
                                <input style="margin-left: 5px;" type='text' placeholder="Jam Selesai"  class="form-control input-time" name="selesai" required />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label >Solar (liter)</label>
                        <input type="text" name="solar" class="form-control text-right input-liter" />
                    </div>
                </div>
                <div class="col-xs-4" >
                    <div class="form-group">
                        <label >Alat</label>
                        {!! Form::select('alat_id',$selectAlat,null,['class'=>'form-control','required']) !!}
                    </div>
                    <div class="form-group">
                        <label >Operator</label>
                        {!! Form::select('operator_id',$selectStaff,null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label >Istirahat</label>
                        <div class='input-group'>
                            <input type='text'  placeholder="Jam Mulai" class="form-control input-time" name="istirahat_mulai"  required />
                            <div class='input-group-field'>
                                <input type='text' placeholder="Jam Selesai"  class="form-control input-time" name="istirahat_selesai" required style="margin-left: 5px;" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label >Oli (liter)</label>
                        <input type="text" name="oli" class="form-control text-right input-liter"/>
                    </div>
                </div>
                <div class="col-xs-4" >
                    <div class="form-group">
                        <label >Lokasi Galian</label>
                        {!! Form::select('lokasi_id',$selectGalian,null,['class'=>'form-control','required']) !!}
                    </div>
                    <div class="form-group">
                        <label >Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="5" ></textarea>
                    </div>
                    <div class="form-group">
                        <label >Total Jam Kerja (jam)</label>
                        <input type="text " name="total_jam_kerja" class="form-control" readonly />
                    </div>
                </div>
            </div>
        </div>   
        <div class="box-footer" >
            <button type="submit" class="btn btn-primary" ><i class="fa fa-save" ></i> Save</button>
            <a href="dailyhd" class="btn btn-danger"><i class="fa fa-close" ></i> Close</a>
        </div>
        </form>
    </div>
  
</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script type="text/javascript" src="plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="plugins/autocomplete/jquery.autocomplete.min.js" type="text/javascript"></script>
<script src="plugins/autonumeric/autoNumeric-min.js" type="text/javascript"></script>
<script src="plugins/select2/dist/js/select2.full.min.js"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script type="text/javascript">
(function ($) {
   $('.input-time').timepicker({
                minuteStep: 1,
                template: 'modal',
                appendWidgetTo: 'body',
                showSeconds: true,
                showMeridian: false,
                defaultTime: false
            });

   // FORMAT SELECT 2
   $('select[name=alat_id]').val([]);
   $('select[name=alat_id]').select2();

   $('select[name=lokasi_id]').val([]);
   $('select[name=lokasi_id]').select2();

   $('select[name=pengawas_id]').val([]);
   $('select[name=pengawas_id]').select2();
   
   $('select[name=operator_id]').val([]);
   $('select[name=operator_id]').select2();

   // AUTONUMERIC
   $('.input-liter').autoNumeric('init',{
        vMin:'0.00',
        vMax:'9999999999.00'
    });

   // SET DATEPICKER
    $('.input-date').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });
    // END OF SET DATEPICKER

    // SET AUTOCOMPLETE SUPPLIER
    $('input[name=pengawas]').autocomplete({
        serviceUrl: 'api/get-auto-complete-staff',
        params: {  'nama': function() {
                        return $('input[name=pengawas]').val();
                    }
                },
        onSelect:function(suggestions){
            $('input[name=pengawas_id]').val(suggestions.data);
            $('input[name=pengawas]').attr('readonly','readonly');
        }

    });

    $('input[name=operator]').autocomplete({
        serviceUrl: 'api/get-auto-complete-staff',
        params: {  'nama': function() {
                        return $('input[name=operator]').val();
                    }
                },
        onSelect:function(suggestions){
            $('input[name=operator_id]').val(suggestions.data);
            $('input[name=operator]').attr('readonly','readonly');
        }

    });

    $('input[name=alat]').autocomplete({
        serviceUrl: 'api/get-auto-complete-alat',
        params: {  'nama': function() {
                        return $('input[name=alat]').val();
                    }
                },
        onSelect:function(suggestions){
            // cek duplikasi data
            var tanggal = $('input[name=tanggal]').val();
            var alat_id = suggestions.data;

            // $.get('dailyhd/cek-duplikasi/' + tanggal +'/' + alat_id,null,function(res){
            //     if(res == 'true'){
                    // data sudah tersimpan
                //     alert('Data alat di tanggal ini telah tersedia.');
                //     $('input[name=alat]').val('');

                // }else{
                    $('input[name=alat_id]').val(suggestions.data);
                    // disabled alat 
                    $('input[name=alat]').attr('readonly','readonly');
                    // focuskan ke input lokasi
                    // $('input[name=lokasi]').focus();
            //     };
            // });

            // $('input[name=alat_id]').val(suggestions.data);
        }

    });

    $('input[name=lokasi]').autocomplete({
        serviceUrl: 'api/get-auto-complete-lokasi-galian',
        params: {  'nama': function() {
                        return $('input[name=lokasi]').val();
                    }
                },
        onSelect:function(suggestions){
            $('input[name=lokasi_id]').val(suggestions.data);
            $('input[name=lokasi]').attr('readonly','readonly');
            // focuskan ke input jam mulai
            // $('input[name=pengawas]').focus();
        }

    });

    // hitung jam kerja
    var mulai;
    var selesai;
    var istirahat_mulai;
    var istirahat_selesai;
    $('input[name=mulai]').timepicker().on('changeTime.timepicker', function(e) {
        mulai = e.time;
        hitungJamKerja();
      });
    $('input[name=selesai]').timepicker().on('changeTime.timepicker', function(e) {
        selesai = e.time;
        hitungJamKerja();
      });

    $('input[name=istirahat_mulai]').timepicker().on('changeTime.timepicker', function(e) {
        istirahat_mulai = e.time;
        hitungJamKerja();
      });
    $('input[name=istirahat_selesai]').timepicker().on('changeTime.timepicker', function(e) {
        istirahat_selesai = e.time;
        hitungJamKerja();
      });

    function hitungJamKerja(){
        if(mulai!="" && selesai!= "" && mulai != null && selesai != null && mulai != undefined && selesai != undefined &&
            istirahat_mulai!="" && istirahat_selesai!= "" && istirahat_mulai != null && istirahat_selesai != null && istirahat_mulai != undefined && istirahat_selesai != undefined ){
            var tgl_mulai = new Date(10,10,2000,mulai.hours,mulai.minutes,mulai.seconds);
            var tgl_selesai = new Date(10,10,2000,selesai.hours,selesai.minutes,selesai.seconds);
            var tgl_istirahat_mulai = new Date(10,10,2000,istirahat_mulai.hours,istirahat_mulai.minutes,istirahat_mulai.seconds);
            var tgl_istirahat_selesai = new Date(10,10,2000,istirahat_selesai.hours,istirahat_selesai.minutes,istirahat_selesai.seconds);
            var selisih = (tgl_selesai - tgl_mulai);  
            var selisih_istirahat = (tgl_istirahat_selesai - tgl_istirahat_mulai);
            var jam_kerja = selisih - selisih_istirahat ;
            // console.log();
            console.log(jam_kerja / (1000 * 60 * 60));

            $('input[name=total_jam_kerja]').val(jam_kerja / (1000 * 60 * 60));
        }else{
            $('input[name=total_jam_kerja]').val('');
        }
    }


// alert('pret');
})(jQuery);
</script>
@append