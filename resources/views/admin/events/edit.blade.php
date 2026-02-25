@extends('admin.layouts.app')
@section('title', 'Event Edit')

@section('style')
    <link href="{{ asset('vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <style>
        .flatpickr-wrapper {
            width: 100%;
        }
    </style>
@endsection
@section('content')

    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Event Update</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form class="row" id="eventForm">
                @method('put')
                <div class="col-6 mb-3">
                    <label class="fs-9" for="title">Title</label>
                    <input class="form-control" id="title" type="text" name="title" value="{{ $event->title }}" />
                </div>
                <div class="col-6 mb-3">
                    <label class="fs-9" for="event_type">Event Type</label>
                    <select class="form-control" id="event_type" name="event_type">
                        <option value="">Select Event Type</option>
                        <option value="meeting" {{ $event->event_type == 'meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="holiday" {{ $event->event_type == 'holiday' ? 'selected' : '' }}>Holiday</option>
                        <option value="deadline" {{ $event->event_type == 'deadline' ? 'selected' : '' }}>Deadline</option>
                        <option value="reminder" {{ $event->event_type == 'reminder' ? 'selected' : '' }}>Reminder</option>
                        <option value="personal" {{ $event->event_type == 'personal' ? 'selected' : '' }}>Personal</option>
                        <option value="birthday" {{ $event->event_type == 'birthday' ? 'selected' : '' }}>Birthday</option>
                        <option value="other" {{ $event->event_type == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-6 mb-3">
                    <label class="fs-9" for="startDate">Start Date</label>
                    <input class="form-control datetimepicker" id="startDate" type="text" name="startDate" value="{{ $event->start_date }}" placeholder="yyyy/mm/dd hh:mm" data-options='{"static":"true","enableTime":"true","dateFormat":"Y-m-d H:i"}' />
                </div>
                <div class="col-6 mb-3">
                    <label class="fs-9" for="endDate">End Date</label>
                    <input class="form-control datetimepicker" id="endDate" type="text" name="endDate" value="{{ $event->end_date }}" placeholder="yyyy/mm/dd hh:mm" data-options='{"static":"true","enableTime":"true","dateFormat":"Y-m-d H:i"}' />
                </div>
                <div class="col-12 mb-3">
                    <label class="fs-9" for="description">Description</label>
                    <textarea class="form-control" rows="3" name="description" id="description">{{ $event->description }}</textarea>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('script')
<script src="{{ asset('vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            $('#eventForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                ajaxCall("{{ route('admin.events.update', $event->id) }}", 'POST', formData, function (response) {
                    if (response.status === 'success') {
                        window.location.href = '{{ route('admin.events.index') }}';
                    }
                }, function (res) {
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    var res = JSON.parse(res.responseText);

                    $.each(res.errors, function (key, value) {
                        $(`#${key}`).addClass('is-invalid').after(` <span class="invalid-feedback">${value}</span> `);
                    });
                });
            });

        });
    </script>
@endsection