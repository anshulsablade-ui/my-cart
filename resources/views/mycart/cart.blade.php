@extends('mycart.layouts.app')
@section('title', 'My Cart')
@section('style')
    
@endsection
@section('content')
    <main class="content-wrapper">

      <!-- Breadcrumb -->
      <nav class="container pt-3 my-3 my-md-4" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="shop-catalog-electronics.html">Shop</a></li>
          <li class="breadcrumb-item active" aria-current="page">Cart</li>
        </ol>
      </nav>


      <!-- Items in the cart + Order summary -->
      <section class="container pb-5 mb-2 mb-md-3 mb-lg-4 mb-xl-5">
        <h1 class="h3 mb-4">Shopping cart</h1>
        @if (count($carts) == 0)
          <div class="row text-center p-5 mt-5">
            <h2 class="h4 mb-3">Your Cart is empty.</h2>
            <p class="mb-4">Explore our products and add them to your cart.</p>
            <div>
              <a class="btn btn-primary" href="{{ route('home') }}">Continue shopping</a>
            </div>
          </div>  
        @else  
        <div class="row">

          <!-- Items list -->
          <div class="col-lg-8">
            <div class="pe-lg-2 pe-xl-3 me-xl-3">

              <!-- Table of items -->
              <table class="table position-relative z-2 mb-4">
                <thead>
                  <tr>
                    <th scope="col" class="fs-sm fw-normal py-3 ps-0"><span class="text-body">Product</span></th>
                    <th scope="col" class="text-body fs-sm fw-normal py-3 d-none d-xl-table-cell"><span class="text-body">Price</span></th>
                    <th scope="col" class="text-body fs-sm fw-normal py-3 d-none d-md-table-cell"><span class="text-body">Quantity</span></th>
                    <th scope="col" class="text-body fs-sm fw-normal py-3 d-none d-md-table-cell"><span class="text-body">Total</span></th>
                    <th scope="col" class="py-0 px-0">
                      <div class="nav justify-content-end">
                        <button type="button" class="nav-link d-inline-block text-decoration-underline text-nowrap py-3 px-0" id="clearCart">Clear cart</button>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="align-middle">

                    @foreach ($carts as $item)
                        <tr data-cart-id="{{ $item->id }}">
                          <td class="py-3 ps-0">
                            <div class="d-flex align-items-center">
                              <a class="flex-shrink-0" href="{{ route('product.show', $item->product->slug) }}">
                                @if ($item->product->discount_percentage)
                                    <span class="badge text-bg-danger position-absolute start-0">-{{ $item->product->discount_percentage }}%</span>
                                @endif
                                <img src="{{ asset('images/products/thumb/' . ($item->product->primaryImage->image ?? 'no-image.png')) }}" width="110" alt="{{ $item->product->name }}">
                              </a>
                              <div class="w-100 min-w-0 ps-2 ps-xl-3">
                                <h5 class="d-flex animate-underline mb-2">
                                  <a class="d-block fs-sm fw-medium text-truncate animate-target" href="{{ route('product.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                </h5>
                                <ul class="list-unstyled gap-1 fs-xs mb-0">
                                  <li class="d-xl-none">
                                      <span class="text-body-secondary">Price:</span> 
                                      <span class="text-dark-emphasis fw-medium">{{ Number::currency($item->product->final_price, 'INR') }}</span>
                                  </li>
                                </ul>
                                <div class="count-input rounded-2 d-md-none mt-3">
                                  <button type="button" class="btn btn-sm btn-icon decrement-btn">
                                    <i class="ci-minus"></i>
                                  </button>
                                  <input type="number" class="form-control form-control-sm cart-quantity" value="{{ $item->quantity }}" name="quantity" readonly="">
                                  <button type="button" class="btn btn-sm btn-icon increment-btn">
                                    <i class="ci-plus"></i>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="h6 py-3 d-none d-xl-table-cell">{{ Number::currency($item->product->final_price, 'INR') }}</td>
                          <td class="py-3 d-none d-md-table-cell">
                            <div class="count-input">
                              <button type="button" class="btn btn-icon decrement-btn">
                                <i class="ci-minus"></i>
                              </button>
                              <input type="number" class="form-control cart-quantity" value="{{ $item->quantity }}" name="quantity" readonly="">
                              <button type="button" class="btn btn-icon increment-btn">
                                <i class="ci-plus"></i>
                              </button>
                            </div>
                          </td>
                          <td class="h6 py-3 d-none d-md-table-cell total-price">{{ Number::currency($item->product->final_price * $item->quantity, 'INR') }}</td>
                          <td class="text-end py-3 px-0">
                            <button type="button" class="btn-close fs-sm cart-remove-btn" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-sm" data-bs-title="Remove" aria-label="Remove from cart"></button>
                          </td>
                        </tr>
                    @endforeach

                </tbody>
              </table>

              <div class="nav position-relative z-2 mb-4 mb-lg-0">
                <a class="nav-link animate-underline px-0" href="shop-catalog-electronics.html">
                  <i class="ci-chevron-left fs-lg me-1"></i>
                  <span class="animate-target">Continue shopping</span>
                </a>
              </div>
            </div>
          </div>


          <!-- Order summary (sticky sidebar) -->
          <aside class="col-lg-4 order-summary" style="margin-top: -100px">
            <div class="position-sticky top-0" style="padding-top: 100px">
              <div class="bg-body-tertiary rounded-5 p-4 mb-3">
                <div class="p-sm-2 p-lg-0 p-xl-2">
                  <h5 class="border-bottom pb-4 mb-4">Order summary</h5>
                  <ul class="list-unstyled fs-sm gap-3 mb-0">
                    <li class="d-flex justify-content-between">
                      Subtotal ({{ count($carts) }} items):
                      <span class="text-dark-emphasis fw-medium subtotal">{{ Number::currency($subtotal, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Discount:
                      <span class="text-danger fw-medium discount">{{ Number::currency($discounted_price, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Gst tax (18%):
                      <span class="text-dark-emphasis fw-medium gst-amount">{{ Number::currency($gstAmount, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Shipping:
                      <span class="text-dark-emphasis fw-medium">Calculated at checkout</span>
                    </li>
                  </ul>
                  <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-between mb-3">
                      <span class="fs-sm">Grand total:</span>
                      <span class="h5 mb-0 grand-total">{{ Number::currency($grandTotal, 'INR') }}</span>
                    </div>
                    <a class="btn btn-lg btn-primary w-100" href="{{ route('checkout.index') }}">
                      Proceed to checkout
                      <i class="ci-chevron-right fs-lg ms-1 me-n1"></i>
                    </a>
                  </div>
                </div>
              </div>

            </div>
          </aside>
        </div>
        @endif
      </section>

    </main>
@endsection
@section('script')
    <script>
        $(document).ready(function () {

            // Clear cart
            $('body').on('click', '#clearCart', function () {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "delete",
                            url: "{{ route('cart.clear') }}",
                            success: function (response) {
                                window.location.reload();
                            }
                        });
                    }
                })
            })

            // Remove item from cart
            $('body').on('click', '.cart-remove-btn', function () {
                let cartId = $(this).closest('tr').data('cart-id');
                
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
                            type: "delete",
                            url: "{{ route('cart.remove') }}",
                            data: {
                                cart_id: cartId
                            },
                            success: function (response) {
                                window.location.reload();
                            }
                        });
                    }
                })
            });

            $('body').on('click', '.increment-btn', function () {
                let cartId = $(this).closest('tr').data('cart-id');
                let quantity = $(this).closest('.count-input').find('.cart-quantity').val();
                quantity++;
                $(this).closest('tr').find('.cart-quantity').val(quantity);
                updateCartQuantity(cartId, quantity);
            });

            $('body').on('click', '.decrement-btn', function () {
                let cartId = $(this).closest('tr').data('cart-id');
                let quantity = $(this).closest('.count-input').find('.cart-quantity').val();
                if (quantity > 1) {
                    quantity--;
                    $(this).closest('tr').find('.cart-quantity').val(quantity);
                    updateCartQuantity(cartId, quantity);
                }
            });

            function updateCartQuantity(cartId, quantity) {
                $.ajax({
                    type: "post",
                    url: "{{ route('cart.update') }}",
                    data: {
                        cart_id: cartId,
                        quantity: quantity
                    },
                    success: function (response) {
                        // window.location.reload();
                        if(response.status == 'success') {
                            console.log(response.message);

                            $('tr[data-cart-id="' + cartId + '"]').find('.total-price').text('₹' + response.cart.quantity * response.cart.product.final_price);
                            $('.order-summary').find('.subtotal').text(response.subtotal);
                            $('.order-summary').find('.discount').text(response.discounted_price);
                            $('.order-summary').find('.gst-amount').text(response.gstAmount);
                            $('.order-summary').find('.grand-total').text(response.grand_total);
                            
                            messageAlert(response.message, 'success');
                            
                        }
                    }
                });
            }

        });
    </script>
@endsection