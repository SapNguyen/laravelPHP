<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandHome extends JsonResource
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
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name,
            'brand_logo' => $this->brand_logo,
            'brand_img' => $this->brand_img,
            'brand_des' => $this->brand_des,
            'brand_des_img' => $this->product_update_date,
            'brand_active' => $this->product_active
        ];
    }
}
