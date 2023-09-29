<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallSession extends Model
{
    use HasFactory;
    protected $table = 'hall_session';
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }
}
