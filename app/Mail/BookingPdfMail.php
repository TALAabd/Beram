<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingPdfMail extends Mailable
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
        return $this->view('email.bookingPdfMail')->subject('New Booking receipt');

    }
}
