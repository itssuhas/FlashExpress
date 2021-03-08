@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h2>
           <b>Update Payment</b>
        </h2>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Payment</li>
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
                    <form action='/paymentdboyupdate' method="post" id="retailerreg" name="retailerreg" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="id" value="{{$values->id}}">
                            <div class="row">
                            
                                <div class="col-md-6  order_id  {{($errors->has('order_id'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Order Id</label>
                                    <input type="text" value="{{$values->order_id}}"
                                         name="order_id" class="form-control"
                                               id="order_id" placeholder="Enter Order Id" readonly="">
                                        @if ($errors->has('order_id'))
                                            <small class="help-block">
                                                {{ $errors->first('order_id') }}
                                            </small>
                                        @endif

                                    </div>
                                </div>
                            <div class="col-md-6  payable_amount  {{($errors->has('payable_amount'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Payable Amount</label>
                                    <input type="text" value="{{$values->payable_amount}}"
                                         name="payable_amount" class="form-control"
                                               id="payable_amount" placeholder="Enter payable amount" readonly="">
                                        @if ($errors->has('payable_amount'))
                                            <small class="help-block">
                                                {{ $errors->first('payable_amount') }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                   <div class="col-md-6  paid_amount  {{($errors->has('paid_amount'))?'has-error':''}}">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Paid Amount</label>
                                    <input type="text" value="{{$values->paid_amount}}"
                                         name="paid_amount" class="form-control"
                                               id="paid_amount" placeholder="Enter Paid Amount">
                                        @if ($errors->has('paid_amount'))
                                            <small class="help-block">
                                                {{ $errors->first('paid_amount') }}
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

                         order_id: {
                            validators: {
                                notEmpty: {
                                    message: 'Order Id is required'
                                }
                            }
                        },
                        payable_amount: {
                            validators: {
                                notEmpty: {
                                    message: 'payable amount is required'
                                }
                            }
                        },
                        paid_amount: {
                            validators: {
                                notEmpty: {
                                    message: 'Paid Amount is required'
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