<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\MobileRegisterRequest;
use App\Http\Requests\PhoneVerificationRequest;
use App\Models\Otp;
use App\Models\User;
use Ichtrojan\Otp\Otp as OtpOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{

    protected $user;
    protected $otp;
    protected $modelOtp;

    public function __construct(User $user, Otp $otp, OtpOtp $modelOtp)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->modelOtp = $modelOtp;
    }

    public function sendResetOtp(MobileRegisterRequest $request)
    {
        $user_exists = $this->user->where('phone_number', $request->phone_number)->first();
        if($user_exists)
        {
            if($this->otp->sendVerificationNotificationToPhone($request->phone_number))
            {
                return response()->json(['message'=>'OTP sent successfully',
                    'status'=>true], 200);
            }
        }
        else 
        {
            return response()->json(['message'=>'Error occured while sending OTP', 'status'=>false], 422);
        }
        return response()->json(['message'=>'User with this mobile number doesnot exists', 'status'=>false], 404);
    }

    public function resetVerify(PhoneVerificationRequest $request)
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

    public function store(ForgotPasswordRequest $request)
    {
        $input = $request->all();

        $userVerified = Otp::where('identifier',$request->phone_number)->where('valid', 0)->first();
        // dd($userVerified);

        $input['password'] = Hash::make($input['password']);

        $user = User::where('phone_number', $request->phone_number);
        // dd($user);

        if($userVerified){
        $user->update([
            'password'=>$input['password'],
        ]);
        $userVerified->delete();
        return response()->json(['message'=>'Password changed successfully', 'status'=>true], 200);
        }
    }

}
