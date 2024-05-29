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
                <h4>Reset PIN</h4>
              </div>
              <div class="card-body">
                <form method="POST" action="{{ url('reset-pin') }}" class="needs-validation" novalidate="">
                @csrf

                  <div class="form-group">
                     <div class="d-block">
                      <label for="phone" class="d-block">{{ __('Phone No') }}</label>
                    </div>
                          <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" autocomplete="phone" placeholder="Enter Phone No" autofocus>
                  </div>

                  <div class="form-group">
                     <div class="d-block">
                      <label for="phone" class="d-block">{{ __('Verification Code') }}</label>
                      </div>
                      <div class="input-group mb-2 mr-sm-2">
                        <input type="text" class="form-control" value="{{old('verification_code')}}" name="verification_code" placeholder="Verification Code">
                        <div class="input-group-append">
                          <a href="javascript:void(0)" id="get-code" class="btn btn-primary input-group-text">Get Code</a>
                        </div>
                      </div> 
                  </div>

                  <div class="form-group">
                    <div class="d-block">
                      <label for="pin" class="control-label">New PIN</label>
                    </div>
                    <input id="pin" placeholder="New PIN" type="password" class="form-control @error('pin') is-invalid @enderror" name="pin" tabindex="2" required>
                    @error('pin')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="row mb-0">
                      <div class="col-md-12">
                          <button type="submit" class="btn btn-primary btn-block">
                              {{ __('Submit') }}
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

<script type="text/javascript">
  $(document).ready(function(){


    $(document).on('click', '#get-code', function() {
        var phone = $("#phone").val();
        var domObj=$(this);
        domObj.html(`<div class="spinner-border" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>`);
        domObj.prop('disabled', true);

        $.ajax({
            url: "{{url('send-code')}}",
            type: "POST",
            data: {
                phone: phone,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                if(result.success){
                    success(result.message)
                    countdown(1, 00);
                }else{
                    error(result.message);
                    domObj.html('Get Code');
                    domObj.prop('disabled', false);

                }
            },
            error: function(err) {
                error(err.statusText);
            }
        });
    });



var timeoutHandle;
function countdown(minutes, seconds) {
    function tick() {
        var counter = document.getElementById("get-code");
        counter.innerHTML =
            minutes.toString() + ":" + (seconds < 10 ? "0" : "") + String(seconds);
        seconds--;
        if (seconds >= 0) {
            timeoutHandle = setTimeout(tick, 1000);
        } else {
            if (minutes >= 1) {
                // countdown(mins-1);   never reach “00″ issue solved:Contributed by Victor Streithorst
                setTimeout(function () {
                    countdown(minutes - 1, 59);
                }, 1000);
            }else{
              counter.innerHTML='Get Code';
              $("#get-code").prop('disabled', false);  
            }
        }
    }
    tick();
}



  });
</script>

</html>