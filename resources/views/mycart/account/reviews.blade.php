@extends('mycart.layouts.app')
@section('title', 'My Reviews')
@section('style')
    
@endsection
@section('content')
    <!-- Page content -->
    <main class="content-wrapper">
      <div class="container py-5 mt-n2 mt-sm-0">
        <div class="row pt-md-2 pt-lg-3 pb-sm-2 pb-md-3 pb-lg-4 pb-xl-5">
          @include('mycart.account.sidebar')

          <!-- Reviews content -->
          <div class="col-lg-9">
            <div class="ps-lg-3 ps-xl-0">

              <!-- Page title + Sorting select -->
              <div class="row align-items-center pb-2 mb-sm-1">
                <div class="col-sm-6 col-md-7 col-xxl-8 mb-3 mb-md-0">
                  <h1 class="h2 me-3 mb-0">My reviews</h1>
                </div>
              </div>

              <!-- Basic accordion example -->
              <div class="accordion" id="accordionExample">
                @foreach ($reviews as $review)
                  <!-- Item -->
                  <div class="accordion-item">
                      <!-- Item -->
                      <div class="d-md-flex align-items-center justify-content-between gap-4 py-3">
                        <div class="nav flex-nowrap position-relative align-items-center">

                          <div>
                            @if ($review->product->discount_percentage)
                              <span class="badge bg-danger position-absolute top-0 start-0">-{{ $review->product->discount_percentage }}%</span>
                            @endif
                            <img src="{{ asset('images/products/thumb/' . ($review->product->primaryImage->image ?? 'no-image.png')) }}" class="d-block my-xl-1" width="64" alt="Product thumb">
                          </div>

                          <div class="ps-3">
                            <a class="nav-link stretched-link hover-effect-underline p-0" href="{{ route('product.show', $review->product->slug) }}">{{ $review->product->name }}</a>
                            <div class="h6 mb-0">{{ Number::currency($review->product->final_price, 'INR') }}</div>
                            @if ($review->product->base_price != $review->product->final_price)
                              <del class="text-body-tertiary fs-sm fw-normal">{{ Number::currency($review->product->base_price, 'INR') }}</del>
                            @endif
                          </div>
                          
                        </div>
                        <div class="position-relative d-flex align-items-center text-decoration-none min-w-0 pt-1 pt-md-0 ps-3 ps-md-0 mb-2 mb-md-0">
                          <div class="flex-shrink-0 d-md-none" style="width: 64px"></div>
                          <div class="d-flex gap-1 fs-sm me-2 me-sm-3">
                            @for ($i = 1; $i <= 5; $i++)
                              <i class="{{ $review->rating >= $i ? 'ci-star-filled text-warning' : 'ci-star text-body-tertiary opacity-75' }}"></i>
                            @endfor
                          </div>
                          <button type="button" class="accordion-button mx-3 animate-underline collapsed" data-bs-toggle="collapse" data-bs-target="#collapse{{ $review->id }}" aria-expanded="false" aria-controls="collapse{{ $review->id }}"></button>
                        </div>
                      </div>
                    </h3>
                    <div class="accordion-collapse collapse" id="collapse{{ $review->id }}" aria-labelledby="heading{{ $review->id }}" data-bs-parent="#accordionExample">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                          <div class="fs-sm text-body-secondary">{{ $review->created_at->format('F j, Y') }}</div>
                        </div>
                        <div class="accordion-body"><p class="fs-sm mb-0">{{ $review->review }}</p></div>
                    </div>
                  </div>
                @endforeach
              </div>

              <!-- Item -->
              {{-- <div class="d-md-flex align-items-center justify-content-between gap-4 border-bottom py-3">
                <div class="nav flex-nowrap position-relative align-items-center">
                  <img src="assets/img/shop/electronics/thumbs/18.png" class="d-block my-xl-1" width="64" alt="Product thumb">
                  <a class="nav-link stretched-link hover-effect-underline ps-3 p-0" href="shop-product-general-electronics.html">Apple iPhone 14 128GB Blue</a>
                </div>
                <div class="d-flex pt-2 pt-md-0 ps-3 ps-md-0 mb-2 mb-md-0">
                  <div class="d-md-none" style="width: 64px"></div>
                  <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#reviewForm">Leave a review</button>
                </div>
              </div> --}}

              <!-- Pagination -->
              <nav class="pt-3 pb-2 pb-sm-0 mt-2 mt-md-3" aria-label="Page navigation">
                  {{ $reviews->links('mycart.pagination.custom') }}
              </nav>
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@section('script')

@endsection
