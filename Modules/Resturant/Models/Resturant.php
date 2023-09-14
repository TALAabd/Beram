<?php

namespace Modules\Resturant\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Authentication\Models\User;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Modules\Booking\Models\Booking;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\HasSlug;
use App\Models\CoreTerm;
use Illuminate\Support\Facades\Auth;

class Resturant extends Model implements HasMedia
{
    use HasSlug;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'resturants';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'resturant';
    public    $type          = 'resturant';
    const PATH = 'resturants';
    const SERVICE = 'resturants';

    public $translatable = ['name', 'title', 'content', 'address', 'policy'];

    protected $fillable = [
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
        'status',
        'user_id',
        'create_user',
    ];

    protected $hidden = [
        'update_user',
        'deleted_at',
    ];

    protected $casts = [
        'map_lat' => 'float',
        'map_lng' => 'float',
        'location_id' => 'integer',
        'map_zoom' => 'integer',
        'is_featured' => 'boolean',
        'star_rate' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function scopeResturants($query)
    {
        $user = Auth::user();
        if ($user->role == "administrator") {
            return $query;
        } elseif ($user->role == "employee") {
            return $query->whereIn('id', $user->parent->resturants->pluck('id'));
        } else {
            return $query->whereIn('id', $user->resturants->pluck('id'));
        }
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

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function terms()
    {
        return $this->belongsToMany(CoreTerm::class, 'resturant_term');
    }

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
