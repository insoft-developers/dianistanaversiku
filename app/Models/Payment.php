<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "payment_name",
        "payment_desc",
        "payment_type",
        "due_date",
        "periode",
        "payment_amount",
        "payment_dedication"

    ];
}
