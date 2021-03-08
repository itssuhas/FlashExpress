<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use PaytmWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Response;
use File;
use DateTime;
use Illuminate\Support\Facades\Mail;

class Img
{                    

    public $id;
    public $cat_name;
    public $p_id;
    public $imgpath = array();
    // public $subcid;

}
class HomeController extends Controller
{
    public function vendorRegistrationpanel()
    {
        if (isset($_GET['text'])) {
            $text = $_GET['text'];
            $retailer = DB::table('users')
                ->select('id','name', 'mob', 'gmail', 'address','img')
                ->where('name', 'like', '%' . $text . '%')
                ->orWhere('gmail', 'like', '%' . $text . '%')
                ->orWhere('mob', 'like', '%' . $text . '%')
                ->orWhere('address', 'like', '%' . $text . '%')
               ->paginate(10);

            $retailer->appends(array(
                'text' => $text,
            ));
            
            $data ='';

        } else {

            $retailer = DB::table('users')
                ->select('id','name', 'mob', 'gmail', 'address','img')
                ->orderBy('id', 'desc')
                ->paginate(10);

         $admincity = DB::table('users')
                ->join('city','users.city','city.city_id')
                ->select('users.id','users.name','city.city_name')
                ->where('users.user_type','=','ADMIN')
                ->get();
            
                $data ='';
 
        }

    return view('vendorregisterpanel', ['retailer' => $retailer,'data'=>$data,'admincity'=>$admincity]);
    }

    public function vendorRegistrationpanelSubmit(Request $request)
    {

        $rules = array(

            'admincity'=>'required',
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'address' => 'required',
            'password' => 'required',
            'confirm_password' =>'required|same:password',
            'vendor_bussiness'=>'required',
            'img'=>'required',
          
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return Redirect::to('/api/vendor-registrationpanel')
                ->with('failure','Some Problem Are Occured')
                ->withInput($request->except('password')); 
        } else {
            try {
                    $admincity = $request->input("admincity");
                    $name = $request->input("name");
                    $email = $request->input("email");
                    $mobile = $request->input("mobile");
                    $address = $request->input("address");
                    $password = $request->input("password");
                    $vendor_bussiness = $request->input("vendor_bussiness");

                    $confirm_pass = $request->input("confirm_password");
                    $userId = strtoupper(mt_rand(100000, 999999));
                    $dateToday = date("d-m-Y H:I:s");
                    $todaysDate = date("Y-m-d H:I:s", strtotime($dateToday));
                    
                    $PWD = bcrypt($password);

                    $mobilenumber = DB::table('users')
                    ->where('mob',$mobile)
                    ->select('mob')->value('mobile');

                    if($mobile != $mobilenumber)
                    {

                   if(Input::file('img'))
                    {
                        $file=Input::file('img');

                        $file->move('images/vendor/',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    
                    }
                   
                    $insert = DB::table('users')
                    ->insertGetId(['user_type'=>'RETAILER','id'=> $userId,'admin_id'=>$admincity,'mob' => $mobile,'password'=> $PWD,'password_plain'=>$password,'name'=>$name,'last_login' => date("Y-m-d H:I:s"),'status'=>'Pending','gmail'=>$email,'address'=>$address,'img'=>$filename,'vendor_bussiness'=>$vendor_bussiness]);

                    $data = 'Retailer Registered Sucessfully.';
                    
                    }
                    else 
                    {
                    $data = 'Mobile already Registered';
                    }

            } catch (QueryException $e) {
                if ($e->errorInfo[1] == 1062) {
                    $data = 'Duplicate entry for Mobile Number';
                } else {
                    $data = 'Error! Something went wrong...';
                }
            }
            $admincity = DB::table('users')
                ->join('city','users.city','city.city_id')
                ->select('users.id','users.name','city.city_name')
                ->where('users.user_type','=','ADMIN')
                ->get();


        return view('vendorregisterpanel', ['data' => $data,'admincity'=>$admincity]);
        }
  }

    public function registerClient(Request $request)
    {
       $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(

            'full_name' => 'required',
            'mob' => 'required',
            'adhar_no' => 'required',
            'password' =>'required',
            'vehicle_type' => 'required',
            'address' => 'required',     
            'reference_mobile'=> 'required',
            'user_type'=>'required',   
        );  

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {

            $full_name = $request->input('full_name');
            $mob = $request->input('mob');
            $adhar_no = $request->input('adhar_no');
            $address = $request->input('address');
            $password = $request->input('password');
            $vehicle_type = $request->input('vehicle_type');
            $address = $request->input('address');
            $reference_mobile = $request->input('reference_mobile');
            $user_type = $request->input('user_type');
            $status='Pending';
            $is_verify=0;
            $otp = strtoupper(mt_rand(100000, 999999));
            $date = Carbon::now();    
            $dd = $date->format('y-m-d h:i:s');

            try {


            $mobile = DB::table('user_regi')->where('mob','=', $mob)->value('mob');

            if($mobile != $mob)
            {   

                       $id = DB::table('user_regi')->insert(['full_name' => $full_name,'mob'=>$mob,'adhar_no' => $adhar_no,'address'=>$address,'password'=>$password,'vehicle_type'=>$vehicle_type,'reference_mobile'=>$reference_mobile,'user_type'=>$user_type,'status'=>$status,'is_verify'=>$is_verify,'otp'=>$otp,'reg_date'=>$dd]);

                    $response = array(
                        'error' => false,
                        'otp'=>$otp,
                        'msg' => 'Registration Successfully',
                    );
             }
             else
             {
                $response = array(
                        'error' => false,
                        'msg' => 'Mobile No already registered',
                    );

            }    
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function register(Request $request)
    {
       $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(

            'full_name' => 'required',
            'mob' => 'required',
            'password' =>'required',
            'confirm_pass'=>'required|same:password',
            'landmark' => 'required',
            'address' => 'required',     
            'city'=> 'required',
            'area'=> 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {

            $full_name = $request->input('full_name');
            $mob = $request->input('mob');
            $address = $request->input('address');
            $password = $request->input('password');
            $confirm_pass = $request->input('confirm_pass');
            $landmark = $request->input('landmark');
            $address = $request->input('address');
            $city = $request->input('city');
            $area = $request->input('area');
            $status='Pending';
            $is_verify=0;
            $otp = strtoupper(mt_rand(100000, 999999));
            $date = Carbon::now();    
            $dd = $date->format('y-m-d h:i:s');

            try {

            $mobile = DB::table('user_regi')->where('mob','=', $mob)->value('mob');

            if($mobile != $mob)
            {   

                       $id = DB::table('user_regi')->insert(['full_name' => $full_name,'mob'=>$mob,'landmark' => $landmark,'address'=>$address,'password'=>$password,'confirm_pass'=>$confirm_pass,'city'=>$city,'area'=>$area,'status'=>$status,'is_verify'=>$is_verify,'otp'=>$otp,'reg_date'=>$dd]);
                
                 $response = $this->sendSms($mob, $otp);

                    $response = array(
                        'error' => false,
                        'otp'=>$otp,
                        'msg' => 'Registration Successfully',
                    );
             }
             else
             {
                $response = array(
                        'error' => false,
                        'msg' => 'Mobile No already registered',
                    );

            }    
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function verifyOtp(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        
        $rules = array(
            
            'mob' => 'required',
            'otp' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else {
            
            $mob = $request->input('mob');
            $otp = $request->input('otp');
            
            try {

                $otp_key = DB::table('user_regi')
                    ->select('otp')
                    ->where('mob', $mob)->value('otp');
                    
                if ($otp_key != $otp) {
                    $response = array(
                        'error' => 'true',
                        'msg' => 'your Mobile No not registered with us',
                    );

                } else {

                    DB::table('user_regi')
                        ->where('mob', $mob)
                        ->update(['is_verify' => 1]);
                    
                    $response = array(
                        'error' => false,
                        'msg' => 'Mobile otp verification is Successfully',
                    );
                }
            } catch (Exception $e) {
            }
        }
        return Response::json($response);
    }

    public function userlogin(Request $request)
    {
         $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(
            'mob' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else {

            $username = $request->input('mob');
            $password = $request->input('password');
            
            try {

                $userdata = ['mob' => $username, 'password' => $password];
                
                $data = DB::table('user_regi')
                    ->select('id','full_name','mob','landmark','city','area','address')
                    ->where('mob', '=', $username)
                    ->where('password', '=', $password)
                    ->where('is_verify','=',1)
                    ->first();
                
                $is_verify = DB::table('user_regi')
                    ->where('mob', '=', $username)
                    ->where('password','=', $password)
                    ->value('is_verify');
                
                if (($is_verify == 1)) {

                        $response = array(
                        'error' => false,
                        'msg' => 'Login Successfully',
                        'data' => $data,
                      
                    );

                if (($data)) {
                    $response = array(
                        'error' => false,
                        'msg' => 'Login Successfully',
                        'data' => $data,
                      
                    );
                } else $response = array(
                    'error' => true,
                    'msg' => 'wrong username or password'

                );

            }   
             else $response = array(
                    'error' => true,
                    'msg' => 'Your Account is not verified'

                );

            }

             catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function forgetPassword(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(
            'mob' =>'required',
            'pass' =>'required',
            'confirm_pass'=>'required|same:pass',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else {
            try {

                $mob = $request->input('mob');    
                $pass = $request->input('pass');
                $confirm_pass = $request->input('confirm_pass');

                  $pass1= DB::table('user_regi')
                        ->where('mob','=', $mob)
                        ->update(['password' => $pass,'confirm_pass'=>$confirm_pass]);

                $pass1 = DB::table('user_regi')
                            ->where('mob','=',$mob)
                            ->select('password')->first();

                if (($pass1)) {

                    $pass=$pass1->password;
                    $message ="your New password is:" .$pass;
                   $response = $this->sendSms($mob, $message);
                    
                    
                    $response = array(
                        'error' => false,
                        'msg' => 'password changed Successfully ',
                        'pass' => $pass

                    );
                } else {
                    $response = array(
                        'error' => true,
                        'msg' => ' Your mobile number not registered with us.'
                    );
                }
            }
            catch (Exception $e) {
            }
        }
        return Response::json($response);
    }

    public function resendOtp(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again',
        );
        $rules = array(
            'mob' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else {
            try {

                $mob = $request->input('mob');
                $otp =strtoupper(mt_rand(100000, 999999));
                DB::table('user_regi')
                            ->where('mob', $mob)
                            ->update(['otp' => $otp]);

                $otp_key = DB::table('user_regi')->where('mob', $mob)->value('otp');
                
                if ($otp_key) {
                
                    $message = "Your New Otp is :".$otp_key;
                  
                   $response = $this->sendSms($mob, $message);
                    
                    $response = array(
                        'error' => false,
                        'msg' => 'otp resend Successful',
                        'otp' => $otp_key

                    );
                } else {
                    $response = array(
                        'error' => true,
                        'msg' => 'OTP does not sent'
                    );
                }
            }
            catch (Exception $e) {
            }
        }
        return Response::json($response);
    }

    public function store(Request $request)
    {
       try {

            $id = $request->input('id');

                    if(Input::file('image'))
                    {
                        $file=Input::file('image');

                        $file->move('uploads/userprofiles',$file->getClientOriginalName());
                       
                        $filename = $file->getClientOriginalName();
                    

                    }

                    $id = DB::table('user_regi')
                         ->where('id',$id)
                         ->update(['image' => $filename]);

                $response = array(
                        'error' => false,
                        'msg' => 'file upload successfully'
                    );
                
    }
    catch (QueryException $e) {
            }
        return Response::json($response);  
    }

    public function deliveryBoylogin(Request $request)
    {
         $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(
            'mob' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else {

            $username = $request->input('mob');
            $password = $request->input('password');
            
            try {

                $userdata = ['mob' => $username, 'password' => $password];
                
                $data = DB::table('deliveryboy')
                    ->select('id','name','mobile','address','gmail')
                    ->where('mobile', '=', $username)
                    ->where('password', '=', $password)
                    ->first();
                
                if (($data)) {

                    $response = array(
                        'error' => false,
                        'msg' => 'Login Successfully',
                        'data' => $data,  
                    );
                } else $response = array(
                    'error' => true,
                    'msg' => 'wrong username or password'
                );
            }

             catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }


    public function forgetPassdeliveryboy(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(
            'mob' =>'required',
            'pass' =>'required',
            'confirm_pass'=>'required|same:pass',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else {
            try {

                $mob = $request->input('mob');    
                $pass = $request->input('pass');
                $confirm_pass = $request->input('confirm_pass');

                  $pass1= DB::table('deliveryboy')
                        ->where('mobile','=', $mob)
                        ->update(['password' => $pass,'confirm_pass'=>$confirm_pass]);

                $pass1 = DB::table('deliveryboy')
                            ->where('mobile','=',$mob)
                            ->select('password')->first();

                if (($pass1)) {

                    $pass=$pass1->password;
                 $message ="your New password is:" .$pass;
                 $response = $this->sendSms($mob, $message);
                    
                    
                    $response = array(
                        'error' => false,
                        'msg' => 'password changed Successfully ',
                        'pass' => $pass

                    );
                } else {
                    $response = array(
                        'error' => true,
                        'msg' => ' Your mobile number not registered with us.'
                    );
                }
            }
            catch (Exception $e) {
            }
        }
        return Response::json($response);
    }

    public function showBanner(Request $request)
    {            
         try {
                $id = DB::table('banner')
                ->select('id','image')
                ->get();    

                
                $output=array();
                
                foreach ($id as $key => $value) {
                  
                    $output[]=array('id'=>$value->id,'image'=>'http://192.168.2.11/flashexpress/public/images/banner/'.$value->image);
                }

                if ($output) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'banner deatials',
                        'data' => $output,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        
        return Response::json($response);

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

    public function updateprofileEdit(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(
            'user_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
            
        } else { 
            try {    

                $user_id = $request->input('user_id');    

                $select = DB::table('user_regi')
                    ->select('id','mob','full_name','city','area','landmark','address','image')
                    ->where('id','=', $user_id)
                    ->first();
                               
                   

                $output=array();

                if($select) {
                   
                    $output[]=['id'=>$select->id,'mob'=> $select->mob,'full_name'=> $select->full_name,'city'=>$select->city,'area'=>$select->area,'landmark'=>$select->landmark,'address'=>$select->address,'image'=>'http://192.168.2.11/flashexpress/public/uploads/userprofiles/'.$select->image];
                    
                    $response = array(
                        'error' => false,
                        'data'=> $output,
                      
                    );
                }
                else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Record Not Found.',
                    );
                }
            } catch (Exception $e) {
                 $response = array(
                        'error' => true,
                        'msg' => 'Record Not Found.',
                    );
            }
        }
        return Response::json($response);
    }
    
    public function updateprofile(Request $request)
    {

        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(
           
            'id' => 'required',
            'mob' => 'required',
            'full_name' => 'required',
            'city'=> 'required',
            'area'=>'required',
            'landmark'=>'required', 
            'address' =>'required',
         );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else
         {
            $id = $request->input('id');   
            $mob = $request->input('mob');
            $full_name = $request->input('full_name');   
            $city = $request->input('city');
            $area = $request->input('area');
            $landmark = $request->input('landmark');
            $address = $request->input('address');

            try {
              
                $data= DB::table('user_regi')
                ->where('id', '=', $id)
                ->update(['full_name'=>$full_name,'city'=>$city,'mob'=>$mob,'area'=>$area,'landmark'=>$landmark,'address'=>$address]);
                
            
                    $response = array(
                        'error' => true,
                        'msg' => 'profile updated successfully',        
                    );
                
             
            } catch (Exception $e) {

            }
        }
        return Response::json($response);
    }
    
    public function updatededeliveryBoyEdit(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(

            'delivery_boy_id' => 'required',
        
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else { 

            try {    

                $delivery_boy_id = $request->input('delivery_boy_id');    
                $select = DB::table('deliveryboy')
                    ->select('id','name','city','area','address','mobile','gmail')
                    ->where('id','=', $delivery_boy_id)
                    ->first();

                $output=array();

                if($select) {
                   
                    $output[]=['id'=>$select->id,'name'=> $select->name,'city'=>$select->city,'area'=>$select->area,'address'=>$select->address,'mobile'=>$select->mobile,'gmail'=>$select->gmail];
                    
                    $response = array(
                        'error' => false,
                        'data'=> $output,
                      
                    );
                }
                else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Record Not Found.',
                    );
                }
            } catch (Exception $e) {
            }
        }
        return Response::json($response);
    }
    
    public function updateDeliveryBoy(Request $request)
    {

        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(
           
            'id' => 'required',
            'name'=>'required',
            'city'=> 'required',
            'area'=>'required',
            'address' =>'required',
            'mobile' => 'required',
            'gmail'=>'required',
         );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else
         {
            $id = $request->input('id');   
            $name = $request->input('name');   
            $city = $request->input('city');
            $area = $request->input('area');
            $address = $request->input('address');
            $mobile = $request->input('mobile');
            $gmail = $request->input('gmail');

            try {
              
                $data= DB::table('deliveryboy')
                ->where('id', '=', $id)
                ->update(['name'=>$name,'city'=>$city,'area'=>$area,'address'=>$address,'mobile'=>$mobile,'gmail'=>$gmail]);
            
                    $response = array(
                        'error' => true,
                        'msg' => 'profile updated successfully',        
                    );
                
             
            } catch (Exception $e) {

            }
        }
        return Response::json($response);
    }

    public function EcommBussiness(Request $request)
    {            
         try {
                $id = DB::table('ecomm_category')
                ->select('id','cat_name','image')
                ->get();    

                
                $output=array();
                
                foreach ($id as $key => $value) {
                  
                    $output[]=array('id'=>$value->id,'bussiness_name'=>$value->cat_name,'image'=>'http://192.168.2.11/flashexpress/public/images/bussiness/'.$value->image);
                }

                if ($output) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'Types Of Ecommorce Bussiness',
                        'data' => $output,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        return Response::json($response);
    }

    public function showResCategory(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(

            'bussiness_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else { 
            try {    

                $bussiness_id = $request->input('bussiness_id');    

                $select = DB::table('restaurant_cat')
                    ->select('id','business_type_id','res_cat_name','image')
                    ->where('business_type_id','=', $bussiness_id)
                    ->get();
                               
                $output=array();

                foreach ($select as $key => $value) {

                    $output[]=['id'=>$value->id,'business_type_id'=>$value->business_type_id,'res_cat_name'=> $value->res_cat_name,'image'=>'http://192.168.2.11/flashexpress/public/images/category/'.$value->image];
                }

                if($output)
                {
                        $response = array(
                        'error' => false,
                        'msg' => 'Restaurant category deatials',
                        'data'=> $output,
                      
                    );
                }
                else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Record Not Found.',
                    );
                }
            } catch (Exception $e) {
                
            }
        }
        return Response::json($response);
    }

    public function HotelList(Request $request)
    {            
         try {
                $id = DB::table('hotel')
                ->select('id','vendor_id','hotel_name','hotel_address','city','area','hotel_contact','start_time','end_time','hotel_type','image','status')
                ->where('status','=','1')
                ->get();    

                
                $output=array();
                
                foreach ($id as $key => $value) {
                  
                    $output[]=array('id'=>$value->id,'vendor_id'=>$value->vendor_id,'hotel_name'=>$value->hotel_name,'hotel_address'=>$value->hotel_address,'city'=>$value->city,'area'=>$value->area,'start_time'=>$value->start_time,'end_time'=>$value->end_time,'hotel_type'=>$value->hotel_type,'hotel_contact'=>$value->hotel_contact,'image'=>'http://192.168.2.11/flashexpress/public/images/hotel/'.$value->image);
                }

                if ($output) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'hotel List',
                        'data' => $output,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        
        return Response::json($response);

    }
    
    public function showHotelCategorywise(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        
        $rules = array(

            'cat_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else { 
            try {    

                $cat_id = $request->input('cat_id');    

                //dd($cat_id);


                $select = DB::table('hotel')
                    ->select('id','hotel_name','vendor_id','hotel_address','hotel_contact','city','start_time','end_time','area','image')
                    ->where('hotel_type','like',"%".$cat_id.'%')
                    ->where('status','=','1')
                    ->get();
                               
                $output=array();

                foreach ($select as $key => $value) {

                     $output[]=array('id'=>$value->id,'hotel_name'=>$value->hotel_name,'vendor_id'=>$value->vendor_id,'hotel_address'=>$value->hotel_address,'city'=>$value->city,'area'=>$value->area,'start_time'=>$value->start_time,'end_time'=>$value->end_time,'hotel_contact'=>$value->hotel_contact,'image'=>'http://192.168.2.11/flashexpress/public/images/hotel/'.$value->image);
                }

                if($output)
                {
                        $response = array(
                        'error' => false,
                        'msg' => 'Restaurant category deatials',
                        'data'=> $output,
                      
                    );
                }
                else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Record Not Found.',
                    );
                }
            } catch (Exception $e) {
                
            }
        }
        return Response::json($response);
    }

    public function showHotelItemCategory(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(          
            'vendor_id'=>'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else { 
            try {    

                $vendor_id = $request->input('vendor_id');  
                
                $cat_id = DB::table('item')
                    ->select('item_id','category_id')
                    ->where('vendor_id','=',$vendor_id)
                    ->groupBy('category_id')
                    ->get();

                $output=array();

                foreach ($cat_id as $key) {

                    $cid = $key->category_id;

                $hotelMenuType = DB::table('restaurant_cat')
                    ->select('id','res_cat_name','image')
                    ->where('id','=',$cid)
                    ->get();


            foreach ($hotelMenuType as $key => $value) {
    
             $output[]=['id'=>$value->id,'res_cat_name'=> $value->res_cat_name,'image'=>'http://192.168.2.11/flashexpress/public/images/category/'.$value->image];

         }  
            }

                if($output)
                {
                        $response = array(
                        'error' => false,
                        'msg' => 'Restaurant category deatials',
                        'data'=> $output,
                      
                    );
                }
                else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Record Not Found.',
                    );
                }
            } catch (Exception $e) {
                
            }
        }
        return Response::json($response);
    }

    public function vehicleInformation(Request $request)
    {            
         try {
                $id = DB::table('vehicle')
                ->select('vehicle_id','vehicle_model','image','capacity','capacity_unit','rate','km_rate')
                ->get();    

                
                $output=array();
                
                foreach ($id as $key => $value) {
                  
                    $output[]=array('vehicle_id'=>$value->vehicle_id,'vehicle_model'=>$value->vehicle_model,'capacity'=>$value->capacity,'capacity_unit'=>$value->capacity_unit,'rate'=>$value->rate,'km_rate'=>$value->km_rate,'image'=>'http://192.168.2.11/logisticadmin/public/images/vehicle/'.$value->image);
                }

                if ($output) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'vehicle deatials',
                        'data' => $output,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        return Response::json($response);
    }

    public function showHotelitemList(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(

            'vendor_id' => 'required',
            'cat_id' =>'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else { 
            try {    

                $vendor_id = $request->input('vendor_id');    
                $cat_id = $request->input('cat_id');    


                $select = DB::table('item')
                    ->select('item_id','item_name','item_price','item_discount_amt','item_half_price','item_half_discount_amt','admin_item_price','admin_discount_price','admin_half_item_price','admin_half_item_discount_price','qty_type','item_image','item_description','category_id','status')
                    ->where('vendor_id','=',$vendor_id)
                    ->where('category_id','=',$cat_id)
                    ->where('status','=','available')
                    ->where('admin_item_price','>','0')
                    ->get();
                               
                $output=array();


                $city = DB::table('hotel')->where('vendor_id','=',$vendor_id)->value('city');


                foreach ($select as $key => $value) {

                     $output[]=array('item_id'=>$value->item_id,'item_name'=>$value->item_name,'item_price'=>$value->item_price,'item_discount_amt'=>$value->item_discount_amt,'item_half_price'=>$value->item_half_price,'item_half_discount_amt'=>$value->item_half_discount_amt,'admin_item_price'=>$value->admin_item_price,'qty_type'=>$value->qty_type,'admin_discount_price'=>$value->admin_discount_price,'admin_half_item_price'=>$value->admin_half_item_price,'admin_half_item_discount_price'=>$value->admin_half_item_discount_price,'item_description'=>$value->item_description,'category_id'=>$value->category_id,'item_image'=>'http://192.168.2.11/flashexpress/public/images/item/'.$value->item_image);
                }

                if($output)
                {
                        $response = array(
                        'error' => false,
                        'msg' => 'Hotel item list deatials',
                        'data'=> $output,
                        'city'=>$city,
                      
                    );
                }
                else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Record Not Found.',
                    );
                }
            } catch (Exception $e) {
                
            }
        }
        return Response::json($response);
    }
    
    public function deliveryCharges(Request $request)
    {

            try {    

            
                $select = DB::table('delivery_charges')
                    ->select('id','vendor_id','charges_range','charges')
                    ->get();
                               
                $output=array();

                foreach ($select as $key => $value) {

                     $output[]=array('id'=>$value->id,'vendor_id'=>$value->vendor_id,'charges_range'=>$value->charges_range,'charges'=>$value->charges);
                }

                if($output)
                {
                        $response = array(
                        'error' => false,
                        'msg' => 'Delivery Charges',
                        'data'=> $output,
                      
                    );
                }
                else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Record Not Found.',
                    );
                }
            } catch (Exception $e) {
                
            }
        
        return Response::json($response);
    }

    public function OrderDeatils(Request $request)
    {
    
             $response = array(
                'error' => true,
                'msg' => 'Please try again.',
                );

                $date= carbon::now();
         

        $rules = array(
            'order_details' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        }
         else
         {
             $order_details = $request->input('order_details');   
                   

                $orders =  json_decode($order_details);
                


                $status="Pending";

                $dd=Carbon::now();
                $date=$dd->format('y-m-d H:i:s');

            
         try {

    foreach ($orders as $key => $value) 
    {
              $order_no = $value->order_no;
              $order_uid = $value->order_uid;
              $order_actual_amt = $value->order_actual_amt;
              $order_discount_amt = $value->order_discount_amt;
              $order_total_amt = $value->order_total_amt;
              $order_payment_mode = $value->order_payment_mode;
              $order_delivery_address = $value->order_delivery_address;
              $item_id = $value->item_id;
              $item_name = $value->item_name;
              $item_qty_type = $value->item_qty_type;
              $item_vid = $value->item_vid;
              $item_qty = $value->item_qty;
              $item_actual_amount=$value->item_actual_amount;
              $item_discount_amt = $value->item_discount_amt;
              $item_total_amt = $value->item_total_amt;
              $delivery_charges = $value->delivery_charges;


        $id = DB::table('order_details')->insertGetId(['order_no' => $order_no, 'item_vid' => $item_vid,'item_id'=>$item_id,'item_qty'=>$item_qty,'item_name'=>$item_name,'item_qty_type'=>$item_qty_type,'item_actual_amount'=>$item_actual_amount,'item_discount_amt'=>$item_discount_amt,'item_total_amt'=>$item_total_amt,'created_at' => $date, 'updated_at' => $date,'status'=>$status]);  

    }


        $usermob = DB::table('user_regi')->where('id', $order_uid)->value('mob');

        $usermsg="your order is placed successfully";
        
        $response = $this->sendSms($usermob, $usermsg);

        $vendormob = DB::table('users')->where('id', $item_vid)->value('mob');

        $vendormsg = "New Order is comming plz check on your vendor panel";

        $response = $this->sendSms($vendormob, $vendormsg);
        

        $id = DB::table('orders')->insertGetId(['order_no' => $order_no, 'order_uid' => $order_uid,'vendor_id'=>$item_vid,'order_actual_amt'=>$order_actual_amt,'order_discount_amt'=>$order_discount_amt,'order_total_amt'=>$order_total_amt,'order_payment_mode'=>$order_payment_mode,'order_delivery_address' =>$order_delivery_address,'created_at' =>$date,'updated_at'=>$date,'delivery_charges'=>$delivery_charges,'status'=>$status]);


                if($id) 
                {     
                    $response = array(
                        'error' => false,
                        'msg' => 'Order Booking Successfully',
                    );
                }
                 else 
                {
                    $response = array(
                        'error' => true,
                        'msg' => 'Some Problem Are Occured',
                    );
                } 
            }
             catch (QueryException $e) {
            }
     }
        return Response::json($response);
    }

    public function drawerorder(Request $request)
    {

        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        
        $rules = array(
           
            'user_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $user_id = $request->input('user_id');                                   
         try {
                $id = DB::table('orders')
                ->join('user_regi','user_regi.id','orders.order_uid')
                ->select('orders.order_no','orders.vendor_id','orders.order_uid','orders.order_total_amt','orders.order_actual_amt','orders.order_payment_mode','order_discount_amt','orders.status','created_at')
                ->where('orders.order_uid','=',$user_id)
                ->get();    

                $output=array();
                
                foreach ($id as $key => $value) {

                    $output[]=array('order_no'=>$value->order_no,'vendor_id'=>$value->vendor_id,'order_uid'=>$value->order_uid,'order_actual_amt'=>$value->order_actual_amt,'order_discount_amt'=>$value->order_discount_amt,'order_total_amt'=>$value->order_total_amt,'order_payment_mode'=>$value->order_payment_mode,'status'=>$value->status,'created_at'=>$value->created_at);
                } 

                if ($output) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'order deatials',
                        'order' => $output,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }                           
    
    public function drawerorder_deatials(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(
           
            'user_id' => 'required',
            'order_no' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {  
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $user_id = $request->input('user_id');
            $order_no = $request->input('order_no');
            
         try {

            $items_in_order = DB::table('order_details')
                            ->join('orders','order_details.order_no','orders.order_no')
                            ->where('order_details.order_no','=',$order_no)
                            ->where('orders.order_uid','=',$user_id)
                            ->count();

                $id = DB::table('orders')
                ->join('order_details','orders.order_no','order_details.order_no')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->select('orders.order_no','orders.order_uid','orders.created_at','orders.order_delivery_address','user_regi.mob','orders.order_payment_mode','orders.order_total_amt','order_details.item_qty','order_details.item_total_amt','order_details.item_id','order_details.item_discount_amt','orders.delivery_charges')
                ->where('orders.order_uid','=',$user_id)
                ->where('orders.order_no','=',$order_no)
                ->get();    

                $idimg = DB::table('order_details')
                ->join('item','order_details.item_id','item.item_id')
                ->select('item.item_id','item.item_name','item.item_image')
                ->where('order_details.order_no','=',$order_no)
                ->get();    

                $output=array();
                $output1=array();
                
                
                foreach ($id as $key => $value) {
                    $output[]=array('order_no'=>$value->order_no,'order_uid'=>$value->order_uid,'created_at'=>$value->created_at,'order_delivery_address'=>$value->order_delivery_address,'mobile_no'=>$value->mob,'order_payment_mode'=>$value->order_payment_mode,'order_total_amt'=>$value->order_total_amt,'items_in_order'=>$items_in_order,'orderitemqty'=>$value->item_qty,'item_total_amt'=>$value->item_total_amt,'item_id'=> $value->item_id,'item_discount_amt'=>$value->item_discount_amt,'delivery_charges'=>$value->delivery_charges);
                }

                 foreach ($idimg as $key => $value) {
                    $output1[]=array('item_id'=>$value->item_id,'item_name'=>$value->item_name,'item_image'=>'http://192.168.2.11/flashexpress/images/item/'. $value->item_image);
                }

                if ($output && $output1) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'Drawer Order Deatials',
                        'data' => $output,
                        'itemimg' => $output1
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function AssignOrder(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(
           
            'delivery_boy_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {  
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $delivery_boy_id = $request->input('delivery_boy_id');
            
         try {

                $id = DB::table('assign_orders')
                ->join('orders','assign_orders.order_id','orders.order_no')
                ->select('orders.order_no','orders.created_at','orders.order_total_amt','orders.vendor_id','orders.order_delivery_address','order_uid','orders.delivery_charges','orders.status')
                ->where('assign_orders.delivery_boy_id','=',$delivery_boy_id)
                ->whereNull('accepted_dboy_id')
                 ->get();    

                $output = array();
            
                foreach ($id as $key => $value) {

                    $v_id = $value->vendor_id;

                    $output[]=array('order_no'=>$value->order_no,'order_date'=>$value->created_at,'vendor_id'=>$value->vendor_id,'user_id'=>$value->order_uid,'order_total_amt'=>$value->order_total_amt,'order_delivery_address'=>$value->order_delivery_address,'delivery_charges'=>$value->delivery_charges,'status'=>$value->status);
                
                $id = DB::table('users')
                ->join('hotel','users.id','hotel.vendor_id')
                ->select('users.name','users.mob','hotel.hotel_address','hotel.hotel_name')
                ->where('users.id','=',$v_id)
                ->first();  

                }
                
                if ($output && $id) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'Delivery Boy Order Deatials',
                        'data' => $output,
                        'vendor_deatils'=>$id,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function AssignOrder_deatials(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(
           
            'order_no' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {  
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $order_no = $request->input('order_no');
            
         try {

            $items_in_order = DB::table('order_details')
                            ->join('orders','order_details.order_no','orders.order_no')
                            ->where('order_details.order_no','=',$order_no)
                            ->count();

                $id = DB::table('orders')
                ->join('order_details','orders.order_no','order_details.order_no')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->select('orders.order_no','orders.order_uid','orders.created_at','orders.order_delivery_address','user_regi.mob','user_regi.full_name','orders.order_payment_mode','orders.order_total_amt','order_details.item_qty','order_details.item_total_amt','order_details.item_id','order_details.item_discount_amt','orders.delivery_charges','orders.vendor_id')
                ->where('orders.order_no','=',$order_no)
                ->get();    

                $idimg = DB::table('order_details')
                ->join('item','order_details.item_id','item.item_id')
                ->select('item.item_id','item.item_name','item.item_image')
                ->where('order_details.order_no','=',$order_no)
                ->get();    

                $output=array();
                $output1=array();
                
                
                foreach ($id as $key => $value) {

                    $output[]=array('order_no'=>$value->order_no,'order_uid'=>$value->order_uid,'created_at'=>$value->created_at,'order_delivery_address'=>$value->order_delivery_address,'mobile_no'=>$value->mob,'full_name'=>$value->full_name,'order_payment_mode'=>$value->order_payment_mode,'order_total_amt'=>$value->order_total_amt,'items_in_order'=>$items_in_order,'orderitemqty'=>$value->item_qty,'item_total_amt'=>$value->item_total_amt,'item_id'=> $value->item_id,'item_discount_amt'=>$value->item_discount_amt,'delivery_charges'=>$value->delivery_charges,'vendor_id'=>$value->vendor_id);
                }

                 foreach ($idimg as $key => $value) {
                    $output1[]=array('item_id'=>$value->item_id,'item_name'=>$value->item_name,'item_image'=>'http://192.168.2.11/flashexpress/images/item/'. $value->item_image);
                }

                if ($output && $output1) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'Assign Order Deatials',
                        'data' => $output,
                        'itemimg' => $output1
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }
    
    public function BillDeatils(Request $request)
    {
    
        $response = array(
                'error' => true,
                'msg' => 'Please try again.',
                );

        $rules = array(
            'user_id' => 'required',
            'vendor_id'=>'required',
            'order_id'=>'required',
            'total_amount'=>'required',
            'user_name'=>'required',
            'user_mob'=>'required',
            'address'=>'required',

        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        }
         else
         {
             $user_id = $request->input('user_id');
             $order_id = $request->input('order_id');
             $total_amount = $request->input('total_amount');
             $user_name = $request->input('user_name');

             $vendor_id = $request->input('vendor_id');
             
             $user_mob = $request->input('user_mob');
             $address = $request->input('address');
             $dd=Carbon::now();
             $date=$dd->format('y-m-d H:i:s');
             $status='Recived';

         try {
                if(Input::file('sign_img'))
                    {
                        $file=Input::file('sign_img');
                        $file->move('uploads/sign',$file->getClientOriginalName());
                        $filename = $file->getClientOriginalName();
                    }

                $id = DB::table('bill_deatils')
                ->insertGetId(['user_id'=>$user_id,'order_id' => $order_id,'total_amount' =>$total_amount,'payable_amount'=>$total_amount,'payable_amount_vendor'=>$total_amount,'user_name'=>$user_name,'user_mob'=>$user_mob,'address'=>$address,'payable_status'=>$status,'vendor_id'=>$vendor_id]);

                DB::table('orders')
                        ->where('order_no', $order_id)
                        ->update(['status' => 'completed']);

                DB::table('order_details')
                        ->where('order_no', $order_id)
                        ->update(['status' => 'completed']);
                              

                if($id) 
                {     
                    $response = array(
                        'error' => false,
                        'msg' => 'Bill Added Successfully',
                    );
                }
                else 
                {
                    $response = array(
                        'error' => true,
                        'msg' => 'Some Problem Are Occured',
                    );
                } 
            }
             catch (QueryException $e) {
            }
     }
        return Response::json($response);
    }

    public function acceptRejectorder(Request $request)
    {

        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(
           
            'user_id' => 'required',
            'order_id' => 'required',
            'status'=>'required',
            'vendor_id'=>'required',
            'dboy_id' =>'required',
         );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        }
        else
        {
            $user_id = $request->input('user_id');   
            $order_id = $request->input('order_id');
            $vendor_id = $request->input('vendor_id');
            $status = $request->input('status');
            $dboy_id = $request->input('dboy_id');

            try {
              
             $acceptuser = DB::table('orders')
            ->where('order_no','=',$order_id)
            ->value('accepted_dboy_id');
 

        if($acceptuser == '' || $status== 'ACCEPT' || $status =='REJECT')
        {

        $data = DB::table('user_regi')
                ->where('id', '=', $user_id)
                ->update(['order_id'=>$order_id]);

        $data = DB::table('user_regi')
                ->where('id', '=', $user_id)
                ->update(['order_status'=>$status]);


        $data = DB::table('orders')
                ->where('order_uid', '=' , $user_id)
                ->where('order_no', '=' , $order_id)
                ->update(['accepted_dboy_id'=>$dboy_id,'status'=>$status]);

            $data = DB::table('order_details')
                ->where('order_no', '=' , $order_id)
                ->update(['accepted_dboy_id'=>$dboy_id,'status'=>$status]);

            $data = DB::table('assign_orders')
                ->where('delivery_boy_id', '=' , $dboy_id)
                ->update(['status'=>$status]);

                    if($status == 'ACCEPT')
                    {
                    
                    $response = array(
                        'error' => true,
                        'msg' => 'Order Accepted Successfully',        
                    );

                    }

                    if($status == 'REJECT')
                    {
                    
                    $response = array(
                        'error' => true,
                        'msg' => 'Order Rejected Successfully ',        
                    );

                    }
        } 
        else
        {
            $response = array(
                        'error' => true,
                        'msg' => 'Order already Accepted',        
                    );
        }
       
            } catch (Exception $e) {

            }
        }
        return Response::json($response);
    }

    public function deliveryAssignOrder(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );

        $rules = array(
           
            'delivery_boy_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {  
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $delivery_boy_id = $request->input('delivery_boy_id');
            
         try {

                $id = DB::table('assign_orders')
                ->join('orders','assign_orders.order_id','orders.order_no')
                ->select('orders.order_no','orders.created_at','orders.order_total_amt','orders.vendor_id','orders.order_delivery_address','order_uid','orders.delivery_charges','orders.status')
                ->where('orders.accepted_dboy_id','=',$delivery_boy_id)
                ->where('orders.status','=','ACCEPT')
                ->whereNotNull('assign_orders.status')
                ->get();    

                $output = array();
            
                foreach ($id as $key => $value) {

                    $v_id = $value->vendor_id;

                    $output[]=array('order_no'=>$value->order_no,'order_date'=>$value->created_at,'vendor_id'=>$value->vendor_id,'user_id'=>$value->order_uid,'order_total_amt'=>$value->order_total_amt,'order_delivery_address'=>$value->order_delivery_address,'delivery_charges'=>$value->delivery_charges,'status'=>$value->status);
                
                }

                if ($output) 
                {

                $id = DB::table('users')
                ->join('hotel','users.id','hotel.vendor_id')
                ->select('users.name','users.mob','hotel.hotel_address')
                ->where('users.id','=',$v_id)
                ->first(); 

                    $response = array(
                        'error' => false,
                        'msg' => 'Delivery Boy Order Deatials',
                        'data' => $output,
                        'vendor_deatils'=>$id,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function deliveryAssignOrder_deatials(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(
           
            'delivery_boy_id' => 'required',
            'order_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {  
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $delivery_boy_id = $request->input('delivery_boy_id');
            $order_id = $request->input('order_id');

            
         try {

            $items_in_order = DB::table('order_details')
                            ->join('orders','order_details.order_no','orders.order_no')
                            ->where('order_details.accepted_dboy_id','=',$delivery_boy_id)
                            ->count();

                $id = DB::table('orders')
                ->join('order_details','orders.order_no','order_details.order_no')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->select('orders.order_no','orders.order_uid','orders.created_at','orders.order_delivery_address','user_regi.mob','user_regi.full_name','orders.order_payment_mode','orders.order_total_amt','order_details.item_qty','order_details.item_total_amt','order_details.item_id','order_details.item_discount_amt','orders.delivery_charges','orders.accepted_dboy_id')
                ->where('orders.accepted_dboy_id','=',$delivery_boy_id)
                ->where('orders.status','=','ACCEPT')
                ->where('orders.order_no','=',$order_id)
                ->get();    


                $idimg = DB::table('order_details')
                ->join('item','order_details.item_id','item.item_id')
                ->select('item.item_id','item.item_name','item.item_image')
                ->where('order_details.accepted_dboy_id','=',$delivery_boy_id)
                ->where('order_details.status','=','ACCEPT')
                ->where('order_details.order_no','=',$order_id)  
                ->get();    

                $output=array();
                $output1=array();
                
                
                foreach ($id as $key => $value) {

                    $output[]=array('order_no'=>$value->order_no,'order_uid'=>$value->order_uid,'created_at'=>$value->created_at,'order_delivery_address'=>$value->order_delivery_address,'mobile_no'=>$value->mob,'full_name'=>$value->full_name,'order_payment_mode'=>$value->order_payment_mode,'order_total_amt'=>$value->order_total_amt,'items_in_order'=>$items_in_order,'orderitemqty'=>$value->item_qty,'item_total_amt'=>$value->item_total_amt,'item_id'=> $value->item_id,'item_discount_amt'=>$value->item_discount_amt,'delivery_charges'=>$value->delivery_charges);
                }

                 foreach ($idimg as $key => $value) {
                    $output1[]=array('item_id'=>$value->item_id,'item_name'=>$value->item_name,'item_image'=>'http://192.168.2.11/flashexpress/images/item/'. $value->item_image);
                }

                if ($output && $output1) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'delivery boy Assign Order Deatials',
                        'data' => $output,
                        'itemimg' => $output1
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function deliveryBoyHistory(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        $rules = array(
           
            'delivery_boy_id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {  
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $delivery_boy_id = $request->input('delivery_boy_id');
            
         try {

                $id = DB::table('orders')
                ->select('orders.order_no','orders.created_at','orders.order_total_amt','orders.vendor_id','orders.order_delivery_address','order_uid','orders.delivery_charges','orders.status')
                ->where('orders.accepted_dboy_id','=',$delivery_boy_id)
                ->where('orders.status','=','completed')
                ->get();    

                $output = array();
            
                foreach ($id as $key => $value) {

                    $v_id = $value->vendor_id;

                    $output[]=array('order_no'=>$value->order_no,'order_date'=>$value->created_at,'vendor_id'=>$value->vendor_id,'user_id'=>$value->order_uid,'order_total_amt'=>$value->order_total_amt,'order_delivery_address'=>$value->order_delivery_address,'delivery_charges'=>$value->delivery_charges,'status'=>$value->status);
                }

                if ($output) 
                {

                $id = DB::table('users')
                ->join('hotel','users.id','hotel.vendor_id')
                ->select('users.name','users.mob','hotel.hotel_address','hotel.hotel_name')
                ->where('users.id','=',$v_id)
                ->first(); 

                $payable_amount = DB::table('bill_deatils')
                        ->where('user_id','=',$delivery_boy_id)
                        ->sum('payable_amount');

                $paid_amount = DB::table('bill_deatils')
                        ->where('user_id','=',$delivery_boy_id)
                        ->sum('paid_amount');
                        
                    $response = array(
                        'error' => false,
                        'msg' => 'Delivery Boy Order Deatials',
                        'data' => $output,
                        'vendor_deatils'=>$id,
                        'payable_amount'=>$payable_amount,
                        'paid_amount'=>$paid_amount,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function deliveryBoyHistory_deatials(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        
        $rules = array(
           
            'delivery_boy_id' => 'required',
            'order_id'=>'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {  
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $delivery_boy_id = $request->input('delivery_boy_id');
            $order_id = $request->input('order_id');

            
         try {

            $items_in_order = DB::table('order_details')
                            ->join('orders','order_details.order_no','orders.order_no')
                            ->where('order_details.accepted_dboy_id','=',$delivery_boy_id)
                            ->where('order_details.order_no','=',$order_id)
                            ->count();

                $id = DB::table('orders')
                ->join('order_details','orders.order_no','order_details.order_no')
                ->join('user_regi','orders.order_uid','user_regi.id')
                ->select('orders.order_no','orders.order_uid','orders.created_at','orders.order_delivery_address','user_regi.mob','user_regi.full_name','orders.order_payment_mode','orders.order_total_amt','order_details.item_qty','order_details.item_total_amt','order_details.item_id','order_details.item_discount_amt','orders.delivery_charges','orders.accepted_dboy_id')
                ->where('orders.accepted_dboy_id','=',$delivery_boy_id)
                ->where('orders.order_no','=',$order_id)
                ->where('orders.status','=','completed')
                ->get();    

                $idimg = DB::table('order_details')
                ->join('item','order_details.item_id','item.item_id')
                ->select('item.item_id','item.item_name','item.item_image')
                ->where('order_details.accepted_dboy_id','=',$delivery_boy_id)
                ->where('order_details.order_no','=',$order_id)
                ->where('order_details.status','=','completed')
                ->get();    

                $output = array();
                $output1 = array();
                
                
                foreach ($id as $key => $value) {

                    $output[]=array('order_no'=>$value->order_no,'order_uid'=>$value->order_uid,'created_at'=>$value->created_at,'order_delivery_address'=>$value->order_delivery_address,'mobile_no'=>$value->mob,'full_name'=>$value->full_name,'order_payment_mode'=>$value->order_payment_mode,'order_total_amt'=>$value->order_total_amt,'items_in_order'=>$items_in_order,'orderitemqty'=>$value->item_qty,'item_total_amt'=>$value->item_total_amt,'item_id'=> $value->item_id,'item_discount_amt'=>$value->item_discount_amt,'delivery_charges'=>$value->delivery_charges);
                }

                 foreach ($idimg as $key => $value) {
                    $output1[]=array('item_id'=>$value->item_id,'item_name'=>$value->item_name,'item_image'=>'http://192.168.2.11/flashexpress/public/images/item/'. $value->item_image);
                }

                if ($output && $output1) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'delivery boy Assign Order Deatials',
                        'data' => $output,
                        'itemimg' => $output1
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function showPaymentMode(Request $request)
    {    
         try {

                $payment = DB::table('payment_mode')
                ->select('id','payment_city','payment_mode')
                ->get(); 

                if ($payment) 
                {
                    foreach ($payment as $key => $value) 
                        {

                    $pmode=(explode(",",$value->payment_mode));
                    $mode = count($pmode);

                    
                    for($i = 0; $i < $mode ; $i++)
                    {
                    $roomimggg[] = array('id'=>$value->id,'payment_city'=>$value->payment_city,'payment_mode'=>$pmode[$i]);
                    }    
                    }

                       $response = array(
                        'error' => false,
                        'msg' => 'payment mode deatials',
                        'mode' => $roomimggg,
                     
                    );
                }
            else
            {
                $response = array(
                'error' => false,
                'msg' => 'Payment Mode Not Available',          
                );
            }
            } catch (QueryException $e) {
            }
        return Response::json($response);
    }
    
    public function FeedbackSubmit(Request $request)
    {
        $response = array(
                'error' => true,
                'msg' => 'Please try again.',
                );
        $rules = array(
            'user_id' => 'required',
            'comment'=>'required',
           
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        }
         else
         {
             $user_id = $request->input('user_id');
             $comment = $request->input('comment');

             $dd=Carbon::now();
             $date=$dd->format('y-m-d H:i:s');


         try {
           

                $id = DB::table('feedback')
                ->insertGetId(['user_id'=>$user_id,'comment' => $comment,'date'=>$date]);

                if($id) 
                {     
                    $response = array(
                        'error' => false,
                        'msg' => 'Feedback Added Successfully',
                    );
                }
                else 
                {
                    $response = array(
                        'error' => true,
                        'msg' => 'Some Problem Are Occured',
                    );
                } 
            }
             catch (QueryException $e) {
            }
     }
        return Response::json($response);
    }

public function roundToNextHour() {

//$selectedTime = "9:15:00";

$selectedTime = DB::table('orders')->where('order_no', '272')->value('created_at');

$endTime = strtotime("+3 minutes", strtotime($selectedTime));

    $as =date('H:i:s', $endTime);

}

    public function JweelerycatList(Request $request)
    {            

         try {

                $category = DB::table('jewellery_cat')
                ->select('id','p_id','cat_name')
                ->get(); 

                    $imagefinal = array();

                foreach ($category as $key => $value) {

                        $id = $value->id;
                        $p_id = $value->p_id;
                        $cat_name = $value->cat_name;


                    $imagearray = array();
                    
                    $tmpimgarray =array();

                    $imagearray = DB::table('jewellery_sub_cat')
                    ->select('id','cat_id','sub_cat_name','sub_cat_img')
                    ->where('cat_id','=',$id)
                    ->get();    


                    foreach ($imagearray as $key => $value) {
                        
                            $img = 'http://192.168.2.11/flashexpress/public/images/jewlsubcat/'.$value->sub_cat_img;
                            $id = $value->id;
                            $sub_cat_name = $value->sub_cat_name;
                            $cat_id = $value->cat_id;
                            
                    array_push($tmpimgarray, array("img" => $img,'id'=>$id,'sub_cat_name'=>$sub_cat_name,'cat_id'=>$cat_id));

                    }   


                    $myimg = new Img();
                    
                    $myimg ->id = $id;
                    $myimg ->p_id = $p_id;
                    $myimg ->cat_name = $cat_name;

                    $myimg ->imgpath = $tmpimgarray;


                array_push($imagefinal,$myimg);

                    }
          
                    $response = array(
                        'error' => false,
                        'msg' => 'JweelerycatList category image deatials',
                        'cat_name'=>$imagefinal,
                        );

            } catch (QueryException $e) {
            }
      return Response::json($response);
    }

    public function cancelOrder(Request $request)
    {

        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        
        $rules = array(
            
                 'vendor_id' =>'required',
                 'user_id' => 'required',
                 'user_name' => 'required',
                 'cancel_reason'=> 'required',
                 'cancel_amount' => 'required',
                 'order_id' => 'required',
                
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {

            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields.',
            );
        } else
         {
            $vendor_id = $request->input('vendor_id');   
            $user_id = $request->input('user_id');   
            $user_name = $request->input('user_name');
            $cancel_reason = $request->input('cancel_reason');
            $cancel_amount = $request->input('cancel_amount');     
            $order_id = $request->input('order_id');
            
            
            try {
              
                $data = DB::table('cancel_order')
                    ->insertGetId(['vendor_id'=>$vendor_id,'user_id'=>$user_id,'user_name'=>$user_name,'cancel_reason'=>$cancel_reason,'cancel_amount'=>$cancel_amount,'order_id'=>$order_id]);


            $data = DB::table('order_details')
                            ->where('order_no','=',$order_id)
                            ->update(['status'=>'Canceled']);  
            
            $data = DB::table('orders')
                            ->orWhere('order_uid',$user_id)
                            ->where('order_no','=',$order_id)
                            ->update(['status'=>'Canceled']);  

    $usermob = DB::table('user_regi')->where('id','=',$user_id)->value('mob');
    $usermsg="Your order is canceled successfully";

    $vendormob = DB::table('users')->where('id','=',$vendor_id)->value('mob');
    $vendormsg ="Your Hotel order is Canceled";

    $response = $this->sendSms($vendormob, $vendormsg);        
    $response = $this->sendSms($usermob, $usermsg);    

                    $response = array(
                        'error' => true,
                        'msg' => 'Order Canceled Successfully',        
                    );
                
            } catch (Exception $e) {

            }
        }
        return Response::json($response);
    }


    public function showJwelleryitemList(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        
        $rules = array(
           
            'p_id' => 'required',
            'cat_id'=>'required',
            'sub_cat_id'=>'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {  
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $p_id = $request->input('p_id');
            $cat_id = $request->input('cat_id');
            $sub_cat_id = $request->input('sub_cat_id');
            

            
         try {


                $id = DB::table('jewellery_product')
                ->select('id','p_id','cat_id','sub_cat_id','name','price','image')
                ->where('p_id','=',$p_id)
                ->where('cat_id','=',$cat_id)
                ->where('sub_cat_id','=',$sub_cat_id)
                ->get();    

                $output = array();
                
                
                foreach ($id as $key => $value) {

                    $output[]=array('id'=>$value->id,'p_id'=>$value->p_id,'cat_id'=>$value->cat_id,'sub_cat_id'=>$value->sub_cat_id,'name'=>$value->name,'price'=>$value->price,'image'=>'http://127.0.0.1/flashexpress/public/images/jewlproduct/'.$value->image);
                }
                if ($output) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'Jwellery Item List',
                        'data' => $output,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }


    public function showJwelleryitemListDeatils(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        
        $rules = array(
           
            'item_id' => 'required',
            
        );
        $validator = Validator::make($request->all(), $rules);



        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $item_id = $request->input('item_id');
         
         try {
  

                $data = DB::table('jewellery_product')
                ->select('id','p_id','cat_id','sub_cat_id','name','price','discount_percentage','description','no_of_diamonds','diamond_shape','setting_type','diamond_for','synthetic_stone','sku')
                ->where('id','=',$item_id)
                ->get(); 

                $output=array();
                
                foreach ($data as $key => $value) {

                    $output[]=array('id'=>$value->id,'p_id'=>$value->p_id,'cat_id'=>$value->cat_id,'sub_cat_id'=>$value->sub_cat_id,'name'=>$value->name,'price'=>$value->price,'discount_percentage'=>$value->discount_percentage,'description'=>$value->description,'no_of_diamonds'=>$value->no_of_diamonds,'diamond_shape'=>$value->diamond_shape,'setting_type'=>$value->setting_type,'diamond_for'=>$value->diamond_for,'synthetic_stone'=>$value->synthetic_stone,'sku'=>$value->sku);
                }

                $images = DB::table('jewellery_product')
                ->select('id','gallery')
                ->where('id','=',$item_id)
                ->get(); 

                if ($images) 
                {
                    foreach ($images as $key => $value) 
                        {

                    $roomimg=(explode("|",$value->gallery));
                    $dim = count($roomimg);

                    
                    for($i = 0; $i < $dim ; $i++)
                    {
                    $roomimggg[] = array('id'=>$value->id,'image'=>'http://127.0.0.1/flashexpress/public/images/jewlproduct/'. $roomimg[$i]);
                    }    
                
                        }

                       $response = array(
                        'error' => false,
                        'msg' => 'Item Deatils',
                        'data'=>$output,
                        'image' => $roomimggg,

                     
                    );
                

                }
    else
    {                  $response = array(
                        'error' => false,
                        'msg' => 'Room Record Not Found',
                       
                    );
     }

            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }

    public function showJwelleryRelateditemList(Request $request)
    {
        $response = array(
            'error' => true,
            'msg' => 'Please try again.',
        );
        
        $rules = array(
           
            'sub_cat_id'=>'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {  
            $response = array(
                'error' => true,
                'msg' => 'Please provide all required fields',
            );
        } else {
           
            $sub_cat_id = $request->input('sub_cat_id');
            

            
         try {


                $id = DB::table('jewellery_product')
                ->select('id','p_id','cat_id','sub_cat_id','name','price','image')
                ->where('sub_cat_id','=',$sub_cat_id)
                ->get();    

                $output = array();
                
                
                foreach ($id as $key => $value) {

                    $output[]=array('id'=>$value->id,'p_id'=>$value->p_id,'cat_id'=>$value->cat_id,'sub_cat_id'=>$value->sub_cat_id,'name'=>$value->name,'price'=>$value->price,'image'=>'http://127.0.0.1/flashexpress/public/images/jewlproduct/'.$value->image);
                }
                if ($output) 
                {
                    $response = array(
                        'error' => false,
                        'msg' => 'Jwellery Related Item List',
                        'data' => $output,
                        );
                 }
                 else {
                    $response = array(
                        'error' => true,
                        'msg' => 'Records Not Found'
                    );
                }
            } catch (QueryException $e) {
            }
        }
        return Response::json($response);
    }


}

