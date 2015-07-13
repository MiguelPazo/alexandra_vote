<?php namespace Ale;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{

    protected $fillable = [];
    public $timestamps = false;

    public function scopeProcess($query, $idProcess)
    {
        $query->where('process_id', $idProcess);
    }

}
