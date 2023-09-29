<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','session_id','seat_id','hall_id'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

}
