<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        {{-- <img src="img/logo-bg-white.png" class="img-responsive"> --}}
        <ul class="sidebar-menu">

            @foreach($sidemenu as $dt )
                @if(Auth::user()->has($dt->access_name))
                    @if(count($dt->childmenu) > 0)
                        <li class="treeview {{Request::is($dt->class_request) ? 'active':''}}" >
                            <a href="{{$dt->href}}">
                                <i class="{{$dt->icon}}"></i>
                                <span>{{$dt->title}}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                @foreach($dt->childmenu as $chd)
                                    <li class="{{Request::is($chd->class_request) ? 'active':''}}" ><a href="{{$chd->href}}"><i class="fa fa-circle-o"></i> {{$chd->title }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        @if(!$dt->parentMenu)
                            <li class="{{Request::is($dt->class_request) ? 'active':''}}" >
                                <a href="{{$dt->href}}"> 
                                    <i class="{{$dt->icon}}"></i> 
                                    <span>{{$dt->title}} </span> 
                                </a>
                            </li>
                        @endif
                    @endif
                @endif
            @endforeach

            
            <!-- Menu Hide & Show -->
            <!-- <li style="background-color: #DD4B39;" >
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <i class="fa fa-bars" class=""></i> 
                    <span style="color:transparent;" >.</span> 
                </a>
            </li> -->

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
