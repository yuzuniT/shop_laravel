<?php

namespace Tests\Feature;

use App\Livewire\AdminOrders;
use App\Livewire\AdminProducts;
use App\Livewire\AdminUsers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // ヘルパー
    // =========================================================

    private function createAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function createUser(): User
    {
        return User::factory()->create(['role' => 'user']);
    }
    
    // =========================================================
    // 管理ページへのアクセス制御
    // =========================================================

    /**
     * TC_ADMIN_01
     * 管理者ユーザーが管理ダッシュボードへアクセスできること
     */
    public function test_管理者ユーザーが管理ダッシュボードへアクセスできること(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertStatus(200);
    }

    /**
     * TC_ADMIN_02
     * 一般ユーザーが管理ページへアクセスした場合、403エラーが返ること
     */
    public function test_一般ユーザーが管理ページへアクセスした場合403エラーが返ること(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(403);
    }

    /**
     * TC_ADMIN_03
     * 未ログインユーザーが管理ページへアクセスした場合、ログインページへリダイレクトされること
     */
    public function test_未ログインユーザーが管理ページへアクセスした場合ログインページへリダイレクトされること(): void
    {
        $this->get('/admin')
            ->assertRedirect(route('login'));
    }    

    // =========================================================
    // 商品管理
    // =========================================================

    /**
     * TC_ADMIN_04
     * 管理者が新しい商品を追加できること
     */
    public function test_管理者が新しい商品を追加できること(): void
    {
        $admin = $this->createAdmin();

        Livewire::actingAs($admin)
            ->test(AdminProducts::class)
            ->set('form.id', 'TEST001')
            ->set('form.product_name', 'テスト商品')
            ->set('form.description', 'テスト商品の説明')
            ->set('form.base_price', 1000)
            ->set('form.stock_quantity', 10)
            ->set('form.is_active', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'id' => 'TEST001',
            'product_name' => 'テスト商品',
            'description' => 'テスト商品の説明',
            'base_price' => 1000,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);
    }

    /**
     * TC_ADMIN_05
     * 商品名が空の場合、バリデーションエラーが返ること
     */
    public function test_商品名が空の場合バリデーションエラーが返ること(): void
    {
        $admin = $this->createAdmin();

        Livewire::actingAs($admin)
            ->test(AdminProducts::class)
            ->set('form.id', 'TEST001')
            ->set('form.product_name', '')
            ->set('form.description', 'テスト商品の説明')
            ->set('form.base_price', 1000)
            ->set('form.stock_quantity', 10)
            ->set('form.is_active', true)
            ->call('save')
            ->assertHasErrors(['form.product_name']);
    }    

    /**
     * TC_ADMIN_06
     * 価格に負の値を指定した場合、バリデーションエラーが返ること
     */
    public function test_価格に負の値を指定した場合バリデーションエラーが返ること(): void
    {
        $admin = $this->createAdmin();

        Livewire::actingAs($admin)
            ->test(AdminProducts::class)
            ->set('form.id', 'TEST001')
            ->set('form.product_name', 'テスト商品')
            ->set('form.description', 'テスト商品の説明')
            ->set('form.base_price', -1)
            ->set('form.stock_quantity', 10)
            ->set('form.is_active', true)
            ->call('save')
            ->assertHasErrors(['form.base_price']);
    }

    /**
     * TC_ADMIN_07
     * 管理者が既存商品を編集できること
     */
    public function test_管理者が既存商品を編集できること(): void
    {
        $admin = $this->createAdmin();
        $product = Product::factory()->create([
            'product_name' => '元の商品名',
            'description' => '元の商品説明',
            'base_price' => 500,
            'stock_quantity' => 5,
            'is_active' => true,
            ]);

        Livewire::actingAs($admin)
            ->test(AdminProducts::class)
            ->call('openForm', (string) $product->id) // 編集モードでフォームを開く
            ->set('form.product_name', '更新後の商品名')
            ->set('form.description', '更新後の商品説明')
            ->set('form.base_price', 1000)
            ->set('form.stock_quantity', 10)
            ->set('form.is_active', false)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'id' => (string) $product->id,
            'product_name' => '更新後の商品名',
            'description' => '更新後の商品説明',
            'base_price' => 1000,
            'stock_quantity' => 10,
            'is_active' => false,
        ]);
    }        
    
    /**
     * TC_ADMIN_08
     * 管理者が商品を削除できること
     */
    public function test_管理者が商品を削除できること(): void
    {
        $admin = $this->createAdmin();
        $product = Product::factory()->create();

        Livewire::actingAs($admin)
            ->test(AdminProducts::class)
            ->call('delete', (string) $product->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('products', [
            'id' => (string) $product->id,
        ]);
    }

    // =========================================================
    // 注文管理
    // =========================================================

    /**
     * TC_ADMIN_09
     * 管理者が注文一覧ページを表示できること
     */
    public function test_管理者が注文一覧ページを表示できること(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get('/admin/orders')
            ->assertStatus(200);
    }
    
    /**
     * TC_ADMIN_10
     * 管理者が注文ステータスをpendingからshippedに変更できること
     */
    public function test_管理者が注文ステータスをpendingからshippedに変更できること(): void
    {
        $admin = $this->createAdmin();
        $order = Order::factory()->create(['order_status' => 'pending']);
        
        Livewire::actingAs($admin)
            ->test(AdminOrders::class)
            ->call('updateOrderStatus', $order->id, 'shipped')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'shipped',
        ]);
    }

    /**
     * TC_ADMIN_11
     * 無効なステータス値を指定した場合、バリデーションエラーが返ること
     */
    public function test_無効なステータス値を指定した場合バリデーションエラーが返ること(): void
    {
        $admin = $this->createAdmin();
        $order = Order::factory()->create(['order_status' => 'pending']);

        Livewire::actingAs($admin)
            ->test(AdminOrders::class)
            ->call('updateOrderStatus', $order->id, 'hogehoge')
            ->assertHasErrors('order_status');

        // ステータスが変更されていないことを確認
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'pending',
        ]);
    }

    // =========================================================
    // ユーザー管理
    // =========================================================

    /**
     * TC_ADMIN_12
     * 管理者がユーザー一覧ページを表示できること
     */
    public function test_管理者がユーザー一覧ページを表示できること(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertStatus(200);
    }    

    /**
     * TC_ADMIN_13
     * 管理者が一般ユーザーのロールをadminに変更できること
     */
    public function test_管理者が一般ユーザーのロールをadminに変更できること(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createUser();

        Livewire::actingAs($admin)
            ->test(AdminUsers::class)
            ->call('openForm', $user->id) // 編集モードでフォームを開く
            ->set('form.role', 'admin')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'admin',
        ]);
    }

    // =========================================================
    // お問い合わせ管理（TC_ADMIN_14・15） - 保留：未実装
    // =========================================================
}
