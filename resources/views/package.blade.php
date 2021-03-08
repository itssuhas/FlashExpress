@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
     <b>Package Deatils</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Package Deatils</li>
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
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addRetailer">Add Package</button>
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
                                       <th>Package Name</th>
                                       <th>Package Km</th>
                                       <th>Package Rate</th>
                                       <th>Update</th>
                                       <th>Delete</th>
                                     </tr>
                                     </thead>
                                     @php($i=1)
                                      @foreach($retailer as $item)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->package_name}}</td>
                                        <td>{{$item->package_km}}</td>
                                        <td>{{$item->package_rate}}</td>
                                        <td>                           <a href="{{URL::to('rateupdate/'.$item->package_id) }}">
                                       <span class="glyphicon glyphicon-edit">
                                          Update
                                       </span> </a></td>
                                        <td>
                                        <a href="{{URL::to('packegedelete/'.$item->package_id) }}">
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
                    <h4 class="modal-title">Add Package</h4>
                </div>
                <div class="modal-body">
                    <form action="package_deatils" method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            
                            <div class="row">
                             <div class="col-md-6   package_name      {{($errors->has(' package_name    '))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Package Name</label>
                                        <input type="text" value="{{old(' package_name')}}"
                                         name="package_name" class="form-control"
                                               id="package_name" placeholder="Enter Package Name">
                                        @if ($errors->has('package_name'))
                                            <small class="help-block">
                                                {{ $errors->first('package_name') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6  package_km  {{($errors->has('package_km'))?'has-error':''}}">
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


                                <div class="col-md-6 package_rate {{($errors->has('package_rate'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Package Rate</label>
                                        <input type="text" value="{{old('package_rate')}}"
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

                        package_name: {
                            validators: {
                                notEmpty: {
                                    message: 'package  name is required'
                                }
                            }
                        },
                         package_km: {
                            validators: {
                                notEmpty: {
                                    message: 'Package km is required'
                                }
                            }
                        },
                    package_rate: {
                            validators: {
                                notEmpty: {
                                    message: 'Package Rate is required'
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