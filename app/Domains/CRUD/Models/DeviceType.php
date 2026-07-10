<?php

namespace App\Domains\CRUD\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceType extends Model
{
    protected $fillable = [
        'type',
    ];

    public $timestamps = false;

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'type_id');
    }
}
