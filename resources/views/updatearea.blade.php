@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h2>
           <b>Update Area</b>
        </h2>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Area</li>
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
                    <form action='/areaupdate' method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="{{$values->id}}">
                            <div class="row">
                                 <div class="col-md-6  city_name  {{($errors->has('city_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">City Name</label>
                                    <input type="text" value="{{$values->city_name}}"
                                         name="city_name" class="form-control"
                                               id="city_name" placeholder="Enter Area Name" readonly>
                                        @if ($errors->has('city_name'))
                                            <small class="help-block">
                                                {{ $errors->first('city_name') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-6  area_name  {{($errors->has('area_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Area Name</label>
                                    <input type="text" value="{{$values->area_name}}"
                                         name="area_name" class="form-control"
                                               id="area_name" placeholder="Enter Area Name">
                                        @if ($errors->has('area_name'))
                                            <small class="help-block">
                                                {{ $errors->first('area_name') }}
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

                         area_name: {
                            validators: {
                                notEmpty: {
                                    message: 'Name is required'
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