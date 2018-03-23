<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'SharkyBoy BotMan') }}</title>
  <!-- Styles -->
  {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
  <link rel="icon" type="image/png" href="{{ asset('image/Sharkboy2_0.png') }}">
  @yield('head')
</head>

<body>
  <div id='app'>
    <div class="content-wrapper">
      @include('layouts.partials.nav')
      @yield('content')
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>

</html>
