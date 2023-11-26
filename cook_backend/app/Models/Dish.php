<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable=[
        "name",
        'description',
        'user_id'
        ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
            return $this->belongsToMany(Product::class);
    }
}
