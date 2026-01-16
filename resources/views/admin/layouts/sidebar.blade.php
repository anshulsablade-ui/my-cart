
<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
  <div class="d-flex align-items-center">
      <div class="toggle-icon-wrapper">
        <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
      </div>
      <a class="navbar-brand" href="../index.html">
        <div class="d-flex align-items-center py-3">
          <img class="me-2" src="../assets/img/icons/spot-illustrations/mycart.png" alt="" width="40" />
          <span class="font-sans-serif text-primary">MyCart</span>
        </div>
      </a>
  </div>
  <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
    <div class="navbar-vertical-content scrollbar">
      <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
        <li class="nav-item"><!-- parent pages-->
          <a class="nav-link" href="#dashboard" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="dashboard">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                <span class="nav-link-text ps-1">Dashboard</span>
            </div>
          </a>
          <a class="nav-link dropdown-indicator" href="#e-commerce" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="e-commerce">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon"><span class="fas fa-shopping-cart"></span></span>
                <span class="nav-link-text ps-1">E commerce</span>
            </div>
          </a>
          <ul class="nav collapse" id="e-commerce">
            <li class="nav-item"><a class="nav-link dropdown-indicator" href="#product" data-bs-toggle="collapse" aria-expanded="false" aria-controls="e-commerce">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Product</span></div>
              </a>
              <ul class="nav collapse" id="product">
                <li class="nav-item"><a class="nav-link" href="../app/e-commerce/product/product-list.html">
                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Product list</span></div>
                  </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Category list</span></div>
                  </a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.brands.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Brand list</span></div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item"><a class="nav-link dropdown-indicator" href="#orders" data-bs-toggle="collapse" aria-expanded="false" aria-controls="e-commerce">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Orders</span></div>
              </a>
              <ul class="nav collapse" id="orders">
                <li class="nav-item"><a class="nav-link" href="../app/e-commerce/orders/order-list.html">
                    <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Order list</span></div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="../app/e-commerce/customers.html">
                <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Customers</span></div>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
