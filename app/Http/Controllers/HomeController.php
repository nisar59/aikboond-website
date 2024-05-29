<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Countries;
use App\Models\States;
use App\Models\AddressesAndTowns;
use App\Models\Cities;
use App\Models\Areas;
use App\Models\VerificationMsgs;
use App\Models\User as Donor;
use Throwable;
use Artisan;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $user=Auth::user();

        $donors=Donor::query();

       $donors=$donors->count();


        return view('home', compact('donors'));
    }




    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function fetchStates(Request $req){
         try {
        $data['states'] =States::where("country_id", $req->country_id)->get(["name", "id"]);                          
        return response()->json($data);
        }
        catch(Exception $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }catch(Throwable $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }


    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function fetchCity(Request $req){
        try {
        $data['cities'] =Cities::where("state_id", $req->state_id)->get(["name", "id"]);                          
        return response()->json($data);
        }
        catch(Exception $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }catch(Throwable $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }
        
    }
        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function fetchAreas(Request $req) {
        try{
        $data['areas'] =Areas::where("city_id", $req->city_id)->get(["name", "id"]);                          
        return response()->json($data);
    }
         catch(Exception $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }catch(Throwable $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }
        }

           /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function fetchAdress(Request $req){
        try{
        $data['address'] =AddressesAndTowns::where("area_id", $req->area_id)->get(["name", "id"]);                          
        return response()->json($data);
    }
         catch(Exception $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }catch(Throwable $ex){
            $res=['success'=>false, 'error'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }
}




    public function verificationCode(Request $req)
    {
        $res=['success'=>false, 'errors'=>[], 'message'=>null, 'data'=>null];
        
         try{
            if($req->phone==null){
                $res=['success'=>false, 'errors'=>[], 'message'=>'Please enter phone no or correct it (i.e 03005566778).', 'data'=>null];
                return response()->json($res);
            }

            if(strlen($req->phone)!=11){
                $res=['success'=>false, 'errors'=>[], 'message'=>'Please enter 11 digit phone no (i.e 03005566778).', 'data'=>null];
                return response()->json($res);
            }

            $phone=$req->phone;
            $phone=preg_replace('/[^0-9]/', "", $phone);

            $start_with_0=str_starts_with($phone, '03');

            if($start_with_0){
                $phone = substr_replace($phone,'92',0,1);
            }

            $code=GenerateVerificationCode();
            $msg="Your aikboond verification code is: ".$code. ", don't share this code with anyone.";
            $msg_rsp=sendMsg($phone, $msg);


            if($msg_rsp->success){
                VerificationMsgs::create([
                    'phone'=>$phone,
                    'code'=>$code
                ]);
                $res=['success'=>true, 'errors'=>[], 'message'=>'Verification code sent successfully to your phone.', 'data'=>null];
                return response()->json($res);
            }else{
                $res=['success'=>false, 'errors'=>[], 'message'=>$msg_rsp->message, 'data'=>null];
                return response()->json($res);
            }

            $res=['success'=>false, 'errors'=>[], 'message'=>'Something went wrong, try again', 'data'=>null];
            return response()->json($res);


         }catch(Exception $ex){
            $res=['success'=>false, 'message'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }catch(Throwable $ex){
            $res=['success'=>false, 'message'=>'Something went wrong with this error: '.$ex->getMessage(), 'data'=>null];
             return response()->json($res);
        }
    }



}
