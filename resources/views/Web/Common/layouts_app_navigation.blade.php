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
    <link href="{{ asset('css/width.css') . $system_version }}" rel="stylesheet" />            

    @yield('pagestyle')

    <style>
      /* Sidebar (PC用) */
      .sidebar {
        width: 220px;
        min-height: 100vh;
      }
      .sidebar .nav-link {
        padding: 10px 15px;
        font-size: 0.95rem;
        color: #333;
      }
      .sidebar .nav-link:hover {
        background-color: #f8f9fa;
      }

      /* Bottom Navigation (スマホ用) */
      nav.fixed-bottom .nav-link {
        padding: 6px 0;
        font-size: 0.8rem;
      }
      nav.fixed-bottom .bi {
        display: block;
      }
    </style>
  </head>

  <body>
    <div class="loader-area">
      <div class="loader"></div>
    </div>

    <div class="d-flex">
      {{-- Sidebar for PC --}}
      <nav id="sidebarMenu" class="d-none d-md-block bg-light sidebar border-end">
        <div class="position-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a href="" class="nav-link">
                <i class="bi bi-house-door"></i> ホーム
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link">
                <i class="bi bi-search"></i> 検索
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link">
                <i class="bi bi-bell"></i> 通知
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link">
                <i class="bi bi-person"></i> マイページ
              </a>
            </li>
          </ul>
        </div>
      </nav>

      {{-- Main Content --}}
      <main class="flex-grow-1">
        @yield('content')
      </main>
    </div>

    {{-- Bottom Navigation for Mobile --}}
    <nav class="navbar navbar-expand bg-light navbar-light fixed-bottom border-top shadow-sm d-md-none">
      <ul class="nav nav-justified w-100">
        <li class="nav-item">
          <a href="" class="nav-link text-center">
            <i class="bi bi-house-door fs-5"></i><br>
            <small>ホーム</small>
          </a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link text-center">
            <i class="bi bi-search fs-5"></i><br>
            <small>検索</small>
          </a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link text-center">
            <i class="bi bi-bell fs-5"></i><br>
            <small>通知</small>
          </a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link text-center">
            <i class="bi bi-person fs-5"></i><br>
            <small>マイページ</small>
          </a>
        </li>
      </ul>
    </nav>

    <!-- JS -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') . $system_version }}"></script>
    <script src="{{ asset('js/bootstrap53/bootstrap.js') . $system_version }}"></script>
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
