@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <b>Completed Orders</b>
        </h1>

        <ol class="breadcrumb">
            <li>Total Deposited Money:<b> {{$total}} Rs.</b></li>
            <li>Total  Deposited Money Between Date:<b> {{$reqtotal}} Rs.</b></li>                
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Total Deposited Money</li>
        </ol>
    </section>


    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"> 



           <!--  <a href="{{ url('downloadExcel/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a> -->

 
<!--             <a href="{{ url('downloadExcel/csv') }}"><button class="btn btn-success">Download CSV</button></a> -->

        </h3>
                
                </div>
            <form action="/completed_order" autocomplete="off" method="get" id="pointtransfer" name="pointtransfer">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="box-body">

                <div class="row">
                <div class="col-md-4 inside {{($errors->has('fdate'))?'has-error':''}}">
                    <div class="form-group date">
                        <label>From Date:</label>

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar" title="From Date"></i>
                            </div>
                            <input type="text" @if(isset($fromDate)) value="{{$fromdates}}" @endif
                                   name="fdate" class="date form-control" id="datepicker"
                                   placeholder="Enter date">
                        </div>
                        @if ($errors->has('fdate'))
                            <small class="help-block">
                                {{ $errors->first('fdate') }}
                            </small>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 inside  {{($errors->has('tdate'))?'has-error':''}}">
                    <div class="form-group date">
                        <label>To Date:</label>

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar" title="To date"></i>
                            </div>
                            <input type="text"  @if(isset($toDate)) value="{{$todates}}" @endif
                                    name="tdate" class="date form-control" id="datepickers"
                                   placeholder="Enter date">
                        </div>
                        @if ($errors->has('tdate'))
                            <small class="help-block">
                                {{ $errors->first('tdate') }}
                            </small>
                        @endif
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-12">



                    <button type="submit"  style=" margin: 6px;" class="btn btn-info pull-right">Search
                    </button>

                    <button type="submit" style=" margin: 6px;" class="btn btn-info pull-right">Back
                    </button>

                </div>

            </div>

        </div>
            </form>
                </div>
        </div>
    </div>
    <!-- Main content -->
      <!-- Main content -->
    <section class="content">

                   
          
        <!-- Default box -->
        <div class="box">
            <!--div class="box-header with-border">
                <h3 class="box-title">Title</h3>
            </div-->
          <!--     <a href="{{ url('downloadExcel/xls') }}"><button class="btn btn-success">Download Report In Excel Format!!</button></a> -->
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
                    @if(isset($retailer))
                        @if(count($retailer) >0)
                
                            <div class="table-responsive">

                                <table  id="example" class="table table-bordered table-hover retailer-search">

                                     <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Order Id</th>
                                        <th>User Name</th>
                                        <th>Mob.No</th>
                                        <th>Hotel Name</th>
                                        <th>Hotel Address</th>
                                        <th>Discount Amount</th>
                                        <th>Total Amount</th>
                                        <th>Paymnet Mode</th>
                                        <th>Delivery Address</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                        </thead>
                                     @php($i=1)
                                    @foreach($retailer as $item)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$item->order_no}}</td>
                                            <td>{{$item->full_name}}</td>          
                                            <td>{{$item->mob}}</td>
                                            <td>{{$item->hotel_name}}</td>
                                            <td>{{$item->hotel_address}}</td>
                                            <td>{{$item->order_discount_amt}}</td>
                                            <td align="center"><i class="fa fa-rupee"> {{$item->order_total_amt}} </i></td>
                                            <td>{{$item->order_payment_mode }}</td>
                                            <td>{{$item->order_delivery_address }}</td>
                                            <td>{{$item->created_at }}</td>
                                            <td>{{$item->status }}</td>

                                            <td>                              
                                    <a href="{{URL::to('getVendorOrderItemDeatils/'.$item->order_id) }}">
                                       <span class="glyphicon glyphicon-edit">
                                        View Deatils
                                       </span> </a></td>
                                        </tr>


                                    @endforeach

                                    </table>
                             

                            </div>
                            <div class="retailer-pagination">
                                {{ $retailer->links() }}
                            </div>
                        @else
                            <table class="table table-responsive table-hover">
                                <tr><th>No Record found</th></tr>
                            </table>
                        @endif
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
  
    </div>

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

     
    @endif

   
@endsection