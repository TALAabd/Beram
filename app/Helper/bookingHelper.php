<?php

namespace App\Helper;

use Modules\Booking\Models\Booking;

class bookingHelper
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = 'AAAAD__YhWU:APA91bFZ7iokain0nxw-mRlsz0zBz-9q_1JdrX3wHAic74XmdhCIZ5whnTl60kuql2LhoZ7UNj5-qDyDXpG4qP8ek1MzIlWVT8nRmy6SdomovEbf6UxkOe6ZfzpB4qU1JZW3PxCgrgdp';
    }

    public static function generateBookingCode(): int
    {
        $booking_id = 10000000 + Booking::all()->count() + 1;

        if (Booking::find($booking_id)) {
            $booking_id = Booking::orderBy('id', 'DESC')->first()->id + 1;
        }
        return $booking_id;
    }

    public static function taxCalculation($price, $tax, $tax_type = null): float
    {
        $amount = ($price / 100) * $tax;
        return $amount;
    }

    public static function bookingStatusUpdateMessage($status)
    {
        if ($status == 'Pending') {
            // $data = BusinessSetting::where('type', 'booking_pending_message')->first()->value;
        } elseif ($status == 'Confirmed') {
            // $data = BusinessSetting::where('type', 'booking_confirmation_msg')->first()->value;
        } elseif ($status == 'Cancelled') {
            // $data = BusinessSetting::where('type', 'booking_cancelled_message')->first()->value;
        } elseif ($status == 'Completed') {
            // $data = BusinessSetting::where('type', 'booking_completed_message')->first()->value;
        } else {
            $data = '{"status":"0","message":""}';
        }
        $res = json_decode($data, true);

        if ($res['status'] == 0) {
            return 0;
        }
        return $res['message'];
    }

    public static function sendPushNotificationToDevice(string $fcmToken, array $data): string
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $apiKey='AAAAD__YhWU:APA91bFZ7iokain0nxw-mRlsz0zBz-9q_1JdrX3wHAic74XmdhCIZ5whnTl60kuql2LhoZ7UNj5-qDyDXpG4qP8ek1MzIlWVT8nRmy6SdomovEbf6UxkOe6ZfzpB4qU1JZW3PxCgrgdp';
        $header = [
            "authorization: key=" . $apiKey,
            "content-type: application/json",
        ];

        $payload = bookingHelper::buildPayload($fcmToken, $data);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $header,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public static function sendPushNotificationToTopic(string $topic, array $data): string
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $apiKey='AAAAD__YhWU:APA91bFZ7iokain0nxw-mRlsz0zBz-9q_1JdrX3wHAic74XmdhCIZ5whnTl60kuql2LhoZ7UNj5-qDyDXpG4qP8ek1MzIlWVT8nRmy6SdomovEbf6UxkOe6ZfzpB4qU1JZW3PxCgrgdp';
        $header = [
            "authorization: key=" . $apiKey,
            "content-type: application/json",
        ];
        $payload = bookingHelper::buildPayload($topic, $data);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $header,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private static function buildPayload(string $fcmToken, array $data): string
    {
        if (!isset($data['booking_code'])) {
            $data['booking_code'] = null;
        }
        $payload = [
            "to" => $fcmToken,
            "data" => [
                "title" => $data['title'],
                "body" => $data['description'].'('.$data['booking_code'].')',
                "image" => $data['image'] ?? "",
                "booking_code" => $data['booking_code'],
                "is_read" => 0,
            ],
            "notification" => [
                "title" => $data['title'],
                "body" => $data['description'].'('.$data['booking_code'].')',
                "image" => $data['image'] ?? "",
                "booking_code" => $data['booking_code'],
                "title_loc_key" => $data['booking_code'],
                "is_read" => 0,
                "icon" => "new",
                "sound" => "default",
            ],
        ];
        return json_encode($payload);
    }
}
