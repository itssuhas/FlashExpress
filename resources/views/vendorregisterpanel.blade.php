<nav class="navbar navbar-expand-sm bg-dark navbar-dark">

  <div class="container-fluid" style="background-color:#3c8dbc;"> 
    <div class="navbar-header">
      <div class="navbar-brand"><b><span style="color:black;"><u>Flash</span><span style="color:red;">Express</span></u></b>
    </div>
        <div class="navbar-brand"><b><u><a href="/"><span style="color: black">Home</a></span></u></b>
        
    </div>
   </div>
  </div>
</nav>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
        
    </script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tachyons/4.9.1/tachyons.min.css">
    <link rel="stylesheet" href="/vendors/formvalidation/dist/css/formValidation.min.css">

</head>


<div class="container">

                <div class="modal-header">
                    <h4 class="modal-title" align="center"><b><u>Vendor Registration</u></b></h4>
   
                </div>
        
         @if($data != null)  
        <div class="alert alert-danger">
        <ul>
                <li>{{ $data }}</li>
       
        </ul>
        </div>
        @endif
        
                <div class="modal-body">
                    <form action="vendor-registrationpanel" method="post" id="retailerreg"
                     name="myForm" enctype="multipart/form-data">
                        <input type="hidden" name="" value="">

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Select Admin & City</label>
                                         
                        <select name="admincity" class="form-control" required="">
                          @foreach($admincity as $user)
                            <option value="{{$user->id}}">{{$user->city_name.' '.$user->name}}</option>
                        @endforeach  
                        </select>
                                            <small id="help-block">
                                                    <div id="city_error" class="val_error"></div>
                                            </small>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Name :-</label>
                                        <input type="text" value="{{old('name')}}" name="name" class="form-control"
                                               id="name" placeholder="Enter vendor Name" required>
                                            <small id="help-block">
                                                    <div id="name_error" class="val_error"></div>

                                            </small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email :-</label>
                                        <input type="email" value="{{old('email')}}" name="email" class="form-control"
                                               id="email" placeholder="Enter vendor Email" required>
                                            <small id="help-block">
                                            <div id="email_error" class="val_error"></div>   
                                            </small>
                                 </div>
                                </div>
                            

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Mobile No :-</label>
                                        <input type="number" value="{{old('mobile')}}" name="mobile" class="form-control"
                                               id="mobile" placeholder="Enter Mobile No" required>
                                    <small id="help-block">
                                    <div id="mob_error" class="val_error"></div>
                                    </small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Address :-</label>
                                        <input type="text" value="{{old('address')}}"
                                         name="address" class="form-control"
                                               id="address" placeholder="Enter Vendor Address" required>
                                            <small class="help-block">
                                                <div id="address_error" class="val_error">            
                                                 </div>

                                            </small>
                                    </div>
                                </div>
                        
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Password :-</label>
                                        <input type="password" value="{{old('password')}}" name="password"
                                               class="form-control" id="password" placeholder="Enter Password" required>

                                            <small class="help-block">
                                            <div id="pass_error" class="val_error"></div>

                                            </small>
                                    </div>
                                </div>
                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> Confirm Password :-</label>
                                        <input type="password" value="{{old('confirm_password')}}" name="confirm_password"
                                               class="form-control" id="confirm_password" placeholder="Enter confirm Password" required>

                                            <small class="help-block">
                                            <div id="pass_error" class="val_error"></div>

                                            </small>
                                    </div>
                                </div>
                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Vendor Bussiness</label>
                                         <select name="vendor_bussiness" class="form-control">
                                        <option>Courier</option>
                                        <option>Ecommorce</option>
                                        </select>                             
                                <small class="help-block">
                                            <div id="pass_error" class="val_error"></div>
                                            </small>
                                    </div>
                                </div>

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Images</label>
                                        <input type="file" value="{{old('img')}}" name="img"
                                               class="form-control" id="img" placeholder="Enter Images" required>

                                            <small class="help-block">
                                            <div id="pass_error" class="val_error"></div>

                                            </small>
                                    </div>
                                </div>    
                            </div>
                            <div class="modal-footer">
                                <button type="submit"  class="col-md-12 btn btn-primary">Submit</button>
                            </div>
                           </div>
                    </form>
                </div>
      </div>
    
