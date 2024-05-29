<?php

function initPayment()
{
	$url='https://pakistan.paymob.com/api/auth/tokens';
	$data['api_key']="ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TVRFeU9UTXpMQ0p1WVcxbElqb2lNVFk1T0RBM05qa3hOaTQxTXpJek56a2lmUS4zWXRkeFdKY1ZyMDI0UFhBbFRNQVg2VEVkWm5HQ0p5cEJIYVVlRFBJZWpxLVZjWUtwaGV6M28wRVRXd0wyQ2k1UzZ0MUZBQ2lRNmRRT0RoTDM0SnNIZw==";
	$headers=[];
	
	$response=Http::withHeaders($headers)->post($url, $data);

	$res=['success'=>$response->successful(), 'data'=>$response->body()];

	return (object) $res;
}

function initOrder()
{
	$url='https://pakistan.paymob.com/api/ecommerce/orders';
	$data=[
		'auth_token'=>null,
		'delivery_needed'=>false,
		'amount_cents'=>100,
		'currency'=>'PKR'
	];
	$headers=[];

	$init_payment=initPayment();

	if(!$init_payment->success){
		$res=['success'=>false, 'data'=>'Payment gateway not initiated'];
		return (object) $res;
	}

	$init_payment_resp=json_decode($init_payment->data);
	$auth_token=$init_payment_resp->token;
	
	$data['auth_token']=$auth_token;

	$response=Http::withHeaders($headers)->post($url, $data);
	$rsp=[];
	if($response->successful()){
		$rsp= json_decode($response->body());
		$rsp->auth_token=$auth_token;
		$rsp=json_encode($rsp);
	}else{
		$rsp=$response->body();
	}

	$res=['success'=>$response->successful(), 'data'=>$rsp];

	return (object) $res;
}


function getPaymentKey($auth_token, $integration_id)
{
	$url='https://pakistan.paymob.com/api/acceptance/payment_keys';

	$data = [
	   "auth_token" => $auth_token, 
	   "amount_cents" => "100", 
	   "expiration" => 3600, 
	   "order_id" => "16254777", 
	   "billing_data" => [
	         "apartment" => "803", 
	         "email" => "claudette09@exa.com", 
	         "floor" => "42", 
	         "first_name" => "Clifford", 
	         "street" => "Ethan Land", 
	         "building" => "8028", 
	         "phone_number" => "+86(8)9135210487", 
	         "shipping_method" => "PKG", 
	         "postal_code" => "01898", 
	         "city" => "Jaskolskiburgh", 
	         "country" => "CR", 
	         "last_name" => "Nicolas", 
	         "state" => "Utah" 
	      ], 
	   "currency" => "PKR", 
	   "integration_id" => $integration_id, 
	   "lock_order_when_paid" => "false" 
	]; 
 
	$headers=[];

	$response=Http::withHeaders($headers)->post($url, $data);

	$res=['success'=>$response->successful(), 'data'=>$response->body()];

	return (object) $res;
}