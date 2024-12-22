<?php

namespace App\Http\Resources\v1\Products;

use App\Services\v1\Products\ProductPricingService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ProductResource transforms product data into a structured format for API responses.
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request The current HTTP request instance.
     * @return array<string, mixed> The structured product response.
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,                          // The unique identifier of the product.
            'slug'          => $this->slug,                        // The SEO-friendly slug for the product.
            'name'          => $this->name,                        // The name of the product.
            'description'   => $this->description,                 // The product's description.
            'image'         => storageUrl($this->image),           // The full URL to the product's image.
            'price'         => ProductPricingService::handle($this->price), // Adjusted price based on pricing logic.
            'is_active'     => (bool) $this->is_active,            // Whether the product is active (cast to boolean).
            'created_at'    => $this->created_at,                  // Timestamp of product creation.
            'updated_at'    => $this->updated_at,                  // Timestamp of last update.
        ];
    }
}
