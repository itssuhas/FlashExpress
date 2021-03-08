@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
     <b>Pending Orders Deatils</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Pending Orders Deatils</li>
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
                
                    </div>
                </div>

                <div class="box-body">
                    @if(isset($package_deatils))
                
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered table-hover retailer-search">
                                    <thead>
                                    <tr>
                                       <th>id</th>
                                       <th>Order Id</th>
                                       <th>item Name</th>
                                       <th>Item Qty</th>
                                       <th>Qty type</th>
                                       <th>Amount</th>
                                       <th>discount Amount</th>
                                       <th>Total Amount</th>
                                       <th>Payment Mode</th>
                                       <th>Delivery Address</th>
                                       <th>Status</th>
                                     </tr>
                                     </thead>
                                     @php($i=1)
                                      @foreach($package_deatils as $item)
                                        <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->order_no}}</td>
                                        <td>{{$item->item_name}}</td>
                                        <td>{{$item->item_qty}}</td>
                                        <td>{{$item->item_qty_type}}</td>
                                        <td>{{$item->item_actual_amount}}</td>
                                        <td>{{$item->item_discount_amt}}</td>
                                        <td>{{$item->item_total_amt}}</td>
                                        <td>{{$item->order_payment_mode}}</td>
                                        <td>{{$item->order_delivery_address}}</td>
                                        <td>{{$item->status}}</td>
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
            <div class="box-footer">
                                <div class="container">
                    @foreach($pack as $values)
                    <form action='/getAdminOrder' method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="{{$values->order_id}}">
                        <input type="hidden" name="vendor_id" id="vendor_id" value="{{$values->vendor_id}}">
                            <div class="row">
                                <div class="col-md-6 name {{($errors->has('name'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Delivery Boy</label>
                                                    
                            <select name="name" class="form-control">
                          @foreach($deliveryboy as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                         @endforeach                                    
                            </select>
                                        @if ($errors->has('name'))
                                            <small class="help-block">
                                                {{ $errors->first('name') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-6 order_no {{($errors->has('order_no'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Order No</label>
                                    <input type="text" value="{{$values->order_no}}"
                                         name="order_no" class="form-control"
                                               id="order_id" placeholder="Enter order no">
                                        @if ($errors->has('order_no'))
                                            <small class="help-block">
                                                {{ $errors->first('order_no') }}
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
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
    <!-- Modal -->
  
   
   
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
                        hotel_name: {
                            validators: {
                                notEmpty: {
                                    message: 'Hotel Name is required'
                                }
                            }
                        },
                
                        hotel_address: {
                            validators: {
                                notEmpty: {
                                    message: 'Hotel Address is required'
                                }
                            }
                        },
                   
                        hotel_contact: {
                            validators: {
                                notEmpty: {
                                    message: 'Hotel contact is required'
                                }
                            }
                        },

                        area: {
                            validators: {
                                notEmpty: {
                                    message: 'area contact is required'
                                }
                            }
                        },
                        city: {
                            validators: {
                                notEmpty: {
                                    message: 'city is required'
                                }
                            }
                        },
              
                    adhar_no: {
                            validators: {
                                notEmpty: {
                                    message: 'Adhar No is required'
                                }
                            }
                        },
                    hotel_license_no: {
                            validators: {
                                notEmpty: {
                                    message: 'hotel License is required'
                                }
                            }
                        },

                    bank_deatils: {
                            validators: {
                                notEmpty: {
                                    message: 'Bank Deatils is required'
                                }
                            }
                        },

                    hotel_type: {
                            validators: {
                                notEmpty: {
                                    message: 'hotel type is required'
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