<div style="font-family: Helvetica, Arial, sans-serif; min-width: 1000px; overflow: auto; line-height: 2">
	<div style="margin: 50px auto; width: 70%; padding: 20px 0">
		<div style="border-bottom: 1px solid #eee">
			<a href="" style="font-size: 1.4em; color: #00466a; text-decoration: none; font-weight: 600">AlBeram</a>
		</div>
		<p style="font-size: 1.1em">Hi,</p>
		<p>New Booking has been confirmed for {{ $bookable_name }}, to {{ $booking->first_name }} {{ $booking->last_name }} on 
			{{ date('F j, Y', strtotime($booking->created_at)) }}.</p>
		<h2 style="background: #00466a; margin: 0 auto; width: max-content; padding: 0 10px; color: #fff; border-radius: 4px;">
			<a href="#">Check it out</a>
		</h2>
		<p style="font-size: 0.9em;">Regards,<br />AlBeram</p>
		<hr style="border: none; border-top: 1px solid #eee" />
	</div>
</div>
