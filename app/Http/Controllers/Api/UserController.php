<?php

namespace App\Http\Controllers\Api;

use App\Casts\UserData;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\UserCollection;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $data;
    public function __construct(UserData $userData)
    {
        $this->data = $userData;
    }

    public function getUser(Request $request)
    {
        $users = $this->data->getAllUser();
        if($users)
        {
            return response()->json(new UserCollection($users), 200);
        }
        $data = new \stdClass;
        return response()->json($data, 200);
    }

    public function getUserDetail()
    {
        $id = auth()->user()->id;
        $user = $this->data->getUserById($id);
        if($user)
        {
            return response()->json(new UserResource($user), 200);
        }
        return response()->json('Data Not Found', 200);
    }
}
