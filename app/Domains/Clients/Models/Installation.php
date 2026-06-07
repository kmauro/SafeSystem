<?php

namespace App\Domains\Clients\Models;

use App\Domains\Configs\Models\Category;
use App\Domains\Configs\Models\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

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

    public function clients(): belongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function categories(): belongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function states(): belongsTo
    {
        return $this->belongsTo(State::class);
    }
}
