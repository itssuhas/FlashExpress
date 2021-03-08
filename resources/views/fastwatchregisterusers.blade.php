@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <b>Register Users</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Register Users</li>
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
                    @if(isset($retailer))
                        @if(count($retailer) >0)
                
                            <div class="table-responsive">
                                <table  id="example" class="table table-bordered table-hover retailer-search">
                                     <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Name</th>
                                        <th>Mobile No</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Update</th>
                                        <th>Delete</th>
                                    </tr>
                                        </thead>
                                     @php($i=1)
                                    @foreach($retailer as $item)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$item->full_name}}</td> 
                                            <td>{{$item->mob}}</td>
                                            <td>{{$item->address}}</td>
                                            @if(($item->status == 'Pending'))
                                            <td>
                                             <a href='approveedUser/{{ $item->id }}'>
                                            <span style="font-size:18px;" class="fa fa-check-square-o" onclick="return confirm('Are you sure......?')">Please Approve</span>
                                            </td>
                                            @else
                                            <td style="color:green;">
                                            <b>Approved Success</b>
                                            </td>
                                            @endif
                                            
                                          

                                                <td>                               <a href="{{URL::to('userupdate/'.$item->id) }}">
                                       <span class="glyphicon glyphicon-edit">
                                          Update
                                       </span> </a>

                                            </td>


                                             <td>          
                                             <a href='fastwatchdeleteuser/{{ $item->id }}'>
                                            <span style="font-size:18px;color:red" class="fa fa-trash-o" onclick="return confirm('Are you sure......?')">Delete</span>
                                            </td>
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