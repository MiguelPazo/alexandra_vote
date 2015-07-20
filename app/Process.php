<?php namespace Ale;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{

    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeStatus($query, $status)
    {
        $query->where('status', $status);
    }
}
