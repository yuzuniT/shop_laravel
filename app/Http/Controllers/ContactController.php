<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    //フォーム表示
    public function create()
    {
        return view('contact.index');
    }

    //送信処理
    public function store(ContactRequest $request)
    {


        $data=$request->validated(); // バリデーション済データを取得


        // ここに処理（DB保存・メール送信など）


        // 確認画面へ

        return redirect()->route('contact.confirm')
                        ->with('contact_data',$data);
    }

    public function confirm(){
        return view('contact.confirm');
    }
}
