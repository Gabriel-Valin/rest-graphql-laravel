<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GraphQLTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_products()
    {
        Product::factory(50)->create();

        $response = $this->graphQL('
        {
            products(first: 10) {
              data {
                id
                name
                brand
                category
                color
                price
              }
              paginatorInfo {
                currentPage
                lastPage
              }
            }
          }
        ');

        $response->assertJsonCount(10, 'data.products.data');
    }

    public function test_product_with_id() 
    {
        $product = Product::factory()->create();
        $response = $this->graphQL('
        {
            product(id:'.$product->id.') {
              id
              name
              brand
              category
              color
              price
            }
        }
        ');

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data.product.id')
                ->has('data.product.name')
                ->has('data.product.brand')
        );

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name
        ]);
    }

    public function test_create_mutation()
    {
        $response = $this->graphQL('
        mutation {
            createProduct(
              name: "Product GraphQL"
              brand: "Optico"
              category: "Sunglass"
              color: "Blue"
              price: 150.50
              description: "this is my description"
            ) {
              id
              name
              brand
              category
              color
              price
            }
          }
        ');

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data.createProduct.id')
                ->has('data.createProduct.name')
                ->has('data.createProduct.brand')
        );

        $this->assertDatabaseHas('products', [
            'name' => 'Product GraphQL'
        ]);
    }

    public function test_update_product_mutation()
    {
        $product = Product::factory()->create();

        $response = $this->graphQL('
        mutation {
            updateProduct(
              id:'.$product->id.'
              name: "Product GraphQL Updated"
              brand: "Optico Updated"
              category: "Sunglass Updated"
              color: "Blue Updated"
              price: 150.07
              description: "this is my description Updated"
            ) {
              id
              name
              brand
              category
              color
              price
            }
          }
        ');

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data.updateProduct.id')
                ->has('data.updateProduct.name')
                ->has('data.updateProduct.brand')
        );

        $this->assertDatabaseHas('products', [
            'name' => 'Product GraphQL Updated'
        ]);
    }

    public function test_delete_product_mutation() 
    {
        $product = Product::factory()->create();

        $response = $this->graphQL('
        mutation {
            deleteProduct(id:'.$product->id.') {
              name
            }
          }
        ');

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data.deleteProduct.name')
        );

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }
}
