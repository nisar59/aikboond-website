<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AddressesAndTowns;
use App\Models\User as Donor;
use App\Models\States;
use Carbon\Carbon;
use Throwable;
use Auth;
use DB;
class DonorsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       $req=request();
         if ($req->ajax()) {

            $req->validate([
                'blood_group'=>'required',
                'city_id'=>'required',
                'area_id'=>'required',
                'town_id'=>'required',
            ]);

            $strt=$req->start;
            $length=$req->length;

            $donors=Donor::query();

             if ($req->blood_group != null) {
                $donors->where('blood_group', $req->blood_group);
            }

             if ($req->state_id != null) {
                $donors->where('state_id', $req->state_id);
            }
             if ($req->city_id != null) {
                $donors->where('city_id', $req->city_id);
            }
            if ($req->area_id != null) {
                $donors->where('area_id', $req->area_id);
            }
            if ($req->town_id != null) {
                $donors->where('town_id', $req->town_id);
            } 

            $total=$donors->count();

           $donors=$donors->limit(5)->get();

           return DataTables::of($donors)
           ->setOffset($strt)
           ->with([
                'recordsTotal'=>$total,
                'recordsFiltered'=>$total
           ])
            ->editColumn('name', function ($row) {
                $len=strlen($row->name);
                $str='';
                for ($i=1; $i <$len-4 ; $i++) { 
                   $str.='*';
                }

                return substr($row->name, 0, 2).$str.substr($row->name, -2);
            }) 

            ->editColumn('phone', function ($row) {

                return substr($row->phone, 0, 2) . '********'.substr($row->phone, -2);
            }) 

            ->editColumn('dob', function ($row) {
                return '**' . ' Years';
            })

            ->editColumn('state_id', function ($row) {
                if($row->state()->exists()){
                    return $row->state->name;
                }
            })

            ->editColumn('city_id', function ($row) {
                if($row->city()->exists()){
                    return $row->city->name;
                }
            })

            ->editColumn('area_id', function ($row) {
                if($row->area()->exists()){
                    return $row->area->name;
                }
            })

            ->editColumn('town_id', function ($row) {
                if($row->town()->exists()){
                    return $row->town->name;
                }
            })
           ->editColumn('last_donate_date',function($row)
             {
                 return Carbon::parse($row->last_donate_date)->format('d-m-Y');
             })
           ->rawColumns([])
           ->make(true);
        }
         $states=States::where('country_id',167)->get();
        return view('donors.index',compact('states'));
    }



    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $donor=Donor::find($id);
        $states=States::where('country_id',167)->get();
        return view('donors.edit',compact('donor','states'));
    }



    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $req, $id)
    {
        $req->validate([
            'name'=>'required',
            'phone'=>'required|min:11|max:12|unique:donors,phone,'.$id,
            'dob'=>'required',
            'blood_group'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'area_id'=>'required',
            'town_id'=>'required',
            'address'=>'required',
        ]);
            DB::beginTransaction();
         try{


            $phone=$req->phone;
            $phone=preg_replace('/[^0-9]/', "", $phone);

            $start_with_0=str_starts_with($phone, '03');

            if($start_with_0){
                $phone = substr_replace($phone,'92',0,1);
            }


            $donor=Donor::find($id);

            if($donor==null){
                return redirect()->back()->withInput()->with('error', 'Something went wrong, donor not found');
            }

            if($phone!=$donor->phone){
                if($req->verification_code==null){
                    return redirect()->back()->withInput()->with('error', 'you have changed donor phone no, please verify by providing verification code');
                }
                $check_verification=VerificationMsgs::where(['phone'=>$phone, 'code'=>$req->verification_code])->first();
                if($check_verification==null){
                    return redirect()->back()->withInput()->with('error', 'Wrong verification code');
                }
            }

            $min_age=Settings()->mini_age;
            $max_age=Settings()->max_age;

            $age= Carbon::parse($req->dob)->age;

            if($age < $min_age || $age > $max_age){
                return redirect()->back()->withInput()->with('error', 'Donor is '.$age.' years old, donor should not younger than :'.$min_age.' years and elder than:'.$max_age.' years');
            }

            $inputs=$req->except('_token','verification_code', 'image', 'phone');
            $path=public_path('img/donors');

            if($req->image!=null){
                $inputs['image']=FileUpload($req->image, $path);
            }

            $inputs['phone']=$phone;

            $donor->update($inputs);

            DB::commit();
            return redirect('/')->with('success','Your profile is updated sccessfully');
         }catch(Exception $ex){
            DB::rollback();
         return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }catch(Throwable $ex){
            DB::rollback();
        return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }

    }



}
