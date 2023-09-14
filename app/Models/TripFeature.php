<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class TripFeature extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'trip_features';
 
    protected $fillable = [
        'id',
        'trip_id',
        'feature_id'
    ];

    protected $hidden = [
        'deleted_at','updated_at'
    ];
    public function featureValue()
    {
        return $this->hasMany(FeatureValue::class);
    }
  
}
