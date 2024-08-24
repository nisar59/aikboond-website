<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\VerificationMsgs;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\States;
use Auth;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    { 
        $this->middleware('guest');       
        $states=States::where('country_id',167)->get();
        return view('auth.register',compact('states'));
    }




    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'=>'required',
            'phone'=>'required|min:11|max:11|unique:donors,phone',
            'verification_code'=>'required | min:6 | max:6',
            'dob'=>'required',
            'blood_group'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'ucouncil_id'=>'required',
            'address'=>'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {      $this->middleware('guest');
            $validator = Validator::make([], []);

            $req= (object) $data;
            $unioncouncil=$req->ucouncil_id;
            $phone=$req->phone;
            $phone=preg_replace('/[^0-9]/', "", $phone);

            $start_with_0=str_starts_with($phone, '03');

            if($start_with_0){
                $phone = substr_replace($phone,'92',0,1);
            }


            $check_verification=VerificationMsgs::where(['phone'=>$phone, 'code'=>$req->verification_code])->first();

            if($check_verification==null){
                $validator->errors()->add('verification_code', 'Wrong verification code');
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            $min_age=Settings()->mini_age;
            $max_age=Settings()->max_age;

            $age= Carbon::parse($req->dob)->age;

            if($age < $min_age || $age > $max_age){
                $validator->errors()->add('dob', 'Donor should not younger than :'.$min_age.' years and elder than:'.$max_age.' years');
                throw new \Illuminate\Validation\ValidationException($validator);
            }

            $inputs=(array) $req;
            $inputs['ucouncil_id']=$unioncouncil;
            $inputs['user_id']=0;
            $inputs['phone']=$phone;

        return User::create($inputs);
    }





    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showSetPinForm()
    {   
        if(!Auth::check()){
            return redirect('login');
        }

        return view('auth.set-pin');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showResetPinForm()
    {
        return view('auth.reset-pin');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function setPin(Request $req)
    {   
        if(!Auth::check()){
            return redirect('login');
        }

        $req->validate([
            'pin'=>'required|min:4|max:6'
        ]);

        Auth::user()->update(['pin'=>$req->pin]);

        return redirect('home');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function resetPin(Request $req)
    {   
        $this->middleware('guest');

        $req->validate([
            'pin'=>'required|min:4|max:6',
            'phone'=>'required|min:11|max:11',
            'verification_code'=>'required | min:6 | max:6',
        ]);

        $validator = Validator::make([], []);

        $phone=$req->phone;
        $phone=preg_replace('/[^0-9]/', "", $phone);

        $start_with_0=str_starts_with($phone, '03');

        if($start_with_0){
            $phone = substr_replace($phone,'92',0,1);
        }

        $user=User::where(['phone'=>$phone]);

        if($user->count()<1){
            return redirect()->back()->with('error', 'There is no user registered against this phone no.');
        }

        $check_verification=VerificationMsgs::where(['phone'=>$phone, 'code'=>$req->verification_code])->first();

        if($check_verification==null){
            $validator->errors()->add('verification_code', 'Wrong verification code');
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        User::where(['phone'=>$phone])->update(['pin'=>$req->pin]);

        return redirect('login')->with('success', 'PIN successfully updated, login now.');
    }



}
