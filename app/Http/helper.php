<?php
use App\Models\AddressesAndTowns;
use App\Models\Countries;
use App\Models\Settings;
use App\Models\Tokens;
use App\Models\States;
use App\Models\Cities;
use App\Models\UnionCouncils;

function FileUpload($file, $path){
	if($file==null){return null;}
	 $imgname=$file->getClientOriginalName();
	  if($file->move($path,$imgname)){
	  	return $imgname;
	  }
	  else{
	  	return null;
	  }
}


function Settings()
{
	return Settings::first();
}

function Country($id)
{
	$country=Countries::find($id);
	if ($country!=null) {
		return $country->name;
	}
}


function City($id)
{
	$cities=Cities::find($id);
	if ($cities!=null) {
		return $cities->name;
	}
}
 
function UnionCouncil($id)
{
	$uniCoucil=UnionCouncils::find($id);
	if ($uniCoucil!=null) {
		return $uniCoucil->name;
	}
}

function State($id)
{
	$states=States::find($id);
	if ($states!=null) {
		return $states->name;
	}
}

function AllCoutries()
{
    $Countries=Countries::where('id',167)->get();
    return $Countries;
}

function AllStates($country_id=null)
{	if($country_id==null){
	    $states=States::all();
	}else{
		$states=States::where('country_id',$country_id)->get();
	}
    return $states;
}

function AllCities($state_id=null)
{
    $cities=Cities::where('state_id', $state_id)->get();
    return $cities;
}


function BloodGroups()
{
	$groups=[
			'A+'=>'A+',
			'A-'=>'A-',
			'B+'=>'B+',
			'B-'=>'B-',
			'O+'=>'O+',
			'O-'=>'O-',
			'AB+'=>'AB+',
			'AB-'=>'AB-',
	];

	return $groups;
}


function GenerateVerificationCode(){
	return substr(number_format(time() * rand(),0,'',''),0,6);
}


function sendMsg($phn, $msg)
{
    $response=['success'=>true, 'message'=>null];
    
    $api=Settings()->sms_api;
	$key=Settings()->sms_api_secret;

	if($api==null || $api=="" || $key==null || $key==""){
    	$response=['success'=>false, 'message'=>"sms notifications are disabled"];
    	return (object) $response;
	}

	$sender='8583';

	$url = $api."?hash=".$key."&receivernum=".$phn."&sendernum=".$sender."&textmessage=".$msg;

    $res= Http::timeout(60)->withOptions(['verify' => false])->get($url);
    $body=json_decode($res->body());

    if($res->successful()){

        if($body->STATUS=="ERROR"){
        $response=['success'=>false, 'message'=>$body->ERROR_DESCRIPTION];
        }
        else{
            $response=['success'=>true, 'message'=>$body->ERROR_DESCRIPTION];
        }
    }
    else{
        $response=['success'=>false, 'message'=>"Something went wrong"];
    }

    return (object) $response;

}


function GenerateToken()
{
	$bytes = random_bytes(8);
	$key= bin2hex($bytes);
	if(Tokens::where('token', $key)->count()>0){
		GenerateToken();
	}
	return $key;
}
