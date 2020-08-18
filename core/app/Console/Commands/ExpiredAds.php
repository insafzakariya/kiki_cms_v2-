<?php

namespace App\Console\Commands;

use AdManage\Models\Ad;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;

class ExpiredAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark ads as expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $dt= Carbon::today();
        $past   = $dt->subMonth(3);
        $ads = Ad::where('status',1)->where('ad_expire','<',$past)->update(['status' => 3]);


        /*foreach ($ads as $ad){
            if(((Carbon::parse($ad['ad_expire']) < Carbon::today()))) {
                $ad->update(['status' => 3]);    

                // $user = $ad->email;
                // $user = 'rullzzmm@gmail.com';
                // $url = url('ad/myadvertisement/all');

                // Mail::queue('emails.ad-status', ['url' => $url, 'status' => 3, 'title' => $ad->ad_title], function($message) use ($user) {
                //     $message->to($user, '')->subject('Ad unpublished due to validity period');
                // });            
            }
        }*/

        // $this->info('Expired ads marked successfully!');

        /*PASSWORD RESET REQUIRED EMAIL*/
     /* $user=User::where('wp_id','>',0)->where('mail_sent_count',0)->limit(10)->get();

        foreach ($user as $key => $value) {
            $url = 'http://sambole.lk/front/forget-password';
           return $user_email=$value->email;
       
            Mail::queue('emails.password-reset-required-email',['url' => $url], function ($message) use ($user_email){
                $message->subject('We have upgraded our solution');
                 $message->to($user_email, $name = null);
                // $message->to('insaf.zak@gmail.com', $name = null);
              // $message->cc('insaf.zak@gmail.com', $name = null);
              // $message->cc('sarin.groupit@maharaja.lk', $name = null);
             //  $message->cc('lakshitha@teamcybertech.com', $name = null);
            });
            $value->mail_sent_count+=1;
            $value->save();
        }

    }*/
    }
}