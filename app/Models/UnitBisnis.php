<?php

namespace App\Models;

use App\Traits\DBcustom\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitBisnis extends Model
{
    use HasFactory, SoftDeletes, CrudTrait;

    protected $table = "unit_bisnis";

    protected $guarded = ["id"];
}
