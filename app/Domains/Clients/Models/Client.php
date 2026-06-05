<?php

namespace App\Domains\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cuit',
    ];

    public $timestamps = false;

    public function installations(): HasMany
    {
        return $this->hasMany(Installation::class);
    }
}
