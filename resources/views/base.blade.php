<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FlashExpress</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/css/customStyle.css">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <link href="/css/formValidation.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">

{{--@if(count($data['notificationArr'])>0)
    <div style="position: relative; height: 0px;z-index: 9999;right: 250px !important;top: 49px;float: right;">
        <ul class="dots">
            <li>
                <a href="#near" role="button"  data-modal-position="relative" data-toggle="modal" data-placement="left" style="position: absolute; left: 50px; top: 10px;">
                    <span class="glyphicon glyphicon-bell" style="background-color: #ddd"><mark>{{$data['notificationArr'][0]['paginate']->total()}}</mark></span>
                </a>
            </li>
        </ul>

    </div>
@endif--}}

<!-- Site wrapper -->
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="/dashboard" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>S</b>M</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">
                
                <img src="/dist/img/logo.jpeg" width="200" height="45">
            </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="/dist/img/logo.jpeg" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{Auth::user()->id}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="/dist/img/logo.jpeg" class="img-circle" alt="User Image">

                                <p>
                                    {{ Auth::user()->id }} - {{ Auth::user()->user_type }}
                                    <small>FlashExpress</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer" style="background-color:#3c8dbc;">
                                <a href="/logout" class="btn btn-default btn-flat"><b>Sign out</b>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- =============================================== -->
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="/dist/img/logo.jpeg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->id }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i>FlashExpress</a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
         
                <li class="treeview">
                    <a href="/dashboard">
                        <i class="fa fa-tachometer"></i> <span>DASHBOARD</span>
                        <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="javascript:void(0);" class="changePassword" data-toggle="modal" data-target="#changeMyPasswordModal"><i class="fa fa-circle-o"></i>Change Password</a>
                        </li>
                    </ul>
                </li>
                @if(Auth ::user()->user_type == 'ADMIN'||Auth ::user()->user_type == 'SUPERADMIN')
                <li class="treeview">
                    <a href="javascript:void(0);">
                        <i class="fa fa-users"></i> <span>USER MANAGEMENT</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                     
                        <li>
                            <a href="/register-user"><i class="fa fa-circle-o"></i>Register Users</a>
                        </li> 
                         @if(Auth ::user()->user_type == 'SUPERADMIN')

                        <li>
                            <a href="/register-vendor"><i class="fa fa-circle-o"></i>Register Vendor</a>
                        </li> 
                        @endif
                        
                         @if(Auth ::user()->user_type == 'ADMIN')
                        <li>
                            <a href="/register-adminvendor"><i class="fa fa-circle-o"></i>Register Vendor</a>
                        </li>
                        @endif 

                         @if(Auth ::user()->user_type == 'SUPERADMIN')
                        <li>
                            <a href="/admin_deatils"><i class="fa fa-circle-o"></i>Register Admin</a>
                        </li>
                        @endif   

                    </ul>
                    </li>
                    <li class="treeview">
                    <a href="javascript:void(0);">
                        <i class="fa fa-asterisk"></i> <span>VEHICLE MANAGEMENT</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                    <li>
                        <a href="/sales_orders"><i class="fa fa-circle-o"></i>
                        Add Vehicle</a>
                    </li>    
                    </ul>
                    </li>

                <li class="treeview">
                    <a href="javascript:void(0);">
                        <i class="fa fa-flag"></i> <span>MASTER MANAGEMENT</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="city_deatils">
                                <i class="fa fa-circle-o"></i> <span>Add City</span>
                            </a>
                        </li>
                         <li>
                            <a href="area_deatils">
                                <i class="fa fa-circle-o"></i> <span>Add Area</span>
                            </a>
                        </li>
                        <li>
                            <a href="cat_deatils">
                                <i class="fa fa-circle-o"></i> <span>Add Category</span>
                            </a>
                        </li>
                        <li>
                            <a href="subcat_deatils">
                                <i class="fa fa-circle-o"></i> <span>Add Sub Category</span>
                            </a>
                        </li>
                        <li>
                            <a href="package_deatils">
                                <i class="fa fa-circle-o"></i> <span>Add Package</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="javascript:void(0);">
                        <i class="fa fa-asterisk"></i> <span>DELIVERY BOY</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                    <li>
                        <a href="/deliveryboy_deatils"><i class="fa fa-circle-o"></i>
                        Add Delivery Boy</a>
                    </li>
             <!--        <li>
                        <a href="/delivery_boy_payment"><i class="fa fa-circle-o"></i>
                        Add Delivery Boy Payment</a>
                    </li>  -->      
                    </ul>
                </li>

                <li class="treeview">
                    <a href="javascript:void(0);">
                        <i class="fa fa-asterisk"></i> <span>Payment Information</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                    <li>
                        <a href="/vendor_payment"><i class="fa fa-circle-o"></i>
                        Add Vendor Payment</a>
                    </li>
                    <li>
                        <a href="/delivery_boy_payment"><i class="fa fa-circle-o"></i>
                        Add Delivery Boy Payment</a>
                    </li>       
                    </ul>
                </li>

                @endif

                <li class="treeview">
                    <a href="javascript:void(0);">
                        <i class="fa fa-cutlery"></i><span><h5><b>ECOMMORCE</b></h5></span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                    @if(Auth ::user()->user_type == 'SUPERADMIN')
                    
                    <li>
                        <a href="banner_deatils"><i class="fa fa-circle-o"></i>
                       Add Banner</a>
                    </li>

                    <li>
                        <a href="paymentmode_deatils"><i class="fa fa-circle-o"></i>
                       Add Payment Mode</a>
                    </li>

                    <li>
                        <a href="ecommcat_deatils"><i class="fa fa-circle-o"></i>
                       Add Bussiness Type</a>
                    </li>
                    <li>
                    <a href="restocat_deatils"><i class="fa fa-circle-o"></i>
                       Add Restaurant Category</a>
                    </li>
                    <li>
                        <a href="charges_deatils"><i class="fa fa-circle-o"></i>
                       Add Delivery charges</a>
                    </li>
                    <li>
                    <a href="superadmin_hoteldeatils"><i class="fa fa-circle-o"></i>
                    Restaurant List</a>
                    </li>
                    <li>
                    <a href="admin_itemdeatils"><i class="fa fa-circle-o"></i>
                    Pending Item List</a>
                    </li>
                    <li>
                    <a href="admin_itemdeatilscomplete"><i class="fa fa-circle-o"></i>
                    Complete Item List</a>
                    </li>
                    @endif

        @if(Auth ::user()->user_type == 'ADMIN')
                    
                    <li>
                        <a href="banner_deatils"><i class="fa fa-circle-o"></i>
                       Add Banner</a>
                    </li>

                    <li>
                        <a href="paymentmode_deatils"><i class="fa fa-circle-o"></i>
                       Add Payment Mode</a>
                    </li>

                    <li>
                        <a href="ecommcat_deatils"><i class="fa fa-circle-o"></i>
                       Add Bussiness Type</a>
                    </li>
                    <li>
                    <a href="restocat_deatils"><i class="fa fa-circle-o"></i>
                       Add Restaurant Category</a>
                    </li>
                    <li>
                        <a href="charges_deatils"><i class="fa fa-circle-o"></i>
                       Add Delivery charges</a>
                    </li>
                    <li>
                    <a href="admin_hoteldeatils"><i class="fa fa-circle-o"></i>
                    Restaurant List</a>
                    </li>
                    <li>
                    <a href="admin_itemdeatils"><i class="fa fa-circle-o"></i>
                    Pending Item List</a>
                    </li>
                    <li>
                    <a href="admin_itemdeatilscomplete"><i class="fa fa-circle-o"></i>
                    Complete Item List</a>
                    </li>
                    @endif

                 @if(Auth ::user()->user_type == 'RETAILER')
                    <li>
                        <a href="hotel_deatils"><i class="fa fa-circle-o"></i>
                       Add Restaurant</a>
                    </li>
                    
                    <li>
                        <a href="item_deatils"><i class="fa fa-circle-o"></i>
                       Add Items</a>
                    </li>
              
                    <li>
                        <a href="vendororder_deatils"><i class="fa fa-circle-o"></i>
                     Pending Order</a>
                    </li>
                    <li>
                        <a href="assignvendororder_deatils"><i class="fa fa-circle-o"></i>
                    Assigned Order</a>
                    </li>
                    @endif
<!--                     <li>
                        <a href="comp_deatils"><i class="fa fa-circle-o"></i>
                       Add Computer</a>
                    </li> -->
                @if(Auth ::user()->user_type == 'ADMIN')
                   <!--  <li>
                        <a href="apparel_deatils"><i class="fa fa-circle-o"></i>
                       Cloth Store</a>
                    </li> -->
                     <li>
                        <a href="adminorder_deatils"><i class="fa fa-circle-o"></i>
                       Total Pending Order</a>
                    </li>
                @endif
                
                    </ul>
                    </li>
<!--                 <li class="treeview">
                    <a href="javascript:void(0);">
                        <i class="fa fa-diamond"></i><span><h5><b>JEWELLERY</b></h5></span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                    
                    <li>
                        <a href="jewlcat_deatils"><i class="fa fa-circle-o"></i>
                       Add Category</a>
                    </li>
                    <li>
                        <a href="jewlsubcat_deatils"><i class="fa fa-circle-o"></i>
                       Add Sub Category</a>
                    </li>
                    </ul>
                    </li> -->

                @if(Auth ::user()->user_type == 'ADMIN' || Auth ::user()->user_type == 'SUPERADMIN')

                    <li class="treeview">
                    <a href="javascript:void(0);">
                        <i class="fa fa-flag"></i> <span>REPORTS</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="completed_order">
                                <i class="fa fa-circle-o"></i><span>Complated Order</span>
                            </a>
                        </li>
                          <li>
                            <a href="FeedbackDeatils">
                                <i class="fa fa-circle-o"></i><span>Feedback List</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        @if(Auth ::user()->user_type == 'RETAILER')

                    <li class="treeview">
                    <a href="javascript:void(0);">
                        <i class="fa fa-flag"></i> <span>REPORTS</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="completed_vendor_order">
                                <i class="fa fa-circle-o"></i><span>Complated Order</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

</ul>
</section>
<!-- /.sidebar -->
</aside>
<!-- =============================================== -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
@yield('page-content')
</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
    
<div class="pull-right hidden-xs">
<b>Laravel Version</b> 5.6
</div>

<strong>FlashExpress</strong>
</footer>
</div>

<div id="changeMyPasswordModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Change Password</h5>
            </div>
            <form action="/change-password" method="post" name="changepass" class="changepass">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="change"></div>
                <div class="modal-body" id="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 inside  {{($errors->has('oldpass'))?'has-error':''}}">
                                <label>Old Password</label><span style="color: red"> *</span>
                                <input type="password" id="oldpass" name="oldpass" placeholder="Enter old password"
                                       class="form-control" required>
                                @if ($errors->has('oldpass'))
                                    <small class="help-block">
                                        {{ $errors->first('oldpass') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 inside  {{($errors->has('newpass'))?'has-error':''}}">
                                <label>New Password</label><span style="color: red"> *</span>
                                <input type="password" id="newpass" name="newpass" placeholder="Enter new password"
                                       class="form-control" required>
                                @if ($errors->has('newpass'))
                                    <small class="help-block">
                                        {{ $errors->first('newpass') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 inside  {{($errors->has('newpass'))?'has-error':''}}">
                                <label>Conferm Password</label><span style="color: red"> *</span>
                                <input type="password" id="cnewpass" name="cnewpass" placeholder="Conferm password"
                                       class="form-control" required>
                                @if ($errors->has('cnewpass'))
                                    <small class="help-block">
                                        {{ $errors->first('cnewpass') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                        <input type="hidden" value="{{ Auth::user()->id }}" name="id">
                </div>
                <div class="modal-footer">
                    <button type="submit" id="changepassd" class="btn btn-info pull-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ./wrapper -->
<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>

<link rel="stylesheet" href="../../bower_components/bootstrap-daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="../../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<script src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/js/formValidation.min.js"></script>
<script src="/plugins/formvalidation/framework/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script>
    $('.changepass').formValidation({
        framework: 'bootstrap',
        icon: {},
        fields: {
            oldpass: {
                validators: {
                    notEmpty: {
                        message: 'Enter old Password'
                    }
                }
            },
            newpass: {
                validators: {
                    notEmpty: {
                        message: 'Enter new Password'
                    }
                }
            },
            cnewpass: {
                validators: {
                    notEmpty: {
                        message: 'Enter new Password'
                    },
                    identical: {
                        field: 'newpass',
                        message: 'The password and its confirm are not the same'
                    }
                }
            }
        }
    });

$(document).ready(function () {
$('.sidebar-menu').tree()
(function($){
$('#header').popover('show');
})(jQuery);
});


    $(function () {
        $('#datepicker').datepicker({
            autoclose: true,
            format: 'dd-M-yyyy'
        })
    });

    $(function () {
        $('#datepickers').datepicker({
            autoclose: true,
            format: 'dd-M-yyyy'
        })
    })
</script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script type="text/javascript" src="/js/bootstrap-modal-popover.js"></script>
@yield('page-scripts')
</body>
</html>

