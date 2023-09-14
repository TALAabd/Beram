<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;
use Modules\Authentication\Models\User;

class Story extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'stories';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'story';
    public    $type          = 'story';
    const PATH = 'stories';

    public $translatable = ['title'];

    protected $fillable = [
        'id',
        'title',
        'provider_id'
    ];

    protected $casts = [
        'provider_id' => 'integer',
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

    public function items()
    {
       return $this->hasMany(StoryItem::class);
    }

    public function provider()
    {
       return $this->belongsTo(User::class,'provider_id','id');
    }
}
