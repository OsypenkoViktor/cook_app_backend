<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id',
        "calories",
        "proteins",
        "fats",
        "carbohydrates",
        "description"
    ];
    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

    public function processes()
    {
        return $this->hasMany(CookProcess::class);
    }

    public function dishes()
    {
        return $this->belongsToMany(Dish::class);
    }
}
