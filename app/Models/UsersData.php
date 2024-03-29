<?php

namespace App\Models;

use App\Traits\DBcustom\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersData extends Model
{
    use HasFactory, SoftDeletes, CrudTrait;

    protected $table = "users";

    protected $guarded = ["id"];
}
