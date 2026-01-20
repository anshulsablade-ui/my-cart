@extends('mycart.layouts.app')
@section('title', 'Home')
@section('style')
    
@endsection
@section('content')
    <!-- Page content -->
    <main class="content-wrapper">

      <!-- Hero slider -->
      <section class="container pt-4">
        <div class="row">
          <div class="col-lg-12">
            <div class="position-relative">
              <span class="position-absolute top-0 start-0 w-100 h-100 rounded-5 d-none-dark rtl-flip" style="background: linear-gradient(90deg, #accbee 0%, #e7f0fd 100%)"></span>
              <span class="position-absolute top-0 start-0 w-100 h-100 rounded-5 d-none d-block-dark rtl-flip" style="background: linear-gradient(90deg, #1b273a 0%, #1f2632 100%)"></span>
              <div class="row justify-content-center position-relative z-2">
                <div class="col-xl-5 col-xxl-4 offset-xxl-1 d-flex align-items-center mt-xl-n3">

                  <!-- Text content master slider -->
                  <div class="swiper px-5 pe-xl-0 ps-xxl-0 me-xl-n5" data-swiper="{
                    &quot;spaceBetween&quot;: 64,
                    &quot;loop&quot;: true,
                    &quot;speed&quot;: 400,
                    &quot;controlSlider&quot;: &quot;#sliderImages&quot;,
                    &quot;autoplay&quot;: {
                      &quot;delay&quot;: 5500,
                      &quot;disableOnInteraction&quot;: false
                    },
                    &quot;scrollbar&quot;: {
                      &quot;el&quot;: &quot;.swiper-scrollbar&quot;
                    }
                  }">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide text-center text-xl-start pt-5 py-xl-5">
                        <p class="text-body">Feel the real quality sound</p>
                        <h2 class="display-4 pb-2 pb-xl-4">Headphones ProMax</h2>
                        <a class="btn btn-lg btn-primary" href="shop-product-general-electronics.html">
                          Shop now
                          <i class="ci-arrow-up-right fs-lg ms-2 me-n1"></i>
                        </a>
                      </div>
                      <div class="swiper-slide text-center text-xl-start pt-5 py-xl-5">
                        <p class="text-body">Deal of the week</p>
                        <h2 class="display-4 pb-2 pb-xl-4">Powerful iPad Pro M2</h2>
                        <a class="btn btn-lg btn-primary" href="shop-product-general-electronics.html">
                          Shop now
                          <i class="ci-arrow-up-right fs-lg ms-2 me-n1"></i>
                        </a>
                      </div>
                      <div class="swiper-slide text-center text-xl-start pt-5 py-xl-5">
                        <p class="text-body">Virtual reality glasses</p>
                        <h2 class="display-4 pb-2 pb-xl-4">Experience New Reality</h2>
                        <a class="btn btn-lg btn-primary" href="shop-catalog-electronics.html">
                          Shop now
                          <i class="ci-arrow-up-right fs-lg ms-2 me-n1"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-9 col-sm-7 col-md-6 col-lg-5 col-xl-7">

                  <!-- Binded images (controlled slider) -->
                  <div class="swiper user-select-none" id="sliderImages" data-swiper="{
                    &quot;allowTouchMove&quot;: false,
                    &quot;loop&quot;: true,
                    &quot;effect&quot;: &quot;fade&quot;,
                    &quot;fadeEffect&quot;: {
                      &quot;crossFade&quot;: true
                    }
                  }">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide d-flex justify-content-end">
                        <div class="ratio rtl-flip" style="max-width: 495px; --cz-aspect-ratio: calc(537 / 495 * 100%)">
                          <img src="web/assets/img/home/electronics/hero-slider/01.png" alt="Image">
                        </div>
                      </div>
                      <div class="swiper-slide d-flex justify-content-end">
                        <div class="ratio rtl-flip" style="max-width: 495px; --cz-aspect-ratio: calc(537 / 495 * 100%)">
                          <img src="web/assets/img/home/electronics/hero-slider/02.png" alt="Image">
                        </div>
                      </div>
                      <div class="swiper-slide d-flex justify-content-end">
                        <div class="ratio rtl-flip" style="max-width: 495px; --cz-aspect-ratio: calc(537 / 495 * 100%)">
                          <img src="web/assets/img/home/electronics/hero-slider/03.png" alt="Image">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Scrollbar -->
              <div class="row justify-content-center" data-bs-theme="dark">
                <div class="col-xxl-10">
                  <div class="position-relative mx-5 mx-xxl-0">
                    <div class="swiper-scrollbar mb-4"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Special offers (Carousel) -->
      <section class="container pt-5 mt-2 mt-sm-3 mt-lg-4">

        <!-- Heading + Countdown -->
        <div class="d-flex align-items-start align-items-md-center justify-content-between border-bottom pb-3 pb-md-4">
          <div class="d-md-flex align-items-center">
            <h2 class="h3 pe-3 me-3 mb-md-0">Special offers for you</h2>
          </div>
          <div class="nav ms-3">
            <a class="nav-link animate-underline px-0 py-2" href="shop-catalog-electronics.html">
              <span class="animate-target text-nowrap">View all</span>
              <i class="ci-chevron-right fs-base ms-1"></i>
            </a>
          </div>
        </div>

        <!-- Product carousel -->
        <div class="position-relative mx-md-1">

          <!-- External slider prev/next buttons visible on screens > 500px wide (sm breakpoint) -->
          <button type="button" class="offers-prev btn btn-icon btn-outline-secondary bg-body rounded-circle animate-slide-start position-absolute top-50 start-0 z-2 translate-middle-y ms-n1 d-none d-sm-inline-flex" aria-label="Prev">
            <i class="ci-chevron-left fs-lg animate-target"></i>
          </button>
          <button type="button" class="offers-next btn btn-icon btn-outline-secondary bg-body rounded-circle animate-slide-end position-absolute top-50 end-0 z-2 translate-middle-y me-n1 d-none d-sm-inline-flex" aria-label="Next">
            <i class="ci-chevron-right fs-lg animate-target"></i>
          </button>

          <!-- Slider -->
          <div class="swiper py-4 px-sm-3" data-swiper="{
            &quot;slidesPerView&quot;: 2,
            &quot;spaceBetween&quot;: 24,
            &quot;loop&quot;: true,
            &quot;navigation&quot;: {
              &quot;prevEl&quot;: &quot;.offers-prev&quot;,
              &quot;nextEl&quot;: &quot;.offers-next&quot;
            },
            &quot;breakpoints&quot;: {
              &quot;768&quot;: {
                &quot;slidesPerView&quot;: 3
              },
              &quot;992&quot;: {
                &quot;slidesPerView&quot;: 4
              }
            }
          }">
            <div class="swiper-wrapper">

            @foreach ($products as $product)

              <div class="swiper-slide">
                <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                  <div class="position-relative">
                    <div class="position-absolute top-0 end-0 z-2 hover-effect-target opacity-0 mt-3 me-3">
                      <div class="d-flex flex-column gap-2">
                        <button type="button" class="btn btn-icon btn-secondary animate-pulse d-none d-lg-inline-flex" aria-label="Add to Wishlist">
                          <i class="ci-heart fs-base animate-target"></i>
                        </button>
                      </div>
                    </div>
                    <div class="dropdown d-lg-none position-absolute top-0 end-0 z-2 mt-2 me-2">
                      <button type="button" class="btn btn-icon btn-sm btn-secondary bg-body" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More actions">
                        <i class="ci-more-vertical fs-lg"></i>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end fs-xs p-2" style="min-width: auto">
                        <li>
                          <a class="dropdown-item" href="#!">
                            <i class="ci-heart fs-sm ms-n1 me-2"></i>
                            Add to Wishlist
                          </a>
                        </li>
                      </ul>
                    </div>
                    <a class="d-block rounded-top overflow-hidden p-3 p-sm-4" href="{{ route('product.show', $product->slug) }}">
                      <div class="ratio" style="--cz-aspect-ratio: calc(240 / 258 * 100%)">
                        <img src="{{ asset('images/products/medium/' . ($product->primaryImage->image ?? 'no-image.png')) }}" alt="{{ $product->name }}" class="img-fluid">
                      </div>
                    </a>
                  </div>
                  <div class="w-100 min-w-0 px-1 pb-2 px-sm-3 pb-sm-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                      <div class="d-flex gap-1 fs-xs">
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-half text-warning"></i>
                      </div>
                      <span class="text-body-tertiary fs-xs">(14)</span>
                    </div>
                    <h3 class="pb-1 mb-2">
                      <a class="d-block fs-sm fw-medium text-truncate" href="{{ route('product.show', $product->slug) }}">
                        <span class="animate-target">{{ $product->name }}</span>
                      </a>
                    </h3>
                    <div class="d-flex align-items-center justify-content-between pb-2 mb-1">
                      <div class="h5 lh-1 mb-0">{{ Number::currency($product->price, 'INR') }} <del class="text-body-tertiary fs-sm fw-normal">{{ Number::currency($product->original_price, 'INR') }}</del></div>
                      <button type="button" class="product-card-button btn btn-icon btn-secondary animate-slide-end ms-2" aria-label="Add to Cart">
                        <i class="ci-shopping-cart fs-base animate-target"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
            </div>
          </div>

          <!-- External slider prev/next buttons visible on screens < 500px wide (sm breakpoint) -->
          <div class="d-flex justify-content-center gap-2 mt-n2 mb-3 pb-1 d-sm-none">
            <button type="button" class="offers-prev btn btn-icon btn-outline-secondary bg-body rounded-circle animate-slide-start me-1" aria-label="Prev">
              <i class="ci-chevron-left fs-lg animate-target"></i>
            </button>
            <button type="button" class="offers-next btn btn-icon btn-outline-secondary bg-body rounded-circle animate-slide-end" aria-label="Next">
              <i class="ci-chevron-right fs-lg animate-target"></i>
            </button>
          </div>
        </div>
      </section>

    </main>
@endsection
@section('script')
    
@endsection