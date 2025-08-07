@php
$masterSubMenu = [
    (object)["href" => route('manager.master.m_employer'), "title" => "求人元マスタ", "icon" => "fas fa-building"],
    (object)["href" => route('manager.master.m_employer_user'), "title" => "求人元ユーザーマスタ", "icon" => "fas fa-user"],
    (object)["href" => route('manager.master.m_license'), "title" => "資格/免許マスタ", "icon" => "fas fa-user"],
];
@endphp

<ul class="nav flex-column">
  <li class="nav-item">
    <a
      class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}"
      href="{{ route('manager.dashboard') }}"
    >
      <i class="fas fa-network-wired"></i> Dashboard
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
    <a class="nav-link d-flex align-items-center gap-2" href="#">
      <i class="fas fa-sign-out-alt"></i> Sign out
    </a>
  </li>
</ul>
