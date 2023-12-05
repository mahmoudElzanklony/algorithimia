<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ads_requirments extends Model
{
    use HasFactory;

    protected $fillable = ['ad_id','name'];
}
