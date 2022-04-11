<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dough extends Model
{
    use HasFactory;
    protected $table = 'dough';

    public function product()
    {
        return $this->hasMany(Product::class, "dough_id");
    }
}
