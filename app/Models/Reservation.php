<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'user_id',
        'quantity',
        'reservation_date',
        'total_price',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
