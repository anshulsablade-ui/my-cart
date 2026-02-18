<!-- Sidebar navigation that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
<aside class="col-lg-3">
  <div class="offcanvas-lg offcanvas-start pe-lg-0 pe-xl-4" id="accountSidebar">

    <!-- Header -->
    <div class="offcanvas-header d-lg-block py-3 p-lg-0">
      <div class="d-flex align-items-center">
        <div class="h5 d-flex justify-content-center align-items-center flex-shrink-0 text-primary bg-primary-subtle lh-1 rounded-circle mb-0" style="width: 3rem; height: 3rem">
          @if (session('user.image'))
              <img class="img-fluid rounded-circle border" src="{{ asset('images/users/' . session('user.image') ) }}" alt="{{ session('user.name') }}">
          @else
             {{ substr(session('user.name'), 0, 1) }} 
          @endif
        </div>
        <div class="min-w-0 ps-3">
          <h5 class="h6 mb-1">{{ session('user.name') }}</h5>
          <div class="nav flex-nowrap text-nowrap min-w-0">
            <a class="nav-link animate-underline text-body p-0" href="javascript:void(0);">
              <span class="text-body fw-normal text-truncate">{{ session('user.email') }}</span>
            </a>
          </div>
        </div>
      </div>
      <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#accountSidebar" aria-label="Close"></button>
    </div>

    <!-- Body (Navigation) -->
    <div class="offcanvas-body d-block pt-2 pt-lg-4 pb-lg-0">
      <nav class="list-group list-group-borderless">
        <a class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('orders.index')) pe-none active @endif" href="{{ route('orders.index') }}">
          <i class="ci-shopping-bag fs-base opacity-75 me-2"></i>
          Orders
        </a>
        <a class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('wishlist.index')) pe-none active @endif" href="{{ route('wishlist.index') }}">
          <i class="ci-heart fs-base opacity-75 me-2"></i>
          Wishlist
        </a>
        <a class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('review.index')) pe-none active @endif" href="{{ route('review.index') }}">
          <i class="ci-star fs-base opacity-75 me-2"></i>
          My reviews
        </a>
      </nav>
      <h6 class="pt-4 ps-2 ms-1">Manage account</h6>
      <nav class="list-group list-group-borderless">
        <a class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('profile.index')) pe-none active @endif" href="{{ route('profile.index') }}">
          <i class="ci-user fs-base opacity-75 me-2"></i>
          Personal info
        </a>
        <a class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('address.index')) pe-none active @endif" href="{{ route('address.index') }}">
          <i class="ci-map-pin fs-base opacity-75 me-2"></i>
          Addresses
        </a>
      </nav>
      <nav class="list-group list-group-borderless pt-3">
        <a class="list-group-item list-group-item-action d-flex align-items-center" href="{{ route('logout') }}">
          <i class="ci-log-out fs-base opacity-75 me-2"></i>
          Log out
        </a>
      </nav>
    </div>
  </div>
</aside>
