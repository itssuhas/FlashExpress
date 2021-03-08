@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <b>Dashboard</b>
            <small><b></b></small>
            <br>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">dashboard</li>
        </ol>
    </section>
 <div class="box-body">
      @if(Session::has('alert'))
                 <div class="box-body1">
                    @php ($alert = Session::get('alert'))
                    @if($alert['type']=='error')
                        <div class='alert alert-danger'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×
                            </button>
                            {{$alert['message']}}</div>
                    @else
                        <div class='alert alert-success'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                            {{$alert['message']}}</div>

                    @endif
                    {{Session::forget('alert')}}
                    </div>
                @endif
            </div>

    <!-- Main content -->

    <section class="content">
        <div class="box">
            <div class="box-body">
                <table class="table ">
                 <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                @if($usertype=='SUPERADMIN')
                                    <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-primary text-light" style="background:#00c0ef !important;">
                                                <div class="stat-panel text-center">

                                                    <div class="stat-panel-number h3">{{$users}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Registered Users</b>
                                                    </div>
                                                </div>
                                            </div>
                                      <a href="register-user" class="block-anchor panel-footer"><b>Full Detail</b> &nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>
                               
                                       <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-primary text-light" style="background:#dd4b39 !important;">
                                                <div class="stat-panel text-center">
                                                    <div class="stat-panel-number h3">{{$vendor}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Registered Vendor </b>
                                                    </div>

                                                </div>
                                            </div>
                                       <a href="register-vendor" class="block-anchor panel-footer"><b>Full Detail</b> &nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>
                                 
                                    <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-info text-light" style="background:#00a65a !important;">
                                                <div class="stat-panel text-center">
                                                    <div class="stat-panel-number h3 ">{{$porders}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Pending Orders</b></div>
                                                </div>
                                            </div>
                                <a href="adminorder_deatils" class="block-anchor panel-footer"><b>Full Detail</b>&nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>
                                
                                  <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-info text-light" style="background:#f39c12 !important">
                                                <div class="stat-panel text-center">
                                                    <div class="stat-panel-number h3 ">{{$corders}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Completed Orders</b></div>
                                                </div>
                                            </div>
                                    <a href="completed_order" class="block-anchor panel-footer"><b>Full Detail</b>&nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>         
                                @endif

                                @if($usertype=='ADMIN')
                                    <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-primary text-light" style="background:#00c0ef !important;">
                                                <div class="stat-panel text-center">

                                                    <div class="stat-panel-number h3">{{$users}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Registered Users</b>
                                                    </div>
                                                </div>
                                            </div>
                                      <a href="register-user" class="block-anchor panel-footer"><b>Full Detail</b> &nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>
                               
                                       <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-primary text-light" style="background:#dd4b39 !important;">
                                                <div class="stat-panel text-center">
                                                    <div class="stat-panel-number h3">{{$adminvendor}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Registered Vendor </b>
                                                    </div>

                                                </div>
                                            </div>
                                       <a href="#" class="block-anchor panel-footer"><b>Full Detail</b> &nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>
                                 
                                    <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-info text-light" style="background:#00a65a !important;">
                                                <div class="stat-panel text-center">
                                                    <div class="stat-panel-number h3 ">{{$Adminporders}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Pending Orders</b></div>
                                                </div>
                                            </div>
                                <a href="#" class="block-anchor panel-footer"><b>Full Detail</b>&nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>
                                
                                  <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-info text-light" style="background:#f39c12 !important">
                                                <div class="stat-panel text-center">
                                                    <div class="stat-panel-number h3 ">{{$Admincorders}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Completed Orders</b></div>
                                                </div>
                                            </div>
                                    <a href="#" class="block-anchor panel-footer"><b>Full Detail</b>&nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>         
                                @endif

                                @if($usertype=='RETAILER')
                                        <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-info text-light" style="background:#00a65a !important;">
                                                <div class="stat-panel text-center">
                                                    <div class="stat-panel-number h3 ">{{$vporders}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Pending Orders</b></div>
                                                </div>
                                            </div>
                                <a href="vendororder_deatils" class="block-anchor panel-footer"><b>Full Detail</b>&nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>
                                
                                  <div class="col-md-3">
                                        <div class="panel panel-default text-center">
                                            <div class="panel-body bk-info text-light" style="background:#f39c12 !important">
                                                <div class="stat-panel text-center">
                                                    <div class="stat-panel-number h3 ">{{$vcorders}}</div>
                                                    <div class="stat-panel-title text-uppercase"><b>Completed Orders</b></div>
                                                </div>
                                            </div>
                                    <a href="completed_vendor_order" class="block-anchor panel-footer"><b>Full Detail</b>&nbsp; <i class="fa fa-arrow-down"></i></a>
                                        </div>
                                    </div>    
                                    @endif
                                </div>
                            </div>
                        </div>                
                </table>
            </div>
        </div>
</section>
    
@endsection

@section('page-scripts')
    <script>
        $(document).ready(function(){
            $('.check').on('change', function () {
            if ($(this).is(':checked'))
                $("input[type='checkbox']").prop('checked', true);
            else
                $("input[type='checkbox']").prop('checked', false);
        });
        });
    </script>
@endsection