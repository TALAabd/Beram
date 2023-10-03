<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminBookingMail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking, $bookable_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($booking, $bookable_name)
    {
        $this->booking  = $booking;
        $this->bookable_name = $bookable_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->booking->service_type == 'hotel')
            return $this->view('email.adminBookingMail')->subject('New Hotel Booking');
        elseif ($this->bookable_name->service_type == 'trip')
            return $this->view('email.adminBookingMail')->subject('New Trip Booking');
    }
}
