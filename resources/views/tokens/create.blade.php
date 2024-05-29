@extends('layouts.template')
@section('title')
Tokens
@endsection
@section('content')
        <section class="section">
          <div class="section-body">
            <form action="{{url('/tokens/store')}}" method="post">
              @csrf  
            <div class="row justify-content-center">  
              <div class="col-8 col-md-8">
                <div class="card">
                  <div class="card-header justify-content-center">
                    <h4>Get New Token</h4>
                  </div>
                  <div class="card-body text-primary-all">
                    <div class="row">
                      <div class="col-6">
                        <p class="m-0"><span class="font-weight-bold">Bill to: </span><br><span class="text-dark">{{auth::user()->name}}</span></p>
                      </div>
                      <div class="col-6 text-right">
                        <p class="m-0"><span class="font-weight-bold">Invoice No:</span><span class="text-dark">{{random_int(100000, 999999)}}</span></p>
                        <p class="m-0"><span class="font-weight-bold">Invoice Date:</span><span class="text-dark">{{now()->format('d/m/Y')}}</span></p>
                      </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                      <div class="col-6">
                        <span class="font-weight-bold">Description</span>
                      </div>
                      <div class="col-6 text-right">
                        <span class="font-weight-bold">Amount</span>
                      </div>
                    </div>
                    <hr class="m-0">
                    <div class="row">
                      <div class="col-6">
                        <span class="text-dark">Token</span>
                      </div>
                      <div class="col-6 text-right">
                        <span class="text-dark">100.00</span>
                      </div>
                      <div class="col-6"></div>
                      <div class="col-6 text-right">
                        <p><span class="font-weight-bold">Total:</span>100.00</p>
                      </div>

                      <p class="col-12 text-center mb-0 text-dark">Pay the above mentiond amount to get a new token</p>
                      <p class="col-12 text-center m-0 text-dark">Pay with below methods</p>
                    </div>

                    <div class="row justify-content-center">
                      <div class="col-5">
                        <button name="payment_method" value="easypaisa" class="btn text-white border btn-block p-0"><img style="width:100%; height: 60px; border-radius: 5px;" src="https://www.kindpng.com/picc/m/732-7320284_easypaisa-icon-hd-png-download.png" alt=""></button>

                        <button name="payment_method" value="jazzcash" class="btn text-white border btn-block p-0 bg-transparent"><img style="width:100%; height: 60px; border-radius: 5px;" src="https://www.jazzcash.com.pk/assets/themes/jazzcash/img/mobilink_logo.png" alt=""></button>

                        <button name="payment_method" value="card" class="btn btn-dark text-white-all text-white btn-block p-2"><i class="fas fa-credit-card fa-xl"></i> Debit or Credit Card</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </div>
            </form>
          </div>
        </section>
@endsection
@section('js')

<script>

</script>




@endsection
