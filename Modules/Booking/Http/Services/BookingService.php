<?php

namespace Modules\Booking\Http\Services;

use Modules\Hotels\RepositoryInterface\HotelRepositoryInterface;
use Modules\Hotels\RepositoryInterface\RoomRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Repositories\BookingRepository;
use App\Helper\bookingHelper;
use App\Http\Services\NotificationService;
use App\Traits\NotificationMessages;
use Exception;

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

    public function getAll($status)
    {
        return $this->bookingRepository->getAll($status);
    }


    public function getAllByCustomer($status)
    {
        return $this->bookingRepository->getAllByUser($status);
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
}
