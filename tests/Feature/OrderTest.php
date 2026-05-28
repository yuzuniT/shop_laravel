<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // ヘルパー
    // =========================================================

    // 有効なお届け先情報
    private function validDeliveryData(): array
    {
        return [
            'family_name' => '山田',
            'last_name' => '太郎',
            'postal_code' => '1234567',
            'address' => '東京都新宿区南新宿1-2-3',
            'email' => 'test@example.com',
            'phone_number' => null,
            'payment_method' => 'credit_card',
        ];
    }

    // カートセッションを組み立てる
    private function makeCartSession(Product $product, int $quantity): array
    {
        return [
            'cart' => [
                (string) $product->id => [
                    'product_id'     => (string) $product->id,
                    'product_name'   => $product->product_name,
                    'price'          => $product->base_price,
                    'quantity'       => $quantity,
                    'image_url'      => $product->image_url,
                    'stock_quantity' => $product->stock_quantity,
                ],
            ],
        ];
    }

    // cart + checkout_dataの両方をセッションにセットする（store()テスト用）
    private function makeStoreSession(Product $product, int $quantity): array
    {
        $cartSession = $this->makeCartSession($product, $quantity);
        $shipping_fee = 610;
        $subtotal = $product->base_price * $quantity;
        $checkoutData = array_merge($this->validDeliveryData(), [
            'subtotal' => $subtotal,
            'shipping_fee' => $shipping_fee,
            'total_amount' => $subtotal + $shipping_fee,
        ]);
        return array_merge($cartSession, ['checkout_data' => $checkoutData]);
    }

    // =========================================================
    // お届け先情報の入力
    // =========================================================

    /**
     * TC_ORDER_01
     * 必須項目をすべて入力した場合、注文確認ページへ遷移できること
     */
    public function test_必須項目をすべて入力した場合、注文確認ページへ遷移できること(): void
    {
        $product = Product::factory()->create();

        $response = $this->withSession($this->makeCartSession($product, 1))
            ->post('/checkout/confirm', $this->validDeliveryData());

        // 200 OKで注文確認ページが表示されるか確認
        $response->assertStatus(200);

        // セッションにcheckout_dataが保存されているか確認
        $response->assertSessionHas('checkout_data');
    }

    /**
     * TC_ORDER_02
     * 任意項目（電話番号）を空欄のまま送信した場合、注文確認ページへ遷移できること
     */
    public function test_任意項目（電話番号）を空欄のまま送信した場合、注文確認ページへ遷移できること(): void
    {
        $product = Product::factory()->create();

        $data = $this->validDeliveryData();
        $data['phone_number'] = null;

        $response = $this->withSession($this->makeCartSession($product, 1))
            ->post('/checkout/confirm', $data);

        // 200 OKで注文確認ページが表示されるか確認
        $response->assertStatus(200);

        // セッションにcheckout_dataが保存されているか・電話番号がnullであるか確認
        $checkoutData = session('checkout_data');
        $this->assertNotNull($checkoutData);
        $this->assertNull($checkoutData['phone_number']);
    }

    /**
     * TC_ORDER_03
     * 必須項目がすべて空の場合、全フィールドのバリデーションエラーが返ること
     */
    public function test_必須項目がすべて空の場合、全フィールドのバリデーションエラーが返ること(): void
    {
        $product = Product::factory()->create();

        $response = $this->withSession($this->makeCartSession($product, 1))
            ->post('/checkout/confirm', []);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'family_name', 'last_name', 'postal_code',
            'address', 'email', 'payment_method',
        ]);
    }

    /**
     * TC_ORDER_04
     * 各必須項目を個別に空にした場合、そのフィールドのバリデーションエラーが返ること
     */
    public function 各必須項目を個別に空にした場合バリデーションエラーが返ること(): void
    {
        $product = Product::factory()->create();

        $requiredFields = [
            'family_name', 'last_name', 'postal_code',
            'address', 'email', 'payment_method',
        ];

        foreach ($requiredFields as $field) {
            $data = $this->validDeliveryData();
            $data[$field] = '';

            $response = $this->withSession($this->makeCartSession($product, 1))
                ->post('/checkout/confirm', $data);

            $response->assertRedirect();
            $response->assertSessionHasErrors([$field]);
        }
    }

    /**
     * TC_ORDER_05
     * メールアドレスの形式が不正な場合、バリデーションエラーが返ること
     */
    public function test_メールアドレスの形式が不正な場合、バリデーションエラーが返ること(): void
    {
        $product = Product::factory()->create();

        $data = $this->validDeliveryData();
        $data['email'] = 'hogehoge';

        $response = $this->withSession($this->makeCartSession($product, 1))
            ->post('/checkout/confirm', $data);
        
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    // =========================================================
    // 注文確認画面の表示
    // =========================================================

    /**
     * TC_ORDER_06
     * 注文確認ページでお届け先情報とカート内容が正しく表示されること
     */
    public function test_注文確認ページでお届け先情報とカート内容が正しく表示されること(): void
    {
        $product = Product::factory()->create([
            'product_name' => 'テスト商品',
        ]);

        $response = $this->withSession($this->makeCartSession($product, 1))
            ->post('/checkout/confirm', $this->validDeliveryData());

        $response->assertStatus(200);

        // お届先情報が表示されているか確認
        $response->assertSee('山田');
        $response->assertSee('太郎');
        $response->assertSee('1234567');
        $response->assertSee('東京都新宿区南新宿1-2-3');

        // カート内容が表示されているか確認
        $response->assertSee('テスト商品');
    }

    // =========================================================
    // 注文の確定
    // =========================================================

    /**
     * TC_ORDER_07
     * checkout_dataがない状態で注文確定リクエストを送った場合、エラーが返ること
     */
    public function test_checkout_dataがない状態で注文確定リクエストを送った場合、エラーが返ること(): void
    {
        $product = Product::factory()->create();

        $response = $this->withSession($this->makeCartSession($product, 1))
            ->post('/checkout/store');

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', '注文情報が不足しています。' );
    }

    /**
     * TC_ORDER_08
     * 注文確定後、ordersテーブルに正しい注文情報が保存されること
     */
    public function test_注文確定後、ordersテーブルに正しい注文情報が保存されること(): void
    {
        Mail::fake();
        $product = Product::factory()->create(['stock_quantity' => 10, 'base_price' => 1000]);

        $this->withSession($this->makeStoreSession($product, 2))
            ->post('/checkout/store');

        $this->assertDatabaseHas('orders', [
            'family_name'    => '山田',
            'last_name'      => '太郎',
            'email'          => 'test@example.com',
            'payment_method' => 'credit_card',
            'order_status'   => 'pending',
            'total_amount'   => 2610,
            'shipping_fee'   => 610,
        ]);
    }

    /**
     * TC_ORDER_09
     * 注文確定後、order_itemsテーブルに正しい注文明細が保存されること
     */
    public function test_注文確定後、order_itemsテーブルに正しい注文明細が保存されること(): void
    {
        Mail::fake();
        $product = Product::factory()->create(['stock_quantity' => 10, 'base_price' => 1000]);

        $this->withSession($this->makeStoreSession($product, 2))
            ->post('/checkout/store');

        $order = Order::first();

        $this->assertDatabaseHas('order_items', [
            'order_id'     => $order->id,
            'product_id'   => (string) $product->id,
            'price'        => 1000,
            'quantity'     => 2,
            'ready_status' => 'pending',
        ]);
    }

    /**
     * TC_ORDER_10
     * 注文確定後、商品の在庫数が購入数量分だけ減少すること
     */
    public function test_注文確定後、商品の在庫数が購入数量分だけ減少すること(): void
    {
        Mail::fake();
        $product = Product::factory()->create(['stock_quantity' => 10, 'base_price' => 1000]);

        $this->withSession($this->makeStoreSession($product, 3))
            ->post('/checkout/store');

        $this->assertDatabaseHas('products', [
            'id' => (string) $product->id,
            'stock_quantity' => 7, // 10 - 3 = 7
        ]);
    }

    /**
     * TC_ORDER_11
     * 注文確定後、在庫が0になった商品がis_active=falseになること
     */
    public function test_注文確定後、在庫が0になった商品がis_active_falseになること(): void
    {
        Mail::fake();
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->withSession($this->makeStoreSession($product, 5))
            ->post('/checkout/store');

        $this->assertDatabaseHas('products', [
            'id' => (string) $product->id,
            'stock_quantity' => 0,
            'is_active' => false,
        ]);
    }

    /**
     * TC_ORDER_12
     * 注文確定後、セッションのcartとcheckout_dataがクリアされること
     */
    public function test_注文確定後、セッションのcartとcheckout_dataがクリアされること(): void
    {
        Mail::fake();
        $product = Product::factory()->create();

        $this->withSession($this->makeStoreSession($product, 1))
            ->post('/checkout/store');

        $this->assertNull(session('cart'));
        $this->assertNull(session('checkout_data'));
    }

    /**
     * TC_ORDER_13
     * 注文確定後、注文完了ページへリダイレクトされること
     */
    public function test_注文確定後、注文完了ページへリダイレクトされること(): void
    {
        Mail::fake();
        $product = Product::factory()->create();

        $response = $this->withSession($this->makeStoreSession($product, 1))
            ->post('/checkout/store');

        $response->assertRedirect(route('checkout.complete'))
            ->assertSessionHas('order_completed', true)
            ->assertSessionHas('order_id');
    }

    /**
     * TC_ORDER_14
     * カートが空の状態で注文確定リクエストを送った場合、エラーが返ること
     */
    public function test_カートが空の状態で注文確定リクエストを送った場合、エラーが返ること(): void
    {
        $response = $this->withSession(['cart' => []])
            ->post('/checkout/store');

        $response->assertRedirect(route('cart.empty'));
        $response->assertSessionHas('error', 'カートに商品が入っていません。');
    }

    /**2
     * TC_ORDER_15
     * 注文確定時に商品の在庫が不足していた場合、エラーが返ること
     */
    public function test_注文確定時に商品の在庫が不足していた場合、エラーが返ること(): void
    {
        // 在庫は1だが数量2でカートに入っている状態
        $product = Product::factory()->create(['stock_quantity' => 1]);

        $response = $this->withSession($this->makeStoreSession($product, 2))
            ->post('/checkout/store');

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', $product->product_name . 'の在庫が不足しています。');
    }

    // =========================================================
    // 注文完了画面の表示
    // =========================================================

    /**
     * TC_ORDER_16
     * 注文完了ページで正しい注文IDが表示されること
     */
    public function test_注文完了ページで正しい注文IDが表示されること(): void
    {
        $order = Order::factory()->create();
        
        $response = $this->withSession([
            'order_completed' => true,
            'order_id' => $order->id
            ])
            ->get('/checkout/complete');

        $response->assertStatus(200);
        $response->assertSee($order->id);
    }

    /**
     * TC_ORDER_17
     * order_completedフラグなしで完了ページへアクセスした場合、リダイレクトされること
     */
    public function test_order_completedフラグなしで完了ページへアクセスした場合リダイレクトされること(): void
    {
        $response =$this->get('/checkout/complete');
        $response->assertRedirect(route('products.index'));
    }

    // =========================================================
    // 注文履歴 (TC_ORDER_18~20) - 保留：未実装
    // =========================================================
}
