@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h2>
           <b>Update Vendor</b>
        </h2>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Vendor</li>
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
                    <form action='/vendorupdate' method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="{{$values->id}}">
                            <div class="row">
                                <div class="col-md-6  name  {{($errors->has('name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Name</label>
                                    <input type="text" value="{{$values->name}}"
                                         name="name" class="form-control"
                                               id="name" placeholder="Enter Usernmae">
                                        @if ($errors->has('name'))
                                            <small class="help-block">
                                                {{ $errors->first('name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                                 <div class="col-md-6  mob  {{($errors->has('mob'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mobile No</label>
                                    <input type="text" value="{{$values->mob}}"
                                         name="mob" class="form-control"
                                               id="mob" placeholder="Enter Mobile Number">
                                        @if ($errors->has('mob'))
                                            <small class="help-block">
                                                {{ $errors->first('mob') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-6  gmail  {{($errors->has('gmail'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Gmail</label>
                                    <input type="text" value="{{$values->gmail}}"
                                         name="gmail" class="form-control"
                                               id="gmail" placeholder="Enter gmail">
                                        @if ($errors->has('gmail'))
                                            <small class="help-block">
                                                {{ $errors->first('gmail') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>

                            <div class="col-md-6  address  {{($errors->has('address'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Address</label>
                                    <input type="text" value="{{$values->address}}"
                                         name="address" class="form-control"
                                               id="address" placeholder="Enter Email">
                                        @if ($errors->has('address'))
                                            <small class="help-block">
                                                {{ $errors->first('address') }}
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


                         order_no: {
                            validators: {
                                notEmpty: {
                                    message: 'order No is required'
                                }
                            }
                        },
                        order_date: {
                            validators: {
                                notEmpty: {
                                    message: 'Order date  required'
                                }
                            }
                        },
                        order_description: {
                            validators: {
                                notEmpty: {
                                    message: 'order Description is required'
                                }
                            }
                        },

                    technical_specification: {
                            validators: {
                                notEmpty: {
                                    message: 'technical specification is required'
                                }
                            }
                        },
                        order_unit: {
                            validators: {
                                notEmpty: {
                                    message: 'order unit is required'
                                }
                            }
                        },
                        sale_order_quantity: {
                            validators: {
                                notEmpty: {
                                    message: 'order quantity is required'
                                }
                            }
                        },
                        sale_unit_price: {
                            validators: {
                                notEmpty: {
                                    message: 'unit price is required'
                                }
                            }
                        },
                        sale_order_tax_category: {
                            validators: {
                                notEmpty: {
                                    message: 'Order tax category is required'
                                }
                            }
                        },
                        sale_order_net_amount: {
                            validators: {
                                notEmpty: {
                                    message: 'Net Amount is required'
                                }
                            }
                        },
                        sale_order_tax_amount: {
                            validators: {
                                notEmpty: {
                                    message: 'Order tax amount is required'
                                }
                            }
                        },
                        sale_order_gross_amount: {
                            validators: {
                                notEmpty: {
                                    message: 'Gross amount is required'
                                }
                            }
                        },
                        sale_discount_amount: {
                            validators: {
                                notEmpty: {
                                    message: 'Discount amount is required'
                                }
                            }
                        },
                        sale_grand_Total: {
                            validators: {
                                notEmpty: {
                                    message: 'Grand Total  is required'
                                }
                            }
                        },
                        sale_basic_amount: {
                            validators: {
                                notEmpty: {
                                    message: 'Basic amount is required'
                                }
                            }
                        },
                        sale_vat_values: {
                            validators: {
                                notEmpty: {
                                    message: 'Vat Values is required'
                                }
                            }
                        },
                        sale_shipment_from: {
                            validators: {
                                notEmpty: {
                                    message: 'Sale Shipment From is required'
                                }
                            }
                        },
                        sale_shipment_to: {
                            validators: {
                                notEmpty: {
                                    message: 'Sale Shipment To is required'
                                }
                            }
                        },
                        sale_remark: {
                            validators: {
                                notEmpty: {
                                    message: 'Sale Remark is required'
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