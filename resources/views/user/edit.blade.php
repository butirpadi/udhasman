@extends('layouts.master')

@section('styles')
<link href="plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="plugins/select2/dist/css/select2.min.css">
<style>
    .col-top-item{
        /*cursor:pointer;*/
        border: thin solid #CCCCCC;

    }
    .table-top-item > tbody > tr > td{
        border-top-color: #CCCCCC;
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
        <a href="setting/user" >User Manager</a>
        <i class="fa fa-angle-double-right" ></i> {{$data->username}}
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="box box-solid" >
        <form enctype="multipart/form-data" method="POST" action="setting/user/update" >
            <div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
                <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{$data->username}}</h3></label>
            </div>
            <div class="box-body" >
                <div class="row" >
                    <div class="col-xs-6" >
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control"  autofocus autocomplete="off" value="{{$data->username}}" readonly />
                            <input type="hidden" name="user_id" value="{{$data->id}}">
                        </div>  
                        <div class="form-group" id="input-panggilan" >
                            <label>Password</label>
                            <input type="password" name="password" class="form-control"  autocomplete="off" placeholder="Inputkan untuk merubah password"  />
                        </div>  
                        @if(Auth::user()->has('access_setting') && $data->hide != 'Y')
                        <div class="form-group " id="input-panggilan" >
                            <label>Role</label>
                            {!! Form::select('role',$role,$data->roles()->first()->id,['class'=>'form-control']) !!}
                        </div>  
                        @endif
                    </div>
                    
                </div>

            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="btn-save" ><i class="fa fa-save" ></i> Save</button>
                <a class="btn btn-danger" href="setting/user" ><i class="fa fa-close" ></i> Close</a>
            </div>
        </form>
    </div>
</section><!-- /.content -->

@stop

@section('scripts')
<script type="text/javascript">
    (function ($) {

    })(jQuery);
</script>
@append
