<?php

namespace Modules\Resturant\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Meal extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'meals';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'meal';
    public    $type          = 'meal';
    const PATH = 'meals';
    const SERVICE = 'meals';

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'price',
        'menu_id'
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'menu_id' => 'integer',
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

    public function menu_type()
    {
        return $this->belongsTo(Menu::class);
    }
}
