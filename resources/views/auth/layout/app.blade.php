<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}} | Log in</title>
    @include('auth.layout.partials.css-links') 
</head>
<body class="hold-transition login-page" style="background-color: #f7f7f7 !important;">
    <div class="login-box">
        <div class="login-logo">
            {{-- <a href="{{url('/')}}"><b>{{env('APP_NAME')}}</b></a> --}}
            <img src="{{ url('/logo/psca-logo.png') }}" width="50%">
        </div>
        <!-- /.login-logo -->
        <div class="card">
        @yield('content')
        </div>
    </div>
    <!-- /.login-box -->
    @include('auth.layout.partials.js-links')
</body>
</html>
