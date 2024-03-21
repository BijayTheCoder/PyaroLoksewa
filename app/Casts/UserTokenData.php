<?php

namespace App\Casts;

use App\Models\UserToken;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class UserTokenData implements CastsAttributes
{
    protected $model;

    public function __construct(UserToken $userToken)
    {
        $this->model = $userToken;
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

    public function getUserSpecificToken($id)
    {
        return $this->model->where('user_id', $id)->get();
    }
}
