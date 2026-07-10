<?php

namespace App\Domains\CRUD\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    protected $fillable = [
        'feature',
    ];

    public $timestamps = false;

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'device_feature');
    }
}
