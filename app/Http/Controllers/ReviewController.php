<?php

namespace App\Http\Controllers;

use App\Http\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService)
    {
    }

    public function reviews($hotelId)
    {
        $reviewsByHotel = $this->reviewService->getAllReviewsByHotel($hotelId);
        return $this->successResponse(
            $reviewsByHotel,
            'dataFetchedSuccessfully'
        );
    }

    public function topReviews()
    {
        $reviewsByHotel = $this->reviewService->getAlltopReviews();
        return $this->successResponse(
            $reviewsByHotel,
            'dataFetchedSuccessfully'
        );
    }

    public function reviewAdd($hotelId,Request $request)
    {
        $this->reviewService->create($hotelId,$request);
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }
    public function addTripReview($tripId,Request $request)
    {
        $this->reviewService->addTripReview($tripId,$request);
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }
    
    public function reviewRemove($hotelId,$reviewId)
    {
        $this->reviewService->delete($hotelId,$reviewId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
