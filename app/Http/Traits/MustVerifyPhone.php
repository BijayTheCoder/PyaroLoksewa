<?php
namespace App\Http\Traits;

use App\Notifications\VerifyPhone;
use Stevebauman\Location\Facades\Location;

trait MustVerifyPhone
{
    /**
     * Determine if the user has verified their phone address.
     *
     * @return bool
     */
    // public function hasVerifiedPhone()
    // {
    //     return ! is_null($this->mobile_verified_at);
    // }

    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    // public function markPhoneAsVerified()
    // {
    //     return $this->forceFill([
    //         'mobile_verified_at' => $this->freshTimestamp(),
    //     ])->save();
    // }

    /**
     * Send the phone verification notification.
     *
     * @return void
     */
    public function sendPhoneVerificationNotification($code)
    {
            $this->notify(new VerifyPhone($code));        
    }

    /**
     * Get the phone address that should be used for verification.
     *
     * @return string
     */
    public function getPhoneForVerification()
    {
        return $this->identifier;
    }
}