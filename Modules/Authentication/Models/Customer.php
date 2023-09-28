<?php

namespace Modules\Authentication\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Booking\Models\Booking;

class Customer extends Authenticatable implements JWTSubject, HasMedia
{
    use HasRoles;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasApiTokens, HasFactory, Notifiable;

    protected $table         = 'customers';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    const PATH = 'customers';
    protected $guard_name = 'customer';
    protected $fillable = [
        'name',
        'status',
        'first_name',
        'last_name',
        'email',
        'gender',
        'address',
        'phone',
        'password',
        'birthday',
        'city',
        'state',
        'country',
        'zip_code',
        'bio',
        'fcm_token',
        'nationality'
    ];

    protected $hidden=[
        'password'
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function updateStatus(): bool
    {
        $this->status = $this->status == true ? false : true;
        return $this->save();
    }

    public function getMediaUrlsAttribute()
    {
        return $this->getMedia('thumbnail')->map(function ($media) {
            return $media->getFullUrl();
        });
    }

    protected $appends = ['media_urls'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }
}
