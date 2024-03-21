<?php

namespace App\Models;

use App\Http\Contracts\MustVerifyPhone;
use App\Http\Traits\MustVerifyPhone as TraitsMustVerifyPhone;
use Ichtrojan\Otp\Models\Otp as ModelsOtp;
use Ichtrojan\Otp\Otp as OtpOtp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Otp extends ModelsOtp implements MustVerifyPhone
{
    use HasFactory;
    use TraitsMustVerifyPhone;
    use Notifiable;
    

    public function sendVerificationNotificationToPhone($phone_number)
    {
        $otpotp = new OtpOtp;
        $otp = $otpotp->generate($phone_number,"numeric", 6,1440);
        $code = $otp->token;
        $modelOtp = ModelsOtp::where('token', $code)->first();
        if($modelOtp->identifier)
        {
            $modelOtp->sendPhoneVerificationNotification($code);
            return true;
        }else{
            return response()->json(['message'=>'Phone number does not exist'], 400);
        }
    }
}
