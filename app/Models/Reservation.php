<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded = [];
    use HasFactory;

    public function facility()
    {
        return $this->belongsTo(\App\Models\Facility::class, 'facility_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($this->rate_per_hour * $this->duration)
        );
    }
}
