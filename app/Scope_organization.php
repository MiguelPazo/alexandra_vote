<?php namespace Ale;

use Illuminate\Database\Eloquent\Model;

class Scope_organization extends Model {

	//
    public function election()
    {
        return $this->belongsTo('Ale\Election','election_code');
    }
    public function organization()
    {
        return $this->belongsTo('Ale\Organization','organization_code');
    }
    public function scope()
    {
        return $this->belongsTo('Ale\Scope','scope_code');
    }

    public function scopeCedula($query, $value)
    {
        $query->where('scope_code','=', $value);
    }
}
