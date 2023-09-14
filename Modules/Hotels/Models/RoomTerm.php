<?php

namespace Modules\Hotels\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class RoomTerm extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'room_term';
    protected $seo_type      = 'room_term';
    public    $type          = 'room_term';
    const PATH = 'room_terms';
    const SERVICE = 'room_terms';
}
