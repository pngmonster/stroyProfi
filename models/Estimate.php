<?php
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    protected $table = 'estimates';
    protected $fillable = ['order_id', 'title', 'cost'];
    public $incrementing = false;
    protected $primaryKey = ['order_id', 'title'];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}