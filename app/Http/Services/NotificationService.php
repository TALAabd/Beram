<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use App\Models\Notification;
use App\Helper\bookingHelper;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Models\Customer;
use Modules\Booking\Models\Booking;

class NotificationService
{
    use ModelHelper;

    public function getAll()
    {
        return Notification::where('to_id', Auth::guard('customer')->user()->id)->get();
    }

    public function find($notificationId)
    {
        return $this->findByIdOrFail(Notification::class, 'notification', $notificationId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $notification = Notification::create([
            'title'          => $validatedData['title'],
            'description'    => $validatedData['description'],
            'payload'        => $validatedData['payload'],
            'from_type'      => $validatedData['from_type'],
            'to_id'          => $validatedData['to_id'],
        ]);

        DB::commit();

        return $notification;
    }

    public function update($validatedData, $notificationId)
    {
        $notification = $this->find($notificationId);

        DB::beginTransaction();

        $notification->update($validatedData);

        DB::commit();

        return true;
    }

    public function delete($notificationId)
    {
        $notification = $this->find($notificationId);

        DB::beginTransaction();

        $notification->delete();

        DB::commit();

        return true;
    }

    public function sendNotificationCancelBookingException(Customer $customer, Booking $booking)
    {
        DB::beginTransaction();

        $data = [
            'title'         =>  __('messages.' . "CancelBooking"),
            'description'   =>  __('messages.' . "SorryYouCannotChangeTheBookingStatus"),
            'booking_code'  =>  $booking->booking_code,
            'from_type'     => 'customer',
            'to_id'         => $customer->id,
            'image'         =>  '',
            'payload'       =>  '',
        ];
        $this->create($data);
        if ($customer->fcm_token) {
            bookingHelper::sendPushNotificationToDevice($customer->fcm_token, $data);
        }
        DB::commit();
    }


    public function sendNotificationCancelBooking(Customer $customer, Booking $booking)
    {
        DB::beginTransaction();

        $data = [
            'title'        =>  __('messages.' . $booking->status . "Booking"),
            'description'  =>  __('messages.' . "YourBooking" . $booking->status . "Successfully"),
            'booking_code' =>  $booking->booking_code,
            'to_id'        =>  $customer->id,
            'from_type'    =>  'customer',
            'image'        =>  '',
            'payload'      =>  '',
        ];
        $this->create($data);
        if ($customer->fcm_token) {
            bookingHelper::sendPushNotificationToDevice($customer->fcm_token, $data);
        }

        DB::commit();
    }


    public function sendNotificationChangeStatusBooking(Customer $customer, Booking $booking)
    {
        DB::beginTransaction();

        $data = [
            'title'        =>  __('messages.' . $booking->status . "Booking"),
            'description'  =>  __('messages.' . "YourBooking" . $booking->status . "Successfully"),
            'booking_code' =>  $booking->booking_code,
            'to_id'        =>  $customer->id,
            'from_type'    =>  'customer',
            'image'        =>  '',
            'payload'      =>  '',
        ];
        $this->create($data);
        if ($customer->fcm_token) {
            bookingHelper::sendPushNotificationToDevice($customer->fcm_token, $data);
        }
        DB::commit();
    }
}
