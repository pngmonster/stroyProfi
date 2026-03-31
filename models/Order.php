<?php
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['title', 'price', 'area', 'address', 'client_id'];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    public function workers()
    {
        return $this->belongsToMany(Worker::class, 'order_workers');
    }
    
    public function estimates()
    {
        return $this->hasMany(Estimate::class);
    }
}