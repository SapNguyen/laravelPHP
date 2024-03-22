<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Products extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    // public static $wrap = 'user';

    public function toArray($request)
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'product_material' => $this->product_material,
            'product_des' => $this->product_des,
            'product_price' => $this->product_price,
            'product_update_date' => $this->product_update_date,
            'product_active' => $this->product_active,
            'brand_id' => $this->brand_id,
            'discount_id' => $this->discount_id
        ];
    }
}
