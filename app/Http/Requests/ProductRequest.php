<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required|exists:categories,id',
            'brand' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'stock' => 'required|integer',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',

            'brand_id.required' => 'Please select a brand.',
            'brand_id.exists' => 'Selected brand does not exist.',

            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a string.',
            'name.max' => 'Product name may not be greater than 255 characters.',

            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a numeric value.',

            'compare_price.numeric' => 'Compare price must be a numeric value.',

            'stock.required' => 'Stock is required.',
            'stock.integer' => 'Stock must be an integer value.',

            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',

            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either active or inactive.',

            'images.array' => 'Images must be an array.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Each image must be a file of type: jpeg, png, jpg, gif, svg.',
            'images.*.max' => 'Each image may not be greater than 2048 kilobytes.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException(
            $validator,
            response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422)
        );
    }
}
