<?php

namespace Modules\Resturant\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Table extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'tables';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'table';
    public    $type          = 'table';
    const PATH = 'tables';
    const SERVICE = 'tables';

    public $translatable = ['title', 'content'];

    protected $fillable = [
        'title',
        'content',
        'price',
        'number',
        'size',
        'status',
        'create_user',
        'update_user',
        'resturant_id',
    ];

    protected $hidden = [
        'update_user',
        'deleted_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'size' => 'integer',
        'number' => 'integer',
        'resturant_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function getMediaUrlsAttribute()
    {
        return $this->getMedia('thumbnail')->map(function ($media) {
            return $media->getFullUrl();
        });
    }

    protected $appends = ['media_urls'];

    public function getTranslatedAttribute($attribute)
    {
        return $this->getTranslation($attribute, app()->getLocale());
    }

    public function updateStatus(): bool
    {
        $this->status = $this->status == true ? false : true;
        return $this->save();
    }

    public function resturant()
    {
        return $this->belongsTo(Resturant::class);
    }

    public function terms()
    {
        return $this->belongsToMany(CoreTerm::class, 'table_term');
    }
}
