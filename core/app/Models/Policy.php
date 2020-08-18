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
}
