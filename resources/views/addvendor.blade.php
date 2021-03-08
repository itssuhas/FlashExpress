@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
     <b>Vendor Deatils</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Vendor Deatils</li>
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
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addRetailer">Add Vendor</button>
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
                                        <th>Mob</th>
                                        <th>Gmail</th>
                                        <th>Address</th>
                                        <th>Images</th>
                                        <th>Update</th>
                                        <th>Action</th>
                                        <th>Delete</th>
                                     </tr>
                                     </thead>
                                     @php($i=1)
                                      @foreach($retailer as $item)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->mob}}</td>
                                        <td>{{$item->gmail}}</td>
                                        <td>{{$item->address}}</td>
                                        <td>
                                            <img style="width:100px; height:100px;" src="<?php echo asset("images/vendor/$item->img")?>"></img>

                                        </td>

                                        <td>
                                            <a href="{{URL::to('vendorupdate/'.$item->id) }}">
                                       <span class="glyphicon glyphicon-edit">
                                          Update
                                       </span> </a></td>

                                       <td style="color:green;"> <b>
                                                @if($item->status=='Pending')
                                        <a href="{{URL::to('approveVendors/'.$item->id) }}">
                                       <span class="fa fa-check" onclick="return confirm('Are you sure......?')"> please
                                          Approve
                                       </span> </a>
                                       @else
                                     {{$item->status}} </b>
                                        @endif
                                        </td>
                                       <td>
                                            <a href="{{URL::to('deletevendor/'.$item->id) }}">
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
                    <h4 class="modal-title">Add Vendor</h4>
                </div>
                <div class="modal-body">
                    <form action="vendor_reg" method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            <div class="row">
                                 <div class="col-md-6  name  {{($errors->has('name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Name</label>
                                        <input type="text" value="{{old('name')}}"
                                         name="name" class="form-control"
                                               id="name" placeholder="Enter Retailer Name">
                                        @if ($errors->has('name'))
                                            <small class="help-block">
                                                {{ $errors->first('name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                 <div class="col-md-6  mob  {{($errors->has('mob'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mobile No</label>
                                        <input type="text" value="{{old('mob')}}"
                                         name="mob" class="form-control"
                                               id="mob" placeholder="Enter Mobile No">
                                        @if ($errors->has('mob'))
                                            <small class="help-block">
                                                {{ $errors->first('mob') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6  gmail  {{($errors->has('gmail'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Gmail</label>
                                        <input type="text" value="{{old('gmail')}}"
                                         name="gmail" class="form-control"
                                               id="gmail" placeholder="Enter gmail">
                                        @if ($errors->has('gmail'))
                                            <small class="help-block">
                                                {{ $errors->first('gmail') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6  address  {{($errors->has('address'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Address</label>
                                        <input type="text" value="{{old('address')}}"
                                         name="address" class="form-control"
                                               id="address" placeholder="Enter address">
                                        @if ($errors->has('address'))
                                            <small class="help-block">
                                                {{ $errors->first('address') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                       
                                
                          </div>
                          <div class="row">
                                <div class="col-md-6  password  {{($errors->has('password'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Password</label>
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
                                        <label for="exampleInputEmail1">Confirm Password</label>
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

                        <div class="row">
                            <div class="col-md-12  image  {{($errors->has('image'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Image</label>
                                        <input type="file" value="{{old('image')}}"
                                         name="image" class="form-control"
                                               id="image" placeholder="Enter confirm password">
                                        @if ($errors->has('image'))
                                            <small class="help-block">
                                                {{ $errors->first('image') }}
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
                                    message: 'name is required'
                                }
                            }
                        },
                         price: {
                            validators: {
                                notEmpty: {
                                    message: 'order No is required'
                                }
                            }
                        },
                        description: {
                            validators: {
                                notEmpty: {
                                    message: 'Order date  required'
                                }
                            }
                        },
                        iamge: {
                            validators: {
                                notEmpty: {
                                    message: 'order Description is required'
                                }
                            }
                        },
                    },
                })

            $('#retailerAssignIds')
                .formValidation({
                    framework: 'bootstrap',
                    icon: {
                    },
                    fields: {
                        idNumber: {
                            validators: {
                                notEmpty: {
                                    message: 'Enter number of ids'
                                },
                                numeric: {
                                    message: 'Enter number only'
                                }
                            }
                        }
                    }
                });
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