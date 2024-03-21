<?php

namespace App\Http\Controllers\Api\Auth;

use App\Casts\UserTokenData;
use App\Http\Controllers\Controller;
use App\Http\Requests\MobileRegisterRequest;
use App\Http\Requests\PhoneVerificationRequest;
use App\Http\Requests\UserDetailRequest;
use App\Models\Group;
use App\Models\Level;
use App\Models\Otp;
use App\Models\Position;
use App\Models\Service;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\UserToken;
use Carbon\Carbon;
use Ichtrojan\Otp\Otp as ModelOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    protected $user;
    protected $otp;
    protected $modelOtp;
    protected $userToken;
    protected $service;
    protected $level;
    protected $group;
    protected $position;
    protected $user_preference;
    protected $user_token_data;

    public function __construct(User $user, Otp $otp, ModelOtp $modelOtp, UserToken $userToken, Service $service, Level $level, Group $group, Position $position, UserPreference $userPreference, UserTokenData $userTokenData)
    {   
        $this->user = $user;
        $this->otp = $otp;
        $this->modelOtp = $modelOtp;
        $this->userToken = $userToken;
        $this->service = $service;
        $this->level = $level;
        $this->group = $group;
        $this->position = $position;
        $this->user_preference = $userPreference;
        $this->user_token_data = $userTokenData;
    }

    public function registerMobile(MobileRegisterRequest $request)
    {
        $phone_number = $request->phone_number;
        $userDeleted = $this->user->withTrashed()->where('phone_number', $phone_number)->whereNotNull('deleted_at')->first();
        if($userDeleted)
        {
            $this->user->forceDelete($$userDeleted->id);
        }

        $userStatus = Otp::where('identifier', $request->phone_number)->where('valid', 0)->first();
        if($userStatus)
        {
            return response()->json(['message'=>'Account with this number already exists',
        'status'=>false], 422);
        }

        if($this->otp->sendVerificationNotificationToPhone($phone_number))
        {
            return response()->json(['message'=>'OTP sent successfully',
                'status'=>true], 200);
        }
        else 
        {
            return response()->json(['message'=>'Error occured while sending OTP'], 200);
        }
        
    }

    public function numberVerify(PhoneVerificationRequest $request)
    {
        $validation = $this->modelOtp->validate($request->phone_number,$request->verification_code);
        // dd($validation);
        // dd($validation->status);
                if(!$validation->status)
                {
                    return response()->json(['message'=>'Otp not validated','status'=>true], 422);
                }
        // $request->fulfill();
            // $user = User::where('phone_number',$request->phone_number)->first();
            // $carbon = new Carbon();
            // $user->update(['mobile_verified_at'=>$carbon]);
        return response()->json(['message'=>'Mobile verified successfully',
        'status'=>true], 200);
    }

    public function additionalUserDetail(UserDetailRequest $request)
    {
        $input = $request->all();
        
        $services = $input['services'];
        $levels = $input['levels'];
        $groups = $input['groups'];
        $positions = $input['positions'];
        $current_date_time = Carbon::now()->toDateTimeString();
        
        $data['name'] = $input['fullname'];
        $data['phone_number'] = $input['phone_number'];
        $data['password'] = Hash::make($input['password']);
        $data['device_token'] = $input['device_token'];
        $data['phone_verified_at'] = $current_date_time;

        $verified = $this->otp->where('identifier', $data['phone_number'])->where('valid', 0)->first();
        if($verified)
        {
            $user = User::create($data);

            $token['authorization_token'] = $user->createToken('phone_number')->plainTextToken;
            $token['user_id'] = $user->id;

            $previousToken = $this->user_token_data->getUserSpecificToken($user->id);
            if(count($previousToken)>5)
            {
                $firstToken = $this->user_token_data->getUserSpecificToken($user->id);
                if(isset($firstToken))
                {
                    $existing_token = $firstToken->pluck('id')->first();
                    $this->userToken->where('id', $existing_token)->delete($existing_token);
                }
                
            }
            else 
            {
                $newToken = $this->userToken->create($token);
            }
                $preferences['user_id'] = $user->id;
                if(count($services)>0) 
                    {
                        foreach($services as $user_service)
                        {
                            $preferences['service_id'] = (int) $user_service;
                            if(!$this->user_preference->where('user_id', $preferences['user_id'])->where('service_id', $preferences['service_id'])->first())
                            {
                                $preference_services = $this->user_preference->where('user_id', $preferences['user_id'])->whereNull('service_id')->first();
                                if($preference_services)
                                {
                                    $this->user_preference->update([
                                        'service_id' => $preferences['service_id']
                                    ]);
                                }
                                else 
                                {
                                    $this->user_preference->create([
                                        'user_id' => $preferences['user_id'],
                                        'service_id' => $preferences['service_id']
                                    ]);
                                }
                                
                            }
                        }
        
                    }
                if(count($levels)>0) 
                {
                    foreach($levels as $user_level)
                        {
                            $level['level_id'] = (int) $user_level;
                            if(!$this->user_preference->where('user_id', $preferences['user_id'])->where('level_id', $level['level_id'])->first())
                            {
                                $preference_levels = $this->user_preference->where('user_id', $preferences['user_id'])->whereNull('level_id')->first();
                                if($preference_levels)
                                {
                                    $preference_levels->update([
                                        'level_id' => $level['level_id']
                                    ]);
                                }
                                else 
                                {
                                    $this->user_preference->create([
                                        'user_id' => $preferences['user_id'],
                                        'level_id' => $level['level_id']
                                    ]);
                                }
                            }
                        }
                }
                if(count($groups)>0) 
                {
                    foreach($groups as $user_group)
                        {
                            $group['group_id'] = (int) $user_group;
                            if(!$this->user_preference->where('user_id', $preferences['user_id'])->where('group_id', $group['group_id'])->first())
                            {
                                $preference_groups = $this->user_preference->where('user_id', $preferences['user_id'])->whereNull('group_id')->first();
                                if($preference_groups)
                                {
                                    $preference_groups->update([
                                        'group_id' => $group['group_id']
                                    ]);
                                }
                                else 
                                {
                                    $this->user_preference->create([
                                        'user_id' => $preferences['user_id'],
                                        'group_id' => $group['group_id']
                                    ]);
                                }
                            }
                        }
                }
        
                if(count($positions)>0) 
                {
                    foreach($positions as $user_position)
                        {
                            $position['position_id'] = (int) $user_position;
                            if(!$this->user_preference->where('user_id', $preferences['user_id'])->where('group_id', $position['position_id'])->first())
                            {
                                $preference_positions = $this->user_preference->where('user_id', $preferences['user_id'])->whereNull('position_id')->first();
                                if($preference_positions)
                                {
                                    $preference_positions->update([
                                        'group_id' => $group['group_id']
                                    ]);
                                }
                                else 
                                {
                                    $this->user_preference->create([
                                        'user_id' => $preferences['user_id'],
                                        'position_id' => $group['position_id']
                                    ]);
                                }
                            }
                        }
                }
            $verified->delete();
            return response()->json([
                'message' => 'Registered Successfully',
                'token' => $newToken->authorization_token,
                'id' => $user->id
            ]);
        }

        return response()->json(['message'=>'Phone Number Not Verified'], 422);
        // $input['']
    }
}
