@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
     <b>Cloth Deatils</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Cloth Deatils</li>
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
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addRetailer">Add Cloth</button>
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
                                       <th>Shop Name</th>
                                       <th>Shop Address</th>
                                       <th>Shop Contact</th>
                                       <th>Apparel Type</th>
                                       <th>Delete</th>
                                     </tr>
                                     </thead>
                                     @php($i=1)
                                      @foreach($retailer as $item)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->shop_name}}</td>
                                        <td>{{$item->shop_address}}</td>
                                        <td>{{$item->shop_contact}}</td>
                                        <td>{{$item->apparel_type}}</td>
                                
                                       <td>
                                        <a href="{{URL::to('Appareldelete/'.$item->id) }}">
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
                    <h4 class="modal-title">Add Cloth</h4>
                </div>
                <div class="modal-body">
                    <form action="apparel_deatils" method="post" 
                    id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            
                            <div class="row">
                                <div class="col-md-6 shop_name {{($errors->has('shop_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Shop Name
                                        <input type="text" value="{{old('shop_name')}}"
                                         name="shop_name" class="form-control"
                                               id="shop_name" placeholder="Enter Shop Name">
                                        @if ($errors->has('shop_name'))
                                            <small class="help-block">
                                                {{ $errors->first('shop_name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6  shop_address  {{($errors->has('shop_address'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Shop Address
                                        <input type="text" value="{{old('shop_address')}}"
                                         name="shop_address" class="form-control"
                                               id="shop_address" placeholder="Enter Shop Address">
                                        @if ($errors->has('shop_address'))
                                            <small class="help-block">
                                                {{ $errors->first('shop_address') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                
                          </div>


                            <div class="row">
                                <div class="col-md-6  shop_contact  {{($errors->has('shop_contact'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Shop Contact
                                        <input type="text" value="{{old('shop_contact')}}"
                                         name="shop_contact" class="form-control"
                                               id="shop_contact" placeholder="Enter shop contact">
                                        @if ($errors->has('shop_contact'))
                                            <small class="help-block">
                                                {{ $errors->first('shop_contact') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6  apparel_type  {{($errors->has('apparel_type'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Cloth Type
                                        <input type="text" value="{{old('apparel_type')}}"
                                         name="apparel_type" class="form-control"
                                               id="apparel_type" placeholder="Enter apparel type">
                                        @if ($errors->has('apparel_type'))
                                            <small class="help-block">
                                                {{ $errors->first('apparel_type') }}
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
                        shop_name: {
                            validators: {
                                notEmpty: {
                                    message: 'Shop Name is required'
                                }
                            }
                        },
                
                        shop_address: {
                            validators: {
                                notEmpty: {
                                    message: 'Shop Address is required'
                                }
                            }
                        },
                   
                        shop_contact: {
                            validators: {
                                notEmpty: {
                                    message: 'Shop contact is required'
                                }
                            }
                        },

                        apparel_type: {
                            validators: {
                                notEmpty: {
                                    message: 'Cloth type is required'
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