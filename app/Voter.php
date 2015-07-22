<?php namespace Ale;

use Illuminate\Database\Eloquent\Model;
use Ale\Constants\Db;

class Voter extends Model {

    public $timestamps = false;

    public function scopeEnable($query)
    {       
        return $query->where('enabled',Db::VOTER_ENABLED);
    }
    public function scopePin($query, $pin){
    	return $query->where('pin',$pin);
    }
    public function scopePending($query){
    	return $query->where('status',Db::VOTER_PENDING);
    }
    public function scopeNumele($query,$numele){
    	return $query->where('num_ele',$numele);
    }

}
