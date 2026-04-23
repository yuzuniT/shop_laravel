<x-mail::message>
# ご注文ありがとうございました

{{ $order->family_name }} {{ $order->last_name }} 様

この度は、当店をご利用いただき誠にありがとうございます。<br>
下記内容でご注文を承りましたので、ご確認をお願いいたします。

---

## 注文概要

<x-mail::panel>
**注文番号：** {{ $order->id }}
<br>
**ご注文日時：** {{ $order->created_at->format('Y年m月d日 H:i') }}
</x-mail::panel>

## お届先情報

<x-mail::panel>
**お名前：** {{ $order->family_name }} {{ $order->last_name }}
<br>
**郵便番号：** {{ substr($order->postal_code, 0, 3) }}-{{ substr($order->postal_code, 3) }}
<br>
**住所：** {{ $order->address }}
<br>
**電話番号：** {{ $order->phone_number ?? 'なし' }}
</x-mail::panel>

## お支払い方法

<x-mail::panel>
{{
    [
        'credit_card' => 'クレジットカード決済',
        'bank_transfer' => '銀行振込',
        'cash_on_delivery' => '代金引換（着払い）',
        'convenient_store' => 'コンビニ決済',
    ][$order->payment_method] ?? '不明'
}}
</x-mail::panel>

## ご注文明細
<x-mail::table>
| 商品名 | 単価 | 数量 | 小計 |
| :--- | :--- | :--- | :--- |
@foreach ($order->items as $item)
| {{ $item->product->product_name ?? '商品データなし' }} | ¥{{ number_format($item->price) }} | {{ $item->quantity }} | ¥{{ number_format($item->price * $item->quantity) }} |
@endforeach
</x-mail::table>

**合計金額**

<x-mail::panel>
**小計金額：** ¥{{ number_format($order->total_amount - $order->shipping_fee) }}
<br>
**送料：** ¥{{ number_format($order->shipping_fee) }}
<br>
**合計金額：** ¥{{ number_format($order->total_amount) }}
</x-mail::panel>

---

ご不明な点がございましたら、お気軽にお問い合わせください。
よろしくお願いいたします。
</x-mail::message>