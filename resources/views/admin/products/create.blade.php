@extends('admin.layouts.app')
@section('title', 'Product Create')

@section('style')
    <link href="{{ asset('/vendors/dropzone/dropzone.css') }}" rel="stylesheet" />
@endsection
@section('content')

    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Product Create</h5>
                </div>
                <div class="col-8 col-sm-auto ms-auto text-end ps-0">
                    <div>
                        {{-- <button class="btn btn-falcon-default btn-sm" type="button"
                            onclick="window.location='{{ route('admin.products.create') }}'">
                            <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                            <span>Add New Product</span>
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="row" id="productForm">
                <div class="col-12 mb-3">
                    <label class="form-label" for="name">Name:</label>
                    <input class="form-control" id="name" type="text" name="name" />
                </div>
                <div class="col-4 mb-3">
                    <label class="form-label" for="price">Price:</label>
                    <input class="form-control" id="price" type="text" name="price" />
                </div>
                <div class="col-4 mb-3">
                    <label class="form-label" for="brand">Brand:</label>
                    <select class="form-select" id="brand" name="brand">
                        <option value="" selected>Select Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 mb-3">
                    <label class="form-label" for="category">category:</label>
                    <select class="form-select" id="category" name="category">
                        <option value="" selected>Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="images">Image:</label>
                    <input class="form-control" id="images" type="file" name="images[]" multiple />
                </div>
                <div class="col-12 mb-3">
                    {{-- // images preview --}}
                    <div class="row" id="imagePreview"></div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label" for="description">Description:</label>
                    <textarea class="form-control" id="description" rows="4" name="description"></textarea>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label" for="stock">Stock:</label>
                    <input class="form-control" id="stock" type="text" name="stock" />
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label" for="status">Select status: </label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('/vendors/dropzone/dropzone-min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#productForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                ajaxCall('{{ route('admin.products.store') }}', 'POST', formData, function (response) {
                    if (response.status === 'success') {
                        window.location.href = '{{ route('admin.products.index') }}';
                    }
                }, function (error) {
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    var error = JSON.parse(error.responseText);
                    // console.log(error);
                    $.each(error.message, function (key, value) {
                        $(`#${key}`).addClass('is-invalid').after(` <span class="invalid-feedback">${value}</span> `);
                    });
                });
            });


            $('#images').change(function () {
                var files = $(this)[0].files; // 'this' refers to $('#images')
                var previewContainer = $('#imagePreview');

                // Clear previous previews
                previewContainer.empty();

                // Check if files are selected
                if (files) {
                    $.each(files, function (i, file) {
                        // Ensure the file is an image
                        if (/\.(jpe?g|png|gif|webp)$/i.test(file.name)) {
                            var reader = new FileReader();

                            reader.onload = function (e) {
                                // Append each image
                                previewContainer.append(
                                    `<div class="col-2 mb-2">
                                <img src="${e.target.result}" class="img-fluid img-thumbnail border" />
                                <small class="text-muted text-truncate d-block">${file.name}</small>
                            </div>`
                                );
                            }

                            reader.readAsDataURL(file);
                        }
                    });
                }
            });

        });
    </script>
@endsection