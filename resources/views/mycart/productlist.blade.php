@extends('mycart.layouts.app')
@section('title', 'Product Catalog')
@section('style')

@endsection
@section('content')
    <!-- Page content -->
    <main class="content-wrapper">

      <!-- Breadcrumb -->
      <nav class="container pt-3 my-3 my-md-4" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Catalog with sidebar filters</li>
        </ol>
      </nav>

      <!-- Page title -->
      <h1 class="h3 container mb-4">Shop catalog</h1>

      <!-- Selected filters + Sorting -->
      <section class="container mb-4">
        <div class="row">
          <div class="col-lg-9">
            <div class="d-md-flex align-items-start">
              <div class="h6 fs-sm fw-normal text-nowrap translate-middle-y mt-3 mb-0 me-4">Found <span class="fw-semibold">{{ $products->total() }}</span> items</div>
              <div class="d-flex flex-wrap gap-2">
                {{-- <button type="button" class="btn btn-sm btn-secondary">
                  <i class="ci-close fs-sm ms-n1 me-1"></i>
                  Sale
                </button> --}}
                <button type="button" id="clearFilters" class="btn btn-sm btn-secondary bg-transparent border-0 text-decoration-underline px-0 ms-2">
                  Clear all
                </button>
              </div>
            </div>
          </div>
          <div class="col-lg-3 mt-3 mt-lg-0">
            <div class="d-flex align-items-center justify-content-lg-end text-nowrap">
              <label class="form-label fw-semibold mb-0 me-2">Sort by:</label>
              <div style="width: 190px">
                <select id="sortFilter" name="sort" class="form-select border-0 rounded-0 px-1">
                  <option value="">Relevance</option>
                  <option value="price_asc">Price: Low to High</option>
                  <option value="price_desc">Price: High to Low</option>
                  <option value="newest">Newest Arrivals</option>
                  {{-- <option value="popularity">Popularity</option> --}}
                </select>

              </div>
            </div>
          </div>
        </div>
        <hr class="d-lg-none my-3">
      </section>


      <!-- Products grid + Sidebar with filters -->
      <section class="container pb-5 mb-sm-2 mb-md-3 mb-lg-4 mb-xl-5">
        <div class="row">

          <!-- Filter sidebar that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
          <aside class="col-lg-3">
            <div class="offcanvas-lg offcanvas-start" id="filterSidebar">
              <div class="offcanvas-header py-3">
                <h5 class="offcanvas-title">Filter and sort</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body flex-column pt-2 py-lg-0">

                <!-- Status -->
                {{-- <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                  <h4 class="h6">Status</h4>
                  <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                      <i class="ci-percent fs-sm me-1 ms-n1"></i>
                      Sale
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary">Same Day Delivery</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary">Available to Order</button>
                  </div>
                </div> --}}

                <!-- Categories -->
                <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                  <h4 class="h6 mb-2">Categories</h4>
                  <ul class="list-unstyled d-block m-0">
                    @foreach ($categories->take(8) as $category)          
                      <li class="nav d-block pt-2 mt-1">
                        <a class="nav-link animate-underline fw-normal p-0 categoryFilter" href="javascript:void(0);" data-category-id="{{ $category->id }}">
                          <span class="animate-target text-truncate me-3">{{ $category->name }}</span>
                          <span class="text-body-secondary fs-xs ms-auto">{{ $category->products_count  }}</span>
                        </a>
                      </li>
                    @endforeach
                      <div class="accordion mb-n2">
                        <div class="accordion-item border-0">
                          <div class="accordion-collapse collapse" id="more-categories">
                            <div class="d-flex flex-column gap-1">
                              @foreach ($categories->skip(8) as $category)
                                <li class="nav d-block pt-2 mt-1">
                                  <a class="nav-link animate-underline fw-normal p-0 categoryFilter" href="javascript:void(0);" data-category-id="{{ $category->id }}">
                                    <span class="animate-target text-truncate me-3">{{ $category->name }}</span>
                                    <span class="text-body-secondary fs-xs ms-auto">{{ $category->products_count  }}</span>
                                  </a>
                                </li>
                              @endforeach
                            </div>
                          </div>
                          <div class="accordion-header">
                            <button type="button" class="accordion-button w-auto fs-sm fw-medium collapsed animate-underline py-2" data-bs-toggle="collapse" data-bs-target="#more-categories" aria-expanded="false" aria-controls="more-categories" aria-label="Show/hide more categories">
                              <span class="animate-target me-2" data-label-collapsed="Show all" data-label-expanded="Show less"></span>
                            </button>
                          </div>
                        </div>
                      </div>
                  </ul>
                </div>

                <!-- Price range -->
                {{-- <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                  <h4 class="h6 mb-2" id="slider-label">Price (₹)</h4>
                  <div class="pt-2">
                    <div class="d-flex align-items-center">
                      <input type="number" class="form-control form-icon-start" name="min_price" min="0">
                      <i class="ci-minus text-body-emphasis mx-2"></i>
                      <input type="number" class="form-control form-icon-start" name="max_price" min="0">
                    </div>
                    <button class="btn btn-primary w-100 mt-3" type="button" id="price-filter">filter</button>
                  </div>
                </div> --}}

                <!-- Brand (checkboxes) -->
                <div class="w-100 border rounded p-3 p-xl-4 mb-3 mb-xl-4">
                  <h4 class="h6">Brand</h4>
                  <div class="d-flex flex-column gap-1">
                    @foreach ($brands->take(8) as $brand)    
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input brandFilter" name="brand[]" value="{{ $brand->id }}" id="{{ $brand->slug }}">
                          <label for="{{ $brand->slug }}" class="form-check-label text-body-emphasis">{{ $brand->name }}</label>
                        </div>
                        <span class="text-body-secondary fs-xs">{{ $brand->products_count  }}</span>
                      </div>
                    @endforeach
                    <div class="accordion mb-n2">
                      <div class="accordion-item border-0">
                        <div class="accordion-collapse collapse" id="more-brands">
                          <div class="d-flex flex-column gap-1">
                            @foreach ($brands->skip(8) as $brand)
                              <div class="d-flex align-items-center justify-content-between">
                                <div class="form-check">
                                  <input type="checkbox" class="form-check-input" name="brand[]" value="{{ $brand->id }}" id="{{ $brand->slug }}">
                                  <label for="{{ $brand->slug }}" class="form-check-label text-body-emphasis">{{ $brand->name }}</label>
                                </div>
                                <span class="text-body-secondary fs-xs">{{ $brand->products_count  }}</span>
                              </div>
                            @endforeach
                          </div>
                        </div>
                        <div class="accordion-header">
                          <button type="button" class="accordion-button w-auto fs-sm fw-medium collapsed animate-underline py-2" data-bs-toggle="collapse" data-bs-target="#more-brands" aria-expanded="false" aria-controls="more-brands" aria-label="Show/hide more brands">
                            <span class="animate-target me-2" data-label-collapsed="Show all" data-label-expanded="Show less"></span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- <button class="btn btn-primary" type="button" id="filter">filter</button> --}}
              </div>
            </div>
          </aside>


          <!-- Product grid -->
          <div class="col-lg-9">
            <div id="product-list">
              @include('mycart.component.product', ['products' => $products])
            </div>
          </div>

          {{-- <div class="col-lg-9">
            <div class="row row-cols-2 row-cols-md-3 g-4 pb-3 mb-3">

                @foreach ($products as $product)
                  <!-- Item -->
                  <div class="col">
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

            <!-- Pagination -->
            <nav class="border-top mt-4 pt-3" aria-label="Catalog pagination">
                {{ $products->links('mycart.pagination.catalog') }}
            </nav>

          </div> --}}
        </div>
      </section>

    </main>
        <!-- Filter offcanvas toggle that is visible on screens < 992px wide (lg breakpoint) -->
    <button type="button" class="fixed-bottom z-sticky w-100 btn btn-lg btn-dark border-0 border-top border-light border-opacity-10 rounded-0 pb-4 d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar" aria-controls="filterSidebar" data-bs-theme="light">
      <i class="ci-filter fs-base me-2"></i>
      Filters
    </button>
@endsection
@section('script')

<script>
$(document).ready(function () {

    $('#clearFilters').on('click', function () {

    $.ajax({
      url: "{{ route('products.filter') }}",
      type: "GET",
      beforeSend: function () {
        $('#product-list').html('<p class="text-center py-5">Loading...</p>');
      },
      success: function (response) {
        $('.categoryFilter').removeClass('active');

        $('.brandFilter').prop('checked', false);
       
        $('#product-list').html(response);
      }
    });

  });

  // function updateURL(category, brands, sort, page = 1, min_price, max_price) {
  //   let params = new URLSearchParams();

  //   if (category) params.set('category', category);
  //   if (brands.length) params.set('brands', brands.join(','));
  //   if (sort) params.set('sort', sort);
  //   if (page > 1) params.set('page', page);
  //   if (min_price) params.set('min_price', min_price);
  //   if (max_price) params.set('max_price', max_price);

  //   history.pushState({}, '', '?' + params.toString());
  // }

  function applyFilter(page = 1) {
    let brands = [];
    let category = $('.categoryFilter.active').data('category-id');
    let sort = $('#sortFilter').val();
    let min_price = $('#min_price').val();
    let max_price = $('#max_price').val();

    $('.brandFilter:checked').each(function () {
      brands.push($(this).val());
    });

    // updateURL(category, brands, sort, page, min_price, max_price);

    $.ajax({
      url: "{{ route('products.filter') }}",
      type: "GET",
      data: {
        search: $('#search').val(),
        category: category,
        brands: brands,
        sort: sort,
        page: page,
        min_price: min_price,
        max_price: max_price
      },
      beforeSend: function () {
        $('#product-list').html('<p class="text-center py-5">Loading...</p>');
      },
      success: function (response) {
        $('#product-list').html(response);
      }
    });
  }

  // price-filter
  $('body').on('click', '#price-filter', function () {
    applyFilter();
  })

  // CATEGORY CLICK
  $('body').on('click', '.categoryFilter', function () {
    $('.categoryFilter').removeClass('active');
    $(this).addClass('active');
    $('.brandFilter').prop('checked', false);

    applyFilter();
  });

  // BRAND CHECK
  $('body').on('change', '.brandFilter', function () {
    applyFilter();
  });

  // SORT
  $('#sortFilter').on('change', function () {
    applyFilter();
  });

  // PAGINATION
  // $('body').on('click', '.pagination a', function (e) {
  //   e.preventDefault();
  //   let page = new URL($(this).attr('href')).searchParams.get('page');
  //   applyFilter(page);
  // });

  // RESTORE STATE FROM URL
  // function restoreFromURL() {
  //   let params = new URLSearchParams(window.location.search);

  //   let category = params.get('category');
  //   let brands = params.get('brands');
  //   let sort = params.get('sort');

  //   if (category) {
  //     $('.categoryFilter').removeClass('active');
  //     $('.categoryFilter[data-category-id="' + category + '"]').addClass('active');
  //   }

  //   if (brands) {
  //     brands.split(',').forEach(id => {
  //       $('.brandFilter[value="' + id + '"]').prop('checked', true);
  //     });
  //   }

  //   if (sort) {
  //     $('#sortFilter').val(sort);
  //   }

  //   if (category || brands || sort) {
  //     applyFilter(params.get('page') ?? 1);
  //   }
  // }

  // restoreFromURL();

});
</script>

@endsection

