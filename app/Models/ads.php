<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ads extends Model
{
    use HasFactory;

    protected $fillable = ['name','info'];

    public function requirements(){
        return $this->hasMany(ads_requirments::class,'ad_id');
    }
}
