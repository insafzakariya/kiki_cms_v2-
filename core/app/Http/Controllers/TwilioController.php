<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createChannel($unique_name,$friendly_name)
    {
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        return $channel = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                        ->channels
                        ->create([
                                'uniqueName' =>$unique_name,
                                'friendlyName' =>$friendly_name,
                                'type' => 'private',
                        ]);
       
    }
   
    public function getAllChannels()
    {
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $channel = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
        ->channels(['uniqueName'=>'TEST_CHANNEL_56'])
        ->fetch();
    }

    public function deleteChannel($channelID)
    {
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        return $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
                 ->channels($channelID)
                 ->delete();

       
       
    }

   
}
