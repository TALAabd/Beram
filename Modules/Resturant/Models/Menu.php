<?php

namespace Modules\Resturant\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Menu extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'menus';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'Menu';
    public    $type          = 'Menu';
    const PATH = 'menus';
    const SERVICE = 'menus';

    public $translatable = ['name','title'];

    protected $fillable = [
        'name',
        'title',
        'resturant_id'
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'resturant_id' => 'integer',
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

    public function resturant()
    {
        return $this->belongsTo(Resturant::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
