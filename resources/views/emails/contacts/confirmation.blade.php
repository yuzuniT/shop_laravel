<x-mail::message>
# お問い合わせを受け付けました

{{ $contact->family_name }} {{ $contact->last_name }} 様

この度は、Sound Spaceへお問い合わせいただき誠にありがとうございます。<br>
以下の内容で受付いたしました。

本メールはお問い合わせいただいた内容の控えとして、自動で配信しております。

---

## 受付内容

<x-mail::panel>
**お問い合わせ番号：** {{ $contact->id }}
<br>
**お問い合わせ日時：** {{ $contact->created_at->format('Y年m月d日 H:i') }}
</x-mail::panel>

## ご本人様情報

<x-mail::panel>
**お名前：** {{ $contact->family_name }} {{ $contact->last_name }}
<br>
**メールアドレス：** {{ $contact->email }}
<br>
**電話番号：** {{ $contact->phone_number ?? 'なし' }}
</x-mail::panel>

## お問い合わせ内容

<x-mail::panel>
**お問い合わせの種類：** {{
    [
        'product' => '商品について',
        'order' => '注文・発送について',
        'return' => '返品・交換',
        'payment' => '支払いについて',
        'other' => 'その他',
    ][$contact->contact_type] ?? '不明'
}}
<br>
**件名：** {{ $contact->contact_title }}
<br>
**内容：** 
{!! nl2br(e($contact->message)) !!}
</x-mail::panel>

---

内容を確認の上、担当者より**3営業日以内**にメールにてご返信を差し上げます。
恐れ入りますが、今しばらくお待ちくださいませ。

※本メールに心当たりがない場合は、お手数ですが本メールを破棄していただきますようお願い申し上げます。

よろしくお願いいたします。
</x-mail::message>