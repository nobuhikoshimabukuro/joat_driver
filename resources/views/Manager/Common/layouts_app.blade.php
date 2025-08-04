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
    
    <link href="{{ asset('employer/css/style.css') . $system_version }}" rel="stylesheet" />
    <link href="{{ asset('employer/css/dashboard.css') . $system_version }}" rel="stylesheet" />
    <link href="{{ asset('employer/css/dashboard.rtl.css') . $system_version }}" rel="stylesheet" />

    @yield('pagestyle')

    <style>
      html,
      body {
        height: 100%;
        margin: 0;
      }

      body {
        display: flex;
        flex-direction: column;
      }

      header.navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 56px;
        z-index: 1030;
      }

      .container-fluid {
        flex: 1;
        margin-top: 56px;
        display: flex;
        flex-direction: column;
      }

      .dashboard-body {
        flex: 1;
        display: flex;
        flex-direction: row;
        overflow: hidden;
        min-height: calc(100vh - 56px);
      }

      /* サイドバーは768px以上で表示 */
      .dashboard-sidebar {
        width: 250px;
        min-width: 250px;
        max-width: 300px;
        overflow-y: auto;
        display: none;
      }

      @media (min-width: 768px) {       
        .dashboard-sidebar {
          display: block;
        }
      }

      .dashboard-main {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
      }

      /* 768px未満のときのメニュー（ボタン）表示 */
      .menu-toggle-button {
        display: none;
      }

      @media (max-width: 767.98px) {
        .menu-toggle-button {
          display: block;
        }

        .hide-under-md {
          display: none !important;
        }
      }
    </style>
  </head>

  <body>
    <!-- ヘッダー -->
    <header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">Company name</a>
      <ul class="navbar-nav flex-row d-md-none">
        <li class="nav-item text-nowrap">
          <!-- 768px未満で表示されるメニューボタン -->
          <button
            class="nav-link px-3 text-white menu-toggle-button btn btn-dark"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#sidebarMenu"
            aria-controls="sidebarMenu"
          >
            メニュー
          </button>
        </li>
      </ul>
    </header>

    <!-- サイドバー + メイン -->
    <div class="container-fluid">
      <div class="row dashboard-body">
        <!-- 768px未満はoffcanvasで表示 -->
        <div
          class="offcanvas offcanvas-start d-md-none bg-body-tertiary"
          tabindex="-1"
          id="sidebarMenu"
          aria-labelledby="sidebarMenuLabel"
        >
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarMenuLabel">Company name</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            ></button>
          </div>
          <div class="offcanvas-body d-flex flex-column p-0 pt-3 overflow-y-auto">
            <!-- ナビ部分を共通化して読み込み -->
            @include('Manager.Common.sidebar_nav') 
          </div>
        </div>

        <!-- 768px以上は常時表示 -->
        <nav
          class="dashboard-sidebar sidebar border-end col-md-3 col-lg-2 p-0 bg-body-tertiary d-none d-md-block"
          style="height: calc(100vh - 56px);"
        >
          <!-- ナビ部分を共通化して読み込み -->
          @include('Manager.Common.sidebar_nav')
        </nav>

        <!-- メインエリア -->
        <main class="dashboard-main col-md-9 ms-sm-auto col-lg-10 px-md-4">
          <!-- メインエリア共通 -->          
          <div class="row m-0 p-0 hide-under-md Breadcrumbs">
            <div class="col-12 m-0 p-1 d-flex justify-content-end">
              {{ Breadcrumbs::render(Route::currentRouteName()) }}
            </div>
          </div>

          <!-- メインエリア画面別 -->
          @yield('content')
        </main>
      </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('js/app.js') . $system_version }}"></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') . $system_version }}"></script>
    <script src="{{ asset('js/bootstrap53/bootstrap.js') . $system_version }}"></script>
    <script src="{{ asset('js/fontawesome.js') . $system_version }}"></script>
    <script src="{{ asset('js/common.js') . $system_version }}"></script>
    <script src="{{ asset('employer/js/dashboard.js') . $system_version }}"></script>

    @yield('pagejs')
  </body>
</html>
