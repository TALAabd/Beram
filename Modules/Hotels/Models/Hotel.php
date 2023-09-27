<?php

namespace Modules\Hotels\Models;

use App\Models\CoreTerm;
use Modules\Authentication\Models\User;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Modules\Booking\Models\Booking;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\HasSlug;
use Illuminate\Support\Facades\Auth;
use Maize\Markable\Markable;
use Maize\Markable\Models\Bookmark;
use Maize\Markable\Models\Favorite;
use Maize\Markable\Models\Like;
use Digikraaft\ReviewRating\Traits\HasReviewRating;

class Hotel extends Model implements HasMedia
{
    use Markable;
    use HasSlug;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;
    use HasReviewRating;

    protected $table         = 'hotels';
    protected $slugField     = 'slug';
    protected $slugFromField = 'name';
    protected $seo_type      = 'hotel';
    public    $type          = 'hotel';
    const PATH = 'hotels';
    const SERVICE = 'hotels';

    public $translatable = ['name', 'title', 'content', 'address', 'policy'];

    protected $fillable = [
        'id',
        'name',
        'title',
        'slug',
        'content',
        'location_id',
        'address',
        'map_lat',
        'map_lng',
        'map_zoom',
        'is_featured',
        'policy',
        'star_rate',
        'check_in_time',
        'check_out_time',
        'user_id',
        'create_user',
        'max_price',
        'min_price',
        'fax',
        'web',
        'phone',
        'email',
    ];

    protected $hidden = [
        'update_user',
        'deleted_at',
    ];

    protected $casts = [
        'map_lat' => 'float',
        'map_lng' => 'float',
        'is_featured' => 'boolean',
        'star_rate' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
        'max_price' => 'float',
        'min_price' => 'float',
        'location_id' => 'integer',
        'map_zoom' => 'float',
    ];

    protected static $marks = [
        Favorite::class,
        Like::class,
        Bookmark::class,
    ];

    public function getMediaUrlsAttribute()
    {
        return $this->getMedia('thumbnail')->map(function ($media) {
            return $media->getFullUrl();
        });
    }

    protected $appends = ['media_urls'];


    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, app()->getLocale());
        }
        return $attributes;
    }

    public function scopeHotels($query, $filter)
    {


        // $user = Auth::guard('customer')->user();

        if (isset($filter['min_price']) && isset($filter['max_price'])) {
            $query->where(function ($query) use ($filter) {
                $query->whereBetween('min_price', [$filter['min_price'], $filter['max_price']])
                    ->orWhereBetween('max_price', [$filter['min_price'], $filter['max_price']]);
            });
        }

        //name
        if (isset($filter['name']))
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        //rate
        if (isset($filter['star_rate']))
            $query->where('star_rate', $filter['star_rate']);
        if (isset($filter['location_id'])) //city
            $query->where('location_id', $filter['location_id']);

        if (isset($filter['star_rate']))
            $query->where('star_rate', $filter['star_rate']);
        if (isset($filter['top_rated']) && $filter['top_rated'] == 1)
            $query->where('star_rate', 5);
        if (isset($filter['location_id']))
            $query->where('location_id', $filter['location_id']);

        if (isset($filter['terms']) && count($filter['terms']) != 0) {
            $terms = $filter['terms'];
            $query->whereHas('terms', function ($query) use ($terms) {
                $query->whereIn('hotel_term.core_term_id', $terms);
            });
        }

        $user = Auth::guard('user')->user();

        $newQuery = $query;
        if (!isset($user))
            $newQuery = $query;
        if ($user->role == "administrator") {
            $newQuery = $query;
        } elseif ($user->role == "employee") {
            $newQuery = $query->whereIn('id', $user->parent->hotels->pluck('id'));
        } elseif ($user->role == "provider") {
            $newQuery = $query->whereIn('id', $user->hotels->pluck('id'));
        } elseif ($user->role == "Hotel_provider") {
            $newQuery = $query->whereIn('id', $user->hotels->pluck('id'));
        } else {
            $newQuery = $query;
        }

        if (request()->skip_count != null && request()->max_count != null) {
            $skipCount = request()->skip_count;
            $maxCount  = request()->max_count;
            $newQuery  = $newQuery->skip($skipCount)->take($maxCount);
        }

        return $newQuery;
    }

    public function updateFeatured(): bool
    {
        $this->is_featured = $this->is_featured == true ? false : true;
        return $this->save();
    }

    public function updateStatus(): bool
    {
        $this->status = $this->status == true ? false : true;
        return $this->save();
    }

    public function publisher()
    {
        return $this->belongsTo(User::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function terms()
    {
        return $this->belongsToMany(CoreTerm::class, 'hotel_term');
    }

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}
