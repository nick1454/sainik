<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $table = 'fee_structures';

    protected $fillable = [
        'admission_fee',
        'annual_fee',
        'tution_fee',
        'transport_fee',
        'class_id',
        'status'
    ];
}
