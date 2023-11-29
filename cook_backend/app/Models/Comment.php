<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'text',
        'dish_id'
        ];
    public function author(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function dish()
    {
        return $this->belongsTo(Dish::class,'dish_id');
    }

}
