<?php

namespace Modules\Hotels\Models;

use App\Models\CoreTerm;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Models\Customer;
use Modules\Booking\Models\HotelRoomsBooking;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Room extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'rooms';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'room';
    public    $type          = 'room';
    const PATH = 'rooms';
    const SERVICE = 'rooms';

    public $translatable = ['title', 'content','policy'];

    protected $fillable = [
        'title', 'content', 'foreign_price', 'syrian_price', 'number'
        , 'beds', 'size', 'baths', 'adults', 'children', 'status',
        'policy'
    ];

    protected $hidden = ['create_user', 'update_user', 'deleted_at'];

    protected $casts = [
        'foreign_price' => 'float',
        'syrian_price' => 'float',
        'number' => 'integer',
        'beds' => 'integer',
        'size' => 'integer',
        'adults' => 'integer',
        'children' => 'integer',
        'status' => 'boolean',

    ];

    public function getMediaUrlsAttribute()
    {
        return $this->getMedia('thumbnail')->map(function ($media) {
            return $media->getFullUrl();
        });
    }

    protected $appends = ['media_urls'];

    public function getTranslatedAttribute($attribute)
    {
        return $this->getTranslation($attribute, app()->getLocale());
    }

    public function updateStatus(): bool
    {
        $this->status = $this->status == true ? false : true;
        return $this->save();
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(HotelRoomsBooking::class);
    }

    public function terms()
    {
        return $this->belongsToMany(CoreTerm::class, 'room_term');
    }

    public function scopeFilter($query, $filter)
    {
        // if (Auth::guard('customer')->check()) {
        //     $user = Customer::where('id', Auth::guard('customer')->user()->id)->first();
        //     if ($user->nationality == 'Syrian') {
        $query->when(
            isset($filter['min_price']) && isset($filter['max_price']) &&
                $filter['min_price'] != null && $filter['max_price'] != null,
            function ($query) use ($filter) {
                $query->whereBetween('syrian_price', [$filter['min_price'], $filter['max_price']]);
            }
        );
        // } else {
        //     $query->when(
        //         isset($filter['min_price']) && isset($filter['max_price']) &&
        //             $filter['min_price'] != null && $filter['max_price'] != null,
        //         function ($query) use ($filter) {
        //             $query->whereBetween('foreign_price', [$filter['min_price'], $filter['max_price']]);
        //         }
        //     );
        // }
        // }

        $query->when(isset($filter['adults']) && $filter['adults'] != null, function ($query) use ($filter) {
            $query->where('adults', '=', $filter['adults']);
        });

        $query->when(isset($filter['status']) && $filter['status'] != null, function ($query) use ($filter) {
            $query->where('status', '=', $filter['status']);
        });

        $query->when(isset($filter['id']) && $filter['id'] != null, function ($query) use ($filter) {
            $query->where('hotel_id', '=', $filter['id']);
        });

        $query->when(isset($filter['children']) && $filter['children'] != null, function ($query) use ($filter) {
            $query->where('children', '=', $filter['children']);
        });

        $query->when(isset($filter['beds']) && $filter['beds'] != null, function ($query) use ($filter) {
            $query->where('beds', '=', $filter['beds']);
        });

        $query->when((isset($filter['baths']) && $filter['baths'] != null), function ($query) use ($filter) {
            $query->where('baths', '=', $filter['baths']);
        });

        $query->when((isset($filter['city']) && $filter['city'] != null), function ($query) use ($filter) {
            $city = $filter['city'];
            $query->whereHas('hotel', function ($query) use ($city) {
                $query->where('location_id', $city);
            });
        });


        if (
            isset($filter['checkin_date']) && isset($filter['checkout_date']) &&
            $filter['checkin_date'] != null && $filter['checkout_date'] != null
        ) {
            $query->withSum(['bookings' => function ($query) use ($filter) {
                $query->whereHas('booking', function ($query)  use ($filter) {
                    $query
                        ->whereBetween('check_in_date',    [$filter['checkin_date'], $filter['checkout_date']])
                        ->orWhereBetween('check_out_date', [$filter['checkin_date'], $filter['checkout_date']])
                        ->where('status', 'Confirmed');
                });
            }], 'rooms_count');

            $test = $query->get();
            $ids = [];
            foreach ($test as $q) {
                if ($q->bookings_sum_rooms_count < $q->number || $q->bookings_sum_rooms_count == null) {
                    $ids[] = $q->id;
                };
            }
            $query->whereIn('id', $ids);
        }

        if (request()->skip_count != null && request()->max_count != null) {
            $skipCount = request()->skip_count;
            $maxCount  = request()->max_count;
            $query  = $query->skip($skipCount)->take($maxCount);
        }

        return $query->orderBy('syrian_price', 'asc')->get();
    }
}
