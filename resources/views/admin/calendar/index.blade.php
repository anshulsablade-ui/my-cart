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

        <div class="modal fade" id="authentication-modal" tabindex="-1" role="dialog" aria-labelledby="authentication-modal-label" aria-hidden="true">
          <div class="modal-dialog mt-6" role="document">
            <div class="modal-content border-0">
              <div class="modal-header px-5 position-relative modal-shape-header bg-shape">
                <div class="position-relative z-1">
                  <h4 class="mb-0 text-white" id="authentication-modal-label">Register</h4>
                  <p class="fs-10 mb-0 text-white">Please create your free Falcon account</p>
                </div>
                <div data-bs-theme="dark"><button class="btn-close position-absolute top-0 end-0 mt-2 me-2" data-bs-dismiss="modal" aria-label="Close"></button></div>
              </div>
              <div class="modal-body py-4 px-5">
                <form>
                  <div class="mb-3"><label class="form-label" for="modal-auth-name">Name</label><input class="form-control" type="text" autocomplete="on" id="modal-auth-name" /></div>
                  <div class="mb-3"><label class="form-label" for="modal-auth-email">Email address</label><input class="form-control" type="email" autocomplete="on" id="modal-auth-email" /></div>
                  <div class="row gx-2">
                    <div class="mb-3 col-sm-6"><label class="form-label" for="modal-auth-password">Password</label><input class="form-control" type="password" autocomplete="on" id="modal-auth-password" /></div>
                    <div class="mb-3 col-sm-6"><label class="form-label" for="modal-auth-confirm-password">Confirm Password</label><input class="form-control" type="password" autocomplete="on" id="modal-auth-confirm-password" /></div>
                  </div>
                  <div class="form-check"><input class="form-check-input" type="checkbox" id="modal-auth-register-checkbox" /><label class="form-label" for="modal-auth-register-checkbox">I accept the <a href="#!">terms </a>and <a class="white-space-nowrap" href="#!">privacy policy</a></label></div>
                  <div class="mb-3"><button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Register</button></div>
                </form>
                <div class="position-relative mt-5">
                  <hr />
                  <div class="divider-content-center">or register with</div>
                </div>
                <div class="row g-2 mt-2">
                  <div class="col-sm-6"><a class="btn btn-outline-google-plus btn-sm d-block w-100" href="#"><span class="fab fa-google-plus-g me-2" data-fa-transform="grow-8"></span> google</a></div>
                  <div class="col-sm-6"><a class="btn btn-outline-facebook btn-sm d-block w-100" href="#"><span class="fab fa-facebook-square me-2" data-fa-transform="grow-8"></span> facebook</a></div>
                </div>
              </div>
            </div>
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
                {{-- <div class="mb-3">
                  <label class="form-label" for="timepicker">Select Date Range</label>
                  <input class="form-control datetimepicker" id="timepicker" type="text" placeholder="dd/mm/yy to dd/mm/yy" data-options='{"mode":"range","dateFormat":"d/m/y","disableMobile":true}' />
                </div> --}}
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
                  <textarea class="form-control" rows="3" name="description" id="eventDescription"></textarea>
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
                dayMaxEvents: true, // allow "more" link when too many events
                // eventClick: function(info) {
                
                //     var eventObj = info.event;   // real event object
                
                //     console.log(eventObj);
                
                //     $("#eventDetailsModal .modal-content").html(
                //         getTemplate(eventObj)
                //     );
                
                //     $("#eventDetailsModal").modal('show');
                // }

             
                // events: 'https://fullcalendar.io/api/demo-feeds/events.json'
                events: "{{ route('admin.calendar.events') }}",
            });

            calendar.render();

            $("#addEventForm").submit(function (e) { 
                e.preventDefault();
                let farmData = new FormData(this);
                $.ajax({
                    type: "post",
                    url: "{{ route('admin.calendar.events.store') }}",
                    data: farmData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        window.location.reload();
                    },
                    error: function (response) {
                        var response = JSON.parse(response.responseText);
                        console.log(response);
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

// var getTemplate = function getTemplate(event) {
//   return `
// <div class="modal-header bg-body-tertiary ps-card pe-5 border-bottom-0">
//   <div>
//     <h5 class="modal-title mb-0">${event.title}</h5>
//   </div>
//   <button type="button" class="btn-close position-absolute end-0 top-0 mt-3 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
// </div>

// <div class="modal-body px-card pb-card pt-1 fs-10">

//   ${
//     event.extendedProps.description
//       ? `
//       <div class="d-flex mt-3">
//         ${getStackIcon('fas fa-align-left')}
//         <div class="flex-1">
//           <h6>Description</h6>
//           <p class="mb-0">
//             ${event.extendedProps.description.split(' ').slice(0, 30).join(' ')}
//           </p>
//         </div>
//       </div>
//       `
//       : ''
//   }

//   <div class="d-flex mt-3">
//     ${getStackIcon('fas fa-calendar-check')}
//     <div class="flex-1">
//       <h6>Date and Time</h6>
//       <p class="mb-1">
//         ${window.dayjs && window.dayjs(event.start).format('dddd, MMMM D, YYYY, h:mm A')}
//         ${
//           event.end
//             ? ` – <br/>
//                ${window.dayjs && window.dayjs(event.end).subtract(1,'day').format('dddd, MMMM D, YYYY, h:mm A')}`
//             : ''
//         }
//       </p>
//     </div>
//   </div>

//   ${
//     event.schedules
//       ? `
//       <div class="d-flex mt-3">
//         ${getStackIcon('fas fa-clock')}
//         <div class="flex-1">
//           <h6>Schedule</h6>
//           <ul class="list-unstyled timeline mb-0">
//             ${event.schedules.map(s => `<li>${s.title}</li>`).join('')}
//           </ul>
//         </div>
//       </div>
//       `
//       : ''
//   }

// </div>

// <div class="modal-footer d-flex justify-content-end bg-body-tertiary px-card border-top-0">
//   <button type="button" class="btn btn-falcon-default btn-sm" data-bs-dismiss="modal">
//     Close
//   </button>
// </div>
// `;
// };

    </script>
@endsection