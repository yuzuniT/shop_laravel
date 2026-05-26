<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase as TestsTestCase;

class ProductTest extends TestsTestCase
{
    use RefreshDatabase;

    // =========================================================
    // 商品一覧の表示
    // =========================================================
    
    /**
     * TC_PROD_01
     * 未ログインで商品一覧が正しく表示されること
     */
    public function test_未ログインで商品一覧が正しく表示されること(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

     /**
     * TC_PROD_02
     * ログイン済みユーザーでも商品一覧が正しく表示されること
     */
    public function test_ログイン済みユーザーでも商品一覧が正しく表示されること(): void
    {
        $user = User::factory()->create();
        Product::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    // =========================================================
    // 商品一覧の検索
    // =========================================================

    /**
     * TC_PROD_03
     * キーワード検索で該当商品のみが表示されること
     */
    public function test_キーワード検索で該当商品のみが表示されること(): void
    {
        Product::factory()->create(['product_name' => 'テスト商品A']);
        Product::factory()->create(['product_name' => '別の商品B']);

        $response = $this->get('/?search=テスト');

        $response->assertStatus(200);
        $response->assertSee('テスト商品A');
        $response->assertDontSee('別の商品B');
    }

    /**
     * TC_PROD_04
     * 検索結果が0件の場合、適切なメッセージが表示されること
     */
    public function test_検索結果が0件の場合に適切なメッセージが表示されること(): void
    {
        Product::factory()->create();

        $response = $this->get('/?search=hogehoge');

        $response->assertStatus(200);
        $response->assertSee('「hogehoge」の検索結果：0件');

    }   

    /**
     * TC_PROD_05
     * 検索バーにスクリプトが入力された場合、XSSが防がれること
     */
    public function test_検索バーにスクリプトが入力された場合XSSが防がれること(): void
    {
        $response = $this->get('/?search=' . urlencode('<script>alert(1)</script>'));

        $response->assertStatus(200);

        $response->assertDontSee('<script>alert(1)</script>', false);


    }

    // =========================================================
    // 商品詳細ページの表示
    // =========================================================

    /**
     * TC_PROD_06
     * 商品詳細ページで正しい商品情報が表示されること
     */
    public function test_商品詳細ページで正しい商品情報が表示されること(): void
    {
        $product = Product::factory()->create([
            'product_name'=>'テスト商品',
            'description'=>'テスト説明文',
            'base_price'=>1000,
            'stock_quantity'=>5,
        ]);

        $response=$this->get("/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('テスト説明文');
        $response->assertSee('1,000');
        $response->assertSee('5');
    }

    /**
     * TC_PROD_07
     * 存在しない商品IDが指定された場合、404エラーが返ること
     */
    public function test_存在しない商品IDが指定された場合404エラーが返ること(): void
    {
        $response = $this->get('/products/nonexistent-id-99999');

        $response->assertStatus(404);
    }

    // =========================================================
    // 在庫・公開状態の管理
    // =========================================================

    /**
     * TC_PROD_08
     * 在庫0（stock_quantity=0）の商品が商品一覧に表示されないこと
     */
    public function test_在庫0の商品が商品一覧に表示されないこと(): void
    {
        Product::factory()->create([
            'product_name'=>'在庫あり商品',
        ]);

        Product::factory()->create([
            'product_name'=>'在庫なし商品',
            'stock_quantity'=>0,
        ]);

        $response=$this->get('/');

        $response->assertStatus(200);
        $response->assertSee('在庫あり商品');
        $response->assertDontSee('在庫なし商品');
    }

    /**
     * TC_PROD_09
     * 非公開（is_active=false）の商品が商品一覧に表示されないこと
     */
    public function test_非公開の商品が商品一覧に表示されないこと(): void
    {
        Product::factory()->create([
            'product_name'=>'公開商品',
        ]);
        Product::factory()->create([
            'product_name'=>'非公開商品',
            'is_active'=>false,
        ]);

        $response=$this->get('/');

        $response->assertStatus(200);
        $response->assertSee('公開商品');
        $response->assertDontSee('非公開商品');
    }

    /**
     * TC_PROD_10
     * 在庫切れ商品の詳細ページで「在庫切れ」が表示されること
     */
    // public function test_在庫切れ商品の詳細ページで在庫切れが表示されること(): void
    // {
    //     $product = Product::factory()->create(['stock_quantity' => 0]);
    //     $response = $this->get("/products/{$product->id}");

    //     $response->assertStatus(200);

    //     $response->assertSee('在庫切れ');
    // }

    // =========================================================
    // カテゴリとの関連
    // =========================================================

    /**
     * TC_PROD_12
     * カテゴリ未設定の商品が商品一覧に正常に表示されること
     */
    public function test_カテゴリ未設定の商品が商品一覧に正常に表示されること(): void
    {
        Product::factory()->create(['product_name'=>'カテゴリなし商品']);

        $response=$this->get('/');

        $response->assertStatus(200);
        $response->assertSee('カテゴリなし商品');
    }

    /**
     * TC_PROD_13
     * カテゴリ未設定商品の詳細ページが正常に表示されること
     */
    public function test_カテゴリ未設定商品の詳細ページが正常に表示されること(): void
    {
        $product=Product::factory()->create();
        $response=$this->get("/products/{$product->id}");

        $response->assertStatus(200);
    }
}