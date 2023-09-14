<?php

namespace Modules\Hotels\Models;

use App\Models\CoreTerm;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Room extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'rooms';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'room';
    public    $type          = 'room';
    const PATH = 'rooms';
    const SERVICE = 'rooms';

    public $translatable = ['title', 'content'];

    protected $fillable = [
        'title', 'content','foreign_price', 'syrian_price', 'number', 'beds', 'size','baths','adults', 'children', 'status',
    ];

    protected $hidden = ['create_user', 'update_user', 'deleted_at'];

    protected $casts = [
        'foreign_price' => 'float',
        'syrian_price' => 'float',
        'number' => 'integer',
        'beds' => 'integer',
        'size' => 'integer',
        'adults' => 'integer',
        'children' => 'integer',
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

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function terms()
    {
        return $this->belongsToMany(CoreTerm::class, 'room_term');
    }

    public function scopeFilter($query, $filter)
    {
        // $query->when(isset($filter['min_price']) && isset($filter['max_price']), function ($query) use ($filter) {
        //     $query->whereBetween('price', [$filter['min_price'], $filter['max_price']]);
        // });
        $query->when(isset($filter['adults']), function ($query) use ($filter) {
            $query->where('adults', '>=', $filter['adults']);
        });
        $query->when(isset($filter['children']), function ($query) use ($filter) {
            $query->where('children', '>=', $filter['children']);
        });
    }
}
