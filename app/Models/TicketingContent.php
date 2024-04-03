<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketingContent extends Model
{
    use HasFactory;

    protected $fillable = [
        "ticket_number",
        "user_id",
        "message",
        "is_reply",
        "document",
    ];
}   
