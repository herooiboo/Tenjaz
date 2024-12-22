<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Products\StoreProductRequest;
use App\Http\Requests\v1\Products\UpdateProductRequest;
use App\Http\Resources\v1\Products\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * ProductController handles CRUD operations for products.
 * It leverages the ProductRepository for database interactions
 * and uses custom request classes for validation and resource classes for formatting responses.
 */
class ProductController extends Controller
{
    /**
     * Constructor to inject the ProductRepository.
     *
     * @param ProductRepository $productRepository Handles product-related database operations.
     */
    public function __construct(private readonly ProductRepository $productRepository)
    {
    }

    /**
     * Get all products with pagination.
     *
     * @return JsonResponse A paginated list of products formatted with meta information.
     */
    public function index(): JsonResponse
    {
        return response()->success(
            ProductResource::collection($this->productRepository->paginate()) // Fetch paginated products.
            ->response() // Convert resource collection to a response.
            ->getData(true) // Include meta information like pagination details.
        );
    }

    /**
     * Get a specific product by ID.
     *
     * @param Product $product The product to retrieve.
     * @return JsonResponse The product data in a structured format.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->success(new ProductResource($product));
    }

    /**
     * Create a new product.
     *
     * @param StoreProductRequest $request Validated product creation data.
     * @return JsonResponse The created product data with a success message.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        return response()->success(
            new ProductResource($this->productRepository->create($request->validated())), // Create product.
            'Product created successfully', // Success message.
            201 // HTTP status for resource creation.
        );
    }

    /**
     * Update an existing product.
     *
     * @param UpdateProductRequest $request Validated product update data.
     * @param Product $product The product to update.
     * @return JsonResponse The updated product data with a success message.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->productRepository->update($product->id, $request->validated()); // Update product in the repository.
        return response()->success(
            new ProductResource($this->productRepository->getById($product->id)), // Retrieve updated product.
            'Product updated successfully' // Success message.
        );
    }

    /**
     * Delete a product.
     *
     * @param Product $product The product to delete.
     * @return JsonResponse An empty response with a success message.
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->productRepository->delete($product->id); // Delete product from the repository.
        return response()->success([], 'Product deleted successfully'); // Respond with a success message.
    }

    /**
     * Get a product by its slug.
     *
     * @param Request $request The incoming request containing the slug in the route.
     * @return JsonResponse The product data associated with the slug.
     */
    public function showBySlug(Request $request): JsonResponse
    {
        return response()->success(
            new ProductResource(
                $this->productRepository
                    ->findByField( // Find the product by its slug field.
                        'slug',
                        $request->route('slug') // Extract slug from the route.
                    )
            )
        );
    }
}
