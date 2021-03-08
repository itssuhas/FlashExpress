@extends('base')

@section('page-content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
            <!--small>it all starts here</small-->
        </h1>
        <ol class="breadcrumb">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">dashboard</li>
        </ol>
    </section>
 <div class="box-body">

      @if(Session::has('alert'))
                 <div class="box-body1">
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
                    </div>
                @endif
            </div>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-body">
            <div class="row">
            @if(Auth::user()->user_Type=='ADMIN')
            <div class="col-md-6">
                <table class="table ">
                    <tr ><th class="text-center">Agent ID</th><th class="text-center">Name</th><th class="text-center">Pin</th><th class="text-center">Password</th>
                        @if(Auth::user()->user_type=='ADMIN' ) <th class="text-center">Change password/Pin</th>@endif
                    </tr>


                @foreach($agent as $value)
                    <tr  class="text-center">

                      <td>{{$value->user_id}}</td>
                      <td>{{$value->name}}</td>
                      <td>{{$value->pin}}</td>
                      <td>{{$value->password_plain}}</td>

                        @if(Auth::user()->user_type=='ADMIN' )
                            <td class="text-center"><a user_id="{{$value->user_id}}" class="btn btn-default changePin" data-toggle="modal" data-target="#changePinModal">Change Pin</a>
                            <a class="btn btn-default changePassword" user_id="{{$value->user_id}}" data-toggle="modal" data-target="#changePasswordModal">Change Password</a>
                        @endif
                        </td>
                        </tr>

                @endforeach
                </table>
                {{$agent->appends(['page1' => $agent->currentPage(), 'page2' => $retailer->currentPage()])->links()}}
            </div>
                @endif
                <div class="col-md-6">
                    <table class="table ">
                        <tr ><th class="text-center">Retailer ID</th><th class="text-center">Name</th><th class="text-center">Pin</th><th class="text-center">Password</th>
                            @if(Auth::user()->user_type=='ADMIN' )<th class="text-center">Change password/Pin</th>@endif
                        </tr>


                        @foreach($retailer as $value)
                            <tr  class="text-center">

                                <td>{{$value->user_id}}</td>
                                <td>{{$value->retailer_name}}</td>
                                <td>{{$value->pin}}</td>
                                <td>{{$value->password_plain}}</td>

    @if(Auth::user()->user_type=='ADMIN' ) <td><a user_id="{{$value->user_id}}" class="btn btn-default changePin" data-toggle="modal" data-target="#changePinModal">Change Pin</a>
          <a class="btn btn-default changePassword" user_id="{{$value->user_id}}" data-toggle="modal" data-target="#changePasswordModal">Change Password</a></td>@endif
  </tr>

@endforeach
</table>
{{$retailer->appends(['page1' => $agent->currentPage(), 'page2' => $retailer->currentPage()])->links()}}

</div>
            </div>
</div>

</div>

</section>

<div id="changePasswordModal" class="modal fade">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h5 class="modal-title">Change Password</h5>
</div>


<form action="/change-password" method="post" name="changepass" class="changepass">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="change"></div>
<div class="modal-body" id="modal-body">
<div class="form-group">
  <div class="row">
      <div class="col-md-12 inside  {{($errors->has('oldpass'))?'has-error':''}}">
          <label>Old Password</label><span style="color: red"> *</span>
          <input type="password" id="oldpass" name="oldpass" placeholder="Enter old password"
                 class="form-control" required>
          @if ($errors->has('oldpass'))
              <small class="help-block">
                  {{ $errors->first('oldpass') }}
              </small>
          @endif
      </div>
  </div>
  </div>
<div class="form-group">
  <div class="row">
      <div class="col-md-12 inside  {{($errors->has('newpass'))?'has-error':''}}">
          <label>New Password</label><span style="color: red"> *</span>
          <input type="password" id="newpass" name="newpass" placeholder="Enter new password"
                 class="form-control" required>
          @if ($errors->has('newpass'))
              <small class="help-block">
                  {{ $errors->first('newpass') }}
              </small>
          @endif
      </div>
      </div>
  </div>
<div class="form-group">
  <div class="row">
  <div class="col-md-12 inside  {{($errors->has('newpass'))?'has-error':''}}">
      <label>Confirm Password</label><span style="color: red"> *</span>
      <input type="password" id="cnewpass" name="cnewpass" placeholder="Confirm password"
             class="form-control" required>
      @if ($errors->has('cnewpass'))
          <small class="help-block">
              {{ $errors->first('cnewpass') }}
          </small>
      @endif
  </div>
</div>
</div>
<input type="hidden" id="id" value="" name="id">
</div>

<div class="modal-footer">
  <button type="submit" id="changepassd" class="btn btn-info pull-right">Submit</button>
</div>

</form>

</div>
</div>
</div>


<div class="modal fade" id="changePinModal" role="dialog">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Change Pin</h4>
</div>
<div class="modal-body">
<form action="/changePin" method="post" name="changepin" class="changepinForm">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="change"></div>
<div class="modal-body" id="modal-body">

  <div class="form-group">
      <div class="row">
          <div class="col-md-12 inside  {{($errors->has('oldpin'))?'has-error':''}}">
              <label>Old pin</label><span style="color: red"> *</span>
              <input type="text" id="oldpin" name="oldpin" placeholder="Enter old pin"
                     class="form-control" required>
              @if ($errors->has('oldpin'))
                  <small class="help-block">
                      {{ $errors->first('oldpin') }}
                  </small>
              @endif
          </div>
      </div>
  </div>

  <div class="form-group pinFormGroup">
      <div class="row">
          <div class="col-md-12 inside  {{($errors->has('newpin'))?'has-error':''}}">
              <label>New Pin</label><span style="color: red"> *</span>
              <input type="text" class="newpin form-control" name="newpin" placeholder="Enter New Pin"
                     class="form-control" required>
              <small id="error"></small>
              @if ($errors->has('newpin'))
                  <small class="help-block">
                      {{ $errors->first('newpin') }}
                  </small>

              @endif
          </div>
      </div>
  </div>
  <div class="form-group">
      <div class="row">
          <div class="col-md-12 inside  {{($errors->has('cnewpin'))?'has-error':''}}">
              <label>Conferm Pin</label><span style="color: red"> *</span>
              <input type="text" id="cnewpin" name="cnewpin" placeholder="Conferm pin"
                     class="form-control" required>
              @if ($errors->has('cnewpin'))
                  <small class="help-block">
                      {{ $errors->first('cnewpin') }}
                  </small>
              @endif
          </div>
      </div>
  </div>
  <input type="hidden" class="user_id" value="" name="id">

  <div class="form-group">
      <div class="row">
          <div class="col-md-12">
              <button type="submit" class="btn btn-info pull-right">Submit</button>
          </div>
      </div>
  </div>
</div>

</form>

</div>

</div>

</div>
</div>


@endsection

@section('page-scripts')

<script type="text/javascript">
$(document).ready(function () {
$('.changePassword').click(function (){
id=$(this).attr('user_id');
$('#id').val(id);
});
$('.changePin').click(function (){
id=$(this).attr('user_id');
$('.user_id').val(id);
});
});
</script>
@endsection