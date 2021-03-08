@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
     <b>Delivery Boy Deatils</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Delivery Boy Deatils</li>
        </ol>
    </section>

    <!-- Main content -->
      <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <!--div class="box-header with-border">
                <h3 class="box-title">Title</h3>
            </div-->
            <div class="box-body">
                @if(Session::has('alert'))
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
                @endif
                <div class="box-body">
                    <div class="row">
                 

                        <div class="col-md-12">
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addRetailer">Add Delivery Boy</button>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    @if(isset($retailer))
                
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered table-hover retailer-search">
                                    <thead>
                                    <tr>
                                       <th>id</th>
                                       <th>Name</th>
                                       <th>City</th>
                                       <th>Area</th>
                                       <th>Address</th>
                                       <th>Mobile</th>
                                       <th>Gmail</th>
                                       <th>Update</th>
                                       <th>Delete</th>
                                     </tr>
                                     </thead>
                                     @php($i=1)
                                      @foreach($retailer as $item)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->city}}</td>
                                        <td>{{$item->area}}</td>
                                        <td>{{$item->address}}</td>
                                        <td>{{$item->mobile}}</td>
                                        <td>{{$item->gmail}}</td>
                                        <td>
                                            <a href="{{URL::to('deliveryboyupdate/'.$item->id) }}">
                                       <span class="glyphicon glyphicon-edit">
                                          Update
                                       </span> </a>
                                        </td>
                                      
                                       <td>
                                        <a href="{{URL::to('deliveryBoydelete/'.$item->id) }}">
                                       <span style="color:red;" class="fa fa-trash" onclick="return confirm('Are you sure......?')">
                                          Delete
                                       </span> </a>

                                        </td>
                                        </tr>
                                 @endforeach
                            </table>
                            </div>
                            <div class="retailer-pagination">
                            </div>
                        @else
                            <table class="table table-responsive table-hover">
                                <tr><th>No Record focund</th></tr>
                            </table>
                @endif
                </div>
            </div>
            <!-- /.box-body -->
            <!--div class="box-footer">
                Footer
            </div-->
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
    <!-- Modal -->
    <div class="modal fade" id="addRetailer" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Delivery Boy</h4>
                </div>
                <div class="modal-body">
                    <form action="deliveryboy_deatils" method="post" 
                    id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            
                            <div class="row">
                                <div class="col-md-6  name  {{($errors->has('name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Name
                                        <input type="text" value="{{old('name')}}"
                                         name="name" class="form-control"
                                               id="name" placeholder="Enter Name">
                                        @if ($errors->has('name'))
                                            <small class="help-block">
                                                {{ $errors->first('name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                         
                                         <div class="col-md-6  city  {{($errors->has('city'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">City
                                        <input type="text" value="{{old('city')}}"
                                         name="city" class="form-control"
                                               id="city" placeholder="Enter city">
                                        @if ($errors->has('city'))
                                            <small class="help-block">
                                                {{ $errors->first('city') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                          </div>
                            <div class="row">                    
                             <div class="col-md-6  area  {{($errors->has('area'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Area
                                        <input type="text" value="{{old('area')}}"
                                         name="area" class="form-control"
                                               id="area" placeholder="Enter area">
                                        @if ($errors->has('area'))
                                            <small class="help-block">
                                                {{ $errors->first('area') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                              <div class="col-md-6  address  {{($errors->has('address'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Address
                                        <input type="text" value="{{old('address')}}"
                                         name="address" class="form-control"
                                               id="address" placeholder="Enter Address">
                                        @if ($errors->has('address'))
                                            <small class="help-block">
                                                {{ $errors->first('address') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6  mobile  {{($errors->has('mobile'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mobile No
                                        <input type="text" value="{{old('mobile')}}"
                                         name="mobile" class="form-control"
                                               id="name" placeholder="Enter Mobile">
                                        @if ($errors->has('mobile'))
                                            <small class="help-block">
                                                {{ $errors->first('mobile') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6  gmail  {{($errors->has('gmail'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Gmail
                                        <input type="text" value="{{old('gmail')}}"
                                         name="gmail" class="form-control"
                                               id="gmail" placeholder="Enter Gmail">
                                        @if ($errors->has('gmail'))
                                            <small class="help-block">
                                                {{ $errors->first('gmail') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                
                          </div>
                        
                        <div class="row">
                                <div class="col-md-6  password  {{($errors->has('password'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Password
                                        <input type="text" value="{{old('password')}}"
                                         name="password" class="form-control"
                                               id="password" placeholder="Enter password">
                                        @if ($errors->has('password'))
                                            <small class="help-block">
                                                {{ $errors->first('password') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                 <div class="col-md-6  confirm_pass  {{($errors->has('confirm_pass'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Confirm Password
                                        <input type="text" value="{{old('confirm_pass')}}"
                                         name="confirm_pass" class="form-control"
                                               id="confirm_pass" placeholder="Enter confirm password">
                                        @if ($errors->has('confirm_pass'))
                                            <small class="help-block">
                                                {{ $errors->first('confirm_pass') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                          </div>


                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info pull-right">Submit</button>
                            </div>
                        </div><!-- /.box-body -->
                    </form>
                </div>
            </div>
        </div>
    </div>

   
   
    </div>
    </div>
@endsection
@section('page-scripts')
    @if(Session::has('errorModal'))
        $errorModal = Session::get('errorModal')
        @if($errorModal='retailerRegistration')
            <script>
                $(document).ready(function() {
                    $('#addRetailer').modal('show');
                });
            </script>
        @endif

        @if($errorModal='retailerAssignIds')
            <script>
                $(document).ready(function() {
                    //$('#modalAssignIds').modal('show');
                });
            </script>
        @endif
    @endif

    <script type="text/javascript">
        $(document).ready(function () {

            $('#retailerreg')
                .formValidation({
                    framework: 'bootstrap',
                    icon: {},
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {
                                    message: 'Name is required'
                                }
                            }
                        },
                
                        address: {
                            validators: {
                                notEmpty: {
                                    message: 'Address is required'
                                }
                            }
                        },
                   
                        mobile: {
                            validators: {
                                notEmpty: {
                                    message: 'mobile is required'
                                }
                            }
                        },

                        gmail: {
                            validators: {
                                notEmpty: {
                                    message: 'gmail is required'
                                }
                            }
                        },

                    city: {
                            validators: {
                                notEmpty: {
                                    message: 'city is required'
                                }
                            }
                        },
                    area: {
                            validators: {
                                notEmpty: {
                                    message: 'area is required'
                                }
                            }
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: 'password is required'
                                }
                            }
                        },
                    confirm_pass: {
                            validators: {
                                notEmpty: {
                                    message: 'confirm password is required'
                                }
                            }
                        },


                    },
                })


        });

    </script>
    <script type="text/javascript">
    $(document).ready(function () {        
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.closest('#retailerreg').submit();
            }
        });   
    });
</script>
    <script>
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: "/randomUserId",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function (data) {
                    $("#userId").attr("disabled", "disabled");
                    $('#userId').val("GK"+data);
                    $('#userSubmitId').val("GK"+data);
                }
            });


            $.ajax({
                type: "POST",
                url: "/randomRetailerPin",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                success: function (data) {
                    $("#pin").attr("disabled", "disabled");
                    $('#pin').val(data);
                    $('#agentSubmitPin').val(data);
                }
            });


            $('#idNumber').on('input', function(){
                $('#displayNumbers').html('');
                var randomIds = [];
                var result = new Array();
                idNumber=$(this).val();
                $.ajax({
                    type: "POST",
                    url: "/randomNumberAjax",
                    data: {
                        idNumber: $(this).val(),
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function (data) {
                        obj=JSON.parse(JSON.stringify(data.ids));
                        $('#errorMsg').html(data.error);
                        for(i=0;i<obj.length;i++){
                            $('#displayNumbers').append("<p class='badge badge-pill badge-custom'>"+obj[i]+'</p>');
                            randomIds.push(obj[i]);
                        }
                        $('#ids').val(randomIds);
                    }
                });
            });
            // $('.assignId').click(function(){
            //     retailerId=$(this).attr('data');
            //     $('#retailerId').val(retailerId);
            //     agentId=$(this).attr('agent_id');
            //     $('#agentId').val(agentId);
            // });
            $('.credit').click(function(){
                retailerCreditid=$(this).attr('data');
                $('#retailerCreditid').val(retailerCreditid);
            });
            $('.debit').click(function(){
                retailerdebitid=$(this).attr('data');
                $('#retailerdebitid').val(retailerdebitid);
            });

        });
    </script>

@endsection