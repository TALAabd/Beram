<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Booking\Models\Booking;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class Trip extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table  = 'trips';

    const PATH = 'trips';

    protected $appends = ['media_urls'];
    public $translatable = ['name', 'description'];

    protected $fillable = [
        'id',
        'description', 'starting_city_id',
        'period', 'date', 'price', 'contact', 'name'
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    // protected $appends = ['iamge'];

    public function city()
    {
        return $this->belongsToMany(City::class, 'cities_trips', 'trip_id', 'city_id')->withPivot('dis');
    }

    public function startingCity()
    {
        return $this->belongsTo(City::class, 'starting_city_id');
    }

    public function feature()
    {
        return $this->belongsToMany(Feature::class, 'trip_features', 'trip_id', 'feature_id')
            ->withPivot('id');
    }
    
    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function getMediaUrlsAttribute()
    {
        return $this->getMedia('trip')->map(function ($media) {
            return $media->getFullUrl();
        });

        // return $this->getMedia('trip')->map(function ($media) {
        //     return [
        //         // 'id' => $media->id,
        //         'url' => $media->getFullUrl()
        //     ];
        // });
    }

    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, app()->getLocale());
        }
        return $attributes;
    }

    public function scopeFilters($query)
    {
        $user = Auth::guard('customer')->user();

        if (isset(request()->min_price) && isset(request()->max_price)) {
            $query->where(function ($query) {
                $query->whereBetween('price', [request()->min_price, request()->max_price]);
            });
        }
        if (isset(request()->name))
            $query->where("name->en", 'like', '%' . request()->name . '%')
                ->orWhere("name->ar", 'like', '%' . request()->name . '%');

        if (isset(request()->starting_city_id))
            $query->where('starting_city_id', request()->starting_city_id);

        if (isset(request()->destination_city_id)) {
            $city_id = request()->destination_city_id;
            $query->whereHas('city', function ($query) use ($city_id) {
                $query->where('cities_trips.city_id', $city_id);
            });
        };

        if (isset(request()->date))
            $query->where('date', request()->date);

        if (!isset($user)) return $query;
        if ($user->role == "administrator") {
            return $query;
        }
        // elseif ($user->role == "employee") {
        //     return $query->whereIn('id', $user->parent->hotels->pluck('id'));
        // }
        elseif ($user->role == "provider") {
            return $query->whereIn('id', $user->hotels->pluck('id'));
        } else {
            return $query;
        }
    }
}
