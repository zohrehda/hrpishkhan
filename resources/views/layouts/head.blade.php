<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"  ></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" ></script>

    <script src="{{asset('MD.BootstrapPersianDateTimePicker/dist/jquery.md.bootstrap.datetimepicker.js')}}"></script>

    {{-- <script src="{{ asset('js/app.js') }}" defer></script>
     <script src="{{ asset('js/jquery-2.2.4.min.js') }}"></script>--}}
{{--
    <script src="{{ asset('js/select2.min.js') }}" defer></script>
--}}
    <script src="{{ asset('js/panel.js') }}"></script>
    <script src="{{ asset('js/functions.js') }}"></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
   {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/panel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/side-panel.css') }}" rel="stylesheet">
{{--
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"/>
--}}
    <link href="{{ asset('css/style-fr.css') }}" rel="stylesheet"/>
    <link href="{{asset('MD.BootstrapPersianDateTimePicker/dist/jquery.md.bootstrap.datetimepicker.style.css')}}" rel="stylesheet"/>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>




    <script src="{{ asset('js/main-fr.js') }}"></script>

    @yield('styles')
</head>
