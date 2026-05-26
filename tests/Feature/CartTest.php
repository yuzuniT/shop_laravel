<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class CartTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // ヘルパー：カートセッションを組み立てる
    // =========================================================
    
    private function makeCartSession(Product $product, int $quantity): array
    {
        return [
            'cart' => [
                (string)$product->id=> [
                    'product_id'=>(string)$product->id,
                    'product_name'=>$product->product_name,
                    'price'=>$product->base_price,
                    'quantity'=>$quantity,
                    'image_url'=>$product->image_url,
                    'stock_quantity'=>$product->stock_quantity,
                ],
            ],
        ];
    }

    // =========================================================
    // 商品をカートへ追加
    // =========================================================

    /**
     * TC_CART_01
     * 在庫範囲内の数量（1）でカートに商品を追加できること
     */
    public function test_在庫範囲内の数量1でカートに商品を追加できること(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $response = $this->post('/cart/add', [
            'product_id' => (string)$product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect(route('cart.index'));
        $cart = session('cart');
        $this->assertEquals(1, $cart[(string)$product->id]['quantity']);
    }

    /**
     * TC_CART_02
     * 在庫数と同じ数量（上限値）でカートに追加できること（境界値テスト）
     */
    public function test_在庫数と同じ数量でカートに追加できること(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $response = $this->post('/cart/add', [
            'product_id' => (string)$product->id,
            'quantity' => 5,
        ]);

        $response->assertRedirect(route('cart.index'));
        $cart = session('cart');
        $this->assertEquals(5, $cart[(string)$product->id]['quantity']);
    }

    /**
     * TC_CART_03
     * 数量0を指定した場合、バリデーションエラーが返ること
     */
    public function test_数量0を指定した場合バリデーションエラーが返ること(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/add', [
            'product_id' => (string)$product->id,
            'quantity' => 0,
        ]);

        $response->assertRedirect()
                 ->assertSessionHasErrors(['quantity']);
    }

    /**
     * TC_CART_04
     * 数量に小数（1.2）を指定した場合、バリデーションエラーが返ること
     */
    public function test_数量に小数を指定した場合バリデーションエラーが返ること(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/add', [
            'product_id' => (string)$product->id,
            'quantity' => 1.2,
        ]);

        $response->assertRedirect()
                 ->assertSessionHasErrors(['quantity']);
    }

    /**
     * TC_CART_05
     * 在庫数を超える数量を指定した場合、バリデーションエラーが返ること（境界値テスト）
     */
    public function test_在庫数を超える数量を指定した場合バリデーションエラーが返ること(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $response = $this->post('/cart/add', [
            'product_id' => (string)$product->id,
            'quantity' => 6,
        ]);

        $response->assertRedirect()
                 ->assertSessionHasErrors(['quantity']);
    }

    /**
     * TC_CART_06
     * カートに既存数量がある状態で合算が在庫超過となる場合、バリデーションエラーが返ること
     * AddCartRequestの合算在庫チェックの確認
     */
    public function test_既存数量との合算が在庫超過となる場合バリデーションエラーが返ること(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        // 既にカートに数量3が入っている状態を作る
        $this->withSession($this->makeCartSession($product, 3))
             ->post('/cart/add', [
                'product_id' => (string)$product->id,
                'quantity' => 3, // 3 + 3 = 6 > 在庫5
             ])
             ->assertRedirect()
             ->assertSessionHasErrors(['quantity']);
    }

    /**
     * TC_CART_07
     * 数量に文字列を指定した場合、バリデーションエラーが返ること
     */
    public function test_数量に文字列を指定した場合バリデーションエラーが返ること(): void
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/add', [
            'product_id' => (string)$product->id,
            'quantity' => 'hoge',
        ]);

        $response->assertRedirect()
                 ->assertSessionHasErrors(['quantity']);
    }

    /**
     * TC_CART_08
     * 存在しない商品IDを指定した場合、バリデーションエラーが返ること
     */
    public function test_存在しない商品IDを指定した場合バリデーションエラーが返ること(): void
    {
        $response = $this->post('/cart/add', [
            'product_id' => 'nonexistent_id',
            'quantity' => 1,
        ]);

        $response->assertRedirect()
                 ->assertSessionHasErrors(['product_id']);
    }

    // =========================================================
    // カート内商品の閲覧
    // =========================================================

    /**
     * TC_CART_09
     * カートに追加した1商品の情報が正しく表示されること
     */
    public function test_カートに追加した1商品の情報が正しく表示されること(): void
    {
        $product = Product::factory()->create([
            'product_name' => 'テスト商品',
            'base_price' => 1000,
        ]);

        // セッションに商品が存在することを確認
        $session = $this->makeCartSession($product, 1);
        $this->assertArrayHasKey((string)$product->id, $session['cart']);

        // ビューに商品情報が表示されていることを確認
        $this->withSession($session)
            ->get('/cart')
            ->assertStatus(200)
            ->assertSee('テスト商品')
            ->assertSee('1,000');  
    }

    /**
     * TC_CART_10
     * カートに同じ商品を追加した場合、数量が合算されること
     */
    public function test_カートに同じ商品を追加した場合数量が合算されること(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        // 既にカートに数量2が入っている状態を作る
        $this->withSession($this->makeCartSession($product, 2))
            ->post('/cart/add', [
                'product_id' => (string)$product->id,
                'quantity' => 1,
            ]);
            // セッションの数量が合算されていることを確認
            $cart = session('cart');
            $this->assertEquals(3, $cart[(string)$product->id]['quantity']);
    }

    /**
     * TC_CART_11
     * カートに別の商品を追加した場合、両商品が表示されること
     */
    public function test_カートに別の商品を追加した場合両商品が表示されること(): void
    {
        $productA = Product::factory()->create([
            'product_name' => '商品A',
            'stock_quantity' => 10,
        ]);
        $productB = Product::factory()->create([
            'product_name' => '商品B',
            'stock_quantity' => 10,
        ]);

        $this->withSession($this->makeCartSession($productA,1))
            ->post('/cart/add', [
                'product_id' => (string)$productB->id,
                'quantity' => 1,
            ]);

        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertSee('商品A');
        $response->assertSee('商品B');
    }

    /**
     * TC_CART_12
     * カートが空の場合、/cart/emptyへリダイレクトされること
     */
    public function test_カートが空の場合cart_emptyへリダイレクトされること(): void
    {
        $response = $this->withSession(['cart' => []])->get('/cart');

        $response->assertRedirect(route('cart.empty'));
    }

    /**
     * TC_CART_13
     * 合計金額（小計＋送料）が正しく計算されること
     */
    public function test_合計金額が正しく計算されること(): void
    {
        $product = Product::factory()->create(['base_price' => 1000, 'stock_quantity' => 10]);

        // セッションの数量と価格が正しいことを確認
        $session = $this->makeCartSession($product, 2);
        $this->assertEquals(1000, $session['cart'][(string)$product->id]['price']);
        $this->assertEquals(2, $session['cart'][(string)$product->id]['quantity']);

        // ビューで小計（2000円）が正しく表示されていることを確認
        $this->withSession($session)
            ->get('/cart')
            ->assertStatus(200)
            ->assertSee('2,000');

    }

    // =========================================================
    // カート内商品の数量更新
    // =========================================================

    /**
     * TC_CART_14
     * 数量を正しく更新できること
     */
    public function test_数量を正しく更新できること(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $response = $this->withSession($this->makeCartSession($product, 2))
            ->post('/cart/update', [
                'product_id' => (string)$product->id,
                'quantity' => 5,
            ]);
        $response->assertRedirect(route('cart.index'));

        // セッションの数量が5に更新されていることを確認
        $cart = session('cart');
        $this->assertEquals(5, $cart[(string)$product->id]['quantity']);
    }

    /**
     * TC_CART_15
     * 在庫数を超える数量に更新しようとした場合、バリデーションエラーが返ること（境界値テスト）
     */
    public function test_在庫数を超える数量に更新しようとした場合バリデーションエラーが返ること(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $response = $this->withSession($this->makeCartSession($product, 2))
            ->post('/cart/update', [
                'product_id' => (string)$product->id,
                'quantity' => 6,
            ]);

            $response->assertRedirect()
                     ->assertSessionHasErrors(['quantity']);
    }

    // =========================================================
    // カート内商品の削除
    // =========================================================

    /**
     * TC_CART_16
     * カート内の唯一の商品を削除した場合、カートが空になること
     */
    public function test_カート内の唯一の商品を削除した場合カートが空になること(): void
    {
        $product = Product::factory()->create();

        $response = $this->withSession($this->makeCartSession($product, 1))
            ->post('/cart/delete', [
                'product_id' => (string)$product->id,
            ]);

        $response->assertRedirect(route('cart.index'));
        $cart = session('cart');
        $this->assertEmpty($cart);
    }

    /**
     * TC_CART_17
     * 2商品のうち1つを削除した場合、残り1商品が正しく表示されること
     */
    public function test_2商品のうち1つを削除した場合残り1商品が正しく表示されること(): void
    {
        $productA = Product::factory()->create(['product_name' => '商品A']);
        $productB = Product::factory()->create(['product_name' => '商品B']);

        $session = [
            'cart' => [
                (string)$productA->id => [
                    'product_id'     => (string)$productA->id,
                    'product_name'   => $productA->product_name,
                    'price'          => $productA->base_price,
                    'quantity'       => 1,
                    'image_url'      => $productA->image_url,
                    'stock_quantity' => $productA->stock_quantity,
                ],
                (string)$productB->id => [
                    'product_id'     => (string)$productB->id,
                    'product_name'   => $productB->product_name,
                    'price'          => $productB->base_price,
                    'quantity'       => 1,
                    'image_url'      => $productB->image_url,
                    'stock_quantity' => $productB->stock_quantity,
                ],
            ],
        ];

        $this->withSession($session)
            ->post('/cart/delete', [
                'product_id' => (string)$productA->id,
            ]);

        $cart = session('cart');
        $this->assertArrayNotHasKey((string)$productA->id, $cart);
        $this->assertArrayHasKey((string)$productB->id, $cart);
    }

    /**
     * TC_CART_18
     * セッションに存在しない商品IDを指定した場合、正常にリダイレクトされること
     */
    public function test_セッションに存在しない商品IDを指定した場合正常にリダイレクトされること(): void
    {
        $this->withSession(['cart' => []])
            ->post('/cart/delete', [
                'product_id' => 'nonexistent_id',
            ])
            ->assertRedirect(route('cart.index'));
    }

    // =========================================================
    // 購入手続きへの遷移
    // =========================================================

    /**
     * TC_CART_19
     * カートに商品が入っている状態で購入手続きページへ遷移できること
     */
    public function test_カートに商品が入っている状態で購入手続きページへ遷移できること(): void
    {
        $product = Product::factory()->create();

        $this->withSession($this->makeCartSession($product, 1))
            ->get('/checkout/delivery_form')
            ->assertStatus(200);
    }

    /**
     * TC_CART_20
     * カートが空の状態で購入手続きページへアクセスした場合、/cart/emptyへリダイレクトされること
     */
    public function test_カートが空の状態で購入手続きページへアクセスした場合cart_emptyへリダイレクトされること(): void
    {
        $this->withSession(['cart' => []])
            ->get('/checkout/delivery_form')
            ->assertRedirect(route('cart.empty'));
    }
}
