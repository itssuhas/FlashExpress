@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
     <b>Sub Category Deatils</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Sub Category Deatils</li>
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
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addRetailer">Add Sub Category</button>
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
                                       <th>Category Name</th>
                                       <th>Sub Cat Name</th>
                                       <th>image</th>
                                       <th>Update</th>
                                       <th>Delete</th>
                                     </tr>
                                     </thead>
                                     @php($i=1)
                                      @foreach($retailer as $item)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->cat_name}}</td>
                                        <td>{{$item->sub_cat_name}}</td>
                                        <td><img style="width:100px; height:100px;" src="<?php echo asset("../images/jewlsubcat/$item->sub_cat_img")?>"></img></td>
                                        <td>
                                            <a href="{{URL::to('ecommcategoryupdate/'.$item->id) }}">
                                       <span class="glyphicon glyphicon-edit">
                                          Update
                                       </span> </a>

                                        </td>
                                       <td>
                                        <a href="{{URL::to('Jewellerysubcatdelete/'.$item->id) }}">
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
                    <h4 class="modal-title">Add Category</h4>
                </div>
                <div class="modal-body">
                    <form action="jewlsubcat_deatils" method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            
                            <div class="row">

                                            <div class="col-md-12  cat_id  {{($errors->has('cat_id'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category Name</label>
                                         <select name="cat_id" class="form-control">
                        
                          @foreach($catlist as $user)
                            <option value="{{$user->id}}">{{$user->cat_name}}</option>
                        @endforeach                                            


                                        </select>
                                        @if ($errors->has('cat_id'))
                                            <small class="help-block">
                                                {{ $errors->first('cat_id') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-12  sub_cat_name  {{($errors->has('sub_cat_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Sub Category Name</label>
                                        <input type="text" value="{{old('sub_cat_name')}}"
                                         name="sub_cat_name" class="form-control"
                                               id="sub_cat_name" placeholder="Enter Sub Category Name">
                                        @if ($errors->has('sub_cat_name'))
                                            <small class="help-block">
                                                {{ $errors->first('sub_cat_name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                
                                <div class="col-md-12  image  {{($errors->has('image'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Sub Category Image</label>
                                        <input type="file" value="{{old('image')}}"
                                         name="image" class="form-control"
                                               id="image" placeholder="Enter Image">
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

                        cat_id: {
                            validators: {
                                notEmpty: {
                                    message: 'cat id is required'
                                }
                            }
                        },
                    
                    sub_cat_name: {
                            validators: {
                                notEmpty: {
                                    message: 'sub cat name is required'
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