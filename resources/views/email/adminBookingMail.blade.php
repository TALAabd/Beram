<div style="font-family: Helvetica, Arial, sans-serif; min-width: 1000px; overflow: auto; line-height: 2">
	<div style="margin: 50px auto; width: 70%; padding: 20px 0">
		<div style="border-bottom: 1px solid #eee">
			<a href="" style="font-size: 1.4em; color: #00466a; text-decoration: none; font-weight: 600">Beram</a>
		</div>
		<p style="font-size: 1.1em">Hello,</p>
		<p>New Booking has been created for {{ $bookable_name }}, By {{ $booking->first_name }} {{ $booking->last_name }} on
			{{ date('F j, Y', strtotime($booking->created_at)) }}.</p>
		<h2 style="background: #00466a; margin: 0 auto; width: max-content; padding: 0 10px; color: #fff; border-radius: 4px;">
			@if ($booking->service_type == 'hotel')
				<a href="https://control.alberam.sy/hotel_booking/pending"> Check it out </a>
			@else
				<a href="https://control.alberam.sy/trip_booking/pending"> Check it out </a>
			@endif
		</h2>
		<p style="font-size: 0.9em;">Regards, Beram</p>
		<hr style="border: none; border-top: 1px solid #eee" />
	</div>
</div>
