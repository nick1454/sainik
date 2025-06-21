<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;

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
        'balance_payment',
        'fee_structure_id',
        'payment_for_month',
        'payment_for_year'
    ];

    public function studentInfo()
    {
        return $this->belongsTo(Student::class, 'paid_by');
    }

    public function getReceiptUrlAttribute($value)
    {
        if ($value) {
            return asset($value);
        }

        return null;
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class,'fee_structure_id','id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
