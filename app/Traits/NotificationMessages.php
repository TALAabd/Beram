<?php

namespace App\Traits;

use App\Helper\bookingHelper;
use Modules\Booking\Models\Booking;

trait NotificationMessages
{

    public static function sendNotificationCancelBooking($fcm, Booking $booking)
    {
        $data = [
            'title' =>  __('messages.'."CancelBooking"),
            'description' =>  __('messages.'."SorryYouCannotChangeTheBookingStatus"),
            'booking_code' => $booking->booking_code,
            'image' => '',
        ];
        bookingHelper::sendPushNotificationToDevice($fcm, $data);
    }

    public static function sendNotificationChangeStatusBooking($fcm, Booking $booking)
    {
        $data = [
            'title' =>  __('messages.'.$booking->status."Booking"),
            'description' =>  __('messages.'."YourBooking".$booking->status."Successfully"),
            'booking_code' => $booking->booking_code,
            'image' => '',
        ];
        bookingHelper::sendPushNotificationToDevice($fcm, $data);
    }
}
