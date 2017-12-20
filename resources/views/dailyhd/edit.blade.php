@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
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
        <i class="fa fa-angle-double-right" ></i> {{$data->ref}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid" >
        <form name="form-create-dailyhd" method="POST" action="dailyhd/update" >
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->ref}}</h3></label>
            </div>
            <div class="box-body" >
                <div class="row" >
                    <div class="col-xs-4" >
                        <div class="form-group">
                            <label >Tanggal</label>
                            <input type="text" name="tanggal" class="form-control input-date" value="{{$data->tanggal_formatted}}" required />
                            <input type="hidden" name="dailyhd_id" value="{{$data->id}}" />
                        </div>
                        <div class="form-group">
                            <label >Pengawas</label>
                            <input type="text" name="pengawas" class="form-control" value="{{'['.$data->kode_pengawas . '] ' . $data->nama_pengawas}}" readonly required/>
                            <input type="hidden" name="pengawas_id" class="form-control" value="{{$data->pengawas_id}}" required/>
                        </div>
                        <div class="form-group">
                            <label >Jam Kerja</label>
                            <div class='input-group'>
                                    <input type='text'  placeholder="Jam Mulai" class="form-control input-time" name="mulai" required value="{{$data->mulai}}" />
                                <div class='input-group-field'>
                                    <input type='text' style="margin-left: 5px;" placeholder="Jam Selesai"  class="form-control input-time" name="selesai" required value="{{$data->selesai}}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label >Solar (liter)</label>
                            <input type="text" name="solar" class="form-control text-right input-liter" value="{{$data->solar}}" />                        
                        </div>
                    </div>
                    <div class="col-xs-4" >
                        <div class="form-group">
                            <label >Alat</label>
                            {!! Form::select('alat_id',$selectAlat,$data->alat_id,['class'=>'form-control','required']) !!}
                        </div>
                        <div class="form-group">
                            <label >Operator</label>
                            {!! Form::select('operator_id',$selectStaff,$data->operator_id,['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label >Istirahat</label>
                            <div class='input-group'>
                                <input type='text'  placeholder="Jam Mulai" class="form-control input-time" name="istirahat_mulai" value="{{$data->istirahat_mulai}}"  required />
                                <div class='input-group-field'>
                                    <input type='text' placeholder="Jam Selesai"  class="form-control input-time" name="istirahat_selesai" value="{{$data->istirahat_selesai}}" required style="margin-left: 5px;" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label >Oli (liter)</label>
                            <input type="text" name="oli" class="form-control text-right input-liter" value="{{$data->oli}}" />
                        </div>
                    </div>
                    <div class="col-xs-4" >
                        <div class="form-group">
                            <label >Lokasi Galian</label>
                            {!! Form::select('lokasi_id',$selectGalian,null,['class'=>'form-control','required']) !!}
                        </div>
                        <div class="form-group">
                            <label >Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="5" >{{$data->desc}}</textarea>
                        </div>
                        <div class="form-group">
                            <label >Total Jam Kerja (jam)</label>
                            <input type="text " name="total_jam_kerja" class="form-control" value="{{$data->jam_kerja}}" readonly />
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
        }

    });

    $('input[name=alat]').autocomplete({
        serviceUrl: 'api/get-auto-complete-alat',
        params: {  'nama': function() {
                        return $('input[name=alat]').val();
                    }
                },
        onSelect:function(suggestions){
            $('input[name=alat_id]').val(suggestions.data);
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
        }

    });

    // hitung jam kerja
    var mulai;
    var selesai;
    var istirahat_mulai;
    var istirahat_selesai;
    $('input[name=mulai]').timepicker().on('changeTime.timepicker', function(e) {
        // mulai = e.time;
        hitungJamKerja();
      });
    $('input[name=selesai]').timepicker().on('changeTime.timepicker', function(e) {
        // selesai = e.time;
        hitungJamKerja();
      });

    $('input[name=istirahat_mulai]').timepicker().on('changeTime.timepicker', function(e) {
        // istirahat_mulai = e.time;
        hitungJamKerja();
      });
    $('input[name=istirahat_selesai]').timepicker().on('changeTime.timepicker', function(e) {
        // istirahat_selesai = e.time;
        hitungJamKerja();
      });

    function hitungJamKerja(){
        mulai = $('input[name=mulai]').data('timepicker');
        selesai = $('input[name=selesai]').data('timepicker');
        istirahat_mulai = $('input[name=istirahat_mulai]').data('timepicker');
        istirahat_selesai = $('input[name=istirahat_selesai]').data('timepicker');
        // alert('Mulai ' + mulai.hours +' '+ mulai.minute + ' ' + mulai.second);
        // alert('Selesai ' + selesai.hours +' '+ selesai.minute + ' ' + selesai.second);
        // alert('Istirahat Mulai ' + istirahat_mulai.hours +' '+ istirahat_mulai.minute + ' ' + istirahat_mulai.second);
        // alert('Istirahat Selesai ' + istirahat_selesai.hours +' '+ istirahat_selesai.minute + ' ' + istirahat_selesai.second);

        if(mulai!="" && selesai!= "" && mulai != null && selesai != null && mulai != undefined && selesai != undefined &&
            istirahat_mulai!="" && istirahat_selesai!= "" && istirahat_mulai != null && istirahat_selesai != null && istirahat_mulai != undefined && istirahat_selesai != undefined ){
            var tgl_mulai = new Date(10,10,2000,mulai.hour,mulai.minute,mulai.second);
            var tgl_selesai = new Date(10,10,2000,selesai.hour,selesai.minute,selesai.second);
            var tgl_istirahat_mulai = new Date(10,10,2000,istirahat_mulai.hour,istirahat_mulai.minute,istirahat_mulai.second);
            var tgl_istirahat_selesai = new Date(10,10,2000,istirahat_selesai.hour,istirahat_selesai.minute,istirahat_selesai.second);
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

    // VALIDATE
    $('#btn-validate').click(function(){
        var newform = $('<form>').attr('method','POST').attr('action','dailyhd/validate');
        newform.append($('<input>').attr('type','hidden').attr('name','dailyhd_id').val($('input[name=dailyhd_id]').val()));
        newform.submit(); 
    });


// alert('pret');
})(jQuery);
</script>
@append