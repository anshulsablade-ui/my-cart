<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-pwa="true">
<head>
    <meta charset="utf-8">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyCart | Account - Sign In</title>

    <!-- Theme switcher (color modes) -->
    <script src="{{ asset('web/assets/js/theme-switcher.js') }}"></script>

    <!-- Preloaded local web font (Inter) -->
    <link rel="preload" href="{{ asset('web/assets/fonts/inter-variable-latin.woff2') }}" as="font" type="font/woff2" crossorigin="">

    <!-- Font icons -->
    <link rel="preload" href="{{ asset('web/assets/icons/cartzilla-icons.woff2') }}" as="font" type="font/woff2" crossorigin="">
    <link rel="stylesheet" href="{{ asset('web/assets/icons/cartzilla-icons.min.css') }}">
    <!-- Bootstrap + Theme styles -->
    <link rel="preload" href="{{ asset('web/assets/css/theme.min.css') }}" as="style">
    <link rel="stylesheet" href="{{ asset('web/assets/css/theme.min.css') }}" id="theme-styles">

    <!-- Customizer -->
    <script src="{{ asset('web/assets/js/customizer.min.js') }}"></script>
</head>


  <!-- Body -->
<body>

    <!-- Page content -->
    <main class="content-wrapper w-100 px-3 ps-lg-5 pe-lg-4 mx-auto" style="max-width: 1920px">
      <div class="d-lg-flex">

        <!-- Login form + Footer -->
        <div class="d-flex flex-column min-vh-100 w-100 py-4 mx-auto" style="max-width: 416px">

          <!-- Logo -->
          <header class="navbar px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
            <a href="{{ route('home') }}" class="navbar-brand pt-0">
                <img src="{{ asset('assets/img/favicons/mycart.png') }}" alt="MyCart" width="200" />
            </a>
          </header>

          <h1 class="h2 mt-auto">Welcome back</h1>
          <div class="nav fs-sm mb-4">
            Don't have an account?
            <a class="nav-link text-decoration-underline p-0 ms-2" href="{{ route('register') }}">Create an account</a>
          </div>

          <!-- Form -->
          <form id="userLogin">
            <div class="position-relative mb-4">
              <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email" >
              <div class="invalid-tooltip bg-transparent py-0 email_error"></div>
            </div>
            <div class="mb-4">
              <div class="password-toggle">
                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" >
                <div class="invalid-tooltip bg-transparent py-0 password_error"></div>
                <label class="password-toggle-button fs-lg" aria-label="Show/hide password">
                  <input type="checkbox" class="btn-check">
                </label>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-end mb-4">
              <div class="nav">
                <a class="nav-link animate-underline p-0" href="account-password-recovery.html">
                  <span class="animate-target">Forgot password?</span>
                </a>
              </div>
            </div>
            <button type="submit" class="btn btn-lg btn-primary w-100">Sign In</button>
          </form>

          <!-- Divider -->
          <div class="d-flex align-items-center my-4">
            <hr class="w-100 m-0">
            <span class="text-body-emphasis fw-medium text-nowrap mx-4">or continue with</span>
            <hr class="w-100 m-0">
          </div>

          <!-- Social login -->
          <div class="d-flex flex-column flex-sm-row gap-3 pb-4 mb-3 mb-lg-4">
            <button type="button" class="btn btn-lg btn-outline-secondary w-100 px-2">
              <i class="ci-google ms-1 me-1"></i>
              Google
            </button>
          </div>

        </div>
      </div>
    </main>

    <!-- Bootstrap + Theme scripts -->
    <script src="{{ asset('web/assets/js/theme.min.js') }}"></script>
    <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/ajax.js') }}"></script>
    <script>
                $(document).ready(function () {
            $('#userLogin').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                ajaxCall('{{ route('login.post') }}', 'POST', formData, function (response) {
                    if (response.status === 'success') {
                        window.location.href = '{{ route('home') }}';
                    }
                }, function (error) {
                    $('.is-invalid').removeClass('is-invalid');
                    var error = JSON.parse(error.responseText);
                    // console.log(error);
                    $.each(error.message, function (key, value) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`.${key}_error`).text(value);
                    });
                });
            });
        });
    </script>
  

</body>
</html>