<?php

namespace App\Helpers;

use Twilio\Rest\Client;

class Helper
{
    public static function TwilioMessage(string $number, string $message)
    {
        $account_sid = config('twilio.account_sid');
        $auth_token = config('twilio.auth_token');
        $twilio_number = config('twilio.sms_from');






        // <?php

        // Update the path below to your autoload.php,
        // see https://getcomposer.org/doc/01-basic-usage.md
        // require_once '/path/to/vendor/autoload.php';


        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        // $twilio = new Client($account_sid, $auth_token);

        // $service = $twilio->verify->v2->services
        //     ->create("My First Verify Service");

        // return $service->sid;




        // $verification = $twilio->verify->v2->services("VAbb6b7edaf91872c3a64a655b05b32783")
        //     ->verifications
        //     ->create("+15017122661", "sms");

        // return $verification->status;







        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            $number,
            array(
                'from' => $twilio_number,
                'body' => 'I sent this message in under 10 minutes!'
            )
        );
        return $client;
    }
}
