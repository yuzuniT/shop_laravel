<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactConfirmation;

class ContactController extends Controller
{
    // お問い合わせ情報入力画面
    public function create()
    {
        $user=Auth::user();

        return view('contact.create',[
            'user'=>$user,
        ]);
    }
    // 内容確認画面
    public function confirm(ContactRequest $request)
    {
        $validated=$request->validated();

        session()->put('contact_data',$validated);

        return view('contact.confirm',[
            'contact_data'=>$validated,
        ]);
    }

    // 送信処理
    public function store(Request $request)
    {

        // セッションからお問い合わせ情報を取得
        $contactData=session()->get('contact_data',[]);

        if (empty($contactData)) {
            return redirect()->route('contact.create')
                ->with('error','お問い合わせ情報が不足しています。');
        }

        // 確認画面で「戻る」ボタンが押された場合
        if ($request->has('back')) {
            return redirect()->route('contact.create')->withInput($contactData);
        }

        DB::beginTransaction();
        try {
            // お問い合わせヘッダーを作成
            $contact = Contact::create([
                'user_id' => Auth::id(), // ログインユーザーID
                'family_name' => $contactData['family_name'],
                'last_name' => $contactData['last_name'],
                'email' => $contactData['email'],
                'phone_number' => $contactData['phone_number'] ?? null,
                'contact_type' => $contactData['contact_type'],
                'contact_title' => $contactData['contact_title'],
                'message' => $contactData['message'],
            ]);

            session()->forget('contact_data');

            DB::commit();

            Mail::to($contact->email)->queue(new ContactConfirmation($contact));

            return redirect()->route('contact.complete')
                ->with('contact_completed',true)
                ->with('contact_id',$contact->id);
        } catch (\Exception $e) {
            DB::rollBack();
            // ログに記録
            \Log::error('Contact processing failed: ' .$e->getMessage());
            return redirect()->route('contact.create')
                ->with('error','お問い合わせ処理中にシステムエラーが発生しました。再度お試しください。');
        }
    }

    // お問い合わせ完了画面を表示
    public function complete(Request $request)
    {
        // 'contact_completed'フラグがない場合、不正なアクセスとみなしリダイレクト
        if (!$request->session()->has('contact_completed')) {
            return redirect()->route('products.index')
                ->with('error','不正なアクセスです。');
        }

        // お問い合わせIDがセッションに残っていれば取得し、ビューに渡す
        $contactId = session('contact_id',null);

        return view('contact.complete',[
            'contactId'=> $contactId,
        ]);
    }

}
