<?php

namespace Modules\Hotels\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class HotelTerm extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'hotel_term';
    protected $seo_type      = 'hotel_term';
    public    $type          = 'hotel_term';
    const PATH = 'hotel_terms';
    const SERVICE = 'hotel_terms';
}
