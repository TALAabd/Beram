<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;

class Banner extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'banners';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'banner';
    public    $type          = 'banner';
    const PATH = 'banners';

    public $translatable = ['title'];

    protected $fillable = [
        'id',
        'banner_type',
        'title',
        'url_link',
        'service',
        'provider_id',
        'created_at'
    ];

    protected $casts = [
        'provider_id' => 'integer',
    ];

    protected $hidden = [
        'deleted_at', 'updated_at'
    ];

    protected $appends = ['media_urls'];

    public function getMediaUrlsAttribute()
    {
        return $this->getMedia($this->banner_type)->map(function ($media) {
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
