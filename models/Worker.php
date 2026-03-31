<?php
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $table = 'workers';
    protected $fillable = ['full_name', 'skill', 'phone', 'is_busy', 'salary'];
    protected $casts = [
        'is_busy' => 'boolean',
    ];
    
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_workers');
    }
}