<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function test_can_not_see_products_when_not_logged()
    {
        $response = $this->getJson(route('products.index'));

        $response->assertUnauthorized();
    }

    public function test_can_see_products()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->getJson(route('products.index'));

        $response->assertSuccessful();
    }

    public function test_can_not_store_product_without_valid_data()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('products.store'));

        $response->assertJsonValidationErrors([
            'category_id' => ['required'],
            'title' => ['required'],
            'price' => ['required'],
            'description' => ['required'],
        ]);
    }

    public function test_can_not_store_product_with_unexistent_category()
    {
        $user = User::factory()->admin()->create();

        $productData = [
            'category_id' => $this->faker->numberBetween(12345, 54321),
            'title' => $this->faker->text(),
            'price' => $this->faker->randomFloat(),
            'description' => $this->faker->text(),
        ];

        $response = $this->actingAs($user)->postJson(route('products.store'), $productData);

        $response->assertJsonValidationErrors([
            'category_id' => ['invalid'],
        ]);
    }

    public function test_can_store_product()
    {
        $user = User::factory()->admin()->create();

        $productData = [
            'category_id' => Category::factory()->create()->id,
            'title' => $this->faker->text(),
            'price' => $this->faker->randomFloat(),
            'description' => $this->faker->text(),
        ];

        $response = $this->actingAs($user)->postJson(route('products.store'), $productData);

        $response->assertCreated();
    }

    public function test_can_not_see_product_when_not_logged()
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('products.show', $product->uuid));

        $response->assertUnauthorized();
    }

    public function test_can_not_see_product_nonexistent()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->getJson(route('products.show', $this->faker->uuid()));

        $response->assertNotFound();
    }

    public function test_can_not_see_product()
    {
        $user = User::factory()->admin()->create();

        $product = Product::factory()->create();

        $response = $this->actingAs($user)->getJson(route('products.show', $product->uuid));

        $response->assertSuccessful();
    }

    public function test_can_not_update_product_when_not_logged()
    {
        $product = Product::factory()->create();

        $response = $this->putJson(route('products.update', $product->uuid));

        $response->assertUnauthorized();
    }

    public function test_can_not_update_product_without_valid_data()
    {
        $user = User::factory()->admin()->create();

        $product = Product::factory()->create();

        $response = $this->actingAs($user)->putJson(route('products.update', $product->uuid));

        $response->assertJsonValidationErrors([
            'category_id' => ['required'],
            'title' => ['required'],
            'price' => ['required'],
            'description' => ['required'],
        ]);
    }

    public function test_can_not_update_product_with_nonexistent_category()
    {
        $user = User::factory()->admin()->create();

        $product = Product::factory()->create();

        $productData = [
            'category_id' => $this->faker->numberBetween(12345, 54321),
            'title' => $this->faker->text(),
            'price' => $this->faker->randomFloat(),
            'description' => $this->faker->text(),
        ];

        $response = $this->actingAs($user)->putJson(route('products.update', $product->uuid), $productData);

        $response->assertJsonValidationErrors([
            'category_id' => ['invalid'],
        ]);
    }

    public function test_can_not_update_nonexistent_product()
    {
        $user = User::factory()->admin()->create();

        $product = Product::factory()->create();

        $productData = [
            'category_id' => Category::factory()->create()->id,
            'title' => $this->faker->text(),
            'price' => $this->faker->randomFloat(),
            'description' => $this->faker->text(),
        ];

        $response = $this->actingAs($user)
            ->putJson(route('products.update', $this->faker->uuid()), $productData);

        $response->assertNotFound();
    }

    public function test_can_update_product()
    {
        $user = User::factory()->admin()->create();

        $product = Product::factory()->create();

        $productData = [
            'category_id' => Category::factory()->create()->id,
            'title' => $this->faker->text(),
            'price' => $this->faker->randomFloat(),
            'description' => $this->faker->text(),
        ];

        $response = $this->actingAs($user)
            ->putJson(route('products.update', $product->uuid), $productData);

        $response->assertSuccessful();
        $this->assertDatabaseMissing(Product::class, [
            'title' => $product->title
        ]);
        $this->assertDatabaseHas(Product::class, [
            'title' => $productData['title']
        ]);
    }

    public function test_can_not_delete_product_when_not_logged()
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('products.destroy', $product->uuid));

        $response->assertUnauthorized();
    }

    public function test_can_not_delete_nonexistent_product()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->deleteJson(route('products.destroy', $this->faker->uuid()));

        $response->assertNotFound();
    }

    public function test_can_delete_nonexistent()
    {
        $user = User::factory()->admin()->create();

        $product = Product::factory()->create();

        $response = $this->actingAs($user)->deleteJson(route('products.destroy', $product->uuid));

        $response->assertSuccessful();
    }
}
