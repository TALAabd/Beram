<?php

namespace Modules\Booking\Http\Services;

use Modules\Hotels\RepositoryInterface\HotelRepositoryInterface;
use Modules\Hotels\RepositoryInterface\RoomRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Repositories\BookingRepository;
use App\Helper\bookingHelper;
use App\Http\Services\NotificationService;
use App\Mail\BookingPdfMail;
use App\Mail\ProviderBookingMail;
use App\Traits\NotificationMessages;
use Exception;
use Illuminate\Support\Facades\Mail;
use Modules\Authentication\Models\User;

class BookingService
{
    use NotificationMessages;
    private $hotelRepository;
    private $roomRepository;

    public function __construct(
        private BookingRepository $bookingRepository,
        HotelRepositoryInterface $hotelRepository,
        RoomRepositoryInterface $roomRepository,
        private NotificationService $notificationService
    ) {
        $this->hotelRepository = $hotelRepository;
        $this->roomRepository = $roomRepository;
    }

    public function getAll($type, $status)
    {
        return $this->bookingRepository->getAll($type, $status);
    }
    public function getRecentBookings($request)
    {
        return $this->bookingRepository->getRecentBookings($request);
    }

    public function search($request)
    {
        return $this->bookingRepository->search($request);
    }

    public function getAllByCustomerStatus($status)
    {

        return $this->bookingRepository->getAllByUserStatus($status);
    }

    public function getAllByCustomer($type, $status)
    {
        return $this->bookingRepository->getAllByCustomer($type, $status);
    }



    public function getBookingDetails($bookingId)
    {
        return $booking = $this->find($bookingId);
    }

    public function changeStatus($validatedData, $bookingId)
    {
        $booking = $this->find($bookingId);
        DB::beginTransaction();
        $booking->update($validatedData);
        if ($booking->status == 'Confirmed') {
            if ($booking->service_type == 'hotel') {
                $provider = User::where('id',  $booking->bookable->user_id)->first();
                if ($provider && $provider->email) {
                    Mail::to($provider->email)->send(new ProviderBookingMail($booking, $booking->bookable->getTranslation('name', 'en')));
                }
            } elseif ($booking->service_type == 'trip') {
                $provider = User::where('id',  $booking->bookable->provider_id)->first();
                if ($provider && $provider->email) {
                    Mail::to($provider->email)->send(new ProviderBookingMail($booking, $booking->bookable->getTranslation('name', 'en')));
                }
            }
        }

        $this->notificationService->sendNotificationChangeStatusBooking($booking->customer, $booking);
        DB::commit();
        return $booking;
    }

    public function confirmedBooking($bookingId)
    {
        $booking = $this->find($bookingId);
        DB::beginTransaction();
        $booking->is_confirmed = ($booking->is_confirmed == 1) ? 0 : 1;
        $booking->save();
        DB::commit();
        return $booking;
    }

    public function cancelBooking($bookingId)
    {
        $validatedData['status'] = "Cancelled";
        $booking = $this->find($bookingId);
        DB::beginTransaction();

        if ($booking->status == "Pending") {
            $booking->update($validatedData);
            $this->notificationService->sendNotificationCancelBooking($booking->customer, $booking);
        } else {
            $this->notificationService->sendNotificationCancelBookingException($booking->customer, $booking);
            throw new Exception(__('messages.SorryYouCannotChangeTheBookingStatus'), 401);
        }

        DB::commit();
        return $booking;
    }

    public function find($bookingId)
    {
        return $this->bookingRepository->find($bookingId);
    }

    public function update($validatedData, $bookingId)
    {
        $booking = $this->bookingRepository->find($bookingId);

        DB::beginTransaction();

        $this->bookingRepository->update($validatedData, $booking);

        DB::commit();

        return true;
    }

    public function updateTrip($validatedData, $bookingId)
    {
        $booking = $this->bookingRepository->find($bookingId);
        DB::beginTransaction();

        $validatedData['check_in_date'] = $booking->check_in_date;
        $validatedData['total_price']   = $booking->bookable->price * $validatedData['total_guests'];
        $this->bookingRepository->updateTrip($validatedData, $booking);

        DB::commit();

        return true;
    }

    public function delete($bookingId)
    {
        $booking = $this->bookingRepository->find($bookingId);

        DB::beginTransaction();

        $this->bookingRepository->delete($booking);

        DB::commit();

        return true;
    }

    public function count(): int
    {
        return DB::table('bookings')->count();
    }

    public function saveBookingFile($bookingId, $mediaFile)
    {
        $booking = $this->find($bookingId);
        $booking->clearMediaCollection('pdf');
        $booking->addMedia($mediaFile)->toMediaCollection('pdf');

        $media = $booking->getFirstMedia('pdf')->getPath();
        $admins = User::where('role', 'administrator')->where('status', 1)->get();

        foreach ($admins as $admin) {
            if ($admin->email) {
                $mail = new BookingPdfMail($booking, $booking->bookable->getTranslation('name', 'en'));
                $mail->attach($media, [
                    'as' => $booking->first_name . ' ' . $booking->last_name . ' receipt.pdf',
                ]);
                Mail::to($admin->email)->send($mail);
            }
        }

        if ($booking->service_type == 'hotel') {
            $provider = User::where('id',  $booking->bookable->user_id)->where('role', '!=', 'administrator')->first();
            if ($provider && $provider->email) {
                $mail = new BookingPdfMail($booking, $booking->bookable->getTranslation('name', 'en'));
                $mail->attach($media, [
                    'as' => $booking->first_name . ' ' . $booking->last_name . ' receipt.pdf',
                ]);
                Mail::to($provider->email)->send($mail);
            }
        } elseif ($booking->service_type == 'trip') {
            $provider = User::where('id',  $booking->bookable->provider_id)->where('role', '!=', 'administrator')->first();
            if ($provider && $provider->email) {
                $mail = new BookingPdfMail($booking, $booking->bookable->getTranslation('name', 'en'));
                $mail->attach($media, [
                    'as' => $booking->first_name . ' ' . $booking->last_name . ' receipt.pdf',
                ]);
                Mail::to($provider->email)->send($mail);
            }
        }
    }
}
