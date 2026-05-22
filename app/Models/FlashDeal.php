<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashDeal extends Model
{
    protected $fillable = ['title', 'starts_at', 'ends_at', 'is_active'];
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'boolean'];

    public function products()
    {
        return $this->hasMany(FlashDealProduct::class);
    }
}

class FlashDealProduct extends Model
{
    protected $fillable = ['flash_deal_id', 'product_id', 'sale_price', 'max_quantity', 'sold_quantity'];
    protected $casts = ['sale_price' => 'decimal:2'];

    public function product() { return $this->belongsTo(Product::class); }
    public function deal()    { return $this->belongsTo(FlashDeal::class, 'flash_deal_id'); }
}
