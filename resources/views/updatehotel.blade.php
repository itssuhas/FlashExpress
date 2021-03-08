@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h2>
           <b>Update Hotel</b>
        </h2>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Hotel</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
            <!--div class="box-header with-border">
                <h3 class="box-title">Title</h3>
            </div-->
        <!--     <div class="box-body">
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
              
         
            </div> -->
            <!-- /.box-body -->
            <!--div class="box-footer">
                Footer
            </div-->
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
         
                <div class="container">
                    @foreach($pack as $values)
                    <form action='/hotelupdate' method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="{{$values->id}}">
                            <div class="row">
                                <div class="col-md-6  hotel_name  {{($errors->has('hotel_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Hotel Name</label>
                                    <input type="text" value="{{$values->hotel_name}}"
                                         name="hotel_name" class="form-control"
                                               id="hotel_name" placeholder="Enter hotel name">
                                        @if ($errors->has('hotel_name'))
                                            <small class="help-block">
                                                {{ $errors->first('hotel_name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                 <div class="col-md-6  hotel_address  {{($errors->has('hotel_address'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Hotel Address</label>
                                    <input type="text" value="{{$values->hotel_address}}"
                                         name="hotel_address" class="form-control"
                                               id="hotel_address" placeholder="Enter hotel address">
                                        @if ($errors->has('hotel_address'))
                                            <small class="help-block">
                                                {{ $errors->first('hotel_address') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6  hotel_contact  {{($errors->has('hotel_contact'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Hotel Contact</label>
                                    <input type="text" value="{{$values->hotel_contact}}"
                                         name="hotel_contact" class="form-control"
                                               id="hotel_contact" placeholder="Enter hotel contact">
                                        @if ($errors->has('hotel_contact'))
                                            <small class="help-block">
                                                {{ $errors->first('hotel_contact') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6  start_time  {{($errors->has('start_time'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Start Time</label>
                                    <input type="time" value="{{$values->start_time}}"
                                         name="start_time" class="form-control"
                                               id="start_time" placeholder="Enter Start Time">
                                        @if ($errors->has('start_time'))
                                            <small class="help-block">
                                                {{ $errors->first('start_time') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                               
                               <div class="col-md-6  end_time  {{($errors->has('end_time'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">End Time</label>
                                    <input type="time" value="{{$values->end_time}}"
                                         name="end_time" class="form-control"
                                               id="end_time" placeholder="Enter End Time">
                                        @if ($errors->has('end_time'))
                                            <small class="help-block">
                                                {{ $errors->first('end_time') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                   <div class="col-md-6  city  {{($errors->has('city'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">City</label>
                                    <input type="text" value="{{$values->city}}"
                                         name="city" class="form-control"
                                               id="city" placeholder="Enter End Time">
                                        @if ($errors->has('city'))
                                            <small class="help-block">
                                                {{ $errors->first('city') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                  <div class="col-md-6  area  {{($errors->has('area'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Area</label>
                                    <input type="text" value="{{$values->area}}"
                                         name="area" class="form-control"
                                               id="area" placeholder="Enter Area">
                                        @if ($errors->has('area'))
                                            <small class="help-block">
                                                {{ $errors->first('area') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6  image  {{($errors->has('image'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Image</label>
                                    <input type="file" value="{{$values->image}}"
                                         name="image" class="form-control"
                                               id="image" placeholder="Enter Area">
                                        @if ($errors->has('image'))
                                            <small class="help-block">
                                                {{ $errors->first('image') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                            </div>

                                <button type="submit" class="btn btn-info">Update</button>
                        </div><!-- /.box-body -->
                    </form>
                    @endforeach

                </div>
            </div>
    </section>
    <!-- /.content -->
    <!-- Modal -->
  
            <!-- Modal content-->
    
     

    <!-- Modal -->
   
    </div>
    <!-- Modal -->
  

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
                                    message: 'hotel name is required'
                                }
                            }
                        },
                        hotel_address: {
                            validators: {
                                notEmpty: {
                                    message: 'hotel address required'
                                }
                            }
                        },
                       hotel_contact: {
                            validators: {
                                notEmpty: {
                                    message: 'hotel contact is required'
                                }
                            }
                        },

                    start_time: {
                            validators: {
                                notEmpty: {
                                    message: ' Start Time is required'
                                }
                            }
                        },
                    end_time: {
                            validators: {
                                notEmpty: {
                                    message: 'End Time is required'
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
                        image: {
                            validators: {
                                notEmpty: {
                                    message: 'Order tax category is required'
                                }
                            }
                        },
         
                    },
                })
     });
           

    </script>
    <script>
        
    </script>

@endsection