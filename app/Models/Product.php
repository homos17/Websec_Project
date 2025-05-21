<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
    protected $table = "products";
    protected $fillable = [
        'code',
        'name',
        'price',
        'description',
        'photo',
        'category',
        'quantity',
        'created_by',
    ];

    public function colors() {
        return $this->belongsToMany(Color::class);
}

    public function sizes() {
        return $this->belongsToMany(Size::class, 'product_size', 'product_id', 'size_id');
    }
}