
<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
  <div class="d-flex align-items-center">
      <div class="toggle-icon-wrapper">
        <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
      </div>
      <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
        <div class="d-flex align-items-center py-3">
          <img class="me-2" src="{{ asset('/assets/img/favicons/mycart.png') }}" alt="" width="110" />
          {{-- <span class="font-sans-serif text-primary">MyCart</span> --}}
        </div>
      </a>
  </div>
  <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
    <div class="navbar-vertical-content scrollbar">
      <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
        <li class="nav-item">
          <a class="nav-link @if (request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                <span class="nav-link-text ps-1">Dashboard</span>
            </div>
          </a>
          <a class="nav-link @if (request()->routeIs('admin.products.index')) active @endif" href="{{ route('admin.products.index') }}">
            <div class="d-flex align-items-center">
              <span class="nav-link-icon"><span class="fas fa-shopping-cart"></span></span>
              <span class="nav-link-text ps-1">Product list</span>
            </div>
          </a>
          <a class="nav-link @if (request()->routeIs('admin.categories.index')) active @endif" href="{{ route('admin.categories.index') }}">
            <div class="d-flex align-items-center">
              <span class="nav-link-icon"><span class="fab fa-trello"></span></span>
              <span class="nav-link-text ps-1">Category list</span>
            </div>
          </a>
          <a class="nav-link @if (request()->routeIs('admin.brands.index')) active @endif" href="{{ route('admin.brands.index') }}">
            <div class="d-flex align-items-center">
              <span class="nav-link-icon"><span class="fas fa-tags"></span></span>
              <span class="nav-link-text ps-1">Brand list</span>
            </div>
          </a> 
          <a class="nav-link @if (request()->routeIs('admin.orders.index')) active @endif" href="{{ route('admin.orders.index') }}">
            <div class="d-flex align-items-center">
              <span class="nav-link-icon"><span class="fas fa-box-open"></span></span>
              <span class="nav-link-text ps-1">Order list</span>
            </div>
          </a> 
          <a class="nav-link @if (request()->routeIs('admin.customers.index')) active @endif" href="{{ route('admin.customers.index') }}">
            <div class="d-flex align-items-center">
              <span class="nav-link-icon"><span class="fas fa-user"></span></span>
              <span class="nav-link-text ps-1">Customers</span>
            </div>
          </a>                                                   
          <a class="nav-link @if (request()->routeIs('admin.calendar')) active @endif" href="{{ route('admin.calendar') }}">
            <div class="d-flex align-items-center">
              <span class="nav-link-icon"><span class="fas fa-calendar-alt"></span></span>
              <span class="nav-link-text ps-1">Calendar</span>
            </div>
          </a>                                                   
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
