<?php

namespace Modules\Resturant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ResturantTerm extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $table         = 'resturant_term';
    protected $seo_type      = 'resturant_term';
    public    $type          = 'resturant_term';
    const PATH = 'resturant_terms';
    const SERVICE = 'resturant_terms';
}
