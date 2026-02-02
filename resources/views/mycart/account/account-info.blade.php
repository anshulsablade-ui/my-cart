@extends('mycart.layouts.app')
@section('title', 'My Account')
@section('style')
    
@endsection

@section('content')
    <!-- Page content -->
    <main class="content-wrapper">
      <div class="container py-5 mt-n2 mt-sm-0">
        <div class="row pt-md-2 pt-lg-3 pb-sm-2 pb-md-3 pb-lg-4 pb-xl-5">
          @include('mycart.account.sidebar')

          <!-- Personal info content -->
          <div class="col-lg-9">
            <div class="ps-lg-3 ps-xl-0">

              <!-- Page title -->
              <h1 class="h2 mb-1 mb-sm-2">Personal info</h1>

              <!-- Basic info -->
              <div class="border-bottom py-4">
                <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                  <h2 class="h6 mb-0">Basic info</h2>
                  <a class="nav-link hiding-collapse-toggle text-decoration-underline p-0 collapsed" href=".basic-info" data-bs-toggle="collapse" aria-expanded="false" aria-controls="basicInfoPreview basicInfoEdit">Edit</a>
                </div>
                <div class="collapse basic-info show" id="basicInfoPreview">
                  <div class="avatar avatar-lg mb-3">
                    @if (session('user.image'))
                        <img class="img-fluid rounded-circle border" width="60" src="{{ asset('images/users/' . session('user.image') ) }}" alt="{{ session('user.name') }}">
                    @endif
                  </div>
                  <ul class="list-unstyled fs-sm m-0">
                    <li>{{ session('user.name') }}</li>
                    <li>{{ session('user.phone') }}</li>
                  </ul>
                </div>
                <div class="collapse basic-info" id="basicInfoEdit">
                  <form class="row g-3 g-sm-4" id="ProfileUpdate">
                    <div class="col-sm-12" id="previewImage">
                        @if (session('user.image'))
                            <img class="img-fluid rounded-circle border" width="60" src="{{ asset('images/users/' . session('user.image') ) }}" alt="{{ session('user.name') }}">
                        @endif
                    </div>
                    <div class="col-sm-6">
                      <label for="profile_image" class="form-label">Profile image</label>
                      <div class="position-relative">
                        <input type="file" class="form-control" id="profile_image" name="profile_image" value="{{ session('user.name') }}" >
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="name" class="form-label">Full name</label>
                      <div class="position-relative">
                        <input type="text" class="form-control" id="name" name="name" value="{{ session('user.name') }}" >
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="mobile_number" class="form-label">Mobile number</label>
                      <div class="position-relative">
                        <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="{{ session('user.phone') }}" >
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="d-flex gap-3 pt-2 pt-sm-0">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".basic-info" aria-expanded="true" aria-controls="basicInfoPreview basicInfoEdit">Close</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Password -->
              <div class="py-4">
                <div class="nav flex-nowrap align-items-center justify-content-between pb-1 mb-3">
                  <div class="d-flex align-items-center gap-3 me-4">
                    <h2 class="h6 mb-0">Password</h2>
                  </div>
                  <a class="nav-link hiding-collapse-toggle text-decoration-underline p-0 collapsed" href=".password-change" data-bs-toggle="collapse" aria-expanded="false" aria-controls="passChangePreview passChangeEdit">Edit</a>
                </div>
                <div class="collapse password-change show" id="passChangePreview">
                  <ul class="list-unstyled fs-sm m-0">
                    <li>**************</li>
                  </ul>
                </div>
                <div class="collapse password-change" id="passChangeEdit">
                  <form class="row g-3 g-sm-4" id="passwordChangeForm">
                    <div class="col-sm-6">
                      <label for="current_password" class="form-label">Current password</label>
                      <div class="password-toggle">
                        <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Enter your current password" >
                        <label class="password-toggle-button" aria-label="Show/hide password">
                          <input type="checkbox" class="btn-check">
                        </label>
                    </div>
                    <div class="text-danger current_password_error"></div>
                    </div>
                    <div class="col-sm-6">
                      <label for="new_password" class="form-label">New password</label>
                      <div class="password-toggle">
                        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Create new password" >
                        <label class="password-toggle-button" aria-label="Show/hide password">
                          <input type="checkbox" class="btn-check">
                        </label>
                    </div>
                    <div class="text-danger new_password_error"></div>
                    </div>
                    <div class="col-12">
                      <div class="d-flex gap-3 pt-2 pt-sm-0">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".password-change" aria-expanded="true" aria-controls="passChangePreview passChangeEdit">Close</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Delete account -->
              {{-- <div class="pt-3 mt-2 mt-sm-3">
                <h2 class="h6">Delete account</h2>
                <p class="fs-sm">Once you delete your account, there is no going back. Please be certain.</p>
                <a class="text-danger fs-sm fw-medium" href="#!">Delete account</a>
              </div> --}}
            </div>
          </div>
        </div>
      </div>
    </main>

@endsection
@section('script')
<script>
    $(document).ready(function () {

        //logo preview
        $('#profile_image').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#previewImage').html('<img src="' + e.target.result + '" class="img-fluid rounded-circle border" width="60" />');
            }
            reader.readAsDataURL(this.files[0]);
        });

        //profile update
        $("#ProfileUpdate").submit(function (e) { 
            e.preventDefault();
            ajaxCall("{{ route('profile.update') }}", 'POST', new FormData(this), function (response) {
                if (response.status === 'success') {
                    window.location.reload();      
                    // $('.is-invalid').removeClass('is-invalid');
                    // $('.invalid-feedback').remove();
                    // messageAlert(response.message, 'success');
                }
            }, function (error) {
                $('.is-invalid').removeClass('is-invalid');
                var error = JSON.parse(error.responseText);
                $.each(error.message, function (key, value) {
                    $(`#${key}`).addClass('is-invalid').after(`<div class="invalid-feedback">${value}</div>`);
                });
            });
        });

        // password change
        $("#passwordChangeForm").submit(function (e) { 
            e.preventDefault();
            ajaxCall("{{ route('password.update') }}", 'POST', new FormData(this), function (response) {
                if (response.status === 'success') {
                    $('#passwordChangeForm')[0].reset();
                    $("#current_password, #new_password").removeClass('border-danger');
                    $(".current_password_error, .new_password_error").text('');
                    messageAlert(response.message, 'success');
                }
            }, function (error) {
                var error = JSON.parse(error.responseText);
                
                $.each(error.message, function (key, value) {
                    $(`.${key}`).removeClass('border-danger');
                    $(`.${key}_error`).text('');
                    // console.log(error, key, value);
                    $(`#${key}`).addClass('border-danger');
                    $(`.${key}_error`).text(value);
                });
            });
        });
    });
</script>
@endsection