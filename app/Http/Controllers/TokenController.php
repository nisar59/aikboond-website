<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Tokens;
use Http;
use Auth;

class TokenController extends Controller
{

    public function index()
    {

    if (request()->ajax()) {
        $role=Tokens::orderBy('id','DESC')->get();

            return DataTables::of($role)
                ->addColumn('status', function ($row) {
                    $expiry=Carbon::parse($row->expiry)->isSameDay(today());

                    if($row->payment_status==0 && $expiry){
                        return '<a class="btn btn-info" href="javascript:void()">Activation Pending</a>';
                    }elseif($expiry){
                        return '<a class="btn btn-success" href="javascript:void()">Active</a>';
                    }else{
                        return '<a class="btn btn-warning" href="javascript:void()">Expired</a>';
                    }
                })
                ->editColumn('expiry', function ($row) {
                    return Carbon::parse($row->expiry)->format('d-m-Y');
                })
                ->addColumn('action', function ($row) {
                    $expiry=Carbon::parse($row->expiry)->isSameDay(today());
                    if($row->payment_status==0 && $expiry){
                        return '<a class="btn btn-primary" href="'.url('tokens/activate', $row->id).'">Activate</a>';
                    }
                })

                ->removeColumn('id')
                ->rawColumns(['status', 'action'])
                ->make(true);
    }


        return view('tokens.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('tokens.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $req)
    {

        $req->validate([
            'payment_method' => 'required',
        ]);

            $integration_id=126322;
        if($req->payment_method=="easypaisa"){
            $integration_id=127330;
        }
        if($req->payment_method=="jazzcash"){
             $integration_id=127333;
        }



        $inputs=[];
        $inputs['user_id']=Auth::user()->id;
        $inputs['token']=GenerateToken();
        $inputs['last_used']=null;
        $inputs['expiry']=now()->format('Y-m-d');
        $inputs['payment_method']=$req->payment_method;
        $inputs['payment_status']=0;

        $order=initOrder();
        if(!$order->success){
            return redirect()->back()->with('error','Something went wrong, payment gateway not initiated.');
        }
        $order_rsp=json_decode($order->data);
        $payment_init=getPaymentKey($order_rsp->auth_token, $integration_id);
        if(!$payment_init->success){
            return redirect()->back()->with('error','Something went wrong, payment gateway not initiated.');
        }
        $inputs['logs']=$payment_init->data;
        Tokens::create($inputs);

        $token=json_decode($payment_init->data)->token;

        return redirect()->away('https://pakistan.paymob.com/iframe/'.$token);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function activate($id)
    {
       $token=Tokens::find($id);
       if($token==null){
            return redirect()->back()->with('error','Something went wrong, token not found.');
       }
       if($token->payment_status!=0){
            return redirect()->back()->with('warning','Something went wrong, token can not activated.');
       }
       $token=json_decode($token->logs)->token;
        return redirect()->away('https://pakistan.paymob.com/iframe/'.$token);


    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $this->data['role'] = Role::find($id);
        $this->data['permission'] = Permission::get();
        $this->data['rolepermissions'] = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('roles::edit')->withData($this->data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required',
            'permissions' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('role');
        $role->save();
        $role->syncPermissions($request->input('permissions'));
        return redirect('/roles')->with('success','Role successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $role = Role::find($id);
      $role->syncPermissions([]);
      Role::find($id)->delete();
    return redirect('/roles')->with('success','Role successfully deleted');
    }

}
