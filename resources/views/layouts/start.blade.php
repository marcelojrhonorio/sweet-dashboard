<!DOCTYPE html>
<html lang='pt-BR'>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">

    <title>Sweet | @yield('title')</title>

    @include('layouts.includes.styles.login')

</head>


<body class="gray-bg">

<div class="loginColumns animated fadeInDown">
    <div class="row">


        @yield('content')

    <hr/>
    <div class="row">
        <div class="col-md-4 text-right">
            Sweet

            <small>© 2017</small>
        </div>
    </div>
</div>

@include('layouts.includes.js.scripts')

</body>
</html>