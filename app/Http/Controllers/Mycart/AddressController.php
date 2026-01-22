<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::where('user_id', session()->get('user.id'))->get();
        return view('mycart.address', compact('addresses'));
    }

    public function store(UserAddressRequest $request)
    {
        // dd($request->all());
        $request->validated();
        UserAddress::create([
            'user_id' => session()->get('user.id'),
            'name' => $request->address_name,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'phone' => $request->mobile_number,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pincode' => $request->pincode,
            'is_primary' => $request->is_primary,
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

        $userAddress->update([
            'name' => $request->address_name,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'phone' => $request->mobile_number,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'pincode' => $request->pincode,
            'is_primary' => $request->is_primary,
            'status' => $request->status
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
