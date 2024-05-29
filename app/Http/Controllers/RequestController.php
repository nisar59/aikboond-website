<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Requests;
use App\Models\States;
use Carbon\Carbon;
use Throwable;
use Auth;
use DB;
class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       $req=request();
         if ($req->ajax()) {

            $strt=$req->start;
            $length=$req->length;

            $requests=Requests::where('user_id', Auth::user()->id);

            $total=$requests->count();

           $requests=$requests->get();

           return DataTables::of($requests)
           ->setOffset($strt)
           ->with([
                'recordsTotal'=>$total,
                'recordsFiltered'=>$total
           ])

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
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y');
            })

            ->editColumn('status', function ($row) {
                $status='';

                if($row->status==0){
                    $status='<span class="btn btn-sm btn-warning">Pending</span>';
                }elseif($row->status==1){
                    $status='<span class="btn btn-sm btn-success">Completed</span>';
                }else{
                    $status='<span class="btn btn-sm btn-danger">Rejected</span>';
                }

                return $status;

            })

           ->rawColumns(['status'])
           ->make(true);
        }
         $states=States::where('country_id',167)->get();
        return view('requests.index',compact('states'));
    }





    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function create()
    {
        $states=States::where('country_id',167)->get();
        return view('requests.create',compact('states'));
    }



    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store(Request $req)
    {
        $req->validate([
            'blood_group'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'area_id'=>'required',
            'town_id'=>'required',
            'payment_screenshot'=>'required'
        ]);
            DB::beginTransaction();
         try{


            $inputs=$req->except('_token','payment_screenshot');
            $path=public_path('img/requests');

            if($req->payment_screenshot!=null){
                $inputs['payment_screenshot']=FileUpload($req->payment_screenshot, $path);
            }
            $inputs['user_id']=Auth::user()->id;
            Requests::create($inputs);

            DB::commit();
            return redirect('/')->with('success','Your Request submitted successfully, our representative will contact you soon.');
         }catch(Exception $ex){
            DB::rollback();
         return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }catch(Throwable $ex){
            DB::rollback();
        return redirect()->back()->with('error','Something went wrong with this error: '.$ex->getMessage());
        }

    }





}
