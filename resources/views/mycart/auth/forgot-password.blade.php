<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-pwa="true">
<head>
    <meta charset="utf-8">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyCart | Account - Sign In</title>

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
            <header class="navbar align-items-center px-0 pb-4 mt-n2 mt-sm-0 mb-2 mb-md-3 mb-lg-4">
              <a href="{{ route('home') }}" class="navbar-brand pt-0">
                <img src="{{ asset('assets/img/favicons/mycart.png') }}" alt="MyCart" width="200" />
              </a>
              <div class="nav">
                <a class="nav-link fs-base animate-underline p-0" href="{{ route('login') }}">
                  <i class="ci-chevron-left fs-lg ms-n1 me-1"></i>
                  <span class="animate-target">Back to Sign In</span>
                </a>
              </div>
            </header>
  
            <h1 class="h2 mt-auto">Forgot password?</h1>
            <p class="pb-2 pb-md-3">Enter the email address you used when you joined and we’ll send you instructions to reset your password</p>
  
            <!-- Form -->
            <form class="pb-4 mb-3 mb-lg-4" id="userForgotPassword">
              <div class="position-relative mb-4">
                <div class="is-invalid">
                  <i class="ci-mail position-absolute top-50 start-0 translate-middle-y fs-lg ms-3"></i>
                  <input type="email" name="email" id="email" class="form-control form-control-lg form-icon-start" placeholder="Email address">
                </div>
                <div class="invalid-tooltip bg-transparent py-0 email_error"></div>
              </div>
              <p id="message"></p>
              <button type="submit" class="btn btn-lg btn-primary w-100">Reset password</button>
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
        $('#userForgotPassword').on('submit', function (e) {
          e.preventDefault();
          let formData = new FormData(this);
          ajaxCall("{{ route('forgot-password.post') }}", 'POST', formData, function (response) {
            if (response.status === 'success') {
              $("#email").closest('div').removeClass('is-invalid');
              $("#email").removeClass('is-invalid');
              $(".email_error").text("");
              $('#message').text(response.message);
              // window.location.href = "{{ route('login') }}";
            }
          }, function (error) {
            $('.is-invalid').removeClass('is-invalid');
            var error = JSON.parse(error.responseText);

            $.each(error.message, function (key, value) {
              $("#email").closest('div').addClass('is-invalid');
              $("#email").addClass('is-invalid');
              $(".email_error").text(value);
            });
          });
        });
      });
    </script>
</body>
</html>