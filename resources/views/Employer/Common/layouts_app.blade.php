@php
  $system_version = "?system_version=" . env('system_version');
@endphp

<!doctype html>
<html lang="ja" data-bs-theme="auto">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    @yield('pagehead')

    <!-- CSS -->
    <link href="{{ asset('css/bootstrap53/bootstrap.css') . $system_version }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap53/bootstrap-icons.css') . $system_version }}" rel="stylesheet">
    <link href="{{ asset('company/css/dashboard.css') . $system_version }}" rel="stylesheet">
    <link href="{{ asset('company/css/dashboard.rtl.css') . $system_version }}" rel="stylesheet">

    @yield('pagestyle')

    
    <!-- 共通CSS -->
    <style>
        html, body {
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
            min-height: calc(100vh - 56px); /* ← headerの高さを減算 */
            }

      
        .dashboard-sidebar {
            width: 250px;
            min-width: 250px;
            max-width: 300px;
            overflow-y: auto;
        }
      
        .dashboard-main {
          flex: 1;
          overflow-y: auto;
          padding: 1rem;
        }
      
        @media (max-width: 767.98px) {
          .dashboard-body {
            flex-direction: column;
          }
      
          .dashboard-sidebar {
            width: 100%;
            height: auto;
          }
      
          .dashboard-main {
            height: auto;
            overflow: visible;
          }
        }
      
        .bd-placeholder-img {
          font-size: 1.125rem;
          text-anchor: middle;
          user-select: none;
        }
      
        @media (min-width: 768px) {
          .bd-placeholder-img-lg {
            font-size: 3.5rem;
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
          <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            メニュー
          </button>
        </li>
      </ul>
    </header>

    <!-- サイドバー + メイン -->
    <div class="container-fluid ">

      <div class="row dashboard-body">

        <!-- サイドバー-->
        <nav class="dashboard-sidebar sidebar border-end col-md-3 col-lg-2 p-0 bg-body-tertiary">

          <div class="offcanvas-lg offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu">

            <div class="offcanvas-header">
              <h5 class="offcanvas-title">Company name</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>              
            </div>


            <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">

                <ul class="nav flex-column">
              
                  <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 active" href="#">
                      <i class="fas fa-network-wired"></i> Dashboard
                    </a>
                  </li>
              
                  <!-- master with collapse sub-menu -->
                  <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#masterSubMenu" role="button" aria-expanded="false" aria-controls="masterSubMenu">
                      <i class="fas fa-cogs"></i> master
                      <i class="fas fa-chevron-down ms-auto"></i>
                    </a>
              
                    <div class="collapse ps-4" id="masterSubMenu">
                      <ul class="nav flex-column">
                        <li class="nav-item">
                          <a class="nav-link d-flex align-items-center gap-2" href="#">
                            <i class="fas fa-building"></i> 会社マスタ
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link d-flex align-items-center gap-2" href="#">
                            <i class="fas fa-user"></i> ユーザーマスタ
                          </a>
                        </li>
                      </ul>
                    </div>
                  </li>
              
                </ul>
              
                <hr class="my-3">
              
                <ul class="nav flex-column mb-auto">
              
                  <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                      <i class="fas fa-cog"></i> Settings
                    </a>
                  </li>
              
                  <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="#">
                      <i class="fas fa-sign-out-alt"></i> Sign out
                    </a>
                  </li>
              
                </ul>
              
              </div>

              
              
            {{-- <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">

              <ul class="nav flex-column">

                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center gap-2 active" href="#">
                    <i class="fas fa-network-wired"></i> Dashboard
                  </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2 active" href="#">
                      <i class="fas fa-network-wired"></i> master
                    </a>
                </li>

                master以下にtreeで
                会社マスタ
                ユーザーマスタなど

              </ul>

              <hr class="my-3">

              <ul class="nav flex-column mb-auto">

                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center gap-2" href="#">Settings</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center gap-2" href="#">Sign out</a>
                </li>

              </ul>

            </div> --}}


          </div>
        </nav>

        <!-- メインエリア -->
        <main class="dashboard-main col-md-9 ms-sm-auto col-lg-10 px-md-4">

            <!-- メインエリア共通 -->  
            <div class="row m-0 p-0">
                <div class="col-12 m-0 p-1 d-flex justify-content-end">
                    {{ Breadcrumbs::render(Route::currentRouteName()) }}
                </div>
            </div>
            
          <!-- メインエリア画面別 -->  
          @yield('content')
        </main>

      </div>

    </div>
    
    <script src="{{ asset('js/app.js') . $system_version }}"></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') . $system_version }}"></script>
    <script src="{{ asset('js/bootstrap53/bootstrap.js') . $system_version }}"></script>
    <script src="{{ asset('js/fontawesome.js') . $system_version }}"></script>
    <script src="{{ asset('company/js/dashboard.js') . $system_version }}"></script>

    
    @yield('pagejs')

    <!-- 共通script -->
    <script type="text/javascript">

    </script>
  </body>
</html>
