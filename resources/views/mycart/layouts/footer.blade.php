    <!-- Page footer -->
    <footer class="footer position-relative bg-dark mt-5">
      <span class="position-absolute top-0 start-0 w-100 h-100 bg-body d-none d-block-dark"></span>
      <div class="container position-relative z-1 pt-sm-2 pt-md-3 pt-lg-4" data-bs-theme="dark">

        <!-- Columns with links that are turned into accordion on screens < 500px wide (sm breakpoint) -->
        <div class="accordion py-5" id="footerLinks">
          <div class="row">
            <div class="col-md-4 d-sm-flex flex-md-column align-items-center align-items-md-start pb-3 mb-sm-4">
              <h4 class="mb-sm-0 mb-md-4 me-4">
                <a class="text-dark-emphasis text-decoration-none" href="{{ route('home') }}">
                    <img src="{{ asset('/assets/img/favicons/mycart.png') }}" alt="MyCart" width="140" />
                </a>
              </h4>
            </div>

            <div class="col-md-8">
              <div class="row row-cols-1 row-cols-sm-3 gx-3 gx-md-4">
                <a class="p-0" href="{{ route('map') }}">Map</a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </footer>