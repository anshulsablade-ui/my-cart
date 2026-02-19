<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">
  <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
  <a class="navbar-brand me-1 me-sm-3" href="{{ route('admin.dashboard') }}">
    <div class="d-flex align-items-center">
      <img class="me-2" src="{{ asset('/assets/img/favicons/mycart.png') }}" alt="" width="110" />
    </div>
  </a>

  <ul class="navbar-nav align-items-center d-none d-lg-block">
    <li class="nav-item">
      <div class="search-box" data-list='{"valueNames":["title"]}'>

        <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
          <input id="searchInput" class="form-control search-input fuzzy-search" type="search" placeholder="Search..." aria-label="Search" />
          <span class="fas fa-search search-box-icon"></span>
        </form>

        <div class="btn-close-falcon-container position-absolute end-0 top-50 translate-middle shadow-none" data-bs-dismiss="search">
          <button class="btn btn-link btn-close-falcon p-0" aria-label="Close"></button>
        </div>
        <div class="dropdown-menu border font-base start-0 mt-2 py-0 overflow-hidden w-100" id="previewData">
          <div class="text-center mt-n3">
            <p class="fallback fw-bold fs-8 d-none">No Result Found.</p>
          </div>
        </div>
      </div>
    </li>
  </ul>

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
          <div class="dropdown-divider"></div>
          {{-- <a class="dropdown-item" href="user/profile.html">Profile &amp; account</a>
          <a class="dropdown-item" href="user/settings.html">Settings</a> --}}
          <a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a>
        </div>
      </div>
    </li>
  </ul>
</nav>