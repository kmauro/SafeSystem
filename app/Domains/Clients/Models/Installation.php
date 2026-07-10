<?php

namespace App\Domains\Clients\Models;

use App\Domains\Configs\Models\Category;
use App\Domains\Configs\Models\State;
use App\Domains\CRUD\Models\Device;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Installation extends Model
{
    protected $fillable = [
        'address',
        'description',
        'annotation',
        'client_id',
        'category_id',
        'state_id',
        'assigned_user_id',
    ];

    public $timestamps = true;

    public function clients(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function states(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'installation_devices')
            ->withPivot(['id', 'code']);
    }
}
