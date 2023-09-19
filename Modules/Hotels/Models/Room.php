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

    public $translatable = ['title', 'content'];

    protected $fillable = [
        'title', 'content', 'foreign_price', 'syrian_price', 'number', 'beds', 'size', 'baths', 'adults', 'children', 'status',
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
        if (Auth::guard('customer')->check()) {
            $user = Customer::where('id', Auth::guard('customer')->user()->id)->first();
            if ($user->nationality == 'Syrian') {
                $query->when(isset($filter['min_price']) && isset($filter['max_price']), function ($query) use ($filter) {
                    $query->whereBetween('syrian_price', [$filter['min_price'], $filter['max_price']]);
                });
            } else {
                $query->when(isset($filter['min_price']) && isset($filter['max_price']), function ($query) use ($filter) {
                    $query->whereBetween('foreign_price', [$filter['min_price'], $filter['max_price']]);
                });
            }
        }
        $query->when(isset($filter['adults']), function ($query) use ($filter) {
            $query->where('adults', '>=', $filter['adults']);
        });
        $query->when(isset($filter['children']), function ($query) use ($filter) {
            $query->where('children', '>=', $filter['children']);
        });
        $query->when(isset($filter['beds']), function ($query) use ($filter) {
            $query->where('beds', '>=', $filter['beds']);
        });
        $query->when(isset($filter['baths']), function ($query) use ($filter) {
            $query->where('baths', '>=', $filter['baths']);
        });
        $query->when(request()->city, function ($query) {
            $city = request()->city;
            $query->whereHas('hotel', function ($query) use ($city) {
                $query->where('location_id', $city);
            });
        });
        $query->when(isset($filter['checkin_date']) && isset($filter['checkout_date']), function ($query) use ($filter) {
            $query->whereBetween('', [$filter[''], $filter['']]);
        });

        $query->withSum(['bookings' => function ($query) use ($filter) {
            $query->where('start_date', '<=', $filter['checkout_date'])
                ->where('end_date', '>=', $filter['checkin_date'])
                ->whereHas('booking', function ($query) {
                    $query->where('status', 'Confirmed');
                });
        }], 'rooms_count');

        // dd($query);
        $ids = [];
        foreach ($query as $q) {
            if ($q->bookings_sum_rooms_count < $q->number) {
                $ids[] = $q->id;
            };
        }
        // dd($ids);
        $query->whereIn('id', $ids);
        return $query;
    }
}
