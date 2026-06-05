<?php

namespace App\Domains\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Installation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cuit',
    ];

    public $timestamps = true;

    public function clients(): belongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
