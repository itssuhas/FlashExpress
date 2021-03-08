@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h2>
           <b>Update Restorant Cat</b>
        </h2>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Resto Cat</li>
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
                    <form action='/restocatupdate' method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="{{$values->id}}">
                            <div class="row">

                                 <div class="col-md-6  res_cat_name  {{($errors->has('res_cat_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Restorant Category Name</label>
                                    <input type="text" value="{{$values->res_cat_name}}"
                                         name="res_cat_name" class="form-control"
                                               id="res_cat_name" placeholder="Enter Restorant Category Name">
                                        @if ($errors->has('res_cat_name'))
                                            <small class="help-block">
                                                {{ $errors->first('res_cat_name') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6  image  {{($errors->has('image'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Image</label>
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

                         city_name: {
                            validators: {
                                notEmpty: {
                                    message: 'Name is required'
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
    <script>
    </script>

@endsection