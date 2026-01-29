@extends('mycart.layouts.app')
@section('title', $product->name )
@section('style')

@endsection
@section('content')
    <main class="content-wrapper">

      <!-- Breadcrumb -->
      <nav class="container pt-3 my-3 my-md-4" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="shop-catalog-electronics.html">{{ $product->category->name }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
      </nav>

      <!-- Gallery + Product options -->
      <section class="container pb-5 mb-1 mb-sm-2 mb-md-3 mb-lg-4 mb-xl-5">
        <div class="row">

          <!-- Product gallery -->
          <div class="col-md-6">

            <!-- Preview (Large image) -->
            <div class="swiper" data-swiper="{
              &quot;loop&quot;: true,
              &quot;navigation&quot;: {
                &quot;prevEl&quot;: &quot;.btn-prev&quot;,
                &quot;nextEl&quot;: &quot;.btn-next&quot;
              },
              &quot;thumbs&quot;: {
                &quot;swiper&quot;: &quot;#thumbs&quot;
              }
            }">
              <div class="swiper-wrapper">
                @forelse ($product->images as $item) 
                  <div class="swiper-slide">
                    <div class="ratio ratio-1x1">
                      <img src="{{ asset('images/products/medium/' . $item->image ) }}" data-zoom="{{ asset('images/products/large/'.$item->image) }}" data-zoom-options="{
                        &quot;paneSelector&quot;: &quot;#zoomPane&quot;,
                        &quot;inlinePane&quot;: 768,
                        &quot;hoverDelay&quot;: 500,
                        &quot;touchDisable&quot;: true
                      }" alt="Preview">
                    </div>
                  </div>
                @empty
                  <div class="swiper-slide">
                    <div class="ratio ratio-1x1">
                      <img src="{{ asset('images/products/medium/no-image.png') }}" alt="Preview">
                    </div>
                  </div>
                @endforelse

              </div>

              <!-- Prev button -->
              <div class="position-absolute top-50 start-0 z-2 translate-middle-y ms-sm-2 ms-lg-3">
                <button type="button" class="btn btn-prev btn-icon btn-outline-secondary bg-body rounded-circle animate-slide-start" aria-label="Prev">
                  <i class="ci-chevron-left fs-lg animate-target"></i>
                </button>
              </div>

              <!-- Next button -->
              <div class="position-absolute top-50 end-0 z-2 translate-middle-y me-sm-2 me-lg-3">
                <button type="button" class="btn btn-next btn-icon btn-outline-secondary bg-body rounded-circle animate-slide-end" aria-label="Next">
                  <i class="ci-chevron-right fs-lg animate-target"></i>
                </button>
              </div>
            </div>

            <!-- Thumbnails -->
            <div class="swiper swiper-load swiper-thumbs pt-2 mt-1" id="thumbs" data-swiper="{
              &quot;loop&quot;: true,
              &quot;spaceBetween&quot;: 12,
              &quot;slidesPerView&quot;: 3,
              &quot;watchSlidesProgress&quot;: true,
              &quot;breakpoints&quot;: {
                &quot;340&quot;: {
                  &quot;slidesPerView&quot;: 4
                },
                &quot;500&quot;: {
                  &quot;slidesPerView&quot;: 5
                },
                &quot;600&quot;: {
                  &quot;slidesPerView&quot;: 6
                },
                &quot;768&quot;: {
                  &quot;slidesPerView&quot;: 4
                },
                &quot;992&quot;: {
                  &quot;slidesPerView&quot;: 5
                },
                &quot;1200&quot;: {
                  &quot;slidesPerView&quot;: 6
                }
              }
            }">
              <div class="swiper-wrapper">

                @forelse ($product->images as $item)
                <div class="swiper-slide swiper-thumb">
                  <div class="ratio ratio-1x1" style="max-width: 94px">
                    <img src="{{ asset('images/products/thumb/' . $item->image ) }}" class="swiper-thumb-img" alt="Thumbnail">
                  </div>
                </div>
                @empty
                  <div class="swiper-slide swiper-thumb">
                    <div class="ratio ratio-1x1" style="max-width: 94px">
                      <img src="{{ asset('images/products/thumb/no-image.png') }}" class="swiper-thumb-img" alt="Thumbnail">
                    </div>
                  </div>
                @endforelse
                
              </div>
            </div>
          </div>


          <!-- Product options -->
          <div class="col-md-6 col-xl-5 offset-xl-1 pt-4">
            <div class="ps-md-4 ps-xl-0">
              <div class="position-relative" id="zoomPane" style="height: 400px">

                <!-- Page title -->
                <h1 class="h3 mb-4">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="d-flex flex-wrap align-items-center mb-3">
                  <div class="h4 mb-0 me-3">{{ Number::currency($product->final_price, 'INR') }}
                      @if ($product->base_price != $product->final_price)
                        <del class="text-body-tertiary fs-sm fw-normal">{{ Number::currency($product->base_price, 'INR') }}</del>
                      @endif
                      @if ($product->discount_percentage)
                        <span class="text-success">-{{ $product->discount_percentage }}%</span>
                      @endif
                  </div>
                  @if ($product->stock > 0)
                  <div class="d-flex align-items-center text-success fs-sm ms-auto">
                    <i class="ci-check-circle fs-base me-2"></i>
                    Available to order
                  </div>
                  @else
                    <div class="d-flex align-items-center text-danger fs-sm ms-auto">
                        Out of stock
                    </div>
                  @endif
                </div>

                <!-- Count + Buttons -->
                <div class="d-flex flex-wrap flex-sm-nowrap flex-md-wrap flex-lg-nowrap gap-3 gap-lg-2 gap-xl-3 mb-4">
                  <div class="count-input flex-shrink-0 order-sm-1">
                    <button type="button" class="btn btn-icon btn-lg" data-decrement="" aria-label="Decrement quantity">
                      <i class="ci-minus"></i>
                    </button>
                    <input type="number" id="quantity" name="quantity" class="form-control form-control-lg" value="1" min="1" max="5" readonly="">
                    <button type="button" class="btn btn-icon btn-lg" data-increment="" aria-label="Increment quantity">
                      <i class="ci-plus"></i>
                    </button>
                  </div>
                  <button type="button" data-product-id="{{ $product->id }}" class="btn btn-icon btn-lg btn-secondary animate-pulse order-sm-3 order-md-2 order-lg-3 addToWishlist" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-sm" data-bs-title="Add to Wishlist" aria-label="Add to Wishlist">
                    <i class="ci-heart fs-lg animate-target"></i>
                  </button>
                  <button type="button" class="btn btn-lg btn-primary w-100 animate-slide-end order-sm-2 order-md-4 order-lg-2" id="addToCart" data-product-id="{{ $product->id }}">
                    <i class="ci-shopping-cart fs-lg animate-target ms-n1 me-2"></i>
                    Add to cart
                  </button>
                </div>
                <!-- Product description -->
                <p class="mb-4">{{ $product->description }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Sticky product preview + Add to cart CTA -->
      <section class="sticky-product-banner sticky-top d-md-none" data-sticky-element="">
        <div class="sticky-product-banner-inner pt-5">
          <div class="bg-body border-bottom border-light border-opacity-10 shadow pt-4 pb-2">
            <div class="container d-flex align-items-center">
              <div class="d-flex align-items-center min-w-0 ms-n2 me-3">
                <div class="ratio ratio-1x1 flex-shrink-0" style="width: 50px">
                  <img src="{{ asset('images/products/thumb/' . ($product->primaryImage->image ?? 'no-image.png')) }}" alt="{{ $product->name }}">
                </div>
                <div class="w-100 min-w-0 ps-2">
                  <h4 class="fs-sm fw-medium text-truncate mb-1">{{ $product->name }}</h4>
                  <div class="h6 mb-0">{{ Number::currency($product->final_price, 'INR') }}
                      @if ($product->base_price != $product->final_price)
                        <del class="text-body-tertiary fs-sm fw-normal">{{ Number::currency($product->base_price, 'INR') }}</del>
                      @endif
                  </div>
                </div>
              </div>
              <div class="d-flex gap-2 ms-auto">
                <button type="button" class="btn btn-icon btn-secondary animate-pulse addToWishlist" data-product-id="{{ $product->id }}" aria-label="Add to Wishlist">
                  <i class="ci-heart fs-base animate-target"></i>
                </button>
                <button type="button" class="btn btn-primary animate-slide-end d-none d-sm-inline-flex addToCart" data-product-id="{{ $product->id }}">
                  <i class="ci-shopping-cart fs-base animate-target ms-n1 me-2"></i>
                  Add to cart
                </button>
                <button type="button" class="btn btn-icon btn-primary animate-slide-end d-sm-none addToCart" data-product-id="{{ $product->id }}" aria-label="Add to Cart">
                  <i class="ci-shopping-cart fs-lg animate-target"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Reviews shared container -->
      <section class="container pb-5 mb-2 mb-md-3 mb-lg-4 mb-xl-5">
        <div class="row">
          <div class="col-md-7">
            <!-- Reviews -->
            <div class="d-flex align-items-center pt-5 mb-4 mt-2 mt-md-3 mt-lg-4" id="reviews" style="scroll-margin-top: 80px">
              <h2 class="h3 mb-0">Reviews</h2>
              @if ($orderCompleted)     
                <button type="button" class="btn btn-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#reviewForm">
                  <i class="ci-edit-3 fs-base ms-n1 me-2"></i>
                  Leave a review
                </button>
              @endif
            </div>

            <!-- Reviews stats -->
            <div class="row g-4 pb-3">
              <div class="col-sm-4">

                <!-- Overall rating card -->
                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-body-tertiary rounded p-4">
                  <div class="h1 pb-2 mb-1">4.1</div>
                  <div class="hstack justify-content-center gap-1 fs-sm mb-2">
                    <i class="ci-star-filled text-warning"></i>
                    <i class="ci-star-filled text-warning"></i>
                    <i class="ci-star-filled text-warning"></i>
                    <i class="ci-star-filled text-warning"></i>
                    <i class="ci-star text-body-tertiary opacity-60"></i>
                  </div>
                  <div class="fs-sm">68 reviews</div>
                </div>
              </div>
              <div class="col-sm-8">

                <!-- Rating breakdown by quantity -->
                <div class="vstack gap-3">

                  <!-- 5 stars -->
                  <div class="hstack gap-2">
                    <div class="hstack fs-sm gap-1">
                      5<i class="ci-star-filled text-warning"></i>
                    </div>
                    <div class="progress w-100" role="progressbar" aria-label="Five stars" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
                      <div class="progress-bar bg-warning rounded-pill" style="width: 54%"></div>
                    </div>
                    <div class="fs-sm text-nowrap text-end" style="width: 40px;">37</div>
                  </div>

                  <!-- 4 stars -->
                  <div class="hstack gap-2">
                    <div class="hstack fs-sm gap-1">
                      4<i class="ci-star-filled text-warning"></i>
                    </div>
                    <div class="progress w-100" role="progressbar" aria-label="Four stars" aria-valuenow="23.5" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
                      <div class="progress-bar bg-warning rounded-pill" style="width: 23.5%"></div>
                    </div>
                    <div class="fs-sm text-nowrap text-end" style="width: 40px;">16</div>
                  </div>

                  <!-- 3 stars -->
                  <div class="hstack gap-2">
                    <div class="hstack fs-sm gap-1">
                      3<i class="ci-star-filled text-warning"></i>
                    </div>
                    <div class="progress w-100" role="progressbar" aria-label="Three stars" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
                      <div class="progress-bar bg-warning rounded-pill" style="width: 13%"></div>
                    </div>
                    <div class="fs-sm text-nowrap text-end" style="width: 40px;">9</div>
                  </div>

                  <!-- 2 stars -->
                  <div class="hstack gap-2">
                    <div class="hstack fs-sm gap-1">
                      2<i class="ci-star-filled text-warning"></i>
                    </div>
                    <div class="progress w-100" role="progressbar" aria-label="Two stars" aria-valuenow="6" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
                      <div class="progress-bar bg-warning rounded-pill" style="width: 6%"></div>
                    </div>
                    <div class="fs-sm text-nowrap text-end" style="width: 40px;">4</div>
                  </div>

                  <!-- 1 star -->
                  <div class="hstack gap-2">
                    <div class="hstack fs-sm gap-1">
                      1<i class="ci-star-filled text-warning"></i>
                    </div>
                    <div class="progress w-100" role="progressbar" aria-label="One star" aria-valuenow="3.5" aria-valuemin="0" aria-valuemax="100" style="height: 4px">
                      <div class="progress-bar bg-warning rounded-pill" style="width: 3.5%"></div>
                    </div>
                    <div class="fs-sm text-nowrap text-end" style="width: 40px;">3</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Review -->
            <div class="border-bottom py-3 mb-3">
              <div class="d-flex align-items-center mb-3">
                <div class="text-nowrap me-3">
                  <span class="h6 mb-0">Rafael Marquez</span>
                  <i class="ci-check-circle text-success align-middle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-sm" data-bs-title="Verified customer"></i>
                </div>
                <span class="text-body-secondary fs-sm ms-auto">June 28, 2024</span>
              </div>
              <div class="d-flex gap-1 fs-sm pb-2 mb-1">
                <i class="ci-star-filled text-warning"></i>
                <i class="ci-star-filled text-warning"></i>
                <i class="ci-star-filled text-warning"></i>
                <i class="ci-star-filled text-warning"></i>
                <i class="ci-star-filled text-warning"></i>
              </div>
              <ul class="list-inline gap-2 pb-2 mb-1">
                <li class="fs-sm me-4"><span class="text-dark-emphasis fw-medium">Color:</span> Blue</li>
                <li class="fs-sm"><span class="text-dark-emphasis fw-medium">Model:</span> 128GB</li>
              </ul>
              <p class="fs-sm">The phone has a new A15 Bionic chip, which makes it lightning-fast and responsive. The camera system has also been upgraded, and it now includes a 12-megapixel ultra-wide lens and a 12-megapixel wide lens.</p>
              <ul class="list-unstyled fs-sm pb-2 mb-1">
                <li><span class="text-dark-emphasis fw-medium">Pros:</span> Powerful A15 Bionic chip, improved camera</li>
                <li><span class="text-dark-emphasis fw-medium">Cons:</span> High price tag</li>
              </ul>
              <div class="nav align-items-center">
                <button type="button" class="nav-link animate-underline px-0">
                  <i class="ci-corner-down-right fs-base ms-1 me-1"></i>
                  <span class="animate-target">Reply</span>
                </button>
                <button type="button" class="nav-link text-body-secondary animate-scale px-0 ms-auto me-n1">
                  <i class="ci-thumbs-up fs-base animate-target me-1"></i>
                  0
                </button>
                <hr class="vr my-2 mx-3">
                <button type="button" class="nav-link text-body-secondary animate-scale px-0 ms-n1">
                  <i class="ci-thumbs-down fs-base animate-target me-1"></i>
                  0
                </button>
              </div>
            </div>

            <!-- Review -->
            <div class="border-bottom py-3 mb-3">
              <div class="d-flex align-items-center mb-3">
                <div class="text-nowrap me-3">
                  <span class="h6 mb-0">Daniel Adams</span>
                </div>
                <span class="text-body-secondary fs-sm ms-auto">May 15, 2024</span>
              </div>
              <div class="d-flex gap-1 fs-sm pb-2 mb-1">
                <i class="ci-star-filled text-warning"></i>
                <i class="ci-star-filled text-warning"></i>
                <i class="ci-star-filled text-warning"></i>
                <i class="ci-star-filled text-warning"></i>
                <i class="ci-star text-body-tertiary opacity-75"></i>
              </div>
              <ul class="list-inline gap-2 pb-2 mb-1">
                <li class="fs-sm me-4"><span class="text-dark-emphasis fw-medium">Color:</span> Blue</li>
                <li class="fs-sm"><span class="text-dark-emphasis fw-medium">Model:</span> 128GB</li>
              </ul>
              <p class="fs-sm">The phone has a new A15 Bionic chip, which makes it lightning-fast and responsive. The camera system has also been upgraded, and it now includes a 12-megapixel ultra-wide lens and a 12-megapixel wide lens.</p>
              <ul class="list-unstyled fs-sm pb-2 mb-1">
                <li><span class="text-dark-emphasis fw-medium">Pros:</span> Powerful A15 Bionic chip, improved camera</li>
                <li><span class="text-dark-emphasis fw-medium">Cons:</span> High price tag</li>
              </ul>
              <div class="nav align-items-center">
                <button type="button" class="nav-link animate-underline px-0">
                  <i class="ci-corner-down-right fs-base ms-1 me-1"></i>
                  <span class="animate-target">Reply</span>
                </button>
                <button type="button" class="nav-link text-body-secondary animate-scale px-0 ms-auto me-n1">
                  <i class="ci-thumbs-up text-success fs-base animate-target me-1"></i>
                  18
                </button>
                <hr class="vr my-2 mx-3">
                <button type="button" class="nav-link text-body-secondary animate-scale px-0 ms-n1">
                  <i class="ci-thumbs-down text-danger fs-base animate-target me-1"></i>
                  2
                </button>
              </div>
            </div>

            <div class="nav">
              <a class="nav-link text-primary animate-underline px-0" href="shop-product-reviews-electronics.html">
                <span class="animate-target">See all reviews</span>
                <i class="ci-chevron-right fs-base ms-1"></i>
              </a>
            </div>
          </div>


          <!-- Sticky product preview visible on screens > 991px wide (lg breakpoint) -->
          <aside class="col-md-5 col-xl-4 offset-xl-1 d-none d-md-block" style="margin-top: -100px">
            <div class="position-sticky top-0 ps-3 ps-lg-4 ps-xl-0" style="padding-top: 100px">
              <div class="border rounded p-3 p-lg-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="ratio ratio-1x1 flex-shrink-0" style="width: 110px">
                    <img src="{{ asset('images/products/thumb/' . ( $product->images[0]->image ?? 'no-image.png' )) }}" alt="{{ asset('images/products/'.$product->thumbnail) }}" width="110" alt="iPhone 14">
                  </div>
                  <div class="w-100 min-w-0 ps-2 ps-sm-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                      <div class="d-flex gap-1 fs-xs">
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star text-body-tertiary opacity-75"></i>
                      </div>
                      <span class="text-body-tertiary fs-xs">68</span>
                    </div>
                    <h4 class="fs-sm fw-medium mb-2">{{ $product->name }}</h4>
                    <div class="h5 mb-0">{{ Number::currency($product->final_price, 'INR') }} 
                        @if ($product->base_price != $product->final_price)
                          <del class="text-body-tertiary fs-sm fw-normal">{{ Number::currency($product->base_price, 'INR') }}</del>
                        @endif
                    </div>
                  </div>
                </div>
                <div class="d-flex gap-2 gap-lg-3">
                  <button type="button" class="btn btn-primary w-100 animate-slide-end addToCart" data-product-id="{{ $product->id }}">
                    <i class="ci-shopping-cart fs-base animate-target ms-n1 me-2"></i>
                    Add to cart
                  </button>
                  <button type="button" class="btn btn-icon btn-secondary animate-pulse addToWishlist" data-product-id="{{ $product->id }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-sm" data-bs-title="Add to Wishlist" aria-label="Add to Wishlist">
                    <i class="ci-heart fs-base animate-target"></i>
                  </button>
                </div>
              </div>
            </div>
          </aside>
        </div>
      </section>


      <!-- Viewed products (Carousel) -->
      <section class="container pb-4 pb-md-5 mb-2 mb-sm-0 mb-lg-2 mb-xl-4">
        <h2 class="h3 border-bottom pb-4 mb-0">Viewed products</h2>

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

            @foreach ($simmlarProducts as $product)

              <div class="swiper-slide">
                <div class="product-card animate-underline hover-effect-opacity bg-body rounded">
                  <div class="position-relative">
                    <div class="position-absolute top-0 end-0 z-2 hover-effect-target opacity-0 mt-3 me-3">
                      <div class="d-flex flex-column gap-2">
                        <button type="button" class="btn btn-icon btn-secondary animate-pulse d-none d-lg-inline-flex addToWishlist" data-product-id="{{ $product->id }}" aria-label="Add to Wishlist">
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
                          <a class="dropdown-item addToWishlist" data-product-id="{{ $product->id }}" href="javascript:void(0);">
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

                    <h3 class="pb-1 mb-2">
                      <a class="d-block fs-sm fw-medium text-truncate" href="{{ route('product.show', $product->slug) }}">
                        <span class="animate-target">{{ $product->name }}</span>
                      </a>
                    </h3>
                    <div class="d-flex align-items-center justify-content-between pb-2 mb-1">
                      <div class="h5 lh-1 mb-0">{{ Number::currency($product->final_price, 'INR') }} 
                        @if ($product->base_price != $product->final_price)
                          <del class="text-body-tertiary fs-sm fw-normal">{{ Number::currency($product->base_price, 'INR') }}</del>
                        @endif
                      </div>
                      <button type="button" class="product-card-button btn btn-icon btn-secondary animate-slide-end ms-2 addToCart" data-product-id="{{ $product->id }}" data-product-id="{{ $product->id }}" aria-label="Add to Cart">
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

    <!-- Review form modal -->
    <div class="modal fade" id="reviewForm" data-bs-backdrop="static" tabindex="-1" aria-labelledby="reviewFormLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content" id="reviewForm">
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="reviewFormLabel">Leave a review</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body pb-3 pt-0">
            <div class="mb-3">
              <label class="form-label" for="rating">Rating <span class="text-danger">*</span></label>
              <select class="form-select" name="rating" id="rating" data-select="{
                &quot;placeholderValue&quot;: &quot;Choose rating&quot;,
                &quot;choices&quot;: [
                  {
                    &quot;value&quot;: &quot;&quot;,
                    &quot;label&quot;: &quot;Choose rating&quot;,
                    &quot;placeholder&quot;: true
                  },
                  {
                    &quot;value&quot;: &quot;1&quot;,
                    &quot;label&quot;: &quot;<span class=\&quot;visually-hidden\&quot;>1 star</span>&quot;,
                    &quot;customProperties&quot;: {
                      &quot;icon&quot;: &quot;<span class=\&quot;d-flex gap-1 py-1\&quot;><i class=\&quot;ci-star-filled text-warning\&quot;></i></span>&quot;,
                      &quot;selected&quot;: &quot;1 star&quot;
                    }
                  },
                  {
                    &quot;value&quot;: &quot;2&quot;,
                    &quot;label&quot;: &quot;<span class=\&quot;visually-hidden\&quot;>2 stars</span>&quot;,
                    &quot;customProperties&quot;: {
                      &quot;icon&quot;: &quot;<span class=\&quot;d-flex gap-1 py-1\&quot;><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i></span>&quot;,
                      &quot;selected&quot;: &quot;2 stars&quot;
                    }
                  },
                  {
                    &quot;value&quot;: &quot;3&quot;,
                    &quot;label&quot;: &quot;<span class=\&quot;visually-hidden\&quot;>3 stars</span>&quot;,
                    &quot;customProperties&quot;: {
                      &quot;icon&quot;: &quot;<span class=\&quot;d-flex gap-1 py-1\&quot;><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i></span>&quot;,
                      &quot;selected&quot;: &quot;3 stars&quot;
                    }
                  },
                  {
                    &quot;value&quot;: &quot;4&quot;,
                    &quot;label&quot;: &quot;<span class=\&quot;visually-hidden\&quot;>4 stars</span>&quot;,
                    &quot;customProperties&quot;: {
                      &quot;icon&quot;: &quot;<span class=\&quot;d-flex gap-1 py-1\&quot;><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i></span>&quot;,
                      &quot;selected&quot;: &quot;4 stars&quot;
                    }
                  },
                  {
                    &quot;value&quot;: &quot;5&quot;,
                    &quot;label&quot;: &quot;<span class=\&quot;visually-hidden\&quot;>5 stars</span>&quot;,
                    &quot;customProperties&quot;: {
                      &quot;icon&quot;: &quot;<span class=\&quot;d-flex gap-1 py-1\&quot;><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i><i class=\&quot;ci-star-filled text-warning\&quot;></i></span>&quot;,
                      &quot;selected&quot;: &quot;5 stars&quot;
                    }
                  }
                ]
              }" data-select-template="true"></select>
              {{-- <div class="invalid-feedback" id="rating_error"></div> --}}
            </div>
            <div class="mb-3">
              <label class="form-label" for="review">Review <span class="text-danger">*</span></label>
              <textarea class="form-control" rows="4" id="review" name="review"></textarea>
              {{-- <div class="invalid-feedback" id="review_error"></div> --}}
            </div>
          </div>
          <div class="modal-footer flex-nowrap gap-3 border-0 px-4">
            <button type="reset" class="btn btn-secondary w-100 m-0" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary w-100 m-0">Submit review</button>
          </div>
        </form>
      </div>
    </div>
@endsection
@section('script')
<script>
  $(document).ready(function () {

    $("#reviewForm").submit(function (e) { 
      e.preventDefault();
      let formData = new FormData(this);
      
      $.ajax({
        type: "post",
        url: "{{ route('review.store') }}",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          if(response.status == 'success'){
            messageAlert(response.message, 'success');
            window.location.reload();
            // $('#reviewForm')[0].reset();
            // $('#reviewForm').modal('hide');
          }
        },
        error: function (response) {
        var response = JSON.parse(response.responseText);
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        $.each(response.message, function (key, value) { 
             $(`#${key}`).addClass('is-invalid').after(` <span class="invalid-feedback">${value}</span> `);
        });
        }
      });
      
    });

    $('#addToCart').on('click', function () {
      
      var quantity = $("#quantity").val();
      var productId = $(this).data('product-id');

      $.ajax({
        type: "post",
        url: "{{ route('cart.add') }}",
        data: { product_id: productId, quantity: quantity },
        global: false,
        success: function (response) {
          // var response = JSON.parse(response);
          if(response.status == 'success'){
            messageAlert(response.message, 'success');
            // Update cart count
            $('#cartItemCount').text(response.cart_count);
          } else {
            messageAlert(response.message, 'error');
          }
        },
        error: function (xhr, status, error) {
          var err = JSON.parse(xhr.responseText);
          if(err.status == 'error'){
            messageAlert(err.message, 'error');
          }
          if (err.status == 'success') {
            messageAlert(response.message, 'success');
            $('#cartItemCount').text(response.cartItemCount);
          }
        }
      });
    });

  });
</script>
@endsection