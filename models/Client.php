<?php
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = ['name', 'email', 'phone', 'password_hash'];
    protected $hidden = ['password_hash'];
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}