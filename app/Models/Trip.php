<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;

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
    public $translatable = ['name','description'];

    protected $fillable = [
        'id',
        'description',
        'period','date','price','contact','name'
    ];

    protected $hidden = [
        'deleted_at','updated_at'
    ];

    // protected $appends = ['iamge'];

    public function city()
    {
        return $this->belongsToMany(City::class, 'cities_trips', 'trip_id', 'city_id')->withPivot('dis');;
    }
    public function feature()
    {
        return $this->belongsToMany(Feature::class, 'trip_features', 'trip_id', 'feature_id')
        ->withPivot('id');
    }
    public function getMediaUrlsAttribute()
    {
        return $this->getMedia('trip')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getFullUrl()
            ];
        });
    }

    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, app()->getLocale());
        }
        return $attributes;
    }
}
