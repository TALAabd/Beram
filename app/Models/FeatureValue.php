<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Notifications\Notifiable;

class FeatureValue extends Model
{
    use HasFactory;
    use HasTranslations;
    use Notifiable;

    protected $table = 'feature_values';
 
    const PATH = 'feature_values';

    public $translatable = ['value'];

    protected $fillable = [
        'id',
       'value',
       'tripfeatures_id'
    ];

    protected $hidden = [
        'deleted_at','updated_at'
    ];


    public function tripFeature()
    {
        return $this->belongsTo(TripFeature::class, 'tripfeatures_id');
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
