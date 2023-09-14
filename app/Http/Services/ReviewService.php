<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Hotels\Http\Services\HotelService;
use Digikraaft\ReviewRating\Models\Review;

class ReviewService
{
    public function __construct(private HotelService $hotelService)
    {
    }


    public function getAllReviewsByHotel($hotelId)
    {
        $hotel = $this->hotelService->showHotelDetails($hotelId);
        $reviews=Review::where('model_id','=',$hotel->id)->get();
        $sum = $reviews->sum('rating');
        $count = $reviews->count();
        $avg = $sum / $count;
        $avg=round($avg, 2);
        
        return $reviews = [
            'hotel_id'        => $hotel->id,
            'numberOfReviews' => $hotel->numberOfReviews(),
            'numberOfRatings' => $hotel->numberOfRatings(),
            'averageRating'   => $avg,
            'numberOfReviews' => $hotel->numberOfReviews(),
            'latestReview'    => $hotel->latestReview(),
            'reviews'         => $hotel->reviews,
        ];
    }

    public function getAlltopReviews()
    {
        // Retrieve the top reviews by rating
        $reviews = Review::orderByDesc('rating')->limit(10)->get();

        return $reviews;
    }

    public function create($hotelId, $validatedData)
    {
        DB::beginTransaction();

        $user = Auth::guard('customer')->user();
        $hotel = $this->hotelService->showHotelDetails($hotelId);
        $hotel->review($validatedData['review'], $user, $validatedData['rating'], $validatedData['title']);

        DB::commit();
    }

    public function delete($hotelId, $reviewId)
    {
        DB::beginTransaction();

        $user = Auth::guard('customer')->user();
        $review = Review::where(
            [
                ['id', $reviewId],
                ['author_id', $user->id],
                ['model_id', $hotelId]
            ]
        )->get()->first();

        $result = $review->delete();

        DB::commit();

        return $result;
    }
}
