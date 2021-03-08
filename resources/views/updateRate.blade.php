@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h2>
           <b>Update Package</b>
        </h2>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Package</li>
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
                    <form action='/rateupdate' method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="{{$values->package_id}}">
                            <div class="row">
                                 <div class="col-md-4 package_name {{($errors->has('package_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Package Name</label>
                                    <input type="text" value="{{$values->package_name}}"
                                         name="package_name" class="form-control"
                                               id="package_name" placeholder="Enter Package Name">
                                        @if ($errors->has('package_name'))
                                            <small class="help-block">
                                                {{ $errors->first('package_name') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                 <div class="col-md-4  package_km  {{($errors->has('package_km'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Package Km Range</label>
                                         <select name="package_km" class="form-control">                   
                                      <option> 100 - 200 Km </option>
                                      <option> 201 - 400 Km </option>
                                      <option> 401 - 600 Km </option>
                                      <option> 601 - 800 Km </option>
                                      <option> 801 - 1000 Km </option>
                                        </select>
                                        @if ($errors->has('package_km'))
                                            <small class="help-block">
                                                {{ $errors->first('package_km') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                 <div class="col-md-4 package_rate {{($errors->has('package_rate'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Package Rate</label>
                                    <input type="text" value="{{$values->package_rate}}"
                                         name="package_rate" class="form-control"
                                               id="package_rate" placeholder="Enter Package Rate">
                                        @if ($errors->has('package_rate'))
                                            <small class="help-block">
                                                {{ $errors->first('package_rate') }}
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

                         city_name: {
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