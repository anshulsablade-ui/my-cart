@extends('admin.layouts.app')
@section('title', 'Calendar')

@section('style')
    <link href="{{ asset('vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <style>
        .flatpickr-wrapper {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <div class="card overflow-hidden">
      <div class="card-header">
        <div class="row gx-0 align-items-center">
          <div class="col-auto d-flex order-md-0">
            <h5 class="mb-0">Calendar</h5>
          </div>
          <div class="col d-flex justify-content-end order-md-2">
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addEventModal"> <span class="fas fa-plus me-2"></span>Add Schedule</button>
          </div>
        </div>
      </div>
      <div class="card-body scrollbar">
        <div class="calendar-outline" id="calendar"></div>
      </div>
    </div>

    <div class="modal fade" id="eventDetailsModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border"></div>
      </div>
    </div>
    <div class="modal fade" id="addEventModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content border">
          <form id="addEventForm" autocomplete="off">
            <div class="modal-header px-x1 bg-body-tertiary border-bottom-0">
              <h5 class="modal-title">Create Schedule</h5><button class="btn-close me-n1" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-x1">
            <div class="mb-3">
              <label class="fs-9" for="eventTitle">Title</label>
              <input class="form-control" id="title" type="text" name="title" />
            </div>
            <div class="mb-3">
              <label class="fs-9" for="eventStartDate">Start Date</label>
              <input class="form-control datetimepicker" id="startDate" type="text" name="startDate" placeholder="yyyy/mm/dd hh:mm" data-options='{"static":"true","enableTime":"true","dateFormat":"Y-m-d H:i"}' />
            </div>
            <div class="mb-3">
              <label class="fs-9" for="eventEndDate">End Date</label>
              <input class="form-control datetimepicker" id="endDate" type="text" name="endDate" placeholder="yyyy/mm/dd hh:mm" data-options='{"static":"true","enableTime":"true","dateFormat":"Y-m-d H:i"}' />
            </div>
            <div class="mb-3">
              <label class="fs-9" for="eventDescription">Description</label>
              <textarea class="form-control" rows="3" name="description" id="description"></textarea>
            </div>
            <div class="modal-footer d-flex justify-content-end align-items-center bg-body-tertiary border-0 p-0">
              <button class="btn btn-primary px-4 m-0" type="submit">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
@endsection

@section('script')
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
  <script src="{{ asset('vendors/flatpickr/flatpickr.min.js') }}"></script>
  <script>
    $(document).ready(function () {

      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        selectable: true,
        timeZone: 'Asia/Kolkata',
        themeSystem: 'bootstrap5',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        weekNumbers: true,
        dayMaxEvents: true,

        select: function (info) {
          $("#addEventModal #startDate").flatpickr().setDate(info.start);
          $("#addEventModal #endDate").flatpickr().setDate(info.end);
          $("#addEventModal").modal('show');
          calendar.unselect();
        },

        eventClick: function (info) {
          var eventObj = info.event;
          $("#eventDetailsModal .modal-content").html(
            getTemplate(eventObj)
          );
          $("#eventDetailsModal").modal('show');
        },

        // events: 'https://fullcalendar.io/api/demo-feeds/events.json'
        events: "{{ route('admin.calendar.events') }}",
      });

      calendar.render();

      // Form submit handler
      $("#addEventForm").submit(function (e) {
        e.preventDefault();
        let farmData = new FormData(this);
        $.ajax({
          type: "post",
          url: "{{ route('admin.events.store') }}",
          data: farmData,
          dataType: "json",
          processData: false,
          contentType: false,
          success: function (response) {
            calendar.refetchEvents();
            $('#addEventForm')[0].reset();
            $('#addEventModal').modal('hide');
          },
          error: function (response) {
            var response = JSON.parse(response.responseText);
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            if (response.status == 'error') {
              $.each(response.errors, function (key, value) {
                $(`#${key}`).addClass('is-invalid').after(` <span class="invalid-feedback">${value}</span> `);
              });
            }
          }
        });
      });

    });

    var getTemplate = function getTemplate(event) {
        return `
          <div class="modal-header bg-body-tertiary ps-card pe-5 border-bottom-0">
            <div><h5 class="modal-title mb-0">${event.title}</h5></div>
            <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body px-card pb-card pt-1 fs-10">

          ${event.extendedProps.description ? 
             `<div class="d-flex mt-3">${getStackIcon('fas fa-align-left')}
                <div class="flex-1">
                  <h6>Description</h6>
                  <p class="mb-0">${event.extendedProps.description.split(' ').slice(0, 30).join(' ')}</p>
                </div>
              </div>` : ''}

              <div class="d-flex mt-3">
                ${getStackIcon('fas fa-calendar-check')}
                <div class="flex-1">
                  <h6>Date and Time</h6>
                  <p class="mb-1">
                    ${formatDate(event.start)}
                    ${event.end ? ` – <br/>${formatDate(event.end)}` : ''}
                  </p>
                </div>
              </div>
          </div>
          <div class="modal-footer d-flex justify-content-end bg-body-tertiary px-card border-top-0">
            <button type="button" class="btn btn-falcon-default btn-sm" data-bs-dismiss="modal">Close</button>
          </div>`;
      };

      const formatDate = (d) => { if (!d) return ''; return new Date(d).toLocaleString(); };

  </script>
@endsection