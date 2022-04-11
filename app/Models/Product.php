<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';

    public function categories()
    {
        return $this->belongsTo(Categories::class, "categories_id", "id");
    }

    public function dough()
    {
        return $this->belongsTo(Dough::class, "dough_id", "id");
    }

    public function getProduct()
    {
        $query = DB::table('product')
            ->selectRaw('product.id, product.name, product.description, 
                product.weight, product.price, product.amount,
                categories.name AS categories, dough.name AS dough')
            ->leftJoin('categories', 'categories.id', '=', 'product.categories_id')
            ->leftJoin('dough', 'dough.id', '=', 'product.dough_id');

        return $query;
    }
}
