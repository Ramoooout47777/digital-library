<?php
// app/Models/Cart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total',
        'items_count'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'items_count' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helper Methods
    public function addItem($bookId, $quantity = 1)
    {
        $book = Book::findOrFail($bookId);
        
        $cartItem = $this->items()->where('book_id', $bookId)->first();
        
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->total = $cartItem->price * $cartItem->quantity;
            $cartItem->save();
        } else {
            $cartItem = $this->items()->create([
                'book_id' => $bookId,
                'quantity' => $quantity,
                'price' => $book->final_price,
                'total' => $book->final_price * $quantity,
            ]);
        }

        $this->updateTotals();
        return $cartItem;
    }

    public function removeItem($itemId)
    {
        $this->items()->where('id', $itemId)->delete();
        $this->updateTotals();
    }

    public function updateItemQuantity($itemId, $quantity)
    {
        $cartItem = $this->items()->findOrFail($itemId);
        
        if ($quantity <= 0) {
            $this->removeItem($itemId);
            return;
        }

        $cartItem->quantity = $quantity;
        $cartItem->total = $cartItem->price * $quantity;
        $cartItem->save();
        $this->updateTotals();
    }

    public function clear()
    {
        $this->items()->delete();
        $this->updateTotals();
    }

    public function updateTotals()
    {
        $this->total = $this->items()->sum('total');
        $this->items_count = $this->items()->count();
        $this->save();
    }

    public function getTotalItems()
    {
        return $this->items()->sum('quantity');
    }
}