<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;

class StoryItem extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'story_items';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'story_item';
    public    $type          = 'story_item';
    const PATH = 'story_items';

    public $translatable = ['description'];

    protected $fillable = [
        'story_id',
        'story_type',
        'description'
    ];

    protected $casts = [
        'story_id' => 'integer',
    ];

    protected $hidden = [
        'deleted_at','updated_at'
    ];

    protected $appends = ['iamge'];

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

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
