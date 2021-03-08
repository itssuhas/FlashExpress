@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
     <b>Vehicle Information</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Vehicle Deatils</li>
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
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addRetailer">Add Vehicle</button>
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
                                        <th>Model Name</th>  
                                        <th>Vehicle Name</th>
                                        <th>Vehicle No</th>
                                        <th>Capacity Unit</th>
                                        <th>Image</th>
                                        <th>Update</th>
                                        <th>Delete</th>
                                     </tr>
                                     </thead>
                                     @php($i=1)
                                      @foreach($retailer as $item)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->vehicle_model}}</td>
                                        <td>{{$item->vehicle_name}}</td>
                                        <td>{{$item->vehicle_no}}</td>
                                        <td>{{$item->capacity_unit}}</td>
   
                                        <td>
                                            <img style="width:100px; height:100px;" src="<?php echo asset("images/vehicle/$item->image")?>"></img>
                                         <a href="<?php echo asset("images/vehicle/$item->image")?>">Download</a>

                                        </td>
                                        <td>
                                            <a href="{{URL::to('packageupdate/'.$item->vehicle_id) }}">
                                       <span class="glyphicon glyphicon-edit">
                                          Update
                                       </span> </a></td>
                                       <td>
                                            <a href="{{URL::to('delete/'.$item->vehicle_id) }}">
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
                    <h4 class="modal-title">Add Vehicle</h4>
                </div>
                <div class="modal-body">
                    <form action="sales_orders" method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            <div class="row">
                                 <div class="col-md-6  model_name  {{($errors->has('model_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Model Name</label>
                                        <input type="text" value="{{old('model_name')}}"
                                         name="model_name" class="form-control"
                                               id="model_name" placeholder="Enter Model Name">
                                        @if ($errors->has('model_name'))
                                            <small class="help-block">
                                                {{ $errors->first('model_name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                 <div class="col-md-6  vehicle_name  {{($errors->has('vehicle_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Capacity</label>
                                        <input type="text" value="{{old('vehicle_name')}}"
                                         name="vehicle_name" class="form-control"
                                               id="vehicle_name" placeholder="Enter Vehicle Name">
                                        @if ($errors->has('vehicle_name'))
                                            <small class="help-block">
                                                {{ $errors->first('vehicle_name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                            <div class="col-md-6  capacity_unit  {{($errors->has('capacity_unit'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Capacity Unit</label>
                                        <select name="capacity_unit" class="form-control">
                                <option>Kg</option>
                                <option>Ton</option>
                                    </select>     
                                        @if ($errors->has('capacity_unit'))
                                            <small class="help-block">
                                                {{ $errors->first('capacity_unit') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 vehicle_no {($errors->has('vehicle_no'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Vehicle No</label>
                                        <input type="text" value="{{old('vehicle_no')}}"
                                         name="vehicle_no" class="form-control"
                                               id="vehicle_no" placeholder="Enter rate Km/in">
                                        @if ($errors->has('vehicle_no'))
                                            <small class="help-block">
                                                {{ $errors->first('vehicle_no') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                
                                <div class="col-md-6  image  {{($errors->has('image'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">image</label>
                                        <input type="file" value="{{old('image')}}"
                                         name="image" class="form-control"
                                               id="description" placeholder="Enter Retailer Name">
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

                        model_name: {
                            validators: {
                                notEmpty: {
                                    message: 'Model Name is required'
                                }
                            }
                        },
                         vehicle_name: {
                            validators: {
                                notEmpty: {
                                    message: 'vehicle name is required'
                                }
                            }
                        },
                        rate: {
                            validators: {
                                notEmpty: {
                                    message: 'rate is required'
                                }
                            }
                        },

                    vehicle_no: {
                            validators: {
                                notEmpty: {
                                    message: 'vehicle no is required'
                                }
                            }
                        },
                    capacity: {
                            validators: {
                                notEmpty: {
                                    message: 'capacity is required'
                                }
                            }
                        },
                        image: {
                            validators: {
                                notEmpty: {
                                    message: 'image is required'
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