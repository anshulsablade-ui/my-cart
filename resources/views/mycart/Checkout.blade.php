@extends('mycart.layouts.app')
@section('title', 'Checkout')
@section('style')

@endsection
@section('content')
    <!-- Page content -->
    <main class="content-wrapper">
      <div class="container py-5">
        <div class="row pt-1 pt-sm-3 pt-lg-4 pb-2 pb-md-3 pb-lg-4 pb-xl-5">
          <div class="col-lg-8 col-xl-7 mb-5 mb-lg-0">
            <form id="checkoutForm">
            <div class="accordion d-flex flex-column gap-5 pe-lg-4 pe-xl-0" id="checkout">

              <!-- Shipping address form -->
              <div class="d-flex align-items-start">
                <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle fs-sm fw-semibold lh-1 flex-shrink-0" style="width: 2rem; height: 2rem; margin-top: -.125rem">1</div>
                <div class="w-100 ps-3 ps-md-4">
                  <h1 class="h5 mb-md-4">Shipping address</h1>
                    <div class="row mb-4">
                        <div class="col">
                          @foreach ($addresses as $address)
                            <div class="form-check border-bottom">
                              <input type="radio" class="form-check-input" name="address_id" value="{{ $address->id }}">
                              <label class="form-check-label w-100">
                                
                                  <div class="">
                                    <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                                      <div class="d-flex align-items-center gap-3 me-4">
                                        <h2 class="h6 mb-0">{{ $address->name }}</h2>
                                        @if ($address->is_primary == 1)
                                            <span class="badge text-bg-info rounded-pill">Primary</span>
                                        @endif
                                      </div>
                                      
                                    </div>
                                    <div class="collapse primary-address show" id="primaryAddressPreview">
                                      <ul class="list-unstyled fs-sm m-0">
                                        <li>{{ $address->state->name . ' ' . $address->pincode . ', ' . $address->country->name }}</li>
                                        <li>{{ $address->address }}</li>
                                      </ul>
                                    </div>
                                  </div>
                              </label>
                              <a class="nav-link hiding-collapse-toggle text-decoration-underline p-0 collapsed" href="-7.html" data-bs-toggle="collapse" aria-expanded="false" aria-controls="primaryAddressPreview primaryAddressEdit">Edit</a>
                            </div>
                          @endforeach                
                        </div>
                    </div>
                    <div class="nav mb-4">
                      <a class="nav-link px-0" href="#newAddressModal" data-bs-toggle="modal">
                        Add address line
                        <i class="ci-plus fs-base ms-1"></i>
                      </a>
                    </div>
                    <a class="btn btn-lg btn-primary w-100 d-none d-lg-flex" href="checkout-v1-payment.html">
                      Continue
                      <i class="ci-chevron-right fs-lg ms-1 me-n1"></i>
                    </a>
                </div>
              </div>

              <!-- Payment -->
              <div class="d-flex align-items-start">
                <div class="d-flex align-items-center justify-content-center bg-body-secondary text-body-secondary rounded-circle fs-sm fw-semibold lh-1 flex-shrink-0" style="width: 2rem; height: 2rem; margin-top: -.125rem">2</div>
                <h2 class="h5 text-body-secondary ps-3 ps-md-4 mb-0">Payment</h2>
              </div>

              <!-- Payment method -->
              {{-- <div class="d-flex align-items-start">
                <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle fs-sm fw-semibold lh-1 flex-shrink-0" style="width: 2rem; height: 2rem; margin-top: -.125rem">3</div>
                <div class="w-100 ps-3 ps-md-4">
                  <h2 class="h5 mb-0">Payment</h2>
                  <div class="mb-4" id="paymentMethod" role="list">

                    <!-- Cash on delivery -->
                    <div class="mt-4">
                      <div class="form-check mb-0" role="listitem" data-bs-toggle="collapse" data-bs-target="#cash" aria-expanded="false" aria-controls="cash">
                        <label class="form-check-label w-100 text-dark-emphasis fw-semibold">
                          <input type="radio" class="form-check-input fs-base me-2 me-sm-3" name="payment-method">
                          Cash on delivery
                        </label>
                      </div>
                      <div class="collapse" id="cash" data-bs-parent="#paymentMethod">
                        <div class="d-sm-flex align-items-center pt-3 pt-sm-4 pb-2 ps-3 ms-2 ms-sm-3">
                          <span class="fs-sm me-3">I would require a change from:</span>
                          <div class="input-group mt-2 mt-sm-0" style="max-width: 150px">
                            <span class="input-group-text">
                              <i class="ci-dollar-sign"></i>
                            </span>
                            <input type="number" class="form-control" aria-label="Amount (to the nearest dollar)">
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Credit card -->
                    <div class="mt-4">
                      <div class="form-check mb-0" role="listitem" data-bs-toggle="collapse" data-bs-target="#card" aria-expanded="true" aria-controls="card">
                        <label class="form-check-label d-flex align-items-center text-dark-emphasis fw-semibold">
                          <input type="radio" class="form-check-input fs-base me-2 me-sm-3" name="payment-method" checked="">
                          Credit or debit card
                          <span class="d-none d-sm-flex gap-2 ms-3">
                            <img src="assets/img/payment-methods/amex.svg" class="d-block bg-info rounded-1" width="36" alt="Amex">
                            <img src="assets/img/payment-methods/visa-light-mode.svg" class="d-none-dark" width="36" alt="Visa">
                            <img src="assets/img/payment-methods/visa-dark-mode.svg" class="d-none d-block-dark" width="36" alt="Visa">
                            <img src="assets/img/payment-methods/mastercard.svg" width="36" alt="Mastercard">
                            <img src="assets/img/payment-methods/maestro.svg" width="36" alt="Maestro">
                          </span>
                        </label>
                      </div>
                      <div class="collapse show" id="card" data-bs-parent="#paymentMethod">
                        <form class="needs-validation pt-4 pb-2 ps-3 ms-2 ms-sm-3" novalidate="">
                          <div class="position-relative mb-3 mb-sm-4" data-input-format="{&quot;creditCard&quot;: true}">
                            <input type="text" class="form-control form-control-lg form-icon-end" placeholder="Card number" required="">
                            <span class="position-absolute d-flex top-50 end-0 translate-middle-y fs-5 text-body-tertiary me-3" data-card-icon=""></span>
                          </div>
                          <div class="row row-cols-1 row-cols-sm-2 g-3 g-sm-4">
                            <div class="col">
                              <input type="text" class="form-control form-control-lg" data-input-format="{&quot;date&quot;: true, &quot;datePattern&quot;: [&quot;m&quot;, &quot;y&quot;]}" placeholder="MM/YY">
                            </div>
                            <div class="col">
                              <input type="text" class="form-control form-control-lg" maxlength="4" data-input-format="{&quot;numeral&quot;: true, &quot;numeralPositiveOnly&quot;: true, &quot;numeralThousandsGroupStyle&quot;: &quot;none&quot;}" placeholder="CVC">
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>

                    <!-- PayPal -->
                    <div class="mt-4">
                      <div class="form-check mb-0" role="listitem" data-bs-toggle="collapse" data-bs-target="#paypal" aria-expanded="false" aria-controls="paypal">
                        <label class="form-check-label d-flex align-items-center text-dark-emphasis fw-semibold">
                          <input type="radio" class="form-check-input fs-base me-2 me-sm-3" name="payment-method">
                          PayPal
                          <img src="assets/img/payment-methods/paypal-icon.svg" class="ms-3" width="16" alt="PayPal">
                        </label>
                      </div>
                      <div class="collapse" id="paypal" data-bs-parent="#paymentMethod"></div>
                    </div>

                    <!-- Google Pay -->
                    <div class="mt-4">
                      <div class="form-check mb-0" role="listitem" data-bs-toggle="collapse" data-bs-target="#googlepay" aria-expanded="false" aria-controls="googlepay">
                        <label class="form-check-label d-flex align-items-center text-dark-emphasis fw-semibold">
                          <input type="radio" class="form-check-input fs-base me-2 me-sm-3" name="payment-method">
                          Google Pay
                          <img src="assets/img/payment-methods/google-icon.svg" class="ms-3" width="20" alt="Google Pay">
                        </label>
                      </div>
                      <div class="collapse" id="googlepay" data-bs-parent="#paymentMethod"></div>
                    </div>
                  </div>

                  <!-- Add promo code button -->
                  <div class="nav pb-3 mb-2 mb-sm-3">
                    <a class="nav-link animate-underline p-0" href="#!">
                      <i class="ci-plus-circle fs-xl ms-a me-2"></i>
                      <span class="animate-target">Add a promo code or a gift card</span>
                    </a>
                  </div>

                  <!-- Additional comments -->
                  <textarea class="form-control form-control-lg mb-4" rows="3" placeholder="Additional comments"></textarea>

                  <div class="form-check mb-lg-4">
                    <input type="checkbox" class="form-check-input" id="accept-terms">
                    <label for="accept-terms" class="form-check-label nav align-items-center">
                      I accept the
                      <a class="nav-link text-decoration-underline fw-normal ms-1 p-0" href="terms-and-conditions.html">Terms and Conditions</a>
                    </label>
                  </div>

                  <!-- Pay button visible on screens > 991px wide (lg breakpoint) -->
                  <a class="btn btn-lg btn-primary w-100 d-none d-lg-flex" href="checkout-v1-thankyou.html">Pay $2,406.90</a>
                </div>
              </div> --}}
            </div>
            </form>
          </div>


          <!-- Order summary (sticky sidebar) -->
          <aside class="col-lg-4 offset-xl-1" style="margin-top: -100px">
            <div class="position-sticky top-0" style="padding-top: 100px">
              <div class="bg-body-tertiary rounded-5 p-4 mb-3">
                <div class="p-sm-2 p-lg-0 p-xl-2">
                  <div class="border-bottom pb-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                      <h5 class="mb-0">Order summary</h5>
                      <div class="nav">
                        <a class="nav-link text-decoration-underline p-0" href="checkout-v1-cart.html">Edit</a>
                      </div>
                    </div>
                    <a class="d-flex align-items-center gap-2 text-decoration-none" href="#orderPreview" data-bs-toggle="offcanvas">
                        @foreach ($cartItems as $item)
                            <div class="ratio ratio-1x1" style="max-width: 64px">
                              <img src="{{ asset('images/products/thumb/' . $item->product->primaryImage->image) }}" class="d-block p-1" alt="iPhone">
                            </div>
                        @endforeach
                      
                      <i class="ci-chevron-right text-body fs-xl p-0 ms-auto"></i>
                    </a>
                  </div>
                  <ul class="list-unstyled fs-sm gap-3 mb-0">
                    <li class="d-flex justify-content-between">
                      Subtotal (3 items):
                      <span class="text-dark-emphasis fw-medium">{{ Number::currency($subtotal, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Discount:
                      <span class="text-danger fw-medium">{{ Number::currency($discounted_price, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Gst tax (18%):
                      <span class="text-dark-emphasis fw-medium">{{ Number::currency($gstAmount, 'INR') }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                      Shipping:
                      <span class="text-dark-emphasis fw-medium">Free</span>
                    </li>
                  </ul>
                  <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-between mb-3">
                      <span class="fs-sm">Grand total:</span>
                      <span class="h5 mb-0">{{ Number::currency($grandTotal, 'INR') }}</span>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </aside>
        </div>
      </div>
    </main>

    <!-- Fixed positioned pay button that is visible on screens < 992px wide (lg breakpoint) -->
    <div class="fixed-bottom z-sticky w-100 py-2 px-3 bg-body border-top shadow d-lg-none">
      <a class="btn btn-lg btn-primary w-100" href="checkout-v1-payment.html">
        Continue
        <i class="ci-chevron-right fs-lg ms-1 me-n1"></i>
      </a>
    </div>

    <!-- Add new address modal -->
    <div class="modal fade" id="newAddressModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="newAddressModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newAddressModalLabel">Add new address</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addressForm" class="row g-3 g-lg-4">
              <div class="col-lg-6">
                <div class="position-relative">
                  <label for="address_name" class="form-label">Address name</label>
                  <input type="text" class="form-control" id="address_name" name="address_name" >
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label for="address_type" class="form-label">Address type</label>
                  <select class="form-select" id="address_type" name="address_type" aria-label="Select address type">
                    <option value="">Select address type...</option>
                    <option value="home">Home</option>
                    <option value="office">office</option>
                    <option value="shipping">Shipping</option>
                    <option value="billing">Billing</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="position-relative">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" class="form-control" id="address" name="address" >
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label for="mobile_number" class="form-label">Mobile number</label>
                  <input type="text" class="form-control" id="mobile_number" name="mobile_number" >
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label class="form-label">Country</label>
                  <select class="form-select" id="country" name="country_id" aria-label="Select country" >
                    <option value="">Select country...</option>
                    @foreach ($countries as $country)
                      <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label class="form-label">State</label>
                  <select class="form-select" id="state" name="state_id" aria-label="Select state" >
                    <option value="">Select state...</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label class="form-label">City</label>
                  <select class="form-select" id="city" name="city_id" aria-label="Select city" >
                    <option value="">Select city...</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label for="pincode" class="form-label">Pin code</label>
                  <input type="text" class="form-control" id="pincode" name="pincode" >
                </div>
              </div>
              <div class="col-12">
                <div class="form-check mb-0">
                  <input type="checkbox" class="form-check-input" id="is_primary" name="is_primary" value="1">
                  <label for="is_primary" class="form-check-label">Set as primary address</label>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex gap-3 pt-2 pt-sm-0">
                  <button type="submit" class="btn btn-primary">Add address</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function () {

            $("#addressForm").submit(function (e) { 
                e.preventDefault();

                ajaxCall('{{ route('address.store') }}', 'POST', new FormData(this), function (response) {
                    if (response.status === 'success') {
                        window.location.reload();
                    }
                },
                    function (response) {
                        var response = JSON.parse(response.responseText);
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        $.each(response.message, function (key, value) { 
                             $(`#${key}`).addClass('is-invalid').after(` <div class="invalid-feedback">${value}</div> `);
                        });
                    }
                );

            });

            $('#country').on('change', function () {
                let country_id = $(this).val();
                ajaxCall('/getStates/' + country_id, 'GET', null, function (response) {
                    var data = '<option value="">Select State...</option>';
                    $.each(response, function(index, value) {
                        data += `<option value="${value.id}">${value.name}</option>`;
                    });
                    $('#state').html(data);
                });
            });

            $('#state').on('change', function () {
                let state_id = $(this).val();
                ajaxCall('/getCities/' + state_id, 'GET', null, function (response) {
                    var data = '<option value="">Select City...</option>';
                    $.each(response, function(index, value) {
                        data += `<option value="${value.id}">${value.name}</option>`;
                    });
                    $('#city').html(data);
                });
            });
        });
    </script>
@endsection
