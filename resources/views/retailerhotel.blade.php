@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
     <b>Restaurant Deatils</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Restaurant Deatils</li>
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
                 

<!--                         <div class="col-md-12">
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addRetailer">Add Restaurant</button>
                        </div> -->
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
                                       <th>Contact</th>
                                       <th>Type</th>
                                       <th>Image</th>
                                       <th>Update</th>
                                       <th>Delete</th>
                                     </tr>
                                     </thead>
                                     @php($i=1)
                                      @foreach($retailer as $item)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->hotel_name}}</td>
                                        <td>{{$item->city}}</td>
                                        <td>{{$item->area}}</td>
                                        <td>{{$item->hotel_address}}</td>
                                        <td>{{$item->hotel_contact}}</td>
                                        <td>{{$item->hotel_type}}</td>
                                        <td><img style="width:100px; height:100px;" src="<?php echo asset("../images/hotel/$item->image")?>"></img></td>
                                  <td>                              <a href="{{URL::to('hotelupdate/'.$item->id) }}">
                                       <span class="glyphicon glyphicon-edit">
                                          Update
                                       </span> </a></td>
                                       <td>
                                        <a href="{{URL::to('hoteldatadelete/'.$item->id) }}">
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
                    <h4 class="modal-title">Add Restaurant</h4>
                </div>
                <div class="modal-body">
                    <form action="hotel_deatils" method="post" 
                    id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            
                            <div class="row">
                                <div class="col-md-6 hotel_name {{($errors->has('hotel_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Restaurant Name
                                        <input type="text" value="{{old('hotel_name')}}"
                                         name="hotel_name" class="form-control"
                                               id="hotel_name" placeholder="Enter Restaurant Name">
                                        @if ($errors->has('hotel_name'))
                                            <small class="help-block">
                                                {{ $errors->first('hotel_name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-6  hotel_address  {{($errors->has('hotel_address'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Restaurant Address
                                        <input type="text" value="{{old('hotel_address')}}"
                                         name="hotel_address" class="form-control"
                                               id="hotel_address" placeholder="Enter Restaurant Address">
                                        @if ($errors->has('hotel_address'))
                                            <small class="help-block">
                                                {{ $errors->first('hotel_address') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                          </div>
                            <div class="row">
                                <div class="col-md-6 city {{($errors->has('city'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">City Name
                                        <input type="text" value="{{old('city')}}"
                                         name="city" class="form-control"
                                               id="city" placeholder="Enter City Name">
                                        @if ($errors->has('city'))
                                            <small class="help-block">
                                                {{ $errors->first('city') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-6  area  {{($errors->has('area'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Area Name
                                        <input type="text" value="{{old('area')}}"
                                         name="area" class="form-control"
                                               id="area" placeholder="Enter Area">
                                        @if ($errors->has('area'))
                                            <small class="help-block">
                                                {{ $errors->first('area') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                          </div>

                            <div class="row">
                                <div class="col-md-6  hotel_contact  {{($errors->has('hotel_contact'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Restaurant Contact
                                        <input type="text" value="{{old('hotel_contact')}}"
                                         name="hotel_contact" class="form-control"
                                               id="name" placeholder="Enter Restaurant Contact">
                                        @if ($errors->has('hotel_contact'))
                                            <small class="help-block">
                                                {{ $errors->first('hotel_contact') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6  hotel_type  {{($errors->has('hotel_type'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Hotel Type</label>
                                         <select name="hotel_type[]" class="form-control" multiple>
                        
     
                          @foreach($rescatlist as $user)
                            <option value="{{$user->id}}">{{$user->res_cat_name}}</option>
                        @endforeach  
                        </select>
                                        @if ($errors->has('hotel_type'))
                                            <small class="help-block">
                                                {{ $errors->first('hotel_type') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                
                            </div>
                                <div class="col-md-6 image {{($errors->has('image'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">image
                                        <input type="file" value="{{old('image')}}"
                                         name="image" class="form-control"
                                               id="image" placeholder="Enter image">
                                        @if ($errors->has('image'))
                                            <small class="help-block">
                                                {{ $errors->first('image') }}
                                            </small>
                                        @endif

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
                        hotel_name: {
                            validators: {
                                notEmpty: {
                                    message: 'Hotel Name is required'
                                }
                            }
                        },
                
                        hotel_address: {
                            validators: {
                                notEmpty: {
                                    message: 'Hotel Address is required'
                                }
                            }
                        },
                   
                        hotel_contact: {
                            validators: {
                                notEmpty: {
                                    message: 'Hotel contact is required'
                                }
                            }
                        },

                        hotel_type: {
                            validators: {
                                notEmpty: {
                                    message: 'hotel type is required'
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