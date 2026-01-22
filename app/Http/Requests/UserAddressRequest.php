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
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
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
            'country_id.required' => 'Country is required',
            'state_id.required' => 'State is required',
            'city_id.required' => 'City is required',
            'pincode.required' => 'Pincode is required',

            'address_name.string' => 'Address name must be a string',
            'address_type.in' => 'Address type must be home, office, shipping or billing',
            'address.string' => 'Address must be a string',
            'mobile_number.string' => 'Mobile number must be a string',
            'country_id.exists' => 'Country does not exist',
            'state_id.exists' => 'State does not exist',
            'city_id.exists' => 'City does not exist',
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
