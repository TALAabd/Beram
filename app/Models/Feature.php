<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Feature extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'features';
 
    const PATH = 'features';

    public $translatable = ['name'];

    protected $fillable = [
        'id',
       'name'
    ];

    protected $hidden = [
        'deleted_at','updated_at'
    ];

    public function trip()
    {
        return $this->belongsToMany(Trip::class, 'trip_features', 'feature_id', 'trip_id');
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
