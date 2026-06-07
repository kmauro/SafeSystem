<?php

namespace App\Domains\Configs\Services;

use App\Domains\Configs\Models\Category;
use App\Domains\Configs\Models\State;
use App\Domains\Configs\Repositories\ConfigRepository;
use Illuminate\Support\Collection;

class ConfigService
{

    public function __construct(
        protected ConfigRepository $configRepository,
    ){}

    public function createCategory(array $data): Category
    {
        return $this->configRepository->createCategory($data);
    }

    public function createState(array $data): State
    {
        return $this->configRepository->createState($data);
    }

    public function getCategories(): Collection
    {
        return $this->configRepository->getCategories();
    }

    public function getStates(): Collection
    {
        return $this->configRepository->getStates();
    }
}
