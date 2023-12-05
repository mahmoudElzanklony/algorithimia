<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class projects extends Model
{
    use HasFactory;

    protected $fillable = ['service_id','name','info','skills','link'];

    public function images(){
        return $this->morphMany(images::class,'imageable');
    }
}
