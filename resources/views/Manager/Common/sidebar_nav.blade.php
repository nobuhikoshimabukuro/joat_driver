@php
$masterSubMenu = [
    (object)["href" => route('manager.master.m_employer'), "title" => "求人元マスタ", "icon" => "fas fa-building"],       // そのままビル
    (object)["href" => route('manager.master.m_employer_user'), "title" => "求人元ユーザーマスタ", "icon" => "fas fa-users"], // ユーザ複数
    (object)["href" => route('manager.master.m_license'), "title" => "資格・免許マスタ", "icon" => "fas fa-certificate"], // 証明書
    (object)["href" => route('manager.master.m_address'), "title" => "住所マスタ", "icon" => "fas fa-map-marker-alt"],    // 地図ピン
];
@endphp

<ul class="nav flex-column">
  <li class="nav-item">
    <a
      class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}"
      href="{{ route('manager.dashboard') }}"
    >
      <i class="fas fa-network-wired"></i> ダッシュボード
    </a>
  </li>

  <li class="nav-item">
    <a
      class="nav-link d-flex align-items-center gap-2"
      data-bs-toggle="collapse"
      href="#masterSubMenu"
      role="button"
      aria-expanded="false"
      aria-controls="masterSubMenu"
    >
      <i class="fas fa-cogs"></i> マスター
      <i class="fas fa-chevron-down ms-auto"></i>
    </a>

    <div class="collapse ps-4" id="masterSubMenu">
      <ul class="nav flex-column">
        @foreach ($masterSubMenu as $menu)
          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center gap-2 {{ request()->url() === $menu->href ? 'active' : '' }}"
              href="{{ $menu->href }}"
            >
              <i class="{{ $menu->icon }}"></i> {{ $menu->title }}
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  </li>
  
</ul>

<hr class="my-3" />

<ul class="nav flex-column mb-auto">
  <li class="nav-item">
    <a class="nav-link d-flex align-items-center gap-2" href="#">
      <i class="fas fa-cog"></i> Settings
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('manager.logout') }}">
      <i class="fas fa-sign-out-alt"></i> ログアウト
    </a>
  </li>
</ul>
