@extends('mycart.layouts.app')
@section('title', 'Account Addresses')
@section('style')
    
@endsection
@section('content')
    <!-- Page content -->
    <main class="content-wrapper">
      <div class="container py-5 mt-n2 mt-sm-0">
        <div class="row pt-md-2 pt-lg-3 pb-sm-2 pb-md-3 pb-lg-4 pb-xl-5">
          @include('mycart.account.sidebar')

          <!-- Addresses content -->
          <div class="col-lg-9">
            <div class="ps-lg-3 ps-xl-0">

              <!-- Page title -->
              <h1 class="h2 mb-1 mb-sm-2">Addresses</h1>

              @forelse ($addresses as $address)
                <!-- Shipping address -->
                <div class="border-bottom py-4">
                  <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                    <div class="d-flex align-items-center gap-3 me-4">
                      <h2 class="h6 mb-0">{{ $address->name }}</h2>
                        @if ($address->is_primary == 1)
                            <span class="badge text-bg-info rounded-pill">Primary</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a class="nav-link text-decoration-underline text-danger p-0 delete" href="{{ route('address.delete', $address->id) }}">Delete</a>
                        <a class="nav-link text-decoration-underline p-0 address-edit" href="{{ route('address.edit', $address->id) }}">Edit</a>
                    </div>
                  </div>
                  <div class="primary-address">
                    <ul class="list-unstyled fs-sm m-0">
                      <li>{{ $address->state->name . ' ' . $address->pincode . ', ' . $address->country->name }}</li>
                      <li>{{ $address->address }}</li>
                    </ul>
                  </div>
                </div>                  
              @empty
                <div class="text-center py-5">
                    <p class="text-muted mb-3">You haven’t added any addresses yet.</p>
                </div>
              @endforelse

              <!-- Add address button -->
              <div class="nav pt-4">
                <a class="nav-link animate-underline fs-base px-0" href="#addressModal" data-bs-toggle="modal">
                  <i class="ci-plus fs-lg ms-n1 me-2"></i>
                  <span class="animate-target">Add address</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Add new address modal -->
    <div class="modal fade" id="addressModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addressModalLabel">Add new address</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addressForm" class="row g-3 g-lg-4">
              <input type="hidden" id="address_id" name="address_id" value="">
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
                  <select class="form-select" id="country" name="country" aria-label="Select country" >
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
                  <select class="form-select" id="state" name="state" aria-label="Select state" >
                    <option value="">Select state...</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="position-relative">
                  <label class="form-label">City</label>
                  <select class="form-select" id="city" name="city" aria-label="Select city" >
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
                  <button type="submit" class="btn btn-primary" id="address-submit">Add address</button>
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

        // Reset form on modal close
        $('#addressModal').on('hidden.bs.modal', function () {
          $('#addressForm')[0].reset();
          $('.is-invalid').removeClass('is-invalid');
          $('.invalid-feedback').remove();
          $('#address_id').val('');
          $("#address-submit").text("Add address");
        });

        // Submit address form
        $("#addressForm").submit(function (e) {
          e.preventDefault();

          let addressId = $('#address_id').val();
          let url = addressId ? `/home/address/${addressId}` : "{{ route('address.store') }}";

          let formData = new FormData(this);
          if (addressId) {
              formData.append('_method', 'PUT'); 
          }

          ajaxCall(url, 'POST', formData, function (response) {
            if (response.status === 'success') {
              window.location.reload();
            }
          }, function (response) {
            let res = JSON.parse(response.responseText);
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.each(res.message, function (key, value) {
              $('#' + key).addClass('is-invalid')
                .after(`<div class="invalid-feedback">${value}</div>`);
            });
          });
        });

        // Edit address
        $('body').on('click', '.address-edit', function (e) {
          e.preventDefault();

          let url = $(this).attr('href');

          $.get(url, function (response) {
            if (response.status === 'success') {
              let data = response.data;

              $('#address_id').val(data.id);
              $('#address_name').val(data.name);
              $('#address_type').val(data.address_type);
              $('#address').val(data.address);
              $('#mobile_number').val(data.phone);
              $('#pincode').val(data.pincode);
              $('#is_primary').prop('checked', data.is_primary);

              // Country
              $('#country').val(data.country_id);

              // Load states → then set state
              getStates(data.country_id, function () {
                $('#state').val(data.state_id);

                // Load cities → then set city
                getCities(data.state_id, function () {
                  $('#city').val(data.city_id);
                });
              });
              $("#address-submit").text("Update Address");

              $('#addressModal').modal('show');
            } else {
              messageAlert(response.message, "info");
            }
          });
        });

        // Change handlers
        $('#country').on('change', function () {
          getStates($(this).val());
        });

        $('#state').on('change', function () {
          getCities($(this).val());
        });

        // Load states
        function getStates(country_id, callback = null) {
          if (!country_id) {
            $('#state').html('<option value="">Select State...</option>');
            return;
          }

          ajaxCall('/getStates/' + country_id, 'GET', null, function (response) {
            let html = '<option value="">Select State...</option>';
            $.each(response, function (_, value) {
              html += `<option value="${value.id}">${value.name}</option>`;
            });
            $('#state').html(html);

            if (callback) callback();
          });
        }

        // Load cities
        function getCities(state_id, callback = null) {
          if (!state_id) {
            $('#city').html('<option value="">Select City...</option>');
            return;
          }

          ajaxCall('/getCities/' + state_id, 'GET', null, function (response) {
            let html = '<option value="">Select City...</option>';
            $.each(response, function (_, value) {
              html += `<option value="${value.id}">${value.name}</option>`;
            });
            $('#city').html(html);

            if (callback) callback();
          });
        }
    });
  </script>
@endsection