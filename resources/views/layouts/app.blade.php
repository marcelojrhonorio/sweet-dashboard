<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="noindex, nofollow">
  <meta name="googlebot" content="noindex">
  <title>Sweet | @yield('title') </title>
  @include('layouts.includes.styles.app')
  @yield('style')
</head>
<body>
  {{-- Wrapper--}}
  <div id="wrapper">
    {{-- Navigation --}}
    @include('layouts.navigation')
    {{-- Page wraper --}}
    <div id="page-wrapper" class="gray-bg">
      {{-- Page wrapper --}}
      @include('layouts.topnavbar')
      {{-- Main view  --}}
      @yield('content')
      {{-- Footer --}}
      @include('layouts.footer')
    </div>
    {{-- End page wrapper--}}
  </div>
  {{-- End wrapper--}}

  @include('layouts.includes.js.scripts')
</body>
</html>
