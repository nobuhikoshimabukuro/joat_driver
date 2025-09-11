@php
  $system_version = "?system_version=" . env('system_version');
@endphp

<!doctype html>
<html lang="ja" data-bs-theme="auto">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title')</title>
    @yield('pagehead')
    <!-- CSS -->
    <link href="{{ asset('css/bootstrap53/bootstrap.css') . $system_version }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap53/bootstrap-icons.css') . $system_version }}" rel="stylesheet" />
    <link href="{{ asset('css/swiper-bundle.min.css') . $system_version }}" rel="stylesheet">
    <link href="{{ asset('css/width.css') . $system_version }}" rel="stylesheet" />            

    @yield('pagestyle')

    <style>



    
    </style>
  </head>

  <body>
    <div class="loader-area">
      <div class="loader"></div>
    </div>

    
      {{-- Main Content --}}
      <main class="flex-grow-1">
        @yield('content')
      </main>
    
    <!-- JS -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') . $system_version }}"></script>
    <script src="{{ asset('js/bootstrap53/bootstrap.js') . $system_version }}"></script>
    <script src="{{ asset('js/swiper-bundle.min.js') . $system_version}}"></script>
    <script src="{{ asset('js/app.js') . $system_version }}"></script>
    <script src="{{ asset('js/fontawesome.js') . $system_version }}"></script>
    <script src="{{ asset('js/common.js') . $system_version }}"></script>
    <script src="{{ asset('js/common_ajax.js') . $system_version }}"></script>    


    <script>
      $(function(){
        setTimeout(function(){
            EndLoader();
        }, 1000);
      });

      const Routes = {
          searcPostalCode: "",
          searchAddress: "",
      };
    </script>
    
    @yield('pagejs')
  </body>
</html>
