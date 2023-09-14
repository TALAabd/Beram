<?php

namespace Modules\Authentication\Models;
use Illuminate\Database\Eloquent\Model;

class VerificationCodes extends Model
{
    protected $table         = 'verification_codes';
    protected $fillable = [
        'identity',
        'code',
    ];
    protected $casts = [
        'code' => 'integer',
    ];
}
