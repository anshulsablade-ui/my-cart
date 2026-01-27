<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAddressRequest;
use App\Models\Country;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', session()->get('user.id'))->with('country', 'state', 'city')->get();
        $countries = Country::all();
        return view('mycart.account.addresses', compact('addresses', 'countries'));
    }

    public function store(UserAddressRequest $request)
    {
        // dd($request->all());
        $request->validated();
        $addresses = UserAddress::where('user_id', session()->get('user.id'))->where('is_primary', '1')->first();
        if ($addresses && $request->is_primary == '1') {
            $addresses->update([
                'is_primary' => '0',
            ]);
        }
        UserAddress::create([
            'user_id' => session()->get('user.id'),
            'name' => $request->address_name,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'phone' => $request->mobile_number,
            'country_id' => $request->country,
            'state_id' => $request->state,
            'city_id' => $request->city,
            'pincode' => $request->pincode,
            'is_primary' => $request->is_primary ?? '0',
            'status' => $request->status ?? 'active',
        ]);

        session()->flash('success', 'Address added successfully');
        return response()->json(['status' => 'success', 'message' => 'Address added successfully'], 201);
    }

    public function edit($id)
    {
        $address = UserAddress::where('id', $id)->where('user_id', session()->get('user.id'))->with('country', 'state', 'city')->first();
        return response()->json(['status' => 'success', 'data' => $address], 200);
    }

    public function update(UserAddressRequest $request)
    {
        $request->validated();

        $userAddress = UserAddress::where('id', $request->id)->where('user_id', session()->get('user.id'))->first();

        if (!$userAddress) {
            return response()->json(['status' => 'error', 'message' => 'Address not found'], 404);
        }

        $addresses = UserAddress::where('user_id', session()->get('user.id'))->where('is_primary', '1')->first();
        if ($addresses && $request->is_primary == '1') {
            $addresses->update([
                'is_primary' => '0',
            ]);
        }

        $userAddress->update([
            'name' => $request->address_name,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'phone' => $request->mobile_number,
            'country_id' => $request->country,
            'state_id' => $request->state,
            'city_id' => $request->city,
            'pincode' => $request->pincode,
            'is_primary' => $request->is_primary ?? '0',
            'status' => $request->status ?? 'active',
        ]);
        
        session()->flash('success', 'Address updated successfully');
        return response()->json(['status' => 'success', 'message' => 'Address updated successfully'], 201);
    }

    public function delete(Request $request)
    {
        $address = UserAddress::where('id', $request->id)->where('user_id', session()->get('user.id'))->first();
        if (!$address) {
            return response()->json(['status' => 'error', 'message' => 'Address not found'], 404);
        }
        $address->delete();
        session()->flash('success', 'Address deleted successfully');
        return response()->json(['status' => 'success', 'message' => 'Address deleted successfully'], 200);
    }
}
