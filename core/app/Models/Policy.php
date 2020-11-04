<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model{

    protected $primaryKey = "PolicyID";


    protected $fillable = [
        'PolicyID',
        'Name',
        'PolicyType',
    ];

    static function  getAdvertisementPolicies(){
        return SELF::where('policyType', 6)
            ->where('validFrom', '<=', date("Y-m-d"))
            ->where('validTo', '>=', date("Y-m-d"))
            ->whereStatus(1)
            ->get();
    }
    static function  getChannelContentPolicies(){
        return SELF::where('policyType', 1)
            ->where('validFrom', '<=', date("Y-m-d"))
            ->where('validTo', '>=', date("Y-m-d"))
            ->whereStatus(1)
            ->get();
    }
    static function  getProgrammeContentPolicies(){
        return SELF::where('policyType',2)
            ->where('validFrom', '<=', date("Y-m-d"))
            ->where('validTo', '>=', date("Y-m-d"))
            ->whereStatus(1)
            ->get();
    }
    static function  getEpisodeContentPolicies(){
        // return SELF::where('policyType',4)
        //     ->where('validFrom', '<=', date("Y-m-d"))
        //     ->where('validTo', '>=', date("Y-m-d"))
        //     ->whereStatus(1)
        //     ->get();
        return SELF::where('validFrom', '<=', date("Y-m-d"))
            ->where('validTo', '>=', date("Y-m-d"))
            ->whereStatus(1)
            ->get();
    }
    static function  getChannelContentPoliciesByFilterIds($used_content_policy_ids){
        return SELF::where('policyType', 1)
            ->where('validFrom', '<=', date("Y-m-d"))
            ->where('validTo', '>=', date("Y-m-d"))
            ->whereStatus(1)
            ->whereNotIn('PolicyID', $used_content_policy_ids)
            ->get();
    }
    static function  getProgrammeContentPoliciesByFilterIds($used_content_policy_ids){
        return SELF::where('policyType',2)
            ->where('validFrom', '<=', date("Y-m-d"))
            ->where('validTo', '>=', date("Y-m-d"))
            ->whereStatus(1)
            ->whereNotIn('PolicyID', $used_content_policy_ids)
            ->get();
    }
    static function  getEpisodeContentPoliciesByFilterIds($used_content_policy_ids){
        // return SELF::where('policyType',4)
        //     ->where('validFrom', '<=', date("Y-m-d"))
        //     ->where('validTo', '>=', date("Y-m-d"))
        //     ->whereStatus(1)
        //     ->whereNotIn('PolicyID', $used_content_policy_ids)
        //     ->get();
        return SELF::where('validFrom', '<=', date("Y-m-d"))
            ->where('validTo', '>=', date("Y-m-d"))
            ->whereStatus(1)
            ->whereNotIn('PolicyID', $used_content_policy_ids)
            ->get();
    }
}
