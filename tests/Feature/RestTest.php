<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RestTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_products()
    {
        Product::factory(10)->create();

        $response = $this->get('/api/products');

        $response->assertJsonCount(10, 'data');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_get_product_by_identifier()
    {
        $product = Product::factory()->create();
        $response = $this->get('/api/products/'.$product->id);

        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data.id')
                ->has('data.productName')
                ->has('data.productPrice')
        );
        $response->assertStatus(Response::HTTP_OK);
        
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name
        ]);
    }

    public function test_create_new_product() 
    {
        $response = $this->post('/api/products', [
            'name' => 'Tdd product',
            'category' => 'tdd',
            'description' => 'Lorem epsum text',
            'brand' => 'eotica',
            'price' => 150.03,
            'color' => 'light blue'
        ]);

        $response->assertJsonCount(1);
        $response->assertJson(fn (AssertableJson $json) => 
            $json->has('data.id')
                ->has('data.productName')
                ->has('data.productPrice')
        );
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('products', [
            'name' => 'Tdd product'
        ]);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create();
        $response = $this->put('api/products/'.$product->id, [
            'name' => 'Product UPDATED',
            'category' => 'UPDATED',
            'description' => 'Lorem epsum text UPDATED',
            'brand' => 'eotica UPDATED',
            'price' => 150.03,
            'color' => 'light blue UPDATED'
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Product UPDATED'
        ]);
    }

    public function test_delete_product() 
    {
        $product = Product::factory()->create();
        $response = $this->delete('api/products/'.$product->id);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        
        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }

    public function test_non_existent_delete_product()
    {
        $response = $this->delete('api/products/1010');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_non_exist_product()
    {
        $response = $this->put('api/products/2022', [
            'name' => 'Product UPDATED',
            'category' => 'UPDATED',
            'description' => 'Lorem epsum text UPDATED',
            'brand' => 'eotica UPDATED',
            'price' => 150.03,
            'color' => 'light blue UPDATED'
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_product_without_required_fields()
    {
        $response = $this->put('api/products/2022');
        $response->assertJsonStructure(['data' => ['brand', 'name', 'category']]);
    }
}
