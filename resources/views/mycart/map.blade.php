@extends('mycart.layouts.app')

@section('title', 'Map View')

@section('content')

<main class="content-wrapper">
    <section class="container">

        <div class="row justify-content-center">
            <div class="col-12 py-4">
    
                <div id="map" style="height:400px;width:100%;"></div>
    
                <div class="card mt-3">
                    <div class="card-body">
                        <h6>Weather at selected location</h6>
    
                        <div id="weatherResult">
                            Drag the marker to load weather...
                        </div>
                    </div>
                </div>
    
            </div>
        </div>

    </section>
</main>

@endsection

@section('script')

<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.map_key') }}"></script>

<script>
$(function () {

    let map, marker;

    function initMap() {

        const surat = { lat: 21.1702, lng: 72.8311 };

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: surat
        });

        marker = new google.maps.Marker({
            position: surat,
            map: map,
            draggable: true
        });

        // Load weather for default position
        loadWeather(surat.lat, surat.lng);

        // When marker drag ends
        marker.addListener('dragend', function (e) {

            console.log('Marker dragged to: ', e.latLng.lat(), e.latLng.lng());
            let lat = e.latLng.lat();
            let lng = e.latLng.lng();

            loadWeather(lat, lng);
        });
    }

    function loadWeather(lat, lng)
    {
        $('#weatherResult').html('Loading...');

        $.ajax({
            url: "{{ route('map.weather') }}",
            type: "GET",
            data: {
                lat: lat,
                lng: lng
            },
            success: function (res) {

                if (res.data.main) {

                    let html = `
                        <b>City:</b> ${res.data.name}<br>
                        <b>Temperature:</b> ${res.data.main.temp} °C<br>
                        <b>Feels like:</b> ${res.data.main.feels_like} °C<br>
                        <b>Humidity:</b> ${res.data.main.humidity}%<br>
                        <b>Weather:</b> ${res.data.weather[0].description}
                    `;

                    $('#weatherResult').html(html);
                } else {
                    $('#weatherResult').html('Weather not found');
                }
            },
            error: function () {
                $('#weatherResult').html('Error loading weather');
            }
        });
    }

    initMap();

});
</script>

@endsection