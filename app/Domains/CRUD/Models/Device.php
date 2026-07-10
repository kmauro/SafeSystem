<?php

namespace App\Domains\CRUD\Models;

use App\Domains\Clients\Models\Installation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Device extends Model
{
    protected $fillable = [
        'name',
        'type_id',
    ];

    public $timestamps = false;

    public function type(): BelongsTo
    {
        return $this->belongsTo(DeviceType::class, 'type_id');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'device_feature');
    }

    public function installations(): BelongsToMany
    {
        return $this->belongsToMany(Installation::class, 'installation_devices')
            ->withPivot(['id', 'code']);
    }
}
