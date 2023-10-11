<?php

namespace Modules\Booking\Models;

use App\Models\GuestData;
use App\Models\PaymentMethod;
use App\Models\Trip;
use Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Models\Customer;

class Booking extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    protected $table         = 'bookings';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'booking';
    public    $type          = 'booking';
    const PATH = 'bookings';
    const SERVICE = 'bookings';
    const MEDIA_THUMNAIL = 'bookings';

    const Pending = "Pending";
    const Confirmed = "Confirmed";
    const Cancelled = "Cancelled";
    const Completed = "Completed";

    protected $fillable = [
        'id',
        'booking_code',
        'service_type',
        'check_in_date',
        'check_out_date',
        'total_price',
        'total_guests',
        'customer_notes',
        'status',
        'email',
        'first_name',
        'last_name',
        'phone',
        'nationality',
        'is_confirmed',
        'create_user',
        'payment_id',
        'provider_id'
    ];

    protected $hidden = [
        'provider_id',
        'customer_id',
        'bookable_id',
        'bookable_type',
        'deleted_at'
    ];

    protected $casts = [
        'total_price' => 'float',
        'total_guests' => 'integer',
        'star_rate' => 'integer',
        'phone' => 'integer',
        'is_confirmed' => 'integer',
        'zip_code' => 'integer',
        'booking_code' => 'integer',
    ];
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_id', 'id');
    }
    public function bookable()
    {
        return $this->morphTo();
    }
    public function guest()
    { 
        return $this->hasMany(GuestData::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function roomBookings()
    {
        return $this->hasMany(HotelRoomsBooking::class);
    }

    public function trip()
    {
        return $this->hasMany(Trip::class);
    }

    public function tableBookings()
    {
        return $this->hasMany(RestaurantTableBooking::class);
    }

    public function scopeBookings($query)
    {
        $user = Auth::user();
        if ($user->role == "administrator") {
            return $query;
        } elseif (($user->role == "provider" || $user->role == "Trip_provider") && request()->type == 'trip') {
            $bookings = collect();
            foreach ($user->trips as $trip) {
                $bookings = $bookings->merge($trip->bookings);
            }
            return $query->whereIn('id', $bookings->pluck('id'));
        } else {
            $bookings = collect();
            foreach ($user->hotels as $hotel) {
                $bookings = $bookings->merge($hotel->bookings);
            }
            return $query->whereIn('id', $bookings->pluck('id'));
        }
        return $query;
    }

    public function scopeHotelBookings($query)
    {
        return $query->serviceTypeBookings('hotel');
    }

    public function scopeRestaurantBookings($query)
    {
        return $query->serviceTypeBookings('restaurant');
    }

    public function scopeServiceTypeBookings($query, $serviceType)
    {
        $user = Auth::user();
        if ($user->role == "administrator") {
            return $query->where('service_type', $serviceType);
        } elseif ($user->can('bookings_manager') && $user->role == "employee") {
            $bookings = $user->parent->hotels->flatMap(function ($hotel) {
                return $hotel->bookings->pluck('id');
            });
            return $query->whereIn('id', $bookings)
                ->where('service_type', $serviceType);
        } else {
            $bookings = $user->hotels->flatMap(function ($hotel) {
                return $hotel->bookings->pluck('id');
            });
            return $query->whereIn('id', $bookings)
                ->where('service_type', $serviceType);
        }
    }
}
