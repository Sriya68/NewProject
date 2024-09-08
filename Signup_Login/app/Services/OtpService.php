<?php

namespace App\Services;

use Twilio\Rest\Client;
use App\Models\UserOtp;
use Illuminate\Support\Str;

class OtpService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env("TWILIO_SID"), env("TWILIO_AUTH_TOKEN"));
    }


public function generateOtp($phoneNumber)
{
    $otp = mt_rand(1000, 9999); // Generates a random 4-digit number

    // Save OTP to the database
    UserOtp::updateOrCreate(
        ['phone' => $phoneNumber],
        ['otp' => $otp, 'is_verified' => false]
    );

    return $otp;
}

    public function sendOtp($phoneNumber, $otp)
    {
        $this->twilio->messages->create($phoneNumber, [
            'from' => env("TWILIO_PHONE_NUMBER"),
            'body' => "Your OTP code is {$otp}",
        ]);
    }

    public function verifyOtp($phoneNumber, $otp)
    {
        $otpRecord = UserOtp::where('phone', $phoneNumber)->where('otp', $otp)->first();

        if ($otpRecord) {
            $otpRecord->is_verified = true;
            $otpRecord->save();

            return true;
        }

        return false;
    }

}



