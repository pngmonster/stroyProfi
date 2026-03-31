<?php
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $fillable = ['client_id', 'stars', 'text'];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}