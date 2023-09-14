<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;

class BusinessSetting extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use Notifiable;

    protected $table         = 'business_settings';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    const PATH = 'business_settings';

    public $translatable = ['value'];

    protected $fillable = [
        'id',
        'type',
        'value'
    ];

    protected $appends = ['media_urls'];

    public function getMediaUrlsAttribute()
    {
        return $this->getMedia($this->type)->map(function ($media) {
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

    public function scopeFilter($query, $filter)
    {
        $query->when(isset($filter['type']), function ($query) use ($filter) {
            $query->where('type', '=', $filter['type']);
        });
    }

}
