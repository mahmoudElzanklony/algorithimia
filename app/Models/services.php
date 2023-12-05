<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class services extends Model
{
    use HasFactory;
    protected $fillable = ['category_id','name','info'];

    public function image(){
        return $this->morphOne(images::class,'imageable');
    }
}
