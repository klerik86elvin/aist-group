<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    protected $fillable = ['movie_id','date', 'price_rule_id'];

    public function movie()
    {
        return $this->belongsTo(Movie::class)->select('id','name');
    }
    public function halls()
    {
        return $this->belongsToMany(Hall::class)->withTimestamps();
    }
    public function price()
    {
        return $this->belongsTo(PriceRule::class, 'price_rule_id', 'id')->select('price', 'id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
