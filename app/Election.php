<?php namespace Ale;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $primaryKey="code";
    protected $fillable = [];
    public $timestamps = false;

    public function scopeProcess($query, $idProcess)
    {
        $query->where('process_id', $idProcess);
    }

}
