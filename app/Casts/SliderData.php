<?php

namespace App\Casts;

use App\Models\Slider;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SliderData implements CastsAttributes
{
    protected $model;

    public function __construct(Slider $slider)
    {
        $this->model = $slider;
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

    public function getAll()
    {
        return $this->model->where('publish', 1)->get();
    }
}
