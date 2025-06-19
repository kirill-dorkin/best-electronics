<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

class ReviewController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(ReviewRequest $request): JsonResponse
    {
        $review = Review::create([
            'user_id' => $request->user()->id,
            'technician_id' => $request->technician_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => __('Review submitted successfully'),
            'review' => new ReviewResource($review),
        ]);
    }
}
