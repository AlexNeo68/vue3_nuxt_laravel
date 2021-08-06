<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function orderItems() {
       return $this->hasMany(OrderItem::class);
    }

    public function getTotalAttribute() {
       return $this->orderItems->sum(function($item){
           return $item->price * $item->quantity;
       });
    }

    public function getNameAttribute(){
        return "{$this->last_name} {$this->first_name}";
    }
}
