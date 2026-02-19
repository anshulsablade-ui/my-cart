<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MapWeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        // dd($request->lat);
        $key = config('services.openweather.key');

        $response = Http::get(
            'https://api.openweathermap.org/data/2.5/weather',
            [
                'lat' => $request->lat,
                'lon' => $request->lng,
                'appid' => $key,
                'units' => 'metric'
            ]
        );



        return response()->json([
            'status' => 'success',
            'data' => $response->json()
        ]);


    }
}
