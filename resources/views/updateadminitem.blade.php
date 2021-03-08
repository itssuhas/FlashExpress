@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h2>
           <b>Update Item</b>
        </h2>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Item</li>
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
                    <form action='/adminitemupdate' method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="{{$values->item_id}}">
                            <div class="row">
                                <div class="col-md-6  item_name  {{($errors->has('item_name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Name</label>
                                    <input type="text" value="{{$values->item_name}}"
                                         name="item_name" class="form-control"
                                               id="item_name" placeholder="Enter item name">
                                        @if ($errors->has('item_name'))
                                            <small class="help-block">
                                                {{ $errors->first('item_name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-6 item_price  {{($errors->has('item_price'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Price</label>
                                    <input type="text" value="{{$values->item_price}}"
                                         name="item_price" class="form-control"
                                               id="item_price" placeholder="Enter item price" readonly="readonly">
                                        @if ($errors->has('item_price'))
                                            <small class="help-block">
                                                {{ $errors->first('item_price') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-6 item_discount_amt  {{($errors->has('item_discount_amt'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Discount Amt</label>
                                    <input type="text" value="{{$values->item_discount_amt}}"
                                         name="item_discount_amt" class="form-control"
                                               id="item_discount_amt" placeholder="Enter item discount amt" readonly="readonly">
                                        @if ($errors->has('item_discount_amt'))
                                            <small class="help-block">
                                                {{ $errors->first('item_discount_amt') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 item_half_price {{($errors->has('item_half_price'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Half Price</label>
                                    <input type="text" value="{{$values->item_half_price}}"
                                         name="item_half_price" class="form-control" id="item_half_price" placeholder="Enter item half price" readonly="readonly">
                                        @if ($errors->has('item_half_price'))
                                            <small class="help-block">
                                                {{ $errors->first('item_half_price') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                  
                                  <div class="col-md-6 item_half_discount_amt {{($errors->has('item_half_discount_amt'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Half Discount Amt</label>
                                    <input type="text" value="{{$values->item_half_discount_amt}}"
                                         name="item_half_discount_amt" class="form-control" id="item_half_discount_amt" placeholder="Enter item half discount amt" readonly="readonly">
                                        @if ($errors->has('item_half_discount_amt'))
                                            <small class="help-block">
                                                {{ $errors->first('item_half_discount_amt') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 qty_type {{($errors->has('qty_type'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Qty Type</label>
                                    <input type="text" value="{{$values->qty_type}}"
                                         name="qty_type" class="form-control" id="qty_type" placeholder="Enter item half discount amt" readonly="readonly">
                                        @if ($errors->has('qty_type'))
                                            <small class="help-block">
                                                {{ $errors->first('qty_type') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6  admin_item_price  {{($errors->has('admin_item_price'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Admin Price</label>
                                    <input type="text" value="{{$values->admin_item_price}}"
                                         name="admin_item_price" class="form-control"
                                               id="admin_item_price" placeholder="Enter item price">
                                        @if ($errors->has('admin_item_price'))
                                            <small class="help-block">
                                                {{ $errors->first('admin_item_price') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-6 admin_discount_price  {{($errors->has('admin_discount_price'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Admin Discount Amount</label>
                                    <input type="text" value="{{$values->admin_discount_price}}"
                                         name="admin_discount_price" class="form-control"
                                               id="admin_discount_price" placeholder="Enter admin discount price">
                                        @if ($errors->has('admin_discount_price'))
                                            <small class="help-block">
                                                {{ $errors->first('admin_discount_price') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-6 admin_half_item_price  {{($errors->has('admin_half_item_price'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Admin Half Item Price</label>
                                    <input type="text" value="{{$values->admin_half_item_price}}"
                                         name="admin_half_item_price" class="form-control"
                                               id="admin_half_item_price" placeholder="Enter item price">
                                        @if ($errors->has('admin_half_item_price'))
                                            <small class="help-block">
                                                {{ $errors->first('admin_half_item_price') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-6 admin_half_item_discount_price {{($errors->has('admin_half_item_discount_price'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Admin Half Item Discount Price</label>
                                    <input type="text" value="{{$values->admin_half_item_discount_price}}"
                                         name="admin_half_item_discount_price" class="form-control"
                                               id="admin_half_item_discount_price" placeholder="Enter Admin Half item discount price">
                                        @if ($errors->has('admin_half_item_discount_price'))
                                            <small class="help-block">
                                                {{ $errors->first('admin_half_item_discount_price') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                            <div class="col-md-6  item_description  {{($errors->has('item_description'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Item Description</label>
                                    <input type="text" value="{{$values->item_description}}"
                                         name="item_description" class="form-control"
                                               id="item_description" placeholder="Enter Item Description">
                                        @if ($errors->has('item_description'))
                                            <small class="help-block">
                                                {{ $errors->first('item_description') }}
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

                    item_half_price: {
                            validators: {
                                notEmpty: {
                                    message: 'item half price is required'
                                }
                            }
                        },
                    
                    item_half_discount_amt: {
                            validators: {
                                notEmpty: {
                                    message: 'item half discount amount is required'
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
                    item_discount_amt: {
                            validators: {
                                notEmpty: {
                                    message: 'item discount amount is required'
                                }
                            }
                        },
                    
                    admin_item_price: {
                            validators: {
                                notEmpty: {
                                    message: 'admin item price is required'
                                }
                            }
                        },
                    admin_half_item_price: {
                            validators: {
                                notEmpty: {
                                    message: 'admin half item price is required'
                                }
                            }
                        },
                admin_half_item_discount_price:{
                            validators: {
                                notEmpty: {
                                    message: 'admin half item discount price is required'
                                }
                            }
                        },

        
                    admin_discount_price: {
                            validators: {
                                notEmpty: {
                                    message: 'admin discount price is required'
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