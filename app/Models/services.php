<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class services extends Model
{
    use HasFactory;
    protected $fillable = ['category_id','name','info'];

    public function images(){
        return $this->morphMany(images::class,'imageable');
    }

    public function category(){
        return $this->belongsTo(categories::class,'category_id');
    }

    public function projects()
    {
        return $this->hasMany(projects::class,'service_id');
    }
}
