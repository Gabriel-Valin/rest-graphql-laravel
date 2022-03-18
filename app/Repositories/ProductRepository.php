<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository {
    protected $entity;

    public function __construct(Product $product) 
    {
        $this->entity = $product;
    }

    public function getAllProducts() 
    {
        return $this->entity->all();
    }

    public function getProduct(int $identify) 
    {
        return $this->entity->findOrFail($identify);
    }

    public function createNewProduct(array $data) 
    {
        return $this->entity->create($data);
    }

    public function updateProduct(string $identify, array $data) 
    {
        $product = $this->getproduct($identify);

        return $product->update($data);
    }

    public function deleteProduct(string $identify) 
    {
        $product = $this->getproduct($identify);

        return $product->delete();
    }
}