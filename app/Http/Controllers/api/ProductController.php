<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\CreateProduct;
use App\Http\Requests\UpdateProduct;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller {
    protected $productService;

    public function __construct(ProductService $productService) 
    {
        $this->productService = $productService;
    }

    public function getAllProducts(Request $request): JsonResource 
    {
        $products = $this->productService->getAllProducts($request->all());
        return ProductResource::collection($products);
    }

    public function getProductByIdentify(int $productId): JsonResource 
    {
        $product = $this->productService->getProductByIdentifier($productId);
        return new ProductResource($product);
    }

    public function createNewProduct(CreateProduct $request): JsonResource
    {
        $newProduct = $this->productService->createNewProduct($request->all());
        return new ProductResource($newProduct);
    }

    public function updateProduct(UpdateProduct $request, int $productId): JsonResponse
    {
        $product = $this->productService->updateProduct($productId, $request->all());
        return response()->json(['message' => 'Product updated.'], Response::HTTP_NO_CONTENT);

    }

    public function deleteProduct(int $productId): JsonResponse
    {
        $this->productService->deleteProduct($productId);
        return response()->json(['message' => 'Product deleted.'], Response::HTTP_NO_CONTENT);
    }
}
