<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'description',
        'payload',
        'from_type',
        'to_id'
    ];

    protected $casts = [
        'to_id' => 'integer',
    ];
}
