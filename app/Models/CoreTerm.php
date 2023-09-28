<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Modules\Hotels\Models\Hotel;
use Modules\Hotels\Models\Room;
use Modules\Resturant\Models\Resturant;

class CoreTerm extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'core_terms';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'core_term';
    public    $type          = 'core_term';
    const PATH = 'core_terms';

    public $translatable = ['name', 'content'];

    protected $fillable = [
        'name',
        'content',
        'slug',
        'core_attribute_id',
        'icon_name',
        'price'
    ];

    protected $hidden = ['slug', 'core_attribute_id'];

    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, app()->getLocale());
        }
        return $attributes;
    }

    public function core_attribute()
    {
        return $this->belongsTo(CoreAttribute::class, 'core_attribute_id', 'id');
    }


    public function hotels()
    {
        return $this->belongsToMany(Hotel::class, 'hotel_term');
    }

    public function resturants()
    {
        return $this->belongsToMany(Resturant::class, 'resturant_term');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_term');
    }


}
