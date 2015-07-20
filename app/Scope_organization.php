<?php namespace Ale;

use Illuminate\Database\Eloquent\Model;

class Scope_organization extends Model {

	//
    public function elections()
    {
        return $this->belongsTo('Ale\Election');
    }
    public function organizations()
    {
        return $this->belongsTo('Ale\Organization');
    }
    public function scopes()
    {
        return $this->belongsTo('App\Post');
    }
}
