<!DOCTYPE html>
<html data-bs-theme="light" lang="en-US" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found</title>

    <!--    Favicons-->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/img/favicons/mycart_icon.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('/vendors/simplebar/simplebar.min.js') }}"></script>

    <!--    Stylesheets-->
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
    <link href="{{ asset('/assets/css/user.min.css') }}" rel="stylesheet" id="user-style-default">

  </head>

  <body>
    <main class="main" id="top">
      <div class="container" data-layout="container">
        <div class="row flex-center min-vh-100 py-6 text-center">
          <div class="col-sm-10 col-md-8 col-lg-6 col-xxl-5">
            <a class="d-flex flex-center mb-4" href="{{ route('vendor.dashboard') }}">
              <img class="me-2" src="{{ asset('/assets/img/favicons/mycart.png') }}" alt="" width="200" />
            </a>
            <div class="card">
              <div class="card-body p-4 p-sm-5">
                <div class="fw-black lh-1 text-300 fs-error">404</div>
                <p class="lead mt-4 text-800 font-sans-serif fw-semi-bold w-md-75 w-xl-100 mx-auto">The page you're looking for is not found.</p>
                <hr />
                <p>Make sure the address is correct and that the page hasn't moved.</p>
                <a class="btn btn-primary btn-sm mt-3" href="{{ route('vendor.dashboard') }}"><span class="fas fa-home me-2"></span>Take me home</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!--    JavaScripts-->
    <script src="{{ asset('/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('/vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('/assets/js/theme.js') }}"></script>
  </body>
</html>