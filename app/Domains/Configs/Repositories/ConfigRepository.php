<?php

namespace App\Domains\Configs\Repositories;

use App\Domains\Configs\Models\State;
use App\Domains\Configs\Models\Category;
use Illuminate\Support\Collection;

class ConfigRepository
{
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function createState(array $data): State
    {
        return State::create($data);
    }

    public function getCategories(): Collection
    {
        return Category::all();
    }

    public function getStates(): Collection
    {
        return State::all();
    }
}
