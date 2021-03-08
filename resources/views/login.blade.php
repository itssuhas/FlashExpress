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

    <link href="/css/formValidation.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" style="background-color:white;">
<div class="login-box">
    <div class="login-logo">
         <img style="width:100px; height:100px; border-radius:20px;" src="/dist/img/logo.jpeg">
       <!--  <a href="#" style="color:red;"><b><u>Logistics</u></b></a> -->
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body" style="border-radius:30px; background-color:#CDDC39;">
        <p class="login-box-msg"><b>Sign in to start your session</b></p>

        <form action="/login" method="post" id="login-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            @if(Session::has('alert'))
                @php ($alert = Session::get('alert'))
                @if($alert['type']=='success')
                    <div class="alert alert-success no-border">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {!! $alert['message'] !!}
                    </div>
                @else
                    <div class="alert alert-danger no-border">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        {!! $alert['message'] !!}
                    </div>
                @endif
                @php (Session::forget('alert'))
            @endif
            {{ csrf_field() }}
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
    
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <br>
 <a href="/api/vendor-registrationpanel" class="text-center">New Vendor Registration</a> &nbsp;
 &nbsp; &nbsp; &nbsp;   
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="/bower_components/jquery/dist/jquery.min.js"></script>

<script src="/js/formValidation.min.js"></script>
<script src="/plugins/formvalidation/framework/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        $('#login-form')
            .formValidation({
                framework: 'bootstrap',
                icon: {},
                fields: {
                    username: {
                        validators: {
                            notEmpty: {
                                message: 'Enter Username'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: 'Enter Password'
                            }
                        }
                    },
                },
            })
    });
</script>

</body>
</html>
