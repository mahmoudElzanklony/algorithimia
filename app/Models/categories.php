<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class categories extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name'];

    public function image(){
        return $this->morphOne(images::class,'imageable');
    }
}
