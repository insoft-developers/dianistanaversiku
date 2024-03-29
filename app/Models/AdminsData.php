<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\DBcustom\CrudTrait;

class AdminsData extends Model
{
    use HasFactory, SoftDeletes, CrudTrait;

    protected $table = "admins";

    protected $guarded = ["id"];
}
