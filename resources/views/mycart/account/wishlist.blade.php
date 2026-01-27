@extends('mycart.layouts.app')
@section('title', 'My Wishlist')
@section('style')
    
@endsection
@section('content')
    <!-- Page content -->
    <main class="content-wrapper">
      <div class="container py-5 mt-n2 mt-sm-0">
        <div class="row pt-md-2 pt-lg-3 pb-sm-2 pb-md-3 pb-lg-4 pb-xl-5">
            @include('mycart.account.sidebar')

          <!-- Wishlist content -->
          <div class="col-lg-9">
            <div class="ps-lg-3 ps-xl-0">

              <!-- Page title + Add list button-->
              <div class="d-flex align-items-center border-bottom pb-4 mb-3">
                <h1 class="h2 me-3 mb-0">Wishlist</h1>
              </div>

              <!-- Wishlist items (Grid) -->
              <div class="row row-cols-2 row-cols-md-3 g-4" id="wishlistSelection">

                @foreach ($wishlists as $item)
                  <!-- Item -->
                  <div class="col">
                    <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                      <div class="position-relative">
                        <div class="position-absolute top-0 end-0 z-1 pt-1 pe-1 mt-2 me-2">
                          <div class="form-check fs-lg">

                            <a class="nav-link animate-underline px-0 py-2 removeWishlist" href="javascript:void(0)" data-product-id="{{ $item->product->id }}"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-sm" data-bs-title="Remove Wishlist" aria-label="Remove Wishlist">
                              <i class="ci-trash fs-base me-1"></i>
                            </a>

                          </div>
                        </div>
                        <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="{{ route('product.show', $item->product->slug) }}">

                          @if ($item->product->discount_percentage)
                            <span class="badge bg-danger position-absolute top-0 start-0 mt-2 ms-2 mt-lg-3 ms-lg-3">-{{ $item->product->discount_percentage }}%</span>
                          @endif
                          <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                            <img src="{{ asset('images/products/medium/' . ($item->product->primaryImage->image ?? 'no-image.png')) }}" alt="{{ $item->product->name }}">
                          </div>
                        </a>
                      </div>
                      <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">

                        <h3 class="pb-1 mb-2">
                          <a class="d-block fs-sm fw-medium text-truncate" href="{{ route('product.show', $item->product->slug) }}">
                            <span class="animate-target">{{ $item->product->name }}</span>
                          </a>
                        </h3>
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="h5 lh-1 mb-0">{{ Number::currency($item->product->final_price, 'INR') }} 
                            @if ($item->product->base_price != $item->product->final_price)
                              <del class="text-body-tertiary fs-sm fw-normal">{{ Number::currency($item->product->base_price, 'INR') }}</del>
                            @endif
                          </div>
                          <button type="button" class="product-card-button btn btn-icon btn-secondary animate-slide-end ms-2 addToCart" data-product-id="{{ $item->product->id }}" aria-label="Add to Cart">
                            <i class="ci-shopping-cart fs-base animate-target"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach

              </div>
              
              @if (count($wishlists) == 0)
                <div class="row text-center p-5 mt-5">
                  <h2 class="h4 mb-3">Your wishlist is empty.</h2>
                  <p class="mb-4">Explore our products and add them to your wishlist.</p>
                  <div>
                    <a class="btn btn-primary" href="{{ route('home') }}">Continue shopping</a>
                  </div>
                </div>  
              @endif
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@section('script')
<script>
$(document).ready(function () {

  $('body').on('click', '.removeWishlist', function (e) {
    e.preventDefault();

    let button   = $(this);
    let productId = button.data('product-id');

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {

      if (result.isConfirmed) {
        $.ajax({
          url: "{{ route('wishlist.remove') }}",
          type: "DELETE",
          data: {
            product_id: productId,
            _token: "{{ csrf_token() }}"
          },

          success: function (response) {
            if (response.status === 'success') {
              button.closest('.col').remove();

              messageAlert(response.message, 'success');

              if ($('#wishlistSelection .col').length === 0) {
                $('#wishlistSelection').after(`
                  <div class="row text-center p-5 mt-5">
                    <h2 class="h4 mb-3">Your wishlist is empty.</h2>
                    <p class="mb-4">Explore our products and add them to your wishlist.</p>
                    <div>
                      <a class="btn btn-primary" href="{{ route('home') }}">Continue shopping</a>
                    </div>
                  </div>
                `);
              }
            }
          }
        });
      }
    });
  });

});
</script>
@endsection
