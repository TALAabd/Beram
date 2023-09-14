<?php

namespace App\Models;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Country extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'countries';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    protected $seo_type      = 'country';
    public    $type          = 'country';
    const PATH = 'countries';

    public $translatable = ['name'];

    protected $fillable = [
        'id',
        'name'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

}
