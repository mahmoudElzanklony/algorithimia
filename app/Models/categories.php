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

    public function services()
    {
        return $this->hasMany(services::class,'category_id');
    }

    public function projects()
    {
        return $this->hasManyThrough(projects::class,services::class,'category_id','service_id');
    }
}
