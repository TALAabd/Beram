<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;

class City extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'cities';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'city';
    public    $type          = 'city';
    const PATH = 'cities';

    public $translatable = ['name'];


    protected $fillable = [
        'id',
        'name',
        'best_location',
        'country_id'
    ];

    protected $casts = [
        'country_id' => 'integer',
        'best_location' => 'integer',
    ];

    public function trip()
    {
        return $this->belongsToMany(Trip::class, 'cities_trips','city_id', 'trip_id');
    }

    public function getMediaUrlsAttribute()
    {
        return $this->getMedia('thumbnail')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getFullUrl()
            ];
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

    public function bestLocationStatus(): bool
    {
        $this->best_location = $this->best_location == 1 ? 0 : 1;
        return $this->save();
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }
}
