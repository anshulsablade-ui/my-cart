<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_name' => 'required|string|max:255',
            'address_type' => 'required|in:home,office,shipping,billing',
            'address' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15',
            'country' => 'required|exists:countries,id',
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'pincode' => 'required|string|max:10',
            'is_primary' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'address_name.required' => 'Address name is required',
            'address_type.required' => 'Address type is required',
            'address.required' => 'Address is required',
            'mobile_number.required' => 'Mobile number is required',
            'country.required' => 'Country is required',
            'state.required' => 'State is required',
            'city.required' => 'City is required',
            'pincode.required' => 'Pincode is required',

            'address_name.string' => 'Address name must be a string',
            'address_type.in' => 'Address type must be home, office, shipping or billing',
            'address.string' => 'Address must be a string',
            'mobile_number.string' => 'Mobile number must be a string',
            'country.exists' => 'Country does not exist',
            'state.exists' => 'State does not exist',
            'city.exists' => 'City does not exist',
            'pincode.string' => 'Pincode must be a string',
            'is_primary.boolean' => 'Is primary must be true or false',
        ];
    }

        protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422)
        );
    }
}
