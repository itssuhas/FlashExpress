@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
        <b>Feedback Deatils</b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="active">Feedback Deatils</li>
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
                                        <th>User Name</th>
                                        <th>Mobile</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th>Delete</th>
                                    </tr>
                                        </thead>
                                     @php($i=1)
                                    @foreach($retailer as $item)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$item->full_name}}</td> 
                                            <td>{{$item->mob}}</td>
                                            <td>{{$item->comment}}</td>
                                            <td>{{$item->date}}</td>
                                            <td>    
                                                <a href="{{URL::to('Feedbackdelete/'.$item->id) }}">
                                       <span style="color:red;" class="fa fa-trash" onclick="return confirm('Are you sure......?')">
                                          Delete
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