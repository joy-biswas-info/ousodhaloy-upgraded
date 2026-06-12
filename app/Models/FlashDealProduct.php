<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashDealProduct extends Model
{
    protected $fillable = ['flash_deal_id', 'product_id', 'sale_price', 'max_quantity', 'sold_quantity'];
    protected $casts = ['sale_price' => 'decimal:2'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function deal()
    {
        return $this->belongsTo(FlashDeal::class, 'flash_deal_id');
    }
}