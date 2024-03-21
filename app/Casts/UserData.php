<?php

namespace App\Casts;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class UserData implements CastsAttributes
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
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

    public function getAllUser()
    {
        return $this->model->get();
    }

    public function getUserById($id)
    {
        return $this->model->where('id', $id)->first();
    }
}
