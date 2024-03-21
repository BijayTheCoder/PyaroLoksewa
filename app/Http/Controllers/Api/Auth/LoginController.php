<?php

namespace App\Http\Controllers\Api\Auth;

use App\Casts\UserTokenData;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    
    protected $user;
    protected $user_token_data;
    protected $userToken;
    public function __construct(User $user, UserToken $userToken, UserTokenData $userTokenData)
    {
        $this->user = $user;
        $this->user_token_data = $userTokenData;
        $this->userToken = $userToken;
    }
    
    public function login(LoginRequest $request)
    {
        $user_exists = $this->user->where('phone_number', $request->phone_number)->first();
        if($user_exists)
        {
            $unhased = Hash::check($request->password, $user_exists->password);
            if(!$unhased)
            {
                return response()->json(['message'=>'Password you entered is incorrect'], 401);
            }

            $token['authorization_token'] = $user_exists->createToken('phone_number')->plainTextToken;
            $token['user_id'] = $user_exists->id;

            $previousToken = $this->user_token_data->getUserSpecificToken($user_exists->id);
            if(count($previousToken)>5)
            {
                $firstToken = $this->user_token_data->getUserSpecificToken($user_exists->id);
                if(isset($firstToken))
                {
                    $existing_token = $firstToken->pluck('id')->first();
                    $this->userToken->where('id', $existing_token)->delete();
                }
                
            }
            
            $newToken = $this->userToken->create($token);

            $device_token = $user_exists->device_token;
            if($request->get('device_token'))
            {
                if($device_token)
                {
                    $tokens_list = json_decode($device_token);
                    if(!$tokens_list)
                    {
                        $tokens_list [] = $device_token; 
                    }
                    if(count($tokens_list)>5) 
                    {
                        $deleted = array_shift($tokens_list);
                        if($deleted)
                        {
                            array_push($tokens_list, $request->get('device_token'));
                        }
                    }
                    else 
                    {
                        array_push($tokens_list, $request->get('device_token'));
                    }

                    $user_exists->device_token = json_encode($tokens_list);
                    $user_exists->save();
                }
                else 
                {
                    $tokens_list []= $device_token;
                }

            }
            else 
            {
                $tokens_list []= $device_token;
            }
            return response()->json(
                [
                    'message' => 'User logged in successfully',
                    'device_token' => ($tokens_list)?end($tokens_list):null,
                    'token' => $newToken->authorization_token,
                    'id' => $user_exists->id
                ]
            );

        }
        return response()->json(['message'=>'User with this mobile number doesnot exists'], 404);
        }
}
