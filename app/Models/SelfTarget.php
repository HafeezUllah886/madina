<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfTarget extends Model
{

    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'startDate' => 'datetime',
        'endDate' => 'datetime',
        'targetQty' => 'float',
    ];
    public function category()
    {
        return $this->belongsTo(categories::class, 'categoryID');
    }

   public function getStatusAttribute()
   {
     $products = products::where('catID', $this->categoryID)->get();
     $totalQty = 0;
     foreach($products as $product)
     {
       $purchase = purchase_details::where('productID', $product->id)->whereBetween('date', [$this->startDate, $this->endDate])->sum('qty');
      
       // Guard against null/zero unit values
       $unitValue = optional($product->unit)->value ?? null;
       if($unitValue && $unitValue != 0){
           $totalQty += $purchase / $unitValue;
       }
     }

     if($this->endDate > now())
     {
        if($totalQty >= $this->targetQty)
        {
            return ['totalQty' => $totalQty, 'status' => "Achieved", 'color'=>"success"];
        }
        else
        {
            return ['totalQty' => $totalQty, 'status' => "Pending", 'color'=>"warning"];
        }
     }
     else
     {
        if($totalQty >= $this->targetQty)
        {
            return ['totalQty' => $totalQty, 'status' => "Achieved", 'color'=>"success"];
        }
        else
        {
            return ['totalQty' => $totalQty, 'status' => "Not Achieved", 'color'=>"danger"];
        }
     }
   }
}
