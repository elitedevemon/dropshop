@extends('auth.layouts.authentication')

@section('content')
  <!-- aiz-main-wrapper -->
  <div class="aiz-main-wrapper d-flex flex-column justify-content-md-center bg-white">
    <section class="overflow-hidden bg-white">
      <div class="row">
        <div class="col-xxl-6 col-xl-9 col-lg-10 col-md-7 py-lg-4 mx-auto">
          <div class="card rounded-0 border-0 shadow-none">
            <div class="row no-gutters">
              <!-- Left Side Image-->
              <div class="col-lg-6">
                <img class="img-fit h-100" src="{{ uploaded_asset(get_setting('seller_register_page_image')) }}"
                  alt="">
              </div>

              <!-- Right Side -->
              <div class="col-lg-6 p-lg-5 d-flex flex-column justify-content-center right-content border p-4"
                style="height: auto;">
                <!-- Site Icon -->
                <div class="size-48px mx-lg-0 mx-auto mb-3">
                  <img class="img-fit h-100" src="{{ uploaded_asset(get_setting('site_icon')) }}"
                    alt="{{ translate('Site Icon') }}">
                </div>

                <!-- Titles -->
                <div class="text-lg-left text-center">
                  <h1 class="fs-20 fs-md-24 fw-700 text-primary" style="text-transform: uppercase;">
                    {{ translate('Register your shop') }}</h1>
                </div>
                <!-- Register form -->
                <div class="pt-lg-4 pt-3">
                  <div class="">
                    <form class="form-default" id="reg-form" role="form" action="{{ route('shops.store') }}"
                      method="POST">
                      @csrf

                      <div class="fs-15 fw-600 pb-2">{{ translate('Personal Info') }}</div>
                      <!-- Name -->
                      <div class="form-group">
                        <label class="fs-12 fw-700 text-soft-dark"
                          for="name">{{ translate('Your Name') }}</label>
                        <input class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}"
                          name="name" type="text" value="{{ old('name') }}"
                          placeholder="{{ translate('Full Name') }}" required>
                        @if ($errors->has('name'))
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                          </span>
                        @endif
                      </div>

                      <div class="form-group">
                        <label>{{ translate('Your Email') }}</label>
                        <input class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}"
                          name="email" type="email" value="{{ old('email') }}"
                          placeholder="{{ translate('Email') }}" required>
                        @if ($errors->has('email'))
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                          </span>
                        @endif
                      </div>

                      <!-- phone number -->
                      <div class="form-group">
                        <label>{{ translate('Your Phone') }}</label>
                        <input class="form-control rounded-0{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                          name="phone" type="text" value="{{ old('phone') }}"
                          placeholder="{{ translate('Phone') }}" required>
                        @if ($errors->has('phone'))
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('phone') }}</strong>
                          </span>
                        @endif
                      </div>

                      <!-- password -->
                      <div class="form-group mb-0">
                        <label class="fs-12 fw-700 text-soft-dark"
                          for="password">{{ translate('Password') }}</label>
                        <div class="position-relative">
                          <input
                            class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}"
                            name="password" type="password" placeholder="{{ translate('Password') }}" required>
                          <i class="password-toggle las la-2x la-eye"></i>
                        </div>
                        <div class="mt-1 text-right">
                          <span
                            class="fs-12 fw-400 text-gray-dark">{{ translate('Password must contain at least 6 digits') }}</span>
                        </div>
                        @if ($errors->has('password'))
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                          </span>
                        @endif
                      </div>

                      <!-- password Confirm -->
                      <div class="form-group">
                        <label class="fs-12 fw-700 text-soft-dark"
                          for="password_confirmation">{{ translate('Confirm Password') }}</label>
                        <div class="position-relative">
                          <input class="form-control rounded-0" name="password_confirmation" type="password"
                            placeholder="{{ translate('Confirm Password') }}" required>
                          <i class="password-toggle las la-2x la-eye"></i>
                        </div>
                      </div>

                      <div class="fs-15 fw-600 py-2">{{ translate('Basic Info') }}</div>

                      <div class="form-group">
                        <label class="fs-12 fw-700 text-soft-dark"
                          for="shop_name">{{ translate('Shop Name') }}</label>
                        <input
                          class="form-control rounded-0{{ $errors->has('shop_name') ? ' is-invalid' : '' }}"
                          name="shop_name" type="text" value="{{ old('shop_name') }}"
                          placeholder="{{ translate('Shop Name') }}" required>
                        @if ($errors->has('shop_name'))
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('shop_name') }}</strong>
                          </span>
                        @endif
                      </div>

                      <div class="form-group">
                        <label class="fs-12 fw-700 text-soft-dark"
                          for="address">{{ translate('Address') }}</label>
                        <input class="form-control rounded-0{{ $errors->has('address') ? ' is-invalid' : '' }}"
                          name="address" type="text" value="{{ old('address') }}"
                          placeholder="{{ translate('Address') }}" required>
                        @if ($errors->has('address'))
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('address') }}</strong>
                          </span>
                        @endif
                      </div>

                      <!-- Recaptcha -->
                      @if (get_setting('google_recaptcha') == 1)
                        <div class="form-group">
                          <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>
                        </div>
                        @if ($errors->has('g-recaptcha-response'))
                          <span class="invalid-feedback" role="alert" style="display: block;">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                          </span>
                        @endif
                      @endif

                      <!-- Submit Button -->
                      <div class="mb-4 mt-4">
                        <button class="btn btn-primary btn-block fw-600 rounded-0"
                          type="submit">{{ translate('Register Your Shop') }}</button>
                      </div>
                    </form>
                  </div>
                  <!-- Log In -->
                  <p class="fs-12 text-gray mb-0">
                    {{ translate('Already have an account?') }}
                    <a class="fs-14 fw-700 animate-underline-primary ml-2"
                      href="{{ route('seller.login') }}">{{ translate('Log In') }}</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <!-- Go Back -->
          <div class="mr-md-0 mr-4 mt-3">
            <a class="fs-14 fw-700 d-flex align-items-center text-primary ml-auto"
              href="{{ url()->previous() }}" style="max-width: fit-content;">
              <i class="las la-arrow-left fs-20 mr-1"></i>
              {{ translate('Back to Previous Page') }}
            </a>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection

@section('script')
  @if (get_setting('google_recaptcha') == 1)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  @endif

  <script type="text/javascript">
    @if (get_setting('google_recaptcha') == 1)
      // making the CAPTCHA  a required field for form submission
      $(document).ready(function() {
        $("#reg-form").on("submit", function(evt) {
          var response = grecaptcha.getResponse();
          if (response.length == 0) {
            //reCaptcha not verified
            alert("please verify you are human!");
            evt.preventDefault();
            return false;
          }
          //captcha verified
          //do the rest of your validations here
          $("#reg-form").submit();
        });
      });
    @endif
  </script>
@endsection
