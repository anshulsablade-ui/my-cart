<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCountries()
    {
        $countries = Country::all();
        return response()->json($countries);
    }

    public function getStates($id)
    {
        $states = State::where('country_id', $id)->get();
        return response()->json($states);
    }

    public function getCities($id)
    {
        $cities = City::where('state_id', $id)->get();
        return response()->json($cities);
    }
}
