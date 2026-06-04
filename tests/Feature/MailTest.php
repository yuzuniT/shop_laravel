<?php

namespace Tests\Feature;

use App\Mail\ContactConfirmation;
use App\Mail\OrderConfirmation;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // ヘルパー（OrderTest・ContactTestと同じ構造）
    // =========================================================

    private function validDeliveryData(): array
    {
        return [
            'family_name'    => '山田',
            'last_name'      => '太郎',
            'postal_code'    => '1234567',
            'address'        => '東京都新宿区南新宿1-2-3',
            'email'          => 'order@example.com',
            'phone_number'   => null,
            'payment_method' => 'credit_card',
        ];
    }

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

    private function makeStoreSession(Product $product, int $quantity): array
    {
        $cartSession  = $this->makeCartSession($product, $quantity);
        $shipping_fee = 610;
        $subtotal     = $product->base_price * $quantity;
        $checkoutData = array_merge($this->validDeliveryData(), [
            'subtotal'     => $subtotal,
            'shipping_fee' => $shipping_fee,
            'total_amount' => $subtotal + $shipping_fee,
        ]);
        return array_merge($cartSession, ['checkout_data' => $checkoutData]);
    }

    private function validContactData(): array
    {
        return [
            'family_name'   => '山田',
            'last_name'     => '太郎',
            'email'         => 'contact@example.com',
            'phone_number'  => null,
            'contact_type'  => 'order',
            'contact_title' => 'テスト件名',
            'message'       => 'テストメッセージ内容です。',
        ];
    }

    // =========================================================
    // 注文確認メール
    // =========================================================

    /**
     * TC_MAIL_01
     * 注文確定時にユーザー宛の注文確認メールがキューに積まれること
     */
    public function test_注文確定時に注文確認メールがキューに積まれること(): void
    {
        Mail::fake();

        $product = Product::factory()->create();

        $sessionData = $this->makeStoreSession($product, 1);

        $this->withSession($sessionData)
            ->post('/checkout/store');

        // OrderConfirmationがorder.emailへキューに積まれているか確認
        Mail::assertQueued(OrderConfirmation::class, function ($mail){
            return $mail->hasTo('order@example.com');
        });
    }

    /**
     * TC_MAIL_03
     * 注文確認メールに正しい注文番号・商品情報が含まれること
     */
    public function test_注文確認メールに正しい注文番号と商品情報が含まれること(): void
    {
        Mail::fake();
        $product = Product::factory()->create([
            'product_name' => 'テスト商品',
            'stock_quantity' => 10,
            'base_price' => 1000,
        ]);

        $sessionData = $this->makeStoreSession($product, 1);

        $this->withSession($sessionData)
            ->post('/checkout/store');

        Mail::assertQueued(OrderConfirmation::class, function ($mail) use ($product) {
            // 注文情報が含まれることを確認する
            $order = $mail->order;
            if (!$order) return false;

            // 商品明細が含まれていることを確認する
            $items = $order->items;
            if ($items->isEmpty()) return false;

            // 正しい商品IDか確認する
            return $items->first()->product_id === (string) $product->id;

        });
    }

    // =========================================================
    // お問い合わせ受付メール
    // =========================================================

    /**
     * TC_MAIL_07
     * お問い合わせ送信時にユーザー宛の受付メールがキューに積まれること
     */
    public function test_お問い合わせ送信時にユーザー宛の受付メールがキューに積まれること(): void
    {
        Mail::fake();

        $ContactData = $this->validContactData();
        $this->withSession(['contact_data' => $ContactData])
            ->post('/contact/store');

        // ContactConfirmationがcontact.emailへキューに積まれているか確認
        Mail::assertQueued(ContactConfirmation::class, function ($mail) {
            return $mail->hasTo('contact@example.com');
        });
    }

    // =========================================================
    // 保留テスト
    // TC_MAIL_02: 管理者宛注文確認メール    → 未実装
    // TC_MAIL_05: 発送通知メール            → 未実装（ShippingNotificationMailなし）
    // TC_MAIL_08: 管理者宛お問い合わせメール → 未実装
    // =========================================================
}