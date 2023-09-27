<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class About extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    protected $table = 'abouts';

    const PATH = 'abouts';

    public $translatable = ['title','content','privacy'];

    protected $fillable = [
        'id',
        'title',
        'content',
        'image',
        'privacy'
    ];

    protected $appends = ['media_urls'];

    public function getMediaUrlsAttribute()
    {
        return $this->getMedia('about')->first()->getFullUrl();
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
