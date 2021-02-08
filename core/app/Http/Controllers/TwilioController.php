<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use TwiloManage\Models\ChatLog;

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
                                'type' => 'public',
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
    public function getAllMembers()
    {
        $channelID='CH5eae752253b943eb80d030bebec88105';
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);

        $member = $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
        ->channels($channelID)
        ->members
        ->read([]);

        foreach ($member as $record) {
            return $record;
            // $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
            //      ->channels($channelID)
            //      ->members($record->sid)
            //      ->delete();

            
            // $twilio->chat->v2->services(env('TWILIO_SERVICE_SID'))
            //      ->users("USXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
            //      ->delete();
           
        }
    


    }
    public function getAllChat($channelSID,$channel_id)
    {
        // $channelSID='CH0b14fde1a2644d29afa32bcec26f0206';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://chat.twilio.com/v2/Services/'.env('TWILIO_SERVICE_SID').'/Channels/'.$channelSID.'/Messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_USERPWD, env('TWILIO_ACCOUNT_SID') . ':' .  env('TWILIO_AUTH_TOKEN'));

        $result = curl_exec($ch);
        ChatLog::create([
            'log'=>$result,
            'channel_sid'=>$channelSID,
            'channel_id'=>$channel_id
            ]);

     
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);



    }

   
}
