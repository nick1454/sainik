<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'discount',
        'type',
        'status',
        'paid_by',
        'receipt_url',
    ];

    public function studentInfo()
    {
        return $this->belongsTo(Student::class, 'paid_by');
    }

    public function getReceiptUrlAttribute($value)
    {
        // return "https://res.cloudinary.com/demo/basketball_shot.jpg";
        if ($value) {
            return asset('/'.$value);
        }
        return null;
    }
}
