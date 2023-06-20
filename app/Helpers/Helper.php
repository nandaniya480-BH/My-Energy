<?php

namespace App\Helpers;

use Twilio\Rest\Client;

class Helper
{
    public static function TwilioMessage(string $number, string $message)
    {
        $account_sid = config('laratwilio.account_sid');
        $auth_token = config('laratwilio.auth_token');
        $twilio_number = config('laratwilio.sms_from');

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            $number,
            array(
                'from' => $twilio_number,
                'body' => 'I sent this message in under 10 minutes!'
            )
        );
    }
}
