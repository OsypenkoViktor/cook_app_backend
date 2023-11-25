<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookProcess extends Model
{
    use HasFactory;

    protected $fillable=[
        "name",
        "duration",
        "cookPresenceInterval",
        "description",
        "product_id"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
