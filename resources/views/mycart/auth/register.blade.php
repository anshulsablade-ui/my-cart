<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-pwa="true">
<head>
    <meta charset="utf-8">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyCart | Account - Sign Up</title>

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
          <header class="navbar justify-content-center px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
            <a href="{{ route('home') }}" class="navbar-brand pt-0">
                <img src="{{ asset('assets/img/favicons/mycart.png') }}" alt="MyCart" width="200" />
            </a>
          </header>

          <!-- Form -->
          <form id="userRegister">
            <div class="position-relative mb-4">
                <div class="d-flex justify-content-between">
                    <label for="name" class="form-label">Name</label>
                    <div class="nav align-items-center fs-sm">
                        I already have an account
                        <a class="nav-link text-decoration-underline p-0 ms-2" href="{{ route('login') }}">Sign in</a>
                    </div>
                </div>
              <input type="text" class="form-control form-control-lg" id="name" name="name">
              <div class="invalid-tooltip bg-transparent py-0 name_error"></div>
            </div>
            <div class="position-relative mb-4">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control form-control-lg" id="email" name="email">
              <div class="invalid-tooltip bg-transparent py-0 email_error"></div>
            </div>
            <div class="mb-4">
              <label for="password" class="form-label">Password</label>
              <div class="password-toggle">
                <input type="password" class="form-control form-control-lg" id="password" name="password">
                <div class="invalid-tooltip bg-transparent py-0 password_error"></div>
                <label class="password-toggle-button fs-lg" aria-label="Show/hide password">
                  <input type="checkbox" class="btn-check">
                </label>
              </div>
            </div>
            <button type="submit" class="btn btn-lg btn-primary w-100">
              Create an account
              <i class="ci-chevron-right fs-lg ms-1 me-n1"></i>
            </button>
          </form>

          <!-- Divider -->
          <div class="d-flex align-items-center my-4">
            <hr class="w-100 m-0">
            <span class="text-body-emphasis fw-medium text-nowrap mx-4">or continue with</span>
            <hr class="w-100 m-0">
          </div>

          <!-- Social login -->
          <div class="d-flex flex-column flex-sm-row gap-3 pb-4 mb-3 mb-lg-4">
            <a href="{{ url('/auth/google') }}" class="btn btn-lg btn-outline-secondary w-100 px-2">
              <i class="ci-google ms-1 me-1"></i>
              Google
            </a>
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
            $('#userRegister').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                ajaxCall('{{ route('register.post') }}', 'POST', formData, function (response) {
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