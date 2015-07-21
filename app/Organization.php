<?php namespace Ale;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $primaryKey="code";
    public $timestamps = false;
    public function scope_organization(){
        $this->hasMany('Ale\Scope_organization');
    }
}
