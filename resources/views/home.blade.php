@extends('layouts.master')

@section('styles')
<style>
  .inner h3{
    font-size: 24px!important;
  }
</style>
@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Home
    </h1>
<!--    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
    </ol>-->
</section>

<!-- Main content -->
<section class="content">
    <div class="row">      
        <div class="col-lg-3 col-xs-6" >
            <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{$open_so_count}}</h3>
                  <p>Pembelian Tertunda</p>
                </div>
                <div class="icon">
                  <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="pembelian/filter/state/draft" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
        </div>

        <div class="col-lg-3 col-xs-6" >
            <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{$open_do_count}}</h3>
                  <p>Pengiriman Tertunda</p>
                </div>
                <div class="icon">
                  <i class="fa fa-truck"></i>
                </div>
                <a href="delivery/filter/state/draft" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
        </div>

        <div class="col-lg-3 col-xs-6" >
            <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{number_format($open_ci_count,2,'.',',')}}</h3>
                  <p>Saldo Hutang</p>
                </div>
                <div class="icon">
                  <i class="fa fa-newspaper-o"></i>
                </div>
                <a href="finance/hutang/filter/state/open" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
        </div>

        <div class="col-lg-3 col-xs-6" >
            <div class="small-box bg-orange">
                <div class="inner">
                  <h3>{{number_format($open_piutang_count,2,'.',',')}}</h3>
                  <p>Saldo Piutang</p>
                </div>
                <div class="icon">
                  <i class="fa fa-calendar"></i>
                </div>
                <a href="finance/piutang/filter/state/open" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
        </div>

    </div><!-- /.row -->

</section><!-- /.content -->
@stop