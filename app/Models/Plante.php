<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plante extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price','category_id','image'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
