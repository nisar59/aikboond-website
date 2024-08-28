<?php

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$servername = "localhost";
$username = "bunqndyg_aikboond";
$password = "bunqndyg_aikboond";
$db = "bunqndyg_aikboond";
// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if (!$conn->connect_error) {
 
}
$ip=get_client_ip();
$req=$_REQUEST;
$extra_info=json_encode($req);
$page='login';
$dt=date("Y-m-d H:i:s");
$day=date("Y-m-d");

$sele = "SELECT * FROM visitors WHERE ip_address='{$ip}' and DATE(created_at)='{$day}' and page='{$page}'";
$result = $conn->query($sele);

if($result->num_rows<1){
    $sql = "INSERT INTO visitors (ip_address, page,extra_info, created_at, updated_at)
    VALUES ('{$ip}', '{$page}','{$extra_info}', '{$dt}', '{$dt}')";
    $conn->query($sql);
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{Settings()->portal_name}}</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{asset('assets/css/app.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/bundles/bootstrap-social/bootstrap-social.css')}}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
  <link rel="stylesheet" href="{{asset('assets/bundles/izitoast/css/iziToast.min.css')}}">
  <link rel='shortcut icon' type='image/x-icon' href="{{url('/img/settings/'.Settings()->portal_favicon)}}" />
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-md-12 text-center">
            <img src="{{url('/img/settings/'.Settings()->portal_favicon)}}" width="50" height="50" class="m-2">
            <h2>{{Settings()->portal_name}}</h2>
          </div>
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Login</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
                @csrf
                  <div class="form-group mb-2">
                    <label for="phone">Phone No</label>
                    <input id="phone" type="phone" placeholder="Phone No" class="form-control @error('phone') is-invalid @enderror" name="phone" tabindex="1" required autofocus  value="{{ old('phone') }}">
                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <div class="d-block">
                      <label for="pin" class="control-label">PIN</label>
                    </div>
                    <input id="pin" placeholder="PIN" type="password" class="form-control @error('pin') is-invalid @enderror" name="pin" tabindex="2" required>
                    @error('pin')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <a class="float-right" href="{{url('reset-pin')}}">Forgot pin?</a>
                  </div>

                  <div class="row mb-0">
                      <div class="col-md-12">
                          <button type="submit" class="btn btn-primary btn-block">
                              {{ __('Login') }}
                          </button>
                      </div>
                      <div class="col-md-12 text-center">
                         Don't have an Account? <a href="{{url('register')}}">Register</a>
                         
                      </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- General JS Scripts -->
  <script src="{{asset('assets/js/app.min.js')}}"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="{{asset('assets/scripts.js')}}"></script>
  <!-- Custom JS File -->
  <script src="{{asset('assets/js/custom.js')}}"></script>
  <script src="{{asset('assets/bundles/izitoast/js/iziToast.min.js')}}"></script>
  <script src="{{asset('assets/functions.js')}}"></script>

<script type="text/javascript">
@if (count($errors) > 0)
      @foreach ($errors->all() as $error)
            error("{{$error}}", 'Input error');
      @endforeach
@elseif (Session::has('warning'))
      warning("{{ Session::get('warning') }}");
@elseif (Session::has('success'))
      success("{{ Session::get('success') }}");
@elseif (Session::has('error'))
      error("{{ Session::get('error') }}");
@elseif (Session::has('info'))
      info(`{{ Session::get('info') }}`);
@elseif (isset($warning))
      warning("{{ $warning }}");
@elseif (isset($success))
      success("{{ $success }}");
@elseif (isset($error))
      error("{{ $error }}");
@elseif (isset($info))
      info("{{ $info }}");
@else
@endif
</script>
</html>