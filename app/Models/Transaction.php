<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
   
    protected $fillable = [
        "user_id",
        "business_unit_id",
        "invoice",
        "start_time",
        "finish_time",
        "quantity",
        "total_price",
        "booking_date",
        "description",
        "payment_status",
        "order_status",
        "package_id",
        "package_name",
    ];
}
