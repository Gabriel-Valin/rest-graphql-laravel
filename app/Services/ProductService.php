<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Http\Response;

class ProductService {
    protected $productRepository;

    public function __construct(ProductRepository $productRepository) 
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(array $params, $limit = 10, $page = 1) 
    {
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }

        if (isset($params['page'])) {
            $page = $params['page'];
        }
        
        $products = $this->productRepository->getAllProducts($limit, $page);
        
        if (!$products) {
            response()->json('No products registered.', Response::HTTP_NO_CONTENT);
        }

        return $products;
    }

    public function getProductByIdentifier(int $productId) 
    {
        $product = $this->productRepository->getProduct($productId);

        if (!$product) {
            response()->json('Product not found.', Response::HTTP_NO_CONTENT);
        }

        return $product;
    }

    public function createNewProduct(array $data) 
    {
        $newProduct = $this->productRepository->createNewProduct($data);
        return $newProduct;
    }

    public function deleteProduct(int $productId) 
    {
        return $this->productRepository->deleteProduct($productId);
    }

    public function updateProduct(int $productId, array $data) 
    {
        if ($this->productRepository->getProduct($productId)) {
            return $this->productRepository->updateProduct($productId, $data);
        }
    }
}