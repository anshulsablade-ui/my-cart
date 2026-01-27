<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ProfileController extends Controller
{
    public function index()
    {
        return view('mycart.account.account-info');
    }

    public function update(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string',
            'mobile_number' => 'nullable|numeric',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()], 422);
        }

        $user = User::where('id', session()->get('user.id'))->update([
            'name' => $request->name,
            'phone' => $request->mobile_number
        ]);

        if ($request->hasFile('profile_image')) {
            $user = User::where('id', session()->get('user.id'))->first();
            if (file_exists('images/users/' . $user->image)) {
                unlink('images/users/' . $user->image);
            }
            $profile_image = $request->file('profile_image');
            $profile_image_name = Str::uuid() . '.png';
            
            if (!file_exists($path = 'images/users/')) {
                mkdir($path, 0755, true);
            }

            Image::read($profile_image)->resize(150, 150)->toPng()->save('images/users/' . $profile_image_name);

            $user = User::where('id', session()->get('user.id'))->update([
                'image' => $profile_image_name
            ]);
        }

        session()->put('user', User::where('id', session()->get('user.id'))->first());

        return response()->json(['status' => 'success', 'message' => 'Profile updated successfully'], 200);
    }

    public function deleteAccount()
    {
        $user = User::where('id', session()->get('user.id'))->first();
        if ($user) {
            $user->delete();
            session()->flush();
            return response()->json(['status' => 'success', 'message' => 'Account deleted successfully'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
    }
}
