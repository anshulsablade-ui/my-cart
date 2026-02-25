<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">
  <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
  <a class="navbar-brand me-1 me-sm-3" href="{{ route('vendor.dashboard') }}">
    <div class="d-flex align-items-center">
      <img class="me-2" src="{{ asset('/assets/img/favicons/mycart.png') }}" alt="" width="110" />
    </div>
  </a>

  <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
    <li class="nav-item dropdown">
      <a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="avatar avatar-xl">
          <div class="avatar-name rounded-circle">
            <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
          </div>
        </div>
      </a>
      <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0" aria-labelledby="navbarDropdownUser">
        <div class="bg-white dark__bg-1000 rounded-2 py-2">
          <a class="dropdown-item fw-bold text-warning" href="#!">{{ Auth::user()->name }}</a>
          <a class="dropdown-item" href="javascript:void(0);">{{ Auth::user()->email }}</a>
          <div class="dropdown-divider"></div>
          {{-- <a class="dropdown-item" href="user/profile.html">Profile &amp; account</a>
          <a class="dropdown-item" href="user/settings.html">Settings</a> --}}
          <a class="dropdown-item" href="{{ route('vendor.logout') }}">Logout</a>
        </div>
      </div>
    </li>
  </ul>
</nav>