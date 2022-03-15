@extends('layouts.app')

@section('styles')
    {!! htmlScriptTagJsApi() !!}
@endsection

@section('title', 'Login')




@section('content')

@if(!HrAdminSetup())
<div class="container alert alert-warning  font-weight-bold show">
     Welcome , to start using application, first Hr Admin login.
    </div>
    @endif


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">

                                @if(!HrAdminSetup())

                                <input id="email" type="email"
                                           class="form-control" name="fake"
                disabled
                                           value="{{config('app.hr_admin_email')}}" required autocomplete="email"  autofocus>
                                           <input type="hidden" name="email"  value="{{config('app.hr_admin_email')}}"  >


                                @else

                                <input id="email" type="email"
                                           class="form-control" name="email"

                                           value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @endif

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control" name="password"
                                           required autocomplete="current-password">
                                </div>
                            </div>


                            @if(config('app.recaptcha_status'))
                                <div class="form-group row">
                                    <div
                                        class="g-recaptcha form-group"
                                        data-sitekey="{{config('recaptcha.api_site_key')}}">
                                    </div>
                                </div>
                            @endif


                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                   {{-- @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif--}}
                                </div>
                            </div>
                           {{-- <div class="text-center">
                                <a class="btn btn-link" href="{{ route('register') }}">
                                    {{ __('Register') }}
                                </a>
                            </div>--}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
