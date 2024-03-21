<?php

namespace App\Casts;

use App\Models\Service;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ServiceData implements CastsAttributes
{
    protected $model;

    public function __construct(Service $service)
    {
        $this->model = $service;
    } 

    public function getAll()
    {
        return $this->model->where('publish', 1)->get();
    }

    public function getPaginated($pageSize)
    {
        return $this->model->where('publish', 1)->paginate($pageSize);
    }
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
