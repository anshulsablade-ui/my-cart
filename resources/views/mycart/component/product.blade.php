<div class="row row-cols-2 row-cols-md-3 g-4 pb-3 mb-3">

    @forelse ($products as $product)
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
              @if ($product->discount_percentage)
                <span class="badge text-bg-danger position-absolute start-0">-{{ $product->discount_percentage }}%</span>
              @endif
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
    @empty
    <div class="w-100">
      <p class="text-center py-5">No products found</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<nav class="border-top mt-4 pt-3" aria-label="Catalog pagination">
    {{ $products->links('mycart.pagination.catalog') }}
</nav>