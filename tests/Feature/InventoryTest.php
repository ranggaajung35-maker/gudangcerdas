<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_out_cannot_exceed_available_stock(): void
    {
        $user = User::create(['name' => 'Staff Test', 'email' => 'staff@test.local', 'password' => 'Password123!', 'role' => 'staff']);
        $category = Category::create(['name' => 'Elektronik']);
        $product = Product::create(['sku' => 'TEST-001', 'name' => 'Produk Test', 'category_id' => $category->id, 'purchase_price' => 100, 'selling_price' => 120, 'stock' => 2, 'min_stock' => 1, 'unit' => 'pcs']);

        $response = $this->actingAs($user)->post('/stock-movements', [
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => 3,
            'movement_date' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('quantity');
        $this->assertSame(2, $product->fresh()->stock);
        $this->assertDatabaseCount('stock_movements', 0);
    }

    public function test_stock_in_creates_history_and_updates_product_balance(): void
    {
        $user = User::create(['name' => 'Staff Test', 'email' => 'staff-in@test.local', 'password' => 'Password123!', 'role' => 'staff']);
        $category = Category::create(['name' => 'Peralatan']);
        $product = Product::create(['sku' => 'IN-001', 'name' => 'Barang Masuk', 'category_id' => $category->id, 'purchase_price' => 100, 'selling_price' => 120, 'stock' => 0, 'min_stock' => 1, 'unit' => 'pcs']);

        $response = $this->actingAs($user)->post('/stock-movements', [
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 5,
            'reference' => 'PO-TEST-001',
            'movement_date' => now()->toDateString(),
        ]);

        $response->assertRedirect('/stock-movements');
        $this->assertSame(5, $product->fresh()->stock);
        $this->assertDatabaseHas('stock_movements', ['product_id' => $product->id, 'type' => 'in', 'quantity' => 5]);
    }

    public function test_staff_cannot_delete_a_product(): void
    {
        $user = User::create(['name' => 'Staff Test', 'email' => 'staff-delete@test.local', 'password' => 'Password123!', 'role' => 'staff']);
        $category = Category::create(['name' => 'Peralatan']);
        $product = Product::create(['sku' => 'DEL-001', 'name' => 'Barang Test', 'category_id' => $category->id, 'purchase_price' => 100, 'selling_price' => 120, 'stock' => 0, 'min_stock' => 1, 'unit' => 'pcs']);

        $this->actingAs($user)->delete('/products/'.$product->id)->assertForbidden();
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
