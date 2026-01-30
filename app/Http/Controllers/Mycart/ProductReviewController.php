<?php

namespace App\Http\Controllers\Mycart;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string',
        ]);
        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => $validated->errors()], 422);
        }

        $review = ProductReview::create([
            'product_id' => $request->product_id,
            'user_id' => session()->get('user.id'),
            'rating' => $request->rating,
            'review' => $request->review,
            'status' => $request->status ?? 'active',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Review submitted successfully', 'data' => $review], 201);
    }

    public function edit(Request $request)
    {
        $revirw = ProductReview::where('product_id', $request->product_id)->where('user_id', session()->get('user.id'))->first();
        if (!$revirw) {
            return response()->json(['status' => 'error', 'message' => 'Review not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $revirw], 200);
    }
    public function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string',
        ]);
        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => $validated->errors()], 422);
        }

        $revirw = ProductReview::where('product_id', $request->product_id)->where('user_id', session()->get('user.id'))->first();
        $revirw->update([
            'rating' => $request->rating,
            'review' => $request->review,
            'status' => $request->status ?? 'active',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Review updated successfully'], 200);
    }

    public function delete(Request $request)
    {
        $revirw = ProductReview::where('product_id', $request->product_id)->where('user_id', session()->get('user.id'))->first();
        if (!$revirw) {
            return response()->json(['status' => 'error', 'message' => 'Review not found'], 404);
        }
        $revirw->delete();
        return response()->json(['status' => 'success', 'message' => 'Review deleted successfully'], 200);
    }
}
