<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
//use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use function PHPSTORM_META\type;

class CommonController extends Controller
{
    protected $guard = 'user';

    public function __construct()
    {

        $this->middleware('auth', ['except' => ['showLogin', 'doLogin']]);
    
    }

    public function showLogin()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'username' => 'required', // make sure the email is an actual email
            'password' => 'required' // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('/')
                ->withErrors($validator)// send back all errors to the login form
                ->withInput($request->except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {
            // create our user data for the authentication
            $userdata = array(
                'mob' => $request->input('username'),
                'password' => $request->input('password'),
                'status' =>'Approved'
            );

            // attempt to do the login
            if (Auth::attempt($userdata, true)) {
                $data = Array('type' => 'success', 'message' => 'Login Successful');
                Session::put('alert', $data);

            $response = DB::table('hotel')
                    ->where('vendor_id','=',Auth::user()->id )
                    ->update(['status' => '1']);


                if (Auth::user()->user_type == 'ADMIN'||Auth::user()->user_type == 'RETAILER' || Auth::user()->user_type == 'SUPERADMIN') {
                    return redirect('/dashboard');
                }
             else {
                    //if player trying to login
                    $data = Array('type' => 'failure', 'message' => '<span class="text-semibold">Oops!</span> You are not allowed to login here.');
                    Session::put('alert', $data);
                    return redirect("/");
                }
            } else {
                $data = Array('type' => 'failure', 'message' => '<span class="text-semibold">Oops!</span> The username or password you\'ve entered is wrong.');
                Session::put('alert', $data);
                return redirect("/");
            }
        }
    }

    public function master()
    {

        return([]);
    }

    public function dashboard()
    {
        $data = $this->master();

        $users = DB::table('user_regi')->count();

        $vendor = DB::table('users')->where('user_type','=','RETAILER')->count();
        
        $adminvendor = DB::table('users')
        ->where('user_type','=','RETAILER')
        ->where('admin_id','=',Auth::user()->id)
        ->count();

        $porders = DB::table('orders')->where('status','=','Pending')->count();
        $corders = DB::table('orders')->where('status','=','Completed')->count();


        $Adminporders = DB::table('orders')
        ->where('admin_id','=',Auth::user()->id)
        ->where('status','=','Pending')->count();
        
        $Admincorders = DB::table('orders')
        ->where('admin_id','=',Auth::user()->id)
        ->where('status','=','Completed')->count();


        $vporders = DB::table('orders')
        ->where('vendor_id','=',Auth::user()->id)
        ->where('status','=','Pending')
        ->count();
        
        $vcorders = DB::table('orders')
        ->where('vendor_id','=',Auth::user()->id)
        ->where('status','=','Completed')
        ->count();

        $usertype = DB::table('users')->where('id','=',Auth::user()->id)->value('user_type');

        return view('index', ['data' => $data,'porders'=>$porders,'corders'=>$corders,'vendor'=>$vendor,'users' => $users,'usertype'=>$usertype,'vporders'=>$vporders,'vcorders'=>$vcorders,'adminvendor'=>$adminvendor,'Adminporders'=>$Adminporders,'Admincorders'=>$Admincorders]);
    }

    public function randomNumberAjax(request $request)
    {
        $idNumber = $request->idNumber;
        $ids = DB::table('player')
            ->select('id')
            ->get();

        $idArr = [];
        foreach ($ids as $value) {
            $idArr[] = $value->id;
        }
        $randNumber_arr = [];
        if (Auth::user()->user_type == 'ADMIN') {
            for ($i = 1; $i <= $idNumber; $i++) {
                $rand_number = "GK".mt_rand(00000000, 99999999);
                if (!in_array($rand_number, $idArr) and !in_array($rand_number, $randNumber_arr)) {
                    $randNumber_arr[] = $rand_number;
                } else {
                    $idNumber++;
                }
            }
            $error = '';
        } else {
            $ids = DB::table('player')
                ->select('id')
                ->where('agent_id', Auth::user()->id)
                ->where('retailer_id', '')
                ->orderByRaw('RAND()')
                ->take($idNumber)->get();
            if (count($ids) >= $idNumber) {
                if (!empty($idNumber)) {
                    foreach ($ids as $value) {
                        $randNumber_arr[] = $value->id;
                        $error = '';
                    }
                }
            } else {
                $randNumber_arr = [];
                $error = 'Ids not available';
            }
        }
        return response()->json(['ids' => $randNumber_arr, 'error' => $error]);
    }

    public function getSignOutadmin()
    {
        $response = DB::table('hotel')
            ->where('vendor_id','=',Auth::user()->id )
           ->update(['status' => '0']);
        
        Auth::logout();
        $data = Array('type' => 'success', 'message' => 'You have logged out successfully,login again to continue');
        Session::put('alert', $data);
        return redirect("/");

    }

    public function changePassword(request $request)
    {
        $data = $this->master();
        $agent = DB::table('agent')
            ->join('users','users.id','=','agent.user_id')
            ->select('user_id','name','pin','password_plain')
            ->paginate(10,['*'], 'page1');
        $retailer = DB::table('retailer')
            ->join('users','retailer.user_id','=','users.id');
            if(Auth::user()->user_type=='AGENT'){
                $retailer=$retailer->where('agent_id', Auth::user()->id);
            }
        $retailer=$retailer->select('user_id','retailer_name','pin','password_plain')
            ->paginate(10,['*'], 'page2');
      return view('changePassword', ['data'=>$data,'agent'=>$agent,'retailer'=>$retailer]);
    }

    public function changePasswordSubmit(Request $request)
    {
        $rules = array(
            'oldpass' => 'required',
            'newpass' => 'required',
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::back()->with('error_code', 5)
                ->withErrors($validator)// send back all errors to the login form
                ->withInput($request->except('password')); // send back the input (not the password) so that we can

        } else {
            
            $id = $request->input("id");
            $oldpass = $request->input("oldpass");
            $newpass = $request->input("newpass");

            $PWD = bcrypt($newpass);

            $admindata = DB::table('users')->select('password_plain')->where('id', '=', $id)->get();
            $adminpass = $admindata[0]->password_plain;
            if ($adminpass == $oldpass) {
                $response = DB::table('users')
                    ->where('id', $id)
                    ->update(['password' => $PWD, 'password_plain' => $newpass]);
                $response = "sucess";
                $data = Array('type' => 'sucess', 'message' => 'Password Changed successfully');

            } else {
                $response = "failure";
                $data = Array('type' => 'error', 'message' => 'Falied to change password');
            }
            Session::put('alert', $data);
            return Redirect::back();
        }
    }

    public function SaleOrders()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('vehicle')
                ->select('vehicle_id','vehicle_model','image','vehicle_name','vehicle_no','capacity_unit')
                ->orWhere('vehicle_model', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('vehicle')
                ->select('vehicle_id','vehicle_model','image','vehicle_name','vehicle_no','capacity_unit')
                ->get();

        }

        $data = $this->master();


        return view('saleorders', ['retailer' => $retailer,'data' => $data]);
    }

    public function SaleOrdersSubmit(Request $request)
    {
        $rules = array(

            'model_name' => 'required',
            'vehicle_name'=>'required',
            'capacity_unit'=> 'required',
            'vehicle_no' =>'required',
            'image'=> 'required',

        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/sales_orders')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('vehicle')->count();
                if ($retailerdata < 1000) {

                    $model_name = $request->input("model_name");
                    $vehicle_name = $request->input("vehicle_name");
                    $capacity_unit = $request->input("capacity_unit");
                    $vehicle_no = $request->input("vehicle_no");



                   if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/vehicle/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }

                  
                    $id = DB::table('vehicle')->insertGetId(
                        ['vehicle_model'=>$model_name,'vehicle_name'=>$vehicle_name,'capacity_unit'=>$capacity_unit,'vehicle_no'=>$vehicle_no,'image'=>$filename]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Vehicle Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/sales_orders');
        }
    }

    public function delete($id) 
    {
      
      $select=DB::delete('delete from vehicle where vehicle_id = ?',[$id]);
     
            return redirect('/sales_orders');
    }

    public function getPackage($id)
    {

         $pack=DB::table('vehicle')->where('vehicle_id', '=', $id)->get();

         $data = $this->master();


        return view('lowrisktimeupdate', ['pack' => $pack,'data' => $data]);
    }

    public function lowRiskupdateSubmit(Request $request)
    {
        $rules = array(
            
            'vehicle_model' => 'required', 
            'vehicle_name'=> 'required',
            'capacity_unit'=>'required',
            'vehicle_no' =>'required',
            'image' =>'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/sales_orders')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password')); 

        } else {

            try {

                $retailerdata = DB::table('vehicle')->count();
                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $vehicle_model = $request->input("vehicle_model");
                    $vehicle_name = $request->input("vehicle_name");
                    $capacity_unit = $request->input("capacity_unit");          
                    $vehicle_no = $request->input("vehicle_no");          


                    if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/vehicle/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }                          

                    $id = DB::table('vehicle')
                            ->where('vehicle_id','=',$id)
                            ->update(['vehicle_model' => $vehicle_model,'vehicle_name'=>$vehicle_name,'capacity_unit'=>$capacity_unit,'image'=>$filename,'vehicle_no'=>$vehicle_no]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Vehicle Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/sales_orders');
        }
    }

    public function getuser($id)
    {

         $pack=DB::table('user_regi')->where('id', '=', $id)->get();

         $data = $this->master();

        return view('registeruserupdate', ['pack' => $pack,'data' => $data]);
    }

    public function userupdateSubmit(Request $request)
    {
        $rules = array(
            'mob' => 'required', 
            'full_name' => 'required',
            'address'=> 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/register-user')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('user_regi')->count();

                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $mob = $request->input("mob");
                    $full_name = $request->input("full_name");
                    $address = $request->input("address");
                
                    $id = DB::table('user_regi')
                            ->where('id','=',$id)
                            ->update(['mob'=>$mob,'full_name' => $full_name,'address'=>$address]
                    );

                    $data = Array('type' => 'success', 'message' => 'User Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/register-user');
        }
    }

    public function getvendor($id)
    {

         $pack=DB::table('users')->where('id', '=', $id)->get();

         $data = $this->master();

        return view('updatevendor', ['pack' => $pack,'data' => $data]);
    }

    public function vendorupdateSubmit(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'mob' => 'required', 
            'gmail'=>'required',
            'address'=> 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/register-vendor')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('users')->count();

                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $name = $request->input("name");
                    $mob = $request->input("mob");
                    $gmail = $request->input("gmail");
                    $address = $request->input("address");
                
                    $id = DB::table('users')
                            ->where('id','=',$id)
                            ->update(['mob'=>$mob,'name' => $name,'address'=>$address,'gmail'=>$gmail]
                    );

                    $data = Array('type' => 'success', 'message' => 'Vendor Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/register-vendor');
        }
    }

    public function fastwatchregisteruser()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('user_regi')
                ->select('id','mob','reg_date','full_name','is_verify','address','status','image')
                ->orWhere('user_regi.id', 'like', '%' . $text . '%')
                ->orWhere('user_regi.mob', 'like', '%' . $text . '%')
                ->orWhere('user_regi.full_name', 'like', '%' . $text . '%')
                ->orWhere('user_regi.reg_date', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('user_regi')
                ->select('id','mob','reg_date','full_name','is_verify','address','status','image')
                ->orderBy('user_regi.reg_date', 'desc')
                ->paginate(10);
        }

        $data = $this->master();
        
        return view('fastwatchregisterusers', ['retailer' => $retailer, 'data' => $data]);
    }

    public function fastwatchdeleteuser($id) {
      $select=DB::delete('delete from user_regi where id = ?',[$id]);

            return redirect('/register-user');
     }

    public function deletevendor($id) {

      $select=DB::delete('delete from users where id = ?',[$id]);

            return redirect('/register-vendor');
     }


    public function registeruserClient()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('users')
                ->select('id','name','mob','gmail','address','user_type','status')
                ->where('user_type','=','RETAILER')
                ->orWhere('id', 'like', '%' . $text . '%')
                ->orWhere('mob', 'like', '%' . $text . '%')
                ->orWhere('name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));

        } else {

            $retailer = DB::table('users')
                ->select('id','name','mob','gmail','address','user_type','status')
                ->where('user_type','=','RETAILER')
                ->paginate(10);
        }

        $data = $this->master();

        return view('registerclient', ['retailer' => $retailer, 'data' => $data]);
    }


    public function approveedUser($id)
    {
            DB::table('user_regi')
            ->where('id','=', $id)
            ->update(['status'=>'Approved']);
            return redirect('/register-user');
    }
      
    public function approveedVendor($id)
    {
            DB::table('users')
            ->where('id','=', $id)
            ->update(['status'=>'Approved']);
            return redirect('/register-vendor');
    }

   
    public function getCity($id)
    {

         $pack=DB::table('city')->where('city_id', '=', $id)->get();

         $data = $this->master();


        return view('updatecity', ['pack' => $pack,'data' => $data]);
    }

    public function CityUpdateSubmit(Request $request)
    {
        $rules = array(
            
            'city_name' => 'required',       
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/sales_orders')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {
                
                $retailerdata = DB::table('city')->count();
                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $name = $request->input("city_name");
       
                
                    $id = DB::table('city')
                            ->where('city_id','=',$id)
                            ->update(['city_name' => $name]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'city Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/city_deatils');
        }
    }


    public function cityDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('city')
                ->select('city_id','city_name')
                ->orWhere('city_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('city')
                ->select('city_id','city_name')
                ->get();

        }

        $data = $this->master();


        return view('city', ['retailer' => $retailer,'data' => $data]);
    }

    public function citySubmit(Request $request)
    {
        $rules = array(

            'city_name'=> 'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/city_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('city')->count();

                if ($retailerdata < 1000) {

                     $city_name = $request->input("city_name");

                               
                    $id = DB::table('city')->insertGetId(
                        ['city_name'=>$city_name]
                    ); 
                    $data = Array('type' => 'success', 'message' => 'City Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/city_deatils');
        }
    }

    public function citydelete($id) 
    {
      
      $select=DB::delete('delete from city where city_id = ?',[$id]);
     
            return redirect('/city_deatils');
    }

    public function areaDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('area')
                ->join('city','area.city_id','city.city_id')
                ->select('area.id','area.city_id','area.area_name','city.city_name')
                ->orWhere('area.area_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('area')
                ->join('city','area.city_id','city.city_id')
                ->select('area.id','area.city_id','area.area_name','city.city_name')
                ->get();

        }

        $data = $this->master();

            $citylist = DB::table('city')
                ->select('city_id','city_name')
                ->get();


        return view('area', ['retailer' => $retailer,'data' => $data,'citylist'=>$citylist]);
    }

    public function areaSubmit(Request $request)
    {
        $rules = array(

            'city_id'=>'required',
            'area_name'=> 'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/area_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('area')->count();

                if ($retailerdata < 1000) {

                     $area_name = $request->input("area_name");
                     $city_id = $request->input("city_id");

                               
                    $id = DB::table('area')->insertGetId(
                        ['city_id'=>$city_id,'area_name'=>$area_name]
                    ); 
                    $data = Array('type' => 'success', 'message' => 'Area Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/area_deatils');
        }
    }

    public function areadelete($id) 
    {
      
      $select=DB::delete('delete from area where id = ?',[$id]);
     
            return redirect('/area_deatils');
    }


    public function getArea($id)
    {

         $pack=DB::table('area')
                ->join('city','area.city_id','city.city_id')
                ->select('area.id','area.city_id','area.area_name','city.city_name')
                ->where('area.id', '=', $id)->get();

         $data = $this->master();


        return view('updatearea', ['pack' => $pack,'data' => $data]);
    }

    public function AreaUpdateSubmit(Request $request)
    {
        $rules = array(
            
            'area_name' => 'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/area_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {
                
                $retailerdata = DB::table('area')->count();

                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $area_name = $request->input("area_name");
                    
       
                
                    $id = DB::table('area')
                            ->where('id','=',$id)
                            ->update(['area_name' => $area_name]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'area Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/area_deatils');
        }
    }

    public function categoryDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('category')
                ->select('id','cat_name')
                ->orWhere('cat_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('category')
                ->select('id','cat_name')
                ->get();

        }

        $data = $this->master();


        return view('category', ['retailer' => $retailer,'data' => $data]);
    }

    public function categorySubmit(Request $request)
    {
        $rules = array(

            'cat_name'=> 'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/cat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('category')->count();

                if ($retailerdata < 1000) {

                     $cat_name = $request->input("cat_name");

                               
                    $id = DB::table('category')->insertGetId(
                        ['cat_name'=>$cat_name]
                    ); 
                    $data = Array('type' => 'success', 'message' => 'Category Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/cat_deatils');
        }
    }

    public function catdelete($id) 
    {
      
      $select=DB::delete('delete from category where id = ?',[$id]);
     
            return redirect('/cat_deatils');
    }



    public function getCategory($id)
    {

         $pack=DB::table('category')->where('id', '=', $id)->get();

         $data = $this->master();


        return view('updatecategory', ['pack' => $pack,'data' => $data]);
    }

    public function CategoryUpdateSubmit(Request $request)
    {
        $rules = array(
            
            'cat_name' => 'required',       
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/cat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {
                
                $retailerdata = DB::table('category')->count();
                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $cat_name = $request->input("cat_name");
       
                
                    $id = DB::table('category')
                            ->where('id','=',$id)
                            ->update(['cat_name' => $cat_name]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'category Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/cat_deatils');
        }
    }

    public function SubcategoryDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('subcategory')
                ->join('category','subcategory.cat_id','category.id')
                ->select('subcategory.id','subcategory.cat_id','subcategory.sub_cat_name','category.cat_name')
                ->orWhere('subcategory.sub_cat_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('subcategory')
                ->join('category','subcategory.cat_id','category.id')
                ->select('subcategory.id','subcategory.cat_id','subcategory.sub_cat_name','category.cat_name')
                ->get();

        }

        $data = $this->master();

            $catlist = DB::table('category')
                ->select('id','cat_name')
                ->get();


        return view('subcategory', ['retailer' => $retailer,'data' => $data,'catlist'=>$catlist]);
    }

    public function SubcategorySubmit(Request $request)
    {
        $rules = array(

            'cat_id'=>'required',
            'subcat_name'=> 'required',

        );
 
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/subcat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        
        } else {


            try {

                $retailerdata = DB::table('area')->count();

                if ($retailerdata < 1000) {

                     $subcat_name = $request->input("subcat_name");
                     $cat_id = $request->input("cat_id");

                               
                    $id = DB::table('subcategory')->insertGetId(
                        ['cat_id'=>$cat_id,'sub_cat_name'=>$subcat_name]
                    ); 
                    $data = Array('type' => 'success', 'message' => 'Area Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/subcat_deatils');
        }
    }

    public function subcatdelete($id) 
    {
      
      $select=DB::delete('delete from subcategory where id = ?',[$id]);
     
            return redirect('/subcat_deatils');
    }



    public function getSubcategory($id)
    {

         $pack=DB::table('subcategory')
                ->join('category','subcategory.cat_id','category.id')
                ->select('subcategory.id','subcategory.cat_id','subcategory.sub_cat_name','category.cat_name')
                ->where('subcategory.id', '=', $id)->get();

         $data = $this->master();


        return view('updatesubcategory', ['pack' => $pack,'data' => $data]);
    }

    public function SubcategoryUpdateSubmit(Request $request)
    {
        $rules = array(
            
            'sub_cat_name' => 'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/subcat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        
        } else {

            try {
                
                $retailerdata = DB::table('subcategory')->count();
                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $sub_cat_name = $request->input("sub_cat_name");
                    
       
                
                    $id = DB::table('subcategory')
                            ->where('id','=',$id)
                            ->update(['sub_cat_name' => $sub_cat_name]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Subcategory Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/subcat_deatils');
        }
    }

    public function PackageDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('package')
                ->select('package_id','package_name','package_km','package_rate')
                ->orWhere('package_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('package')
                ->select('package_id','package_name','package_km','package_rate')
                ->get();

        }

        $data = $this->master();
        return view('package', ['retailer' => $retailer,'data' => $data]);
    }

    public function PackageSubmit(Request $request)
    {
        $rules = array(

            'package_name'=>'required',
            'package_km'=> 'required',
            'package_rate'=> 'required',

        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/package_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('package')->count();

                if ($retailerdata < 1000) {

                     $package_name = $request->input("package_name");
                     $package_km = $request->input("package_km");
                     $package_rate = $request->input("package_rate");

                               
                    $id = DB::table('package')->insertGetId(
                        ['package_name'=>$package_name,'package_km'=>$package_km,'package_rate'=>$package_rate]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Package Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/package_deatils');
        }
    }

    public function packegedelete($id) 
    {
      
      $select=DB::delete('delete from package where package_id = ?',[$id]);
     
            return redirect('/package_deatils');
    }


    public function deliveryboyDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('deliveryboy')
                ->select('id','name','city','area','address','mobile','gmail')
                ->orWhere('name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('deliveryboy')
                ->select('id','name','city','area','address','mobile','gmail')
                ->get();

        }

        $data = $this->master();


        return view('deliveryboy', ['retailer' => $retailer,'data' => $data]);
    }

    public function deliveryBoySubmit(Request $request)
    {
        $rules = array(

            'name'=> 'required',
            'city'=>'required',
            'area'=>'required',
            'address'=> 'required',
            'mobile'=> 'required',
            'gmail'=> 'required',
            'password'=>'required',
            'confirm_pass'=>'required|same:password',
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/deliveryboy_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
       
        } else {

            try {

                $retailerdata = DB::table('deliveryboy')->count();

                if ($retailerdata < 1000) {

                     $name = $request->input("name");
                     $city = $request->input("city");
                     $area = $request->input("area");
                     $address = $request->input("address");
                     $mobile = $request->input("mobile");
                     $gmail = $request->input("gmail");
                     $password = $request->input("password");
                     $confirm_pass = $request->input("confirm_pass");
                               
                    $id = DB::table('deliveryboy')->insertGetId(
                        ['name'=>$name,'city'=>$city,'area'=>$area,'address'=>$address,'mobile'=>$mobile,'gmail'=>$gmail,'password'=>$password,'confirm_pass'=>$confirm_pass]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Delivery Boy Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/deliveryboy_deatils');
        }
    }

    public function deliveryBoydelete($id) 
    {
      
      $select=DB::delete('delete from deliveryboy where id = ?',[$id]);
     
            return redirect('/deliveryboy_deatils');
    }


    public function getDeliveryBoy($id)
    {

         $pack=DB::table('deliveryboy')->where('id', '=', $id)->get();

         $data = $this->master();


        return view('updatedeliveryboy', ['pack' => $pack,'data' => $data]);
    }

    public function deliveryBoyUpdateSubmit(Request $request)
    {
        $rules = array(
            
                   'name' => 'required',
                   'city'=>'required',
                   'area'=>'required',
                   'address' => 'required',
                   'mobile' => 'required',
                   'gmail' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/deliveryboy_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
       
        } else {

            try {
                
                $retailerdata = DB::table('deliveryboy')->count();

                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $name = $request->input("name");
                    $city = $request->input("city");
                    $area= $request->input("area");
                    $address = $request->input("address");
                    $mobile = $request->input("mobile");
                    $gmail = $request->input("gmail");
                    
                    $id = DB::table('deliveryboy')
                            ->where('id','=',$id)
                            ->update(['name' => $name,'city'=>$city,'area'=>$area,'address'=>$address,'mobile'=>$mobile,'gmail'=>$gmail]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Delivery Boy Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/deliveryboy_deatils');
        }
    }

    public function getRate($id)
    {

         $pack=DB::table('package')->where('package_id', '=', $id)->get();

         $data = $this->master();


        return view('updateRate', ['pack' => $pack,'data' => $data]);
    }

    public function RateUpdateSubmit(Request $request)
    {
        $rules = array(
            
            'package_name' => 'required', 
            'package_km' =>'required',
            'package_rate'=>'required',     
        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/package_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {
                
                $retailerdata = DB::table('package')->count();
                
                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $package_name = $request->input("package_name");
                    $package_km = $request->input("package_km");
                    $package_rate = $request->input("package_rate");

       
                
                    $id = DB::table('package')
                            ->where('package_id','=',$id)
                            ->update(['package_name' => $package_name,'package_km'=>$package_km,'package_rate'=>$package_rate]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Package Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/package_deatils');
        }
    }

    public function hoteldeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('hotel')
                ->select('id','hotel_name','hotel_address','hotel_contact','hotel_type','city','area','start_time','end_time','image')
                ->orWhere('hotel_name', 'like', '%' . $text . '%')
                ->where('vendor_id','=',Auth::user()->id)
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {
            $retailer = DB::table('hotel')
                ->select('id','hotel_name','hotel_address','hotel_contact','hotel_type','city','start_time','end_time','area','image')
                ->where('vendor_id','=',Auth::user()->id)
                ->get();

        }

         $data = $this->master();

         $rescatlist = DB::table('restaurant_cat')
                ->select('id','res_cat_name')
                ->get();

        $admin_id = DB::table('users')
                    ->where('id','=',Auth::user()->id)
                    ->value('admin_id');

        $room = DB::table('hotel')
        ->where('vendor_id','=',Auth::user()->id)
        ->count();

        return view('hotel', ['retailer' => $retailer,'data' => $data,'rescatlist'=>$rescatlist,'room'=>$room,'admin_id'=>$admin_id]);
    }
    
    public function hoteldataSubmit(Request $request)
    {
        $rules = array(

            'admin_id'=>'required',
            'hotel_name'=> 'required',
            'hotel_address'=> 'required',
            'hotel_contact'=> 'required',
            'bank_deatils'=>'required',
            'adhar_no'=>'required',
            'hotel_license_no'=>'required',
            'hotel_type'=> 'required',
            'city'=>'required',
            'area'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'image'=>'required',
        
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/hotel_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('hotel')->count();

                if ($retailerdata < 1000) {
                    
                     $admin_id = $request->input("admin_id");
                     $hotel_name = $request->input("hotel_name");
                     $hotel_address = $request->input("hotel_address");
                     $hotel_contact = $request->input("hotel_contact");
                     $hotel_type = $request->input("hotel_type");
                    
                     $bank_deatils = $request->input("bank_deatils");
                     $adhar_no = $request->input("adhar_no");
                     $hotel_license_no = $request->input("hotel_license_no");

                     $start_time = $request->input("start_time");
                     $end_time = $request->input("end_time");

                     // $type =  json_encode($hotel_type);
                     
                     $type = implode(',',$hotel_type);

                     $city = $request->input("city");
                     $area = $request->input("area");

                    if(Input::file('image'))
                    {
                        $file=Input::file('image');
                        $file->move('images/hotel/',$file->getClientOriginalName());
                        $filename = $file->getClientOriginalName();
                    }

                    $id = DB::table('hotel')->insertGetId(
                        ['admin_id'=>$admin_id,'vendor_id'=>Auth::user()->id,'hotel_name'=>$hotel_name,'hotel_address'=>$hotel_address,'hotel_contact'=>$hotel_contact,'hotel_type'=>$type,'city'=>$city,'area'=>$area,'bank_deatils'=>$bank_deatils,'adhar_no'=>$adhar_no,'hotel_license_no'=>$hotel_license_no,'start_time'=>$start_time,'end_time'=>$end_time,'image'=>$filename]
                        );

                    $data = Array('type' => 'success', 'message' => 'Hotel Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/hotel_deatils');
        }
    }

    public function hoteldatadelete($id) 
    {
      
      $select=DB::delete('delete from hotel where id = ?',[$id]);
     
            return redirect('/hotel_deatils');
    }

    public function Retailerhoteldeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('hotel')
                ->select('id','hotel_name','hotel_address','hotel_contact','hotel_type','city','area','image')
                ->orWhere('hotel_name', 'like', '%' . $text . '%')
                ->where('admin_id','=',Auth::user()->id)
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('hotel')
                ->select('id','hotel_name','hotel_address','hotel_contact','hotel_type','city','area','image')
                ->where('admin_id','=',Auth::user()->id)
                ->get();

        }

         $data = $this->master();
         $rescatlist = DB::table('restaurant_cat')
                ->select('id','res_cat_name')
                ->get();

        return view('retailerhotel', ['retailer' => $retailer,'data' => $data,'rescatlist'=>$rescatlist]);
    }

    public function Computerdeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('computer')
                ->select('id','name','shop_address','shop_contact','computer_type')
                ->orWhere('name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('computer')
                ->select('id','name','shop_address','shop_contact','computer_type')
                ->get();

        }

        $data = $this->master();


        return view('computer', ['retailer' => $retailer,'data' => $data]);
    }

    public function compdataSubmit(Request $request)
    {
        $rules = array(

            'name'=> 'required',
            'shop_address'=> 'required',
            'shop_contact'=> 'required',
            'computer_type'=> 'required',

        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/comp_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('computer')->count();

                if ($retailerdata < 1000) {

                     $name = $request->input("name");
                     $shop_address = $request->input("shop_address");
                     $shop_contact = $request->input("shop_contact");
                     $computer_type = $request->input("computer_type");
                               
                    $id = DB::table('computer')->insertGetId(
                        ['name'=>$name,'shop_address'=>$shop_address,'shop_contact'=>$shop_contact,'computer_type'=>$computer_type]
                    );

                    $data = Array('type' => 'success', 'message' => 'comuter Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/comp_deatils');
        }
    }

    public function Compdatadelete($id) 
    {
      
      $select=DB::delete('delete from computer where id = ?',[$id]);
     
            return redirect('/comp_deatils');
    }


    public function itemdeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('item')
                ->select('item_id','item_name','item_price','item_discount_amt','item_half_price','item_half_discount_amt','item_image','item_description','vid','category_id','business_type_id','created_at','updated_at','status','qty_type')
                ->orWhere('item_name', 'like', '%' . $text . '%')
                ->where('vendor_id','=',Auth::user()->id)
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('item')
                ->select('item_id','item_name','item_price','item_discount_amt','item_half_price','item_half_discount_amt','item_image','item_description','vid','category_id','business_type_id','created_at','updated_at','status','qty_type')
                ->where('vendor_id','=',Auth::user()->id)
                ->get();

        }

        $data = $this->master();

       

         $rescatlist = DB::table('restaurant_cat')
                ->select('id','res_cat_name')
                ->get();
    



        return view('item', ['retailer' => $retailer,'data' => $data,'rescatlist'=>$rescatlist]);
    }

    public function itemdataSubmit(Request $request)
    {
        $rules = array(

            'category_id'=> 'required',
            'item_name'=> 'required',
            'item_description'=>'required',
            'item_price'=>'required',
            'item_discount_amt'=>'required',
            'item_half_price'=>'required',
            'item_half_discount_amt'=>'required',
            'qty_type'=>'required',
            'image'=>'required|dimensions:min_width=500,min_height=500',

        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/item_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('item')->count();

                if ($retailerdata < 1000) {

                     $category_id = $request->input("category_id");
                     $item_name = $request->input("item_name");
                     $item_price = $request->input("item_price");
                     $item_discount_amt = $request->input("item_discount_amt");

                     $item_half_price = $request->input("item_half_price");
                     $item_half_discount_amt = $request->input("item_half_discount_amt");
                     
                    $qty_type = $request->input("qty_type");
                    $item_description = $request->input("item_description");

                    $type = implode(',', $qty_type);

                     $date=Carbon::now();
                     $dd = $date->format('y-m-d H:i:s');

                     $status="available";


                 if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/item/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }

                    $id = DB::table('item')->insertGetId(
                        ['vendor_id'=>Auth::user()->id,'item_name'=>$item_name,'item_description'=>$item_description,'item_price'=>$item_price,'item_discount_amt'=>$item_discount_amt,'item_half_price'=>$item_half_price,'item_half_discount_amt'=>$item_half_discount_amt,'category_id'=>$category_id,'item_image'=>$filename,'created_at'=>$dd,'updated_at'=>$dd,'qty_type'=>$type,'status'=>$status]
                    );

                    $data = Array('type' => 'success', 'message' => 'item Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/item_deatils');
        }
    }

    public function itemdelete($id) 
    {
      
      $select=DB::delete('delete from item where item_id = ?',[$id]);
     
            return redirect('/item_deatils');
    }
    public function itemdeleteadmin($id) 
    {
      
      $select=DB::delete('delete from item where item_id = ?',[$id]);
     
            return redirect('/admin_itemdeatils');
    }


    public function Appareldeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('apparelstore')
                ->select('id','shop_name','shop_address','shop_contact','apparel_type')
                ->orWhere('shop_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('apparelstore')
                ->select('id','shop_name','shop_address','shop_contact','apparel_type')
                ->get();

        }

        $data = $this->master();


        return view('apperal', ['retailer' => $retailer,'data' => $data]);
    }

    public function ApparelSubmit(Request $request)
    {
        $rules = array(

            'shop_name'=> 'required',
            'shop_address'=> 'required',
            'shop_contact'=> 'required',
            'apparel_type'=> 'required',

        );
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/comp_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('computer')->count();

                if ($retailerdata < 1000) {

                     $shop_name = $request->input("shop_name");
                     $shop_address = $request->input("shop_address");
                     $shop_contact = $request->input("shop_contact");
                     $apparel_type = $request->input("apparel_type");
                               
                    $id = DB::table('apparelstore')->insertGetId(
                        ['shop_name'=>$shop_name,'shop_address'=>$shop_address,'shop_contact'=>$shop_contact,'apparel_type'=>$apparel_type]
                    );

                    $data = Array('type' => 'success', 'message' => 'Apparel Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/apparel_deatils');
        }
    }

    public function Appareldelete($id) 
    {
      
      $select=DB::delete('delete from apparelstore where id = ?',[$id]);
     
            return redirect('/apparel_deatils');
    }

    public function EcommcategoryDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('ecomm_category')
                ->select('id','cat_name','image')
                ->orWhere('cat_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('ecomm_category')
                ->select('id','cat_name','image')
                ->get();

        }

        $data = $this->master();


        return view('ecommcategory', ['retailer' => $retailer,'data' => $data]);
    }

    public function EcommcategorySubmit(Request $request)
    {
        $rules = array(

            'cat_name'=> 'required',
            'image' =>'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/ecommcat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('ecomm_category')->count();

                if ($retailerdata < 1000) {

                     $cat_name = $request->input("cat_name");
         
                 if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/bussiness/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }
                               
                    $id = DB::table('ecomm_category')->insertGetId(
                        ['cat_name'=>$cat_name,'image'=>$filename]
                    ); 
                    $data = Array('type' => 'success', 'message' => 'Category Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/ecommcat_deatils');
        }
    }

    public function Ecommcatdelete($id) 
    {
      
      $select=DB::delete('delete from ecomm_category where id = ?',[$id]);
     
            return redirect('/ecommcat_deatils');
    }

    public function getEcommCategory($id)
    {

         $pack=DB::table('ecomm_category')->where('id', '=', $id)->get();

         $data = $this->master();


        return view('updateecommcat', ['pack' => $pack,'data' => $data]);
    }

    public function EcommCategoryUpdateSubmit(Request $request)
    {
        $rules = array(
            
            'cat_name' => 'required',       
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/ecommcat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        
        } else {

            try {
                
                $retailerdata = DB::table('ecomm_category')->count();
                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $cat_name = $request->input("cat_name");
       
                
                    $id = DB::table('ecomm_category')
                            ->where('id','=',$id)
                            ->update(['cat_name' => $cat_name]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Category Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/ecommcat_deatils');
        }
    }
    
    public function getHotel($id)
    {

         $pack=DB::table('hotel')->where('id', '=', $id)->get();

         $data = $this->master();


        return view('updatehotel', ['pack' => $pack,'data' => $data]);
    }

    public function HotelUpdateSubmit(Request $request)
    {
        $rules = array(
            
            'hotel_name' => 'required',
            'hotel_address' => 'required',
            'hotel_contact'=>'required',
            'start_time' =>'required',
            'end_time'=>'required',
            'city'=>'required',
            'area'=>'required',
            'image'=>'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/ecommcat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        
        } else {

            try {
                
                $retailerdata = DB::table('hotel')->count();
                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $hotel_name = $request->input("hotel_name");
                    $hotel_address = $request->input("hotel_address");
                    $hotel_contact = $request->input("hotel_contact");

                    $start_time = $request->input("start_time");
                    $end_time = $request->input("end_time");
                    $city = $request->input("city");
                    $area = $request->input("area");
                                        
                  if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/hotel/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }
                
                    $id = DB::table('hotel')
                            ->where('id','=',$id)
                            ->update(['hotel_name' => $hotel_name,'hotel_address'=>$hotel_address,'hotel_contact'=>$hotel_contact,'start_time'=>$start_time,'end_time'=>$end_time,'city'=>$city,'area'=>$area,'image'=>$filename]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Hotel Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/hotel_deatils');
        }
    }

    public function RestocategoryDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('restaurant_cat')
                ->select('id','res_cat_name','image')
                ->orWhere('res_cat_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('restaurant_cat')
                ->select('id','res_cat_name','image')
                ->get();

        }

        $data = $this->master();

               $bussinesslist = DB::table('ecomm_category')
                ->select('id','cat_name')
                ->get();

        return view('addrestocategory', ['retailer' => $retailer,'data' => $data,'bussinesslist'=>$bussinesslist]);
    }

    public function RestocategorySubmit(Request $request)
    {
        $rules = array(

            'res_cat_name'=> 'required',
            'bussiness_id'=>'required',
            'image' =>'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/restocat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('restaurant_cat')->count();

                if ($retailerdata < 1000) {

                     $res_cat_name = $request->input("res_cat_name");
                     $bussiness_id = $request->input("bussiness_id");

  
                 if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/category/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }
                               
                    $id = DB::table('restaurant_cat')->insertGetId(
                        ['res_cat_name'=>$res_cat_name,'image'=>$filename,'business_type_id'=>$bussiness_id]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Restaurant Category Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/restocat_deatils');
        }
    }

    public function Restocatdelete($id) 
    {
      
      $select=DB::delete('delete from restaurant_cat where id = ?',[$id]);
     
            return redirect('/restocat_deatils');
    }



    public function getRestocat($id)
    {

         $pack=DB::table('restaurant_cat')->where('id', '=', $id)->get();

         $data = $this->master();


        return view('updaterestocat', ['pack' => $pack,'data' => $data]);
    }

    public function RestocatUpdateSubmit(Request $request)
    {
        $rules = array(
            
            'res_cat_name' => 'required',
            'image'=>'required',
            
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/restocat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        
        } else {

            try {
                
                $retailerdata = DB::table('restaurant_cat')->count();
                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $res_cat_name = $request->input("res_cat_name");
                   
                    if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/category/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }
                
                    $id = DB::table('restaurant_cat')
                            ->where('id','=',$id)
                            ->update(['res_cat_name' => $res_cat_name,'image'=>$filename]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Resto cat Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/restocat_deatils');
        }
    }

    public function bannerDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('banner')
                ->select('id','image')
                ->orWhere('image', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('banner')
                ->select('id','image')
                ->get();

        }

        $data = $this->master();


        return view('resbanner', ['retailer' => $retailer,'data' => $data]);
    }

    public function bannerSubmit(Request $request)
    {
        $rules = array(

            'image'=> 'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/banner_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('banner')->count();

                if ($retailerdata < 1000) {

                    if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/banner/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }
                               
                    $id = DB::table('banner')->insertGetId(
                        ['image'=>$filename]
                    ); 
                    $data = Array('type' => 'success', 'message' => 'Banner Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/banner_deatils');
        }
    }

    public function bannerdelete($id) 
    {
      
      $select=DB::delete('delete from banner where id = ?',[$id]);
     
            return redirect('/banner_deatils');
    }

    public function getitem($id)
    {

         $pack=DB::table('item')->where('item_id', '=', $id)->get();

         $data = $this->master();

        return view('updateitem', ['pack' => $pack,'data' => $data]);
    }

    public function itemupdateSubmit(Request $request)
    {
        $rules = array(
            'item_name' => 'required',
            'item_description'=> 'required',
            'item_price'=>'required',
            'item_discount_amt'=>'required',
            'item_half_price'=>'required',
            'item_half_discount_amt'=>'required',
            'qty_type'=>'required',
            'image'=>'required',

        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/item_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('item')->count();

                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $item_name = $request->input("item_name");
                    $item_description = $request->input("item_description");
                    $item_price = $request->input("item_price");
                    $item_discount_amt = $request->input("item_discount_amt");
                    $item_half_price = $request->input("item_half_price");
                    $item_half_discount_amt = $request->input("item_half_discount_amt");
                    $qty_type = $request->input("qty_type");
                    $type = implode(',',$qty_type);



                    if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/item/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }

                    $id = DB::table('item')
                            ->where('item_id','=',$id)
                            ->update(['item_name'=>$item_name,'item_description'=>$item_description,'item_price'=>$item_price,'item_discount_amt'=>$item_discount_amt,'item_half_price'=>$item_half_price,'item_half_discount_amt'=>$item_half_discount_amt,'qty_type'=>$type,'item_image'=>$filename]
                    );

                    $data = Array('type' => 'success', 'message' => 'Item Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/item_deatils');
        }
    }
    
    public function Adminitemdeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('item')
                ->select('item_id','item_name','admin_item_price','admin_discount_price','admin_half_item_price','admin_half_item_discount_price','item_image','item_description','vid','category_id','business_type_id','qty_type','created_at','updated_at')
                ->orWhere('item_name', 'like', '%' . $text . '%')
                ->where('status','=','available')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('item')
                ->select('item_id','item_name','admin_item_price','admin_discount_price','admin_half_item_price','admin_half_item_discount_price','item_image','item_description','vid','category_id','business_type_id','qty_type','created_at','updated_at')
                ->where('status','=','available')
                ->get();

        }

        $data = $this->master();

         $rescatlist = DB::table('restaurant_cat')
                ->select('id','res_cat_name')
                ->get();

 
        return view('adminitem', ['retailer' => $retailer,'data' => $data,'rescatlist'=>$rescatlist]);
    }


    public function CompleteAdminitemdeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('item')
                ->select('item_id','item_name','admin_item_price','admin_discount_price','admin_half_item_price','admin_half_item_discount_price','item_image','item_description','vid','category_id','business_type_id','created_at','qty_type','updated_at')
                ->orWhere('item_name', 'like', '%' . $text . '%')
                ->where('status','=','available')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('item')
                ->select('item_id','item_name','admin_item_price','admin_discount_price','admin_half_item_price','admin_half_item_discount_price','item_image','item_description','vid','category_id','qty_type','business_type_id','created_at','updated_at')
                ->where('status','=','available')
                ->get();

        }

        $data = $this->master();

         $rescatlist = DB::table('restaurant_cat')
                ->select('id','res_cat_name')
                ->get();

 
        return view('adminitemcomplete', ['retailer' => $retailer,'data' => $data,'rescatlist'=>$rescatlist]);
    }

    public function getAdminitem($id)
    {

         $pack=DB::table('item')->where('item_id', '=', $id)->get();

         $data = $this->master();

        return view('updateadminitem', ['pack' => $pack,'data' => $data]);
    }

    public function adminitemupdateSubmit(Request $request)
    {
        $rules = array(
            'item_name' => 'required',
            'item_description'=> 'required',
            'admin_item_price'=>'required',
            'admin_discount_price'=>'required',
            'admin_half_item_price'=>'required',
            'admin_half_item_discount_price'=>'required',
            'qty_type'=>'required',
           
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/admin_itemdeatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('item')->count();

                if ($retailerdata < 1000) {

    $id = $request->input("id");
    $item_name = $request->input("item_name");
    $admin_item_price = $request->input("admin_item_price");
    $admin_discount_price = $request->input("admin_discount_price");
    $admin_half_item_price = $request->input("admin_half_item_price");
    $admin_half_item_discount_price = $request->input("admin_half_item_discount_price");

    $item_description = $request->input("item_description");
    $qty_type = $request->input("qty_type");
    $status="Completed";


                
                    $id = DB::table('item')
                            ->where('item_id','=',$id)
                            ->update(['item_name'=>$item_name,'admin_item_price' => $admin_item_price,'admin_discount_price'=>$admin_discount_price,'item_description'=>$item_description,'admin_half_item_price'=>$admin_half_item_price,'admin_half_item_discount_price'=>$admin_half_item_discount_price,'qty_type'=>$qty_type]
                    );

                    $data = Array('type' => 'success', 'message' => 'Item Updated Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/admin_itemdeatils');
        }
    }

    public function DeliveryChargesDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('delivery_charges')
                ->select('id','charges_range','charges','created_at')
                ->orWhere('charges_name', 'like', '%' . $text . '%')
                ->where('vendor_id','=',Auth::user()->id)
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('delivery_charges')
                ->select('id','charges_range','charges','created_at')
                ->where('vendor_id','=',Auth::user()->id)
                ->get();

        }

        $data = $this->master();


        return view('deliverycharges', ['retailer' => $retailer,'data' => $data]);
    }

    public function DeliveryChargesSubmit(Request $request)
    {
        $rules = array(

            'charges_range'=> 'required',
            'charges' =>'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/charges_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('delivery_charges')->count();

                if ($retailerdata < 1000) {

                     $charges_range = $request->input("charges_range");
                     $charges = $request->input("charges");

                     $dd = Carbon::now();

                    $date = $dd->format('y-m-d H:i:s');

                    $id = DB::table('delivery_charges')->insertGetId(
                        ['vendor_id'=>Auth::user()->id,'charges_range'=>$charges_range,'charges'=>$charges,'created_at'=>$date]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Delivery Charges Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }   
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/charges_deatils');
        }
    }

    public function deliveryChargesdelete($id) 
    {
      
      $select=DB::delete('delete from delivery_charges where id = ?',[$id]);
     
            return redirect('/charges_deatils');
    }

    public function adminDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('users')
                ->select('id','name','mob','gmail','address','user_type')
                ->orWhere('name', 'like', '%' . $text . '%')
                ->where('user_type','=','ADMIN')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('users')
                ->select('id','name','mob','gmail','address','user_type')
                ->where('user_type','=','ADMIN')
                ->get();

        }

         $city = DB::table('city')
                ->select('city_id','city_name')
                ->get();

        $data = $this->master();


        return view('admin', ['retailer' => $retailer,'data' => $data,'city'=>$city]);
    }

    public function adminSubmit(Request $request)
    {
        $rules = array(

            'name' => 'required',
            'mobile'=>'required',
            'gmail'=> 'required',
            'address' =>'required',
            'password'=>'required',
            'city'=>'required',

        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/admin_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        
        } else {

            try {

                $retailerdata = DB::table('users')->count();
                
                if ($retailerdata < 1000) {

                    $name = $request->input("name");
                    $mob = $request->input("mobile");
                    $gmail = $request->input("gmail");
                    $address = $request->input("address");
                    $user_type='ADMIN';
                    $password = $request->input("password");
                    $status = 'Approved';
                    $city = $request->input("city");
  
                    $PWD = bcrypt($password);
                    $date = Carbon::now();

                    $id = DB::table('users')->insertGetId(
                        ['name'=>$name,'mob'=>$mob,'gmail'=>$gmail,'city'=>$city,'address'=>$address,'user_type'=>$user_type,'updated_at'=>$date,'password_plain'=>$password,'password'=>$PWD,'status'=>$status]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Admin Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/admin_deatils');
        }
    }

    public function admindelete($id) 
    {
      
      $select=DB::delete('delete from users where id = ?',[$id]);
     
            return redirect('/admin_deatils');
    }

    public function vendororderdeatils()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('order_details')
                ->join('orders','order_details.order_no','orders.order_no')
                ->select('orders.order_id','orders.order_no','orders.order_uid','orders.vendor_id','orders.order_actual_amt','orders.order_discount_amt','orders.order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.status','order_details.item_name','order_details.item_qty_type','orders.created_at')
                ->where('orders.vendor_id','=',Auth::user()->id)
                ->where('orders.status','=','Pending')
                ->groupBy('order_details.order_no')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {
            $retailer = DB::table('order_details')
                ->join('orders','order_details.order_no','orders.order_no')
                ->select('orders.order_id','orders.order_no','orders.order_uid','orders.vendor_id','orders.order_actual_amt','orders.order_discount_amt','orders.order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.status','order_details.item_name','order_details.item_qty_type','orders.created_at')
                ->where('orders.vendor_id','=',Auth::user()->id)
                ->where('orders.status','=','Pending')
                ->groupBy('order_details.order_no')
                ->get();
        }

        $data = $this->master();
        return view('vendororder', ['retailer' => $retailer,'data' => $data]);
    }

    public function vendorOrderdelete($id) 
    {
      
            $select=DB::delete('delete from orders where order_no = ?',[$id]);

            $select=DB::delete('delete from order_details where order_no = ?',[$id]);
            return redirect('/vendororder_deatils');
    }


    public function getVendorOrder($id)
    {

         $package_deatils=DB::table('orders')
         ->join('order_details','orders.order_no','order_details.order_no')
         ->where('order_id', '=', $id)
         ->get();


         $pack=DB::table('orders')->where('order_id', '=', $id)->get();

        $deliveryboylist = DB::table('deliveryboy')
            ->get();


         $data = $this->master();


        return view('assignorder', ['pack' => $pack,'package_deatils'=>$package_deatils,'data' => $data,'deliveryboy'=>$deliveryboylist]);
    }

    public function vendororderupdateSubmit(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'order_no' => 'required', 
        
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/vendororder_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('orders')->count();

                if ($retailerdata < 1000) {

                    $vendor_id = $request->input("vendor_id");
                    $name = $request->input("name");
                    $order_no = $request->input("order_no");
                    $order_id = $request->input("order_id");
                 

                    $area = DB::table('hotel')->where('vendor_id','=', $vendor_id)->value('area');

                    $deliveryboyMob = DB::table('deliveryboy')
                   ->select('id','mobile')
                   ->where('area','=',$area)
                   ->get();

        $message = "One near by restaurant order is comming plz check and confirm it on arjunt basis";

foreach ($deliveryboyMob as $key => $value)
{
    $id=$value->id;
    $mob=$value->mobile;
    $response = $this->sendSms($mob, $message);
    $id = DB::table('assign_orders')
    ->insertGetId(['delivery_boy_id'=>$id,'order_id'=>$order_no]); 

}


                $id = DB::table('orders')
                            ->where('order_no','=',$order_no)
                            ->update(['status'=>'Assigned']
                    );

                $id = DB::table('order_details')
                            ->where('order_no','=',$order_no)
                            ->update(['status'=>'Assigned']
                    );
                            
                    $data = Array('type' => 'success', 'message' => 'Assign Orders Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/vendororder_deatils');
        }
    }

    public function assignorderdeatils()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('orders')
                ->select('orders.order_id','orders.order_no','orders.order_uid','orders.order_actual_amt','orders.order_discount_amt','orders.order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.status')
                ->where('orders.vendor_id','=',Auth::user()->id)
                ->where('orders.status','=','Assigned')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {
            $retailer = DB::table('orders')
                ->select('orders.order_id','orders.order_no','orders.order_uid','orders.order_actual_amt','orders.order_discount_amt','orders.order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.status')
                ->where('orders.vendor_id','=',Auth::user()->id)
                ->where('orders.status','=','Assigned')
                ->get();
        }

        $data = $this->master();
        return view('assignvendororder', ['retailer' => $retailer,'data' => $data]);
    }

    public function sendSms($mob, $message)
    {
        $response = $this->PostRequest("http://www.logonutility.in/app/smsapi/index.php",
            
            [
                'key' => '45D75F53E0747A',
                'campaign' => '15275',
                'routeid' => '20',
                'type'=> 'text',
                'contacts' => $mob,
                'senderid' => 'FLASHE',
                'msg' => $message
            ]


        );
        return $response;
    }

    function PostRequest($url, $_data)
    {
        $data = array();
        foreach ($_data as $n => $v) {
            $data[] = "$n=$v";
        }
        $data = implode('&', $data);
        $url = parse_url($url);
        if ($url['scheme'] != 'http') {
            die('Only HTTP request are supported !');
        }
        $host = $url['host'];
        $path = $url['path'];
        $fp = fsockopen($host, 80);
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: " . strlen($data) . "\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        $s = fputs($fp, $data);
        $result = '';
        while (!feof($fp)) {
            $result .= fgets($fp, 128);
        }
        fclose($fp);
        $result = explode("\r\n\r\n", $result, 2);
        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';
        return $content;
    }


    public function PaymentModeDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('payment_mode')
                ->select('id','payment_city','payment_mode')
                ->orWhere('payment_city', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('payment_mode')
                ->select('id','payment_city','payment_mode')
                ->get();

        }

        $data = $this->master();
        return view('paymentmode', ['retailer' => $retailer,'data' => $data]);
    }

    public function PaymentModeSubmit(Request $request)
    {
        $rules = array(

            'payment_city'=> 'required',
            'payment_mode' =>'required',

        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/paymentmode_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('payment_mode')->count();

                if ($retailerdata < 1000) {

                     $payment_city = $request->input("payment_city");
                     $payment_mode = $request->input("payment_mode");
                     
                     $mode = implode(',', $payment_mode);

                    $id = DB::table('payment_mode')->insertGetId(
                        ['payment_city'=>$payment_city,'payment_mode'=>$mode]
                    ); 

                    $data = Array('type' => 'success', 'message' => 'Payment Mode Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }   
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/paymentmode_deatils');
        }
    }

    public function PaymentModedelete($id) 
    {
      
      $select=DB::delete('delete from payment_mode where id = ?',[$id]);
     
            return redirect('/paymentmode_deatils');
    }


    public function deliveryBoyPayment()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('bill_deatils')
                ->join('deliveryboy', 'bill_deatils.user_id', '=', 'deliveryboy.id')
                ->select('bill_deatils.id','bill_deatils.user_id','bill_deatils.order_id','bill_deatils.total_amount','bill_deatils.payable_amount','bill_deatils.paid_amount','bill_deatils.user_name','bill_deatils.user_mob','bill_deatils.sign_img','bill_deatils.payable_status','deliveryboy.name')
                ->orWhere('bill_deatils.id', 'like', '%' . $text . '%')
                ->orWhere('bill_deatils.user_id', 'like', '%' . $text . '%')
                ->orderBy('bill_deatils.id', 'asc')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('bill_deatils')
                ->join('deliveryboy', 'bill_deatils.user_id', '=', 'deliveryboy.id')
                ->select('bill_deatils.id','bill_deatils.user_id','bill_deatils.order_id','bill_deatils.total_amount','bill_deatils.payable_amount','bill_deatils.paid_amount','bill_deatils.user_name','bill_deatils.user_mob','bill_deatils.sign_img','bill_deatils.payable_status','deliveryboy.name')
                ->orderBy('bill_deatils.id', 'asc')
                ->paginate(10);
        }

        $data = $this->master();
        
        return view('deliveryboypayment', ['retailer' => $retailer, 'data' => $data]);
    }

    public function getdeliverBoyPayment($id)
    {

         $pack=DB::table('bill_deatils')->where('id', '=', $id)->get();

         $data = $this->master();

        return view('updatedeliveryboypayment', ['pack' => $pack,'data' => $data]);
    }

    public function deliveryBoyPaymentupdateSubmit(Request $request)
    {
        $rules = array(
            'order_id' => 'required', 
            'payable_amount' => 'required',
            'paid_amount'=> 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/delivery_boy_payment')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('bill_deatils')->count();

                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $order_id = $request->input("order_id");
                    $payable_amount = $request->input("payable_amount");
                    $paid_amount = $request->input("paid_amount");
                
                    if($payable_amount >= $paid_amount)
                    {
                    
                    $ramount = $payable_amount - $paid_amount;

                    

                    $id = DB::table('bill_deatils')
                            ->where('id','=',$id)
                            ->update(['order_id'=>$order_id,'payable_amount' => $ramount,'paid_amount'=>$paid_amount]
                    );
                    
                    $data = Array('type' => 'success', 'message' => 'Payment Updated Successfully');

                }
                else
                {
                   $data = Array('type' => 'error', 'message' => 'Paid Amount is Not Valid');
                }

                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/delivery_boy_payment');
        }
    }
    
    public function vendorPayment()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('bill_deatils')
                ->join('users', 'bill_deatils.vendor_id', '=', 'users.id')
                ->join('hotel','bill_deatils.vendor_id','=','hotel.vendor_id')
                ->select('bill_deatils.id','bill_deatils.user_id','bill_deatils.order_id','bill_deatils.total_amount','bill_deatils.payable_amount','bill_deatils.paid_amount','bill_deatils.user_name','bill_deatils.user_mob','bill_deatils.sign_img','bill_deatils.payable_status','users.name','hotel.hotel_name','bill_deatils.payable_amount_vendor','paid_amount_vendor')
                ->orWhere('bill_deatils.id', 'like', '%' . $text . '%')
                ->orWhere('bill_deatils.user_id', 'like', '%' . $text . '%')
                ->orderBy('bill_deatils.id', 'asc')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('bill_deatils')
                ->join('users', 'bill_deatils.vendor_id', '=', 'users.id')
                ->join('hotel','bill_deatils.vendor_id','=','hotel.vendor_id')
                ->select('bill_deatils.id','bill_deatils.user_id','bill_deatils.order_id','bill_deatils.total_amount','bill_deatils.payable_amount','bill_deatils.paid_amount','bill_deatils.user_name','bill_deatils.user_mob','bill_deatils.sign_img','bill_deatils.payable_status','users.name','hotel.hotel_name','bill_deatils.payable_amount_vendor','paid_amount_vendor')
                ->orderBy('bill_deatils.id', 'asc')
                ->paginate(10);
        }

        $data = $this->master();
        
        return view('vendorpayment', ['retailer' => $retailer, 'data' => $data]);
    }


    public function getvendorPayment($id)
    {

         $pack=DB::table('bill_deatils')->where('id', '=', $id)->get();

         $data = $this->master();

        return view('updatevendorpayment', ['pack' => $pack,'data' => $data]);
    }

    public function vendorPaymentupdateSubmit(Request $request)
    {
        $rules = array(
            'order_id' => 'required', 
            'payable_amount' => 'required',
            'paid_amount'=> 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/vendor_payment')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('bill_deatils')->count();

                if ($retailerdata < 1000) {

                    $id = $request->input("id");
                    $order_id = $request->input("order_id");
                    $payable_amount = $request->input("payable_amount");
                    $paid_amount = $request->input("paid_amount");
                
                    if($payable_amount >= $paid_amount)
                    {
                    
                    $ramount = $payable_amount - $paid_amount;

                    

                    $id = DB::table('bill_deatils')
                            ->where('id','=',$id)
                            ->update(['order_id'=>$order_id,'payable_amount_vendor' => $ramount,'paid_amount_vendor'=>$paid_amount]
                    );
                    
                    $data = Array('type' => 'success', 'message' => 'Payment Updated Successfully');

                }
                else
                {
                   $data = Array('type' => 'error', 'message' => 'Paid Amount is Not Valid');
                }

                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/vendor_payment');
        }
    }

    public function completedOrder(Request $request)
    {
              $data = $this->master();
            $fddate = $request->input('fdate');
            $fromdates = date("Y-m-d", strtotime($fddate));
            $tddate = $request->input('tdate');
            $todates = date("Y-m-d", strtotime($tddate));
        

            $text = $request->input('text');
           
           if(empty($text)){
               $text='';
           }

            if (empty($fddate) && empty($fddate)) {
            $retailer = DB::table('orders')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->join('hotel','orders.vendor_id','hotel.vendor_id')
                ->select('orders.order_id','orders.order_no','orders.order_discount_amt','order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.created_at','orders.status','user_regi.full_name','user_regi.mob','user_regi.address','hotel.hotel_name','hotel.hotel_address','hotel.hotel_contact')
                ->paginate(10);


                    $total = DB::table('orders')
                        ->where('status','=','completed')
                        ->sum('order_total_amt');

                      $reqtotal = DB::table('orders')
                        ->select('order_total_amt')
                        ->where('status','=','completed')
                        ->whereDate('created_at','>=', $fromdates)
                        ->whereDate('created_at','<=', $todates)
                        ->sum('order_total_amt');

             } 
             else if (empty($tddate)) {

                   $retailer = DB::table('orders')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->join('hotel','orders.vendor_id','hotel.vendor_id')
                ->select('orders.order_id','orders.order_no','orders.order_discount_amt','order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.created_at','orders.status','user_regi.full_name','user_regi.mob','user_regi.address','hotel.hotel_name','hotel.hotel_address','hotel.hotel_contact')
                ->whereDate('orders.created_at', '=', $fromdates)
                ->paginate(10);



                   $total = DB::table('orders')
                        ->where('status','=','completed')
                        ->sum('order_total_amt');

                      $reqtotal = DB::table('orders')
                        ->select('order_total_amt')
                        ->where('status','=','completed')
                        ->whereDate('created_at','>=', $fromdates)
                        ->whereDate('created_at','<=', $todates)
                        ->sum('order_total_amt');
       
             } 
             else 
             {

                $retailer = DB::table('orders')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->join('hotel','orders.vendor_id','hotel.vendor_id')
                ->select('orders.order_id','orders.order_no','orders.order_discount_amt','order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.created_at','orders.status','user_regi.full_name','user_regi.mob','user_regi.address','hotel.hotel_name','hotel.hotel_address','hotel.hotel_contact')
                ->whereDate('orders.created_at', '>=', $fromdates)
                ->whereDate('orders.created_at', '<=', $todates)
                ->paginate(10);




                   $total = DB::table('orders')
                        ->where('status','=','completed')
                        ->sum('order_total_amt');

                      $reqtotal = DB::table('orders')
                        ->select('order_total_amt')
                        ->where('status','=','completed')
                        ->whereDate('created_at','>=', $fromdates)
                        ->whereDate('created_at','<=', $todates)
                        ->sum('order_total_amt');
 
 }

        
        return view('completedorder', ['retailer' => $retailer, 'data' => $data,'total'=>$total,'reqtotal'=>$reqtotal]);
    }

    public function completedOrdervendor(Request $request)
    {

              $data = $this->master();
            $fddate = $request->input('fdate');
            $fromdates = date("Y-m-d", strtotime($fddate));
            $tddate = $request->input('tdate');
            $todates = date("Y-m-d", strtotime($tddate));
        

            $text = $request->input('text');
           
           if(empty($text)){
               $text='';
           }

            if (empty($fddate) && empty($fddate)) {
            $retailer = DB::table('orders')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->join('hotel','orders.vendor_id','hotel.vendor_id')
                ->select('orders.order_id','orders.order_no','orders.order_discount_amt','order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.created_at','orders.status','user_regi.full_name','user_regi.mob','user_regi.address','hotel.hotel_name','hotel.hotel_address','hotel.hotel_contact')
                ->where('orders.vendor_id','=',Auth::user()->id)
                ->paginate(10);


                    $total = DB::table('orders')
                        ->where('status','=','completed')
                        ->where('orders.vendor_id','=',Auth::user()->id)
                        ->sum('order_total_amt');

                      $reqtotal = DB::table('orders')
                        ->select('order_total_amt')
                        ->where('status','=','completed')
                        ->whereDate('created_at','>=', $fromdates)
                        ->whereDate('created_at','<=', $todates)
                        ->where('orders.vendor_id','=',Auth::user()->id)
                        ->sum('order_total_amt');

             } 
             else if (empty($tddate)) {


                   $retailer = DB::table('orders')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->join('hotel','orders.vendor_id','hotel.vendor_id')
                ->select('orders.order_id','orders.order_no','orders.order_discount_amt','order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.created_at','orders.status','user_regi.full_name','user_regi.mob','user_regi.address','hotel.hotel_name','hotel.hotel_address','hotel.hotel_contact')
                ->whereDate('orders.created_at', '=', $fromdates)
                ->where('orders.vendor_id','=',Auth::user()->id)
                ->paginate(10);



                   $total = DB::table('orders')
                        ->where('status','=','completed')
                        ->where('orders.vendor_id','=',Auth::user()->id)
                        ->sum('order_total_amt');

                      $reqtotal = DB::table('orders')
                        ->select('order_total_amt')
                        ->where('status','=','completed')
                        ->whereDate('created_at','>=', $fromdates)
                        ->whereDate('created_at','<=', $todates)
                        ->where('orders.vendor_id','=',Auth::user()->id)
                        ->sum('order_total_amt');
       
             } 
             else 
             {

                $retailer = DB::table('orders')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->join('hotel','orders.vendor_id','hotel.vendor_id')
                ->select('orders.order_id','orders.order_no','orders.order_discount_amt','order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.created_at','orders.status','user_regi.full_name','user_regi.mob','user_regi.address','hotel.hotel_name','hotel.hotel_address','hotel.hotel_contact')
                ->whereDate('orders.created_at', '>=', $fromdates)
                ->whereDate('orders.created_at', '<=', $todates)
                ->where('orders.vendor_id','=',Auth::user()->id)
                ->paginate(10);




                   $total = DB::table('orders')
                        ->where('status','=','completed')
                        ->where('orders.vendor_id','=',Auth::user()->id)
                        ->sum('order_total_amt');

                      $reqtotal = DB::table('orders')
                        ->select('order_total_amt')
                        ->where('status','=','completed')
                        ->whereDate('created_at','>=', $fromdates)
                        ->whereDate('created_at','<=', $todates)
                        ->sum('order_total_amt');
 
 }
        
        return view('completedordervendor', ['retailer' => $retailer, 'data' => $data,'total'=>$total,'reqtotal'=>$reqtotal]);
    }


    public function FeedbackDeatils()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('feedback')
                ->join('user_regi','feedback.user_id','user_regi.id')
                ->select('feedback.id','user_regi.full_name','user_regi.mob','feedback.comment','feedback.date')
                ->orWhere('feedback.id', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('feedback')
                ->join('user_regi','feedback.user_id','user_regi.id')
                ->select('feedback.id','user_regi.full_name','user_regi.mob','feedback.comment','feedback.date')
                ->orderBy('feedback.id', 'desc')
                ->paginate(10);
        }

        $data = $this->master();
        
        return view('feedback', ['retailer' => $retailer, 'data' => $data]);
    }


    public function Feedbackdelete($id) 
    {
      
      $select=DB::delete('delete from feedback where id = ?',[$id]);
     
            return redirect('/FeedbackDeatils');
    }

    public function rejectVendorOrder($id)
    {

        $order_id = DB::table('orders')->where('order_id', $id)->value('order_no');

        $user_id = DB::table('orders')->where('order_id', $id)->value('order_uid');

        $user_mob = DB::table('user_regi')->where('id', $user_id)->value('mob');

            DB::table('order_details')
            ->where('order_no','=', $order_id)
            ->update(['status'=>'Rejected']);


            DB::table('orders')
            ->where('order_id','=', $id)
            ->update(['status'=>'Rejected']);

        $message='your order is Rejected because of some Restaurant issue';

        $response = $this->sendSms($user_mob, $message);


            return redirect('/vendororder_deatils');

    }
    
    public function blockItem($id)
    {
            DB::table('item')
            ->where('item_id','=', $id)
            ->update(['status'=>'block']);

            return redirect('/item_deatils');

    }

    public function unblockItem($id)
    {

            DB::table('item')
            ->where('item_id','=', $id)
            ->update(['status'=>'available']);

            return redirect('/item_deatils');

    }

    public function adminorderdeatils()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('order_details')
                ->join('orders','order_details.order_no','orders.order_no')
                ->join('hotel','order_details.item_vid','hotel.vendor_id')
                ->select('orders.order_id','orders.order_no','orders.order_uid','orders.vendor_id','orders.order_actual_amt','orders.order_discount_amt','orders.order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.status','order_details.item_name','order_details.item_qty_type','hotel.hotel_name','orders.created_at','order_details.item_actual_amount','order_details.item_discount_amt','order_details.item_total_amt')
                ->where('orders.status','=','Pending')
                ->groupBy('order_details.order_no')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {
            $retailer = DB::table('order_details')
                ->join('orders','order_details.order_no','orders.order_no')
                ->join('hotel','order_details.item_vid','hotel.vendor_id')
                ->select('orders.order_id','orders.order_no','orders.order_uid','orders.vendor_id','orders.order_actual_amt','orders.order_discount_amt','orders.order_total_amt','orders.order_payment_mode','orders.order_delivery_address','orders.status','order_details.item_name','order_details.item_qty_type','hotel.hotel_name','orders.created_at','order_details.item_actual_amount','order_details.item_discount_amt','order_details.item_total_amt')
                ->where('orders.status','=','Pending')
                ->groupBy('order_details.order_no')
                ->get();
        
        }

        $data = $this->master();

        return view('adminorder', ['retailer' => $retailer,'data' => $data]);
    }

    public function getAdminOrder($id)
    {

         $package_deatils=DB::table('orders')
         ->join('order_details','orders.order_no','order_details.order_no')
         ->where('order_id', '=', $id)
         ->get();


         $pack=DB::table('orders')->where('order_id', '=', $id)->get();

        $deliveryboylist = DB::table('deliveryboy')
            ->get();


         $data = $this->master();


        return view('adminassignorder', ['pack' => $pack,'package_deatils'=>$package_deatils,'data' => $data,'deliveryboy'=>$deliveryboylist]);
    }

    public function adminorderupdateSubmit(Request $request)
    {
        $rules = array(
            'name' => 'required',
            'order_no' => 'required', 
        
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/adminorder_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  

        } else {

            try {

                $retailerdata = DB::table('orders')->count();

                if ($retailerdata < 1000) {

                    $vendor_id = $request->input("vendor_id");
                    $name = $request->input("name");
                    $order_no = $request->input("order_no");
                    $order_id = $request->input("order_id");
                 

                    $area = DB::table('hotel')->where('vendor_id','=', $vendor_id)->value('area');

                    $deliveryboyMob = DB::table('deliveryboy')
                   ->select('id','mobile')
                   ->where('area','=',$area)
                   ->get();

        $message = "One near by restaurant order is comming plz check and confirm it on arjunt basis";

foreach ($deliveryboyMob as $key => $value)
{
    $id=$value->id;
    $mob=$value->mobile;
    $response = $this->sendSms($mob, $message);
    $id = DB::table('assign_orders')
    ->insertGetId(['delivery_boy_id'=>$id,'order_id'=>$order_no]); 

}


                $id = DB::table('orders')
                            ->where('order_no','=',$order_no)
                            ->update(['status'=>'Assigned']
                    );

                $id = DB::table('order_details')
                            ->where('order_no','=',$order_no)
                            ->update(['status'=>'Assigned']
                    );
                            
                    $data = Array('type' => 'success', 'message' => 'Assign Orders Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/adminorder_deatils');
        }
    }

    public function rejectAdminOrder($id)
    {

        $order_id = DB::table('orders')->where('order_id', $id)->value('order_no');

        $user_id = DB::table('orders')->where('order_id', $id)->value('order_uid');

        $user_mob = DB::table('user_regi')->where('id', $user_id)->value('mob');

        $vendor_id = DB::table('orders')->where('order_id', $id)->value('vendor_id');

        $vendor_mob = DB::table('users')->where('id', $vendor_id)->value('mob');

            DB::table('order_details')
            ->where('order_no','=', $order_id)
            ->update(['status'=>'Rejected']);


            DB::table('orders')
            ->where('order_id','=', $id)
            ->update(['status'=>'Rejected']);

        $message ='your order is Rejected because of some Restaurant issue';

        $message_vendor ='your order is rejected from admin side';
        

        $response = $this->sendSms($user_mob, $message);

        $response = $this->sendSms($vendor_mob, $message_vendor);

        return redirect('/adminorder_deatils');

    }


    public function getVendorOrderItemDeatils($id)
    {

         $package_deatils=DB::table('orders')
         ->join('order_details','orders.order_no','order_details.order_no')
         ->where('order_id', '=', $id)
         ->get();


         $pack=DB::table('orders')->where('order_id', '=', $id)->get();

        $deliveryboylist = DB::table('deliveryboy')
            ->get();


         $data = $this->master();


        return view('vendororderitemdeatils', ['pack' => $pack,'package_deatils'=>$package_deatils,'data' => $data,'deliveryboy'=>$deliveryboylist]);
    }


    public function JewellerycategoryDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('jewellery_cat')
                ->select('id','cat_name')
                ->orWhere('cat_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('jewellery_cat')
                ->select('id','cat_name')
                ->get();

        }
            $personname = DB::table('jewellery_person')
                ->select('id','name')
                ->get();
        $data = $this->master();


        return view('jeweelrycategory', ['retailer' => $retailer,'data' => $data,'personname'=>$personname]);
    }

    public function JewellerycategorySubmit(Request $request)
    {
        $rules = array(
            'p_id'=>'required',
            'cat_name'=> 'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/jewlcat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('jewellery_cat')->count();

                if ($retailerdata < 1000) {

                     $p_id = $request->input("p_id");
                     $cat_name = $request->input("cat_name");
         

                               
                    $id = DB::table('jewellery_cat')->insertGetId(
                        ['p_id'=>$p_id,'cat_name'=>$cat_name]
                    ); 
                    $data = Array('type' => 'success', 'message' => 'Category Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/jewlcat_deatils');
        }
    }

    public function Jewellerydelete($id) 
    {
      
      $select=DB::delete('delete from jewellery_cat where id = ?',[$id]);
     
            return redirect('/jewlcat_deatils');
    }

    public function JewellerysubcategoryDeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('jewellery_sub_cat')
                ->join('jewellery_cat','jewellery_sub_cat.cat_id','jewellery_cat.id')
                ->select('jewellery_sub_cat.id','jewellery_sub_cat.sub_cat_name','jewellery_sub_cat.sub_cat_img','jewellery_cat.cat_name')
                ->orWhere('jewellery_sub_cat.sub_cat_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('jewellery_sub_cat')
                ->join('jewellery_cat','jewellery_sub_cat.cat_id','jewellery_cat.id')
                ->select('jewellery_sub_cat.id','jewellery_sub_cat.sub_cat_name','jewellery_sub_cat.sub_cat_img','jewellery_cat.cat_name')
                ->get();

        }

        $catlist = DB::table('jewellery_cat')
                ->select('id','cat_name')
                ->get();

        $data = $this->master();
        return view('jeweelrysubcategory', ['retailer' => $retailer,'data' => $data,'catlist'=>$catlist]);
    }

    public function JewellerysubcategorySubmit(Request $request)
    {
        $rules = array(

            'cat_id'=> 'required',
            'sub_cat_name'=>'required',
            'image'=>'required',

        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/jewlsubcat_deatils')
                ->withErrors($validator)
                ->with('errorModal', 'retailerRegistration')
                ->withInput($request->except('password'));  
        } else {

            try {

                $retailerdata = DB::table('jewellery_sub_cat')->count();

                if ($retailerdata < 1000) {

                     $cat_id = $request->input("cat_id");
                     $sub_cat_name = $request->input("sub_cat_name");
         

                   if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('images/jewlsubcat/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }
         
                               
                    $id = DB::table('jewellery_sub_cat')->insertGetId(
                        ['cat_id'=>$cat_id,'sub_cat_name'=>$sub_cat_name,'sub_cat_img'=>$filename]
                    ); 
                    $data = Array('type' => 'success', 'message' => 'Sub Category Added Successfully');
                    DB::commit();
                } else {
                    DB::rollback();
                    $data = Array('type' => 'error', 'message' => 'Retailer Registration Exceed Limit');
                }
            } catch (QueryException $e) {
                DB::rollback();
                if ($e->errorInfo[1] == 1062) {
                    $data = Array('type' => 'error', 'message' => 'Duplicate entry for Mobile Number');
                } else {
                    $data = Array('type' => 'error', 'message' => 'Error! Something went wrong...');
                }
            }
            Session::put('alert', $data);
            return redirect('/jewlsubcat_deatils');
        }
    }

    public function Jewellerysubcatdelete($id) 
    {
      
      $select=DB::delete('delete from jewellery_sub_cat where id = ?',[$id]);
     
            return redirect('/jewlsubcat_deatils');
    }




    public function SuperAdminhoteldeatils()
    {
        if (isset($_GET['text'])) {

            $text = $_GET['text'];
            
            $retailer = DB::table('hotel')
                ->select('id','hotel_name','hotel_address','hotel_contact','hotel_type','city','area','image')
                ->orWhere('hotel_name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));
        } else {

            $retailer = DB::table('hotel')
                ->select('id','hotel_name','hotel_address','hotel_contact','hotel_type','city','area','image')
                ->get();

        }

         $data = $this->master();
         $rescatlist = DB::table('restaurant_cat')
                ->select('id','res_cat_name')
                ->get();

        return view('superadminhotel', ['retailer' => $retailer,'data' => $data,'rescatlist'=>$rescatlist]);
    }

    public function registerAdminClient()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('users')
                ->select('id','name','mob','gmail','address','user_type','status')
                ->where('user_type','=','RETAILER')
                ->where('admin_id','=',Auth::user()->id)             
                ->orWhere('id', 'like', '%' . $text . '%')
                ->orWhere('mob', 'like', '%' . $text . '%')
                ->orWhere('name', 'like', '%' . $text . '%')
                ->paginate(10);
            $retailer->appends(array(
                'text' => $text,
            ));

        } else {

            $retailer = DB::table('users')
                ->select('id','name','mob','gmail','address','user_type','status')
                ->where('user_type','=','RETAILER')
                ->where('admin_id','=',Auth::user()->id)
                ->paginate(10);
        }

        $data = $this->master();

        return view('registeradminvendor', ['retailer' => $retailer, 'data' => $data]);
    }
}




        


