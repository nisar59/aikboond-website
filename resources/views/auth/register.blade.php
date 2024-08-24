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
    <marquee onmouseover="stop()" onmouseout="start()" style="background: #e51c25;color: white;font-size: 20px;" width="100%" direction="right">
        @include('auth.message')
    </marquee>
    <section class="section">
      <div class="container mt-2">
        <div class="row">
          <div class="col-md-12 text-center">
            <img src="{{url('/img/settings/'.Settings()->portal_favicon)}}" width="50" height="50" class="m-2">
            <h2>{{Settings()->portal_name}}</h2>
          </div>
          <div class="col-md-6 offset-md-3">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Register</h4>
              </div>
              <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-3 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-9">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Enter Name" autofocus>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="dob" class="col-md-3 col-form-label text-md-end">{{ __('DOB') }}</label>

                            <div class="col-md-9">
                                <input id="dob" type="date" class="form-control" name="dob" value="{{ old('dob') }}" autocomplete="dob">
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="phone" class="col-md-3 col-form-label text-md-end">{{ __('Phone No') }}</label>

                            <div class="col-md-9">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" autocomplete="phone" placeholder="Enter Phone No" autofocus>
                            </div>
                        </div>




                        <div class="row mb-3">
                            <label for="phone" class="col-md-3 col-form-label text-md-end">{{ __('Verification Code') }}</label>
                            <div class="col-md-9">
                                <div class="input-group mb-2 mr-sm-2">
                                  <input type="text" class="form-control" value="{{old('verification_code')}}" name="verification_code" placeholder="Verification Code">
                                  <div class="input-group-append">
                                    <a href="javascript:void(0)" id="get-code" class="btn btn-primary input-group-text">Get Code</a>
                                  </div>
                                </div> 
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="phone" class="col-md-3 col-form-label text-md-end">{{ __('Blood Group') }}</label>

                            <div class="col-md-9">
                              <select name="blood_group" class="form-control">
                                <option value="">-- Select Blood Group --</option>
                                @foreach(BloodGroups() as $key=> $bg)
                                  <option @if(old('blood_group')==$key) selected @endif value="{{$key}}">{{$bg}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>




                        <div class="row mb-3">
                            <label for="phone" class="col-md-3 col-form-label text-md-end">{{ __('Last Donated Date') }}</label>
                            <div class="col-md-9">
                              <input type="date" value="{{old('last_donate_date')}}" class="form-control" name="last_donate_date" placeholder="Enter Last Donate Date">
                            </div>
                        </div>


                        <input type="text" hidden name="country_id" value="167">
                        <div class="row mb-3">
                            <label for="phone" class="col-md-3 col-form-label text-md-end">{{ __('States') }}</label>
                            <div class="col-md-9">
                              <select name="state_id" id="state-dropdown" class="form-control select2">
                                <option value="">-- Select State --</option>
                                @foreach($states as $state)
                                <option @if(old('state_id')==$state->id) selected @endif value="{{$state->id}}">{{$state->name}}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label for="phone" class="col-md-3 col-form-label text-md-end">{{ __('City') }}</label>
                            <div class="col-md-9">
                            <select id="city-dropdown" class="form-control select2" name="city_id">
                              <option value="">-- Select City --</option>
                            </select>
                            </div>
                        </div>




                        <div class="row mb-3">
                            <label for="phone" class="col-md-3 col-form-label text-md-end">{{ __('Union Councils Name') }}</label>
                            <div class="col-md-9">
                            <select id="area-dropdown" class="form-control select2" name="ucouncil_id">
                              <option value="">-- Select Union Councils Name --</option>
                            </select>
                                <span>
                                    @include('auth.message')
                                </span>
                            </div>
                        </div>


                        

                        <div class="row mb-3">
                            <label for="phone" class="col-md-3 col-form-label text-md-end">{{ __('Address') }}</label>
                            <div class="col-md-9">
                              <input type="text" value="{{old('address')}}" class="form-control" name="address" placeholder="Enter Address">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                            <div class="col-md-6 offset-md-3">
                               Already have an Account? <a href="{{url('login')}}">Login</a>
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
  $(document).ready(function() {
    setTimeout(function() {
        $("#state-dropdown").trigger('change');
    }, 500);
    // $(document).on('change','#country-dropdown', function() {
    //     var idCountry = this.value;
    //     $("#state-dropdown").html('');
    //     $.ajax({
    //         url: "{{url('states')}}",
    //         type: "POST",
    //         data: {
    //             country_id: idCountry,
    //             _token: '{{csrf_token()}}'
    //         },
    //         dataType: 'json',
    //         success: function(result) {
    //             console.log(result);
    //             $('#state-dropdown').html('<option value="">-- Select State --</option>');
    //             $.each(result.states, function(key, value) {
    //                 var selected=("{{old('state_id')}}"==value.id) ? 'selected' : '';

    //                 $("#state-dropdown").append('<option '+selected+' value="' + value
    //                     .id + '">' + value.name + '</option>');
    //             });
    //             $('#city-dropdown').html('<option value="">-- Select City --</option>');
    //         },
    //         error: function(err) {
    //             error(err.statusText);
    //         }
    //     });
    // });
    /*------------------city listing----------------*/
    $(document).on('change','#state-dropdown', function() {
        var idState = this.value;
        $("#city-dropdown").html('');
        $.ajax({
            url: "{{url('cities')}}",
            type: "POST",
            data: {
                state_id: idState,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(res) {
                $('#city-dropdown').html('<option value="">-- Select City --</option>');
                $.each(res.cities, function(key, value) {
                    var selected='';
                    if(value.id=="{{old('city_id')}}"){
                        selected='selected';
                    }
                    $("#city-dropdown").append('<option '+selected+' value="' + value
                        .id + '">' + value.name + '</option>');
                });
                setTimeout(function () {
                 $("#city-dropdown").trigger('change');
                }, 500)
            },
            error: function(err) {
                error(err.statusText);
            }
        });
        $('#area-dropdown').html('<option value="">-- Select Union Councils Name --</option>');
    });
    /*-----------------union-council listing-----------*/
    $(document).on('change','#city-dropdown', function() {
        var city_id = this.value;
        $("#area-dropdown").html('');
        $.ajax({
            url: "{{url('union-council')}}",
            type: "POST",
            data: {
                city_id: city_id,
                _token: '{{csrf_token()}}'
            },
            dataType: 'json',
            success: function(result) {
                $('#area-dropdown').html('<option value="">Select Union Council Name</option>');
                $.each(result.unioncouncil, function(key, value) {
                    var selected='';
                    if(value.id=="{{old('city_id')}}"){
                        selected='selected';
                    }
                    $("#area-dropdown").append('<option '+selected+' value="' + value.id + '">' + value.name + '</option>');
                });

                setTimeout(function () {
                    $("#area-dropdown").trigger('change');
                },500)
            },
            error: function(err) {
                error(err.statusText);
            }
        });
     });
    


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


</body>


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
</html>