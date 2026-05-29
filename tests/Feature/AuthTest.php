<?php

namespace Tests\Feature;

use App\Livewire\LoginComponent;
use App\Livewire\RegisterComponent;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // ヘルパー
    // =========================================================
    private function validRegisterData(): array
    {
        return [
            'family_name'           => '山田',
            'last_name'             => '太郎',
            'email'                 => 'test@example.com',
            'password'              => 'pass1234',
            'password_confirmation' => 'pass1234',
        ];
    }

    // =========================================================
    // 新規会員登録
    // =========================================================

    /**
     * TC_AUTH_01
     * 必須項目をすべて正しく入力した場合、ユーザー登録が成功すること
     */
    public function test_新規会員登録：必須項目をすべて正しく入力した場合、ユーザー登録が成功すること(): void
    {
        $data = $this->validRegisterData();

        Livewire::test(RegisterComponent::class)
            ->set($data)
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect(route('verification.notice'));

        // usersテーブルにレコードが保存されていることを確認
        $user = User::where('email', $data['email'])->first();
        $this->assertNotNull($user);

        // パスワードがハッシュ化されて保存されていることを確認
        $this->assertTrue(Hash::check($data['password'], $user->password));
    }


    /**
     * TC_AUTH_02
     * 必須フィールドがすべて空の場合、バリデーションエラーが返ること
     */
    public function test_新規会員登録：必須フィールドがすべて空の場合、バリデーションエラーが返ること(): void
    {
        Livewire::test(RegisterComponent::class)
            ->call('register')
            ->assertHasErrors([
                'family_name'=>'required',
                'last_name'=>'required',
                'email'=>'required',
                'password'=>'required',
            ]);
    }

    /**
     * TC_AUTH_03
     * メールアドレスの形式が不正な場合、バリデーションエラーが返ること
     */
    public function test_新規会員登録：メールアドレスの形式が不正な場合、バリデーションエラーが返ること(): void
    {
        $data = $this->validRegisterData();

        Livewire::test(RegisterComponent::class)
            ->set($data)
            ->set('email', 'hogehoge')
            ->call('register')
            ->assertHasErrors(['email'=>'email']);
    }

    /**
     * TC_AUTH_04
     * 登録済みのメールアドレスを指定した場合、バリデーションエラーが返ること
     */
    public function test_新規会員登録：登録済みのメールアドレスを指定した場合、バリデーションエラーが返ること(): void
    {
        $data = $this->validRegisterData();

        User::factory()->create([
            'email' => $data['email']
        ]);

        Livewire::test(RegisterComponent::class)
            ->set($data)
            ->call('register')
            ->assertHasErrors(['email'=>'unique']);
    }

    /**
     * TC_AUTH_05
     * パスワードと確認用パスワードが不一致の場合、バリデーションエラーが返ること
     */
    public function test_新規会員登録：パスワードと確認用パスワードが不一致の場合、バリデーションエラーが返ること(): void
    {
        $data = $this->validRegisterData();

        Livewire::test(RegisterComponent::class)
            ->set($data)
            ->set('password_confirmation', 'password12345')
            ->call('register')
            ->assertHasErrors(['password'=>'confirmed']);
    }

    /**
     * TC_AUTH_06
     * パスワードが文字数不足（7文字）の場合、バリデーションエラーが返ること（境界値テスト）
     */
    public function test_新規会員登録：パスワードが文字数不足（7文字）の場合、バリデーションエラーが返ること(): void
    {
        $data = $this->validRegisterData();

        Livewire::test(RegisterComponent::class)
            ->set($data)
            ->set('password', '1234567')
            ->set('password_confirmation', '1234567')
            ->call('register')
            ->assertHasErrors(['password'=>'min']);
    }

    /**
     * TC_AUTH_07
     * パスワードが最低文字数（8文字）の場合、登録が成功すること（境界値テスト）
     */
    public function test_新規会員登録：パスワードが最低文字数（8文字）の場合、登録が成功すること(): void
    {
        $data = $this->validRegisterData();

        Livewire::test(RegisterComponent::class)
            ->set($data)
            ->set('password', '12345678')
            ->set('password_confirmation', '12345678')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect(route('verification.notice'));
    }

    // =========================================================
    // ログイン
    // =========================================================

    /**
     * TC_AUTH_08
     * 正しいメールアドレスとパスワードでログインが成功すること
     */
    public function test_ログイン：正しいメールアドレスとパスワードでログインが成功すること(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('pass1234'),
        ]);

        Livewire::test(LoginComponent::class)
            ->set('email', 'test@example.com')
            ->set('password', 'pass1234')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('products.index'));
        
        $this->assertAuthenticated();
    }

    /**
     * TC_AUTH_09
     * 必須フィールドがすべて空の場合、バリデーションエラーが返ること
     */
    public function test_ログイン：必須フィールドがすべて空の場合、バリデーションエラーが返ること(): void
    {
        Livewire::test(LoginComponent::class)
            ->call('login')
            ->assertHasErrors([
                'email'=>'required',
                'password'=>'required',
            ]);
    }

    /**
     * TC_AUTH_10
     * 未登録のメールアドレスを指定した場合、認証エラーが返ること
     */
    public function test_ログイン：未登録のメールアドレスを指定した場合、認証エラーが返ること(): void
    {
        Livewire::test(LoginComponent::class)
            ->set('email', 'hogehoge@example.com')
            ->set('password', 'pass1234')
            ->call('login')
            ->assertHasErrors(['login'=>'メールアドレスまたはパスワードが正しくありません。']); // LoginComponentがaddError('login', ...)で追加するエラー
    }

    /**
     * TC_AUTH_11
     * 誤ったパスワードを入力した場合、認証エラーが返ること
     */
    public function test_ログイン：誤ったパスワードを入力した場合、認証エラーが返ること(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('pass1234'),
        ]);

        Livewire::test(LoginComponent::class)
            ->set('email', 'test@example.com')
            ->set('password', 'hogehoge')
            ->call('login')
            ->assertHasErrors(['login'=>'メールアドレスまたはパスワードが正しくありません。']);
    }    

    /**
     * TC_AUTH_12
     * メールアドレスの形式が不正な場合、バリデーションエラーが返ること
     */
    public function test_ログイン：メールアドレスの形式が不正な場合、バリデーションエラーが返ること(): void
    {
        Livewire::test(LoginComponent::class)
            ->set('email', 'hogehoge')
            ->set('password', 'pass1234')
            ->call('login')
            ->assertHasErrors(['email'=>'email']);
    }
    
    // =========================================================
    // ログアウト
    // =========================================================

    /**
     * TC_AUTH_13
     * ログイン済みユーザーがログアウトに成功すること
     */
    public function test_ログアウト：ログイン済みユーザーがログアウトに成功すること(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        // トップページへリダイレクトされていることを確認
        $response->assertRedirect(route('products.index'));

        // ゲスト状態になっていることを確認
        $this->assertGuest();
    }    

    /**
     * TC_AUTH_14
     * 未ログインユーザーがログアウトしようとした場合、リダイレクトされること
     */
    public function test_ログアウト：未ログインユーザーがログアウトしようとした場合、リダイレクトされること(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('products.index'));
    }

    // =========================================================
    // パスワードリセット
    // =========================================================

    /**
     * TC_AUTH_15
     * 登録済みメールアドレスを指定した場合、リセット通知が送信されること
     */
    public function test_パスワードリセット：登録済みメールアドレスを指定した場合、リセット通知が送信されること(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $this->post('/forgot-password', ['email' => 'test@example.com']);

        // ResetPassword通知がユーザーに送信されていることを確認
        Notification::assertSentTo($user,ResetPassword::class);
    }

    /**
     * TC_AUTH_16
     * 未登録メールアドレスを指定した場合、エラーが返ること
     */
    public function test_パスワードリセット：未登録メールアドレスを指定した場合、エラーが返ること(): void
    {
        $response = $this->post('/forgot-password', [
            'email' => 'hogehoge@example.com',
        ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['email']);
    }    

    /**
     * TC_AUTH_17
     * 有効なリセットトークンで新しいパスワードに変更できること
     */
    public function test_パスワードリセット：有効なリセットトークンで新しいパスワードに変更できること(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
        $token = Password::createToken($user);

        $response = $this->post('reset-password', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpass1234',
            'password_confirmation' => 'newpass1234',
        ]);

        // リダイレクトされることを確認
        $response->assertRedirect(route('login'));

        // 新しいパスワードに変更されていることを確認
        $this->assertTrue(Hash::check('newpass1234', $user->fresh()->password));
    }    

    /**
     * TC_AUTH_18
     * 無効なリセットトークンを使用した場合、エラーが返ること
     */
    public function test_パスワードリセット：無効なリセットトークンを使用した場合、エラーが返ること(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post('/reset-password', [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'newpass1234',
            'password_confirmation' => 'newpass1234',
        ]);

        $response->assertRedirect()
            ->assertSessionHasErrors(['email']);
    }    
}    

