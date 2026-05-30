<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    // =========================================================
    // ヘルパー
    // =========================================================

    private function validContactData(): array
    {
        return [
            'family_name'   => '山田',
            'last_name'     => '太郎',
            'email'         => 'test@example.com',
            'phone_number'  => '09012345678',
            'contact_type'  => 'order',
            'contact_title' => 'テスト件名',
            'message'       => 'テストメッセージ内容です。',
        ];
    }

    // =========================================================
    // お問い合わせフォームの入力
    // =========================================================

    /**
     * TC_CONTACT_01
     * 必須項目をすべて正しく入力した場合、確認画面へ遷移できること
     */
    public function test_必須項目をすべて入力した場合確認画面へ遷移できること(): void
    {
        $response = $this->post('/contact/confirm', $this->validContactData());

        // 200 OKで確認ページが表示されるか確認
        $response->assertStatus(200);

        // セッションにcontact_dataが保存されているか確認
        $response->assertSessionHas('contact_data');
    }    

    /**
     * TC_CONTACT_02
     * 任意項目（電話番号）を空欄で送信した場合、確認画面へ遷移できること
     */
    public function test_任意項目を空欄で送信した場合確認画面へ遷移できること(): void
    {
        $data = array_merge($this->validContactData(), ['phone_number' => null]);

        $response = $this->post('/contact/confirm', $data);

        $response->assertStatus(200);

        // セッションにphone_number = nullで保存されていることを確認
        $contactData = session('contact_data');
        $this->assertNotNull($contactData);
        $this->assertNull($contactData['phone_number']);
    }

    /**
     * TC_CONTACT_03
     * 必須フィールドがすべて空の場合、バリデーションエラーが返ること
     */
    public function test_必須フィールドがすべて空の場合バリデーションエラーが返ること(): void
    {
        $response = $this->post('/contact/confirm', []);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'family_name' => 'The family name field is required.',
            'last_name'   => 'The last name field is required.',
            'email'       => 'The email field is required.',
            'contact_type'=> 'The contact type field is required.',
            'contact_title'=> 'The contact title field is required.',
            'message'     => 'The message field is required.',
        ]);
    }

    /**
     * TC_CONTACT_04
     * 各必須項目を個別に空にした場合、そのフィールドのバリデーションエラーが返ること
     */
    public function test_各必須項目を個別に空にした場合そのフィールドのバリデーションエラーが返ること(): void
    {
        $requiredFields = [
            'family_name' => 'The family name field is required.',
            'last_name'   => 'The last name field is required.',
            'email'       => 'The email field is required.',
            'contact_type'=> 'The contact type field is required.',
            'contact_title'=> 'The contact title field is required.',
            'message'     => 'The message field is required.',
        ];

        foreach ($requiredFields as $field => $rule) {
            $data = array_merge($this->validContactData(), [$field => null]);

            $response = $this->post('/contact/confirm', $data);

            $response->assertRedirect();
            $response->assertSessionHasErrors([$field => $rule]);
        }
    }

    /**
     * TC_CONTACT_05
     * メールアドレスの形式が不正な場合、バリデーションエラーが返ること
     */
    public function test_メールアドレスの形式が不正な場合バリデーションエラーが返ること(): void
    {
        $data = array_merge($this->validContactData(), ['email' => 'hogehoge']);

        $response = $this->post('/contact/confirm', $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'The email field must be a valid email address.']);
    }

    /**
     * TC_CONTACT_06
     * 無効なcontact_typeを指定した場合、バリデーションエラーが返ること
     */
    public function test_無効なcontact_typeを指定した場合バリデーションエラーが返ること(): void
    {
        $data = array_merge($this->validContactData(), ['contact_type' => 'hogehoge']);

        $response = $this->post('/contact/confirm', $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['contact_type']);
    }    

    // =========================================================
    // お問い合わせ情報の確認画面
    // =========================================================

    /**
     * TC_CONTACT_07
     * 確認ページでお問い合わせ内容が正しく表示されること
     */
    public function test_確認ページでお問い合わせ内容が正しく表示されること(): void
    {
        $data = $this->validContactData();

        $response = $this->post('/contact/confirm', $data);

        $response->assertStatus(200);
        $response->assertSee($data['family_name']);
        $response->assertSee($data['last_name']);
        $response->assertSee($data['email']);
        $response->assertSee($data['contact_title']);
        $response->assertSee($data['message']);
    }

    // =========================================================
    // お問い合わせ情報の送信
    // =========================================================

    /**
     * TC_CONTACT_08
     * 送信ボタンを押した場合、contactsテーブルに正しく保存されること
     */
    public function test_送信ボタンを押した場合contactsテーブルに正しく保存されること(): void
    {
        Mail::fake();

        $data = $this->validContactData();

        $response = $this->withSession(['contact_data' => $data])
            ->post('/contact/store');
        
        $this->assertDatabaseHas('contacts', [
            'family_name' => $data['family_name'],
            'last_name'   => $data['last_name'],
            'email'       => $data['email'],
            'phone_number' => $data['phone_number'],
            'contact_type' => $data['contact_type'],
            'contact_title' => $data['contact_title'],
            'message' => $data['message'], 
            'status' => 'pending',
        ]);
    }

    /**
     * TC_CONTACT_09
     * 送信後、お問い合わせ完了ページへリダイレクトされること
     */
    public function test_送信後お問い合わせ完了ページへリダイレクトされること(): void
    {
        Mail::fake();

        $data = $this->validContactData();

        $response = $this->withSession(['contact_data' => $data])
            ->post('/contact/store');

        $response->assertRedirect(route('contact.complete'));
        $response->assertSessionHas('contact_completed', true);
        $response->assertSessionHas('contact_id');
    }    

    /**
     * TC_CONTACT_10
     * 送信後、セッションのcontact_dataがクリアされること
     */
    public function test_送信後セッションのcontact_dataがクリアされること(): void
    {
        Mail::fake();

        $data = $this->validContactData();

        $response = $this->withSession(['contact_data' => $data])
            ->post('/contact/store');

        $response->assertSessionMissing('contact_data'); // セッションからcontact_dataが削除されたことを確認する
    }    

    /**
     * TC_CONTACT_11
     * セッションにcontact_dataがない状態で送信しようとした場合、フォームへリダイレクトされること
     */
    public function test_セッションにcontact_dataがない状態で送信しようとした場合フォームへリダイレクトされること(): void
    {
        $response = $this->post('/contact/store');

        $response->assertRedirect(route('contact.create'));
        $response->assertSessionHas('error', 'お問い合わせ情報が不足しています。');
    }    

    // =========================================================
    // お問い合わせ完了画面の表示
    // =========================================================

    /**
     * TC_CONTACT_12
     * お問い合わせ完了ページで正しいお問い合わせIDが表示されること
     */
    public function test_お問い合わせ完了ページで正しいお問い合わせIDが表示されること(): void
    {
        $response = $this->withSession([
            'contact_completed' => true,
            'contact_id' => 123,
        ])->get('/contact/complete');

        $response->assertStatus(200);
        $response->assertSee('123');
    }

    /**
     * TC_CONTACT_13
     * contact_completedフラグなしで完了ページへアクセスした場合、トップページへリダイレクトされること
     */
    public function test_contact_completedフラグなしで完了ページへアクセスした場合トップページへリダイレクトされること(): void
    {
        $response = $this->get('/contact/complete');

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('error', '不正なアクセスです。');
    }
    
}