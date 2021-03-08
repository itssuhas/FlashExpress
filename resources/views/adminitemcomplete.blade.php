@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
     <b>Admin Items Deatils</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Admin Items Deatils</li>
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
<!--                 <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addRetailer">Add Item</button>
                        </div>
                    </div>
                </div> -->

                <div class="box-body">
                    @if(isset($retailer))
                
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered table-hover retailer-search">
                                    <thead>
                                    <tr>
                                       <th>id</th>
                                       <th>Category Id</th>
                                       <th>Name</th>
                                       <th>Admin Price</th>
                                       <th>Admin Discount</th>
                                       <th>Admin Half Price</th>
                                       <th>Admin Half Discount</th>
                                       <th>Qty Type</th>
                                       <th>Description</th>
                                       <th>Image</th>
                                       
                                     </tr>
                                     </thead>
                                     @php($i=1)
                                      @foreach($retailer as $item)
                                        <tr>
                                        <td>{{$i++}}</td>    
                                        <td>{{$item->category_id}}</td>                               
                                        <td>{{$item->item_name}}</td>
                                        <td>{{$item->admin_item_price}}</td>
                                        <td>{{$item->admin_discount_price}}</td>
                                        <td>{{$item->admin_half_item_price}}
                                        </td>
                                        <td>{{$item->admin_half_item_discount_price}}</td>
                                         <td>{{$item->qty_type}}</td>
                                        <td>{{$item->item_description}}
                                        </td>
                                        <td><img style="width:100px; height:100px;" src="<?php echo asset("../images/item/$item->item_image")?>"></img></td>

                          
                                 
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
                    <h4 class="modal-title">Add Item</h4>
                </div>
                <div class="modal-body">
                    <form action="item_deatils" method="post" 
                    id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            
                            <div class="row">
                              <div class="col-md-6 category_id  {{($errors->has('category_id'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Category Name</label>
                                         <select name="category_id" class="form-control">
                        
     
                          @foreach($rescatlist as $user)
                            <option value="{{$user->id}}">{{$user->res_cat_name}}</option>
                        @endforeach  
                        </select>
                                        @if ($errors->has('category_id'))
                                            <small class="help-block">
                                                {{ $errors->first('category_id') }}
                                            </small>

                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6 item_name {{($errors->has('item_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Name
                                        <input type="text" value="{{old('item_name')}}"
                                         name="item_name" class="form-control"
                                               id="item_name" placeholder="Enter Item Name">
                                        @if ($errors->has('item_name'))
                                            <small class="help-block">
                                                {{ $errors->first('item_name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6  item_price  {{($errors->has('item_price'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Price
                                        <input type="text" value="{{old('item_price')}}"
                                         name="item_price" class="form-control"
                                               id="item_price" placeholder="Enter item price">
                                        @if ($errors->has('item_price'))
                                            <small class="help-block">
                                                {{ $errors->first('item_price') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                         
                                <div class="col-md-6 item_discount_amt {{($errors->has('item_discount_amt'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Discount Amount
                                        <input type="text" value="{{old('item_discount_amt')}}"
                                         name="item_discount_amt" class="form-control"
                                               id="item_discount_amt" placeholder="Enter item discount amt">
                                        @if ($errors->has('item_discount_amt'))
                                            <small class="help-block">
                                                {{ $errors->first('item_discount_amt') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6  item_description  {{($errors->has('item_description'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Description
                                        <input type="textbox" value="{{old('item_description')}}"
                                         name="item_description" class="form-control"
                                               id="item_description" placeholder="Enter Item Description">
                                        @if ($errors->has('item_description'))
                                            <small class="help-block">
                                                {{ $errors->first('item_description') }}
                                            </small>
                                        @endif

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
                        category_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Category Name is required'
                                }
                            }
                        },
                
                        item_name: {
                            validators: {
                                notEmpty: {
                                    message: 'item name is required'
                                }
                            }
                        },
                   
                        item_price: {
                            validators: {
                                notEmpty: {
                                    message: 'item price is required'
                                }
                            }
                        },

                        item_discount_amt: {
                            validators: {
                                notEmpty: {
                                    message: 'item discount price is required'
                                }
                            }
                        },

                    item_description: {
                            validators: {
                                notEmpty: {
                                    message: 'item Description is required'
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