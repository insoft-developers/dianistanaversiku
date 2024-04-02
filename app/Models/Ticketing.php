<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticketing extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "ticket_number",
        "user_id",
        "subject",
        "department",
        "priority",
        "message",
        "document",
        "status",
        
    ];
}
