<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Color extends Model{
    protected $table = "colors";
    protected $fillable = [
        "name",
        "hex_code"
    ];

    public function products() {
        return $this->belongsToMany(Product::class);
}
}
