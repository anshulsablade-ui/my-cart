<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-pwa="true">
<head>
    <meta charset="utf-8">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyCart | Account - Reset Password</title>

    <link rel="icon" type="image/png" href="{{ asset('/assets/img/favicons/mycart_icon.png') }}" sizes="32x32">
    <!-- Preloaded local web font (Inter) -->
    <link rel="preload" href="{{ asset('web/assets/fonts/inter-variable-latin.woff2') }}" as="font" type="font/woff2" crossorigin="">

    <!-- Font icons -->
    <link rel="preload" href="{{ asset('web/assets/icons/cartzilla-icons.woff2') }}" as="font" type="font/woff2" crossorigin="">
    <link rel="stylesheet" href="{{ asset('web/assets/icons/cartzilla-icons.min.css') }}">
    <!-- Bootstrap + Theme styles -->
    <link rel="stylesheet" href="{{ asset('web/assets/css/theme.min.css') }}">
</head>


  <!-- Body -->
<body>

    <!-- Page content -->
    <main class="content-wrapper align-content-center w-100 px-3 ps-lg-5 pe-lg-4 mx-auto" style="max-width: 1920px">
      <div class="d-lg-flex">

        <!-- Login form + Footer -->
        <div class="d-flex flex-column w-100 py-4 mx-auto" style="max-width: 416px">

          <!-- Logo -->
          <header class="navbar px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
            <a href="{{ route('home') }}" class="navbar-brand pt-0">
                <img src="{{ asset('assets/img/favicons/mycart.png') }}" alt="MyCart" width="200" />
            </a>
          </header>

          <h1 class="h6">Reset new password</h1>

          <!-- Form -->
          <form id="passwordReset">
            <input type="hidden" name="token" value="{{ $token }}">
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
            <button type="submit" class="btn btn-lg btn-primary w-100">Sign In</button>
          </form>
        </div>
      </div>
    </main>

    <!-- Bootstrap + Theme scripts -->
    <script src="{{ asset('web/assets/js/theme.min.js') }}"></script>
    <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/ajax.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#passwordReset').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                ajaxCall("{{ route('reset-password.post') }}", 'POST', formData, function (response) {
                    if (response.status === 'success') {
                        window.location.href = "{{ route('login') }}";
                    }
                }, function (error) {
                    $('.is-invalid').removeClass('is-invalid');
                    var error = JSON.parse(error.responseText);
                    
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