<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Illuminate\Notifications\Notifiable;

class CityTrip extends Model
{
    use HasFactory;
    use InteractsWithMedia;
    use HasTranslations;
    // use SoftDeletes;
    use Notifiable;

    public $translatable = ['dis'];

    protected $table = 'cities_trips';
 
    const PATH = 'cities_trips';


    protected $fillable = [
        'id',
        'trip_id',
        'city_id',
        'dis'
    ];
    protected $hidden = [
        'deleted_at','updated_at'
    ];
    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, app()->getLocale());
        }
        return $attributes;
    }

}
