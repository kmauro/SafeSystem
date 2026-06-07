<?php

namespace App\Domains\Configs\Models;

use App\Domains\Clients\Models\Installation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $fillable = [
        'name',
        'color',
    ];

    public $timestamps = false;

    public function installations(): HasMany
    {
        return $this->hasMany(Installation::class);
    }
}
