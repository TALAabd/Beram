<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class CoreAttribute extends Model
{
    use HasTranslations;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'core_attributes';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'core_attribute';
    public    $type          = 'core_attribute';
    const PATH = 'core_attributes';

    public $translatable = ['name', 'content'];

    protected $fillable = [
        'name', 'position', 'service', 'slug', 'icon'
    ];
    protected $hidden = ['slug'];

    protected $casts = [
        'position' => 'integer',
    ];

    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, app()->getLocale());
        }
        return $attributes;
    }

    // public function getTranslatedAttribute($attribute)
    // {
    //     return $this->getTranslation($attribute, app()->getLocale());
    // }


    public function core_terms()
    {
        return $this->hasMany(CoreTerm::class);
    }
}
