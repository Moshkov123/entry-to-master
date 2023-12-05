<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function recordingtime()
    {
        return $this->belongsTo(Recordingtime::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function recordDay()
    {
        return $this->belongsTo(Record_day::class);
    }
    
}
