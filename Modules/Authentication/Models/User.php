<?php

namespace Modules\Authentication\Models;

use App\Models\Trip;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Modules\Hotels\Models\Hotel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Resturant\Models\Resturant;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasRoles;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasApiTokens, HasFactory, Notifiable;

    const Employee_Permissions = [
        'hotels_manager',
        'rooms_manager',
        'bookings_manager',
        'employees_manager',
    ];

    protected $table         = 'users';
    protected $slugField     = 'slug';
    protected $slugFromField = 'title';
    const PATH = 'users';
    protected $guard_name = 'user';

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'address',
        'phone',
        'birthday',
        'city',
        'state',
        'country',
        'zip_code',
        'parent_id',
        'role',      //new
        'fcm_token'  //new
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone' => 'integer',
        'zip_code' => 'integer',
        'status' => 'boolean'
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'parent_id'
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


    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function resturants()
    {
        return $this->hasMany(Resturant::class);
    }


    //Tree in table users for employees (provider and admin)
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
    //End Tree
}
