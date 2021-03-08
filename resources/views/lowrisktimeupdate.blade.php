@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h2>
           <b>Update Vehicle</b>
        </h2>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Vehicle</li>
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
                    <form action='/packageupdate' method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="{{$values->vehicle_id}}">
                            <div class="row">

                                 <div class="col-md-6  vehicle_model      {{($errors->has('vehicle_model    '))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Vehicle Model</label>
                                    <input type="text" value="{{$values->vehicle_model}}"
                                         name="vehicle_model" class="form-control"
                                               id="vehicle_model" placeholder="Enter Vehicle Model">
                                        @if ($errors->has('vehicle_model'))
                                            <small class="help-block">
                                                {{ $errors->first('vehicle_model') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6  vehicle_name  {{($errors->has('vehicle_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Capacity</label>
                                    <input type="text" value="{{$values->vehicle_name}}"
                                         name="vehicle_name" class="form-control"
                                               id="vehicle_name" placeholder="vehicle name">
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
                                   
                                <select name="capacity_unit" class="form-control" value="{{$values->capacity_unit}}">
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
                                <div class="col-md-6  vehicle_no  {{($errors->has('vehicle_no'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Vehicle No</label>
                                    <input type="text" value="{{$values->vehicle_no}}"
                                         name="vehicle_no" class="form-control"
                                               id="vehicle_no" placeholder="Enter Vehicle No">
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
                                        <label for="exampleInputEmail1">Images</label>
                                    <input type="file" value="{{$values->image}}"
                                         name="image" class="form-control"
                                               id="image" placeholder="Enter image">
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

                         vehicle_model: {
                            validators: {
                                notEmpty: {
                                    message: 'Vehicle model is required'
                                }
                            }
                        },
                        capacity: {
                            validators: {
                                notEmpty: {
                                    message: 'Capacity is required'
                                }
                            }
                        },
                        capacity_unit: {
                            validators: {
                                notEmpty: {
                                    message: 'capacity unit is required'
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
                    image: {
                            validators: {
                                notEmpty: {
                                    message: 'Image is required'
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