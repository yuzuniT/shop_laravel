@extends('layouts.common')

@section('title','配送先情報の入力')

@section('content_title','お届け先・お支払い方法の入力')

@section('content')
    {{--お問い合わせフォーム--}}

    <form action={{ route('checkout.confirm') }} method="post" class="space-y-6">

        @csrf

        <!-- 名前　-->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- 姓 --}}
            <div>
                <label for="family_name" class="block font-medium text-gray-700 mb-1">
                    姓<span class="text-red-500">（必須）</span>
                </label>
                <input type="text" name="family_name" id="family_name"
                        class="w-full border rounded-md px-3 py-2 bg-white
                        @error('family_name') border-red-500 @enderror
                        {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                        value="{{ old('family_name', $user->family_name ?? '') }}" placeholder="山田" required>
                @error('family_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 名 --}}
            <div>
                <label for="last_name" class="block font-medium text-gray-700 mb-1">
                    名 <span class="text-red-500">（必須）</span>
                </label>
                <input type="text" name="last_name" id="last_name"
                        class="w-full border rounded-md px-3 py-2 bg-white
                        @error('last_name') border-red-500 @enderror
                        {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                        value="{{ old('last_name', $user->last_name ?? '') }}" placeholder="太郎" required>
                @error('last_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- メールアドレス -->
        <div>
            <label for="email" class="block font-medium text-gray-700 mb-1">
                メールアドレス <span class="text-red-500">（必須）</span>
            </label>
            <input type="email" name="email" id="email"
                    class="w-full border rounded-md px-3 py-2 bg-white
                    @error('email') border-red-500 @enderror
                    {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                    value="{{ old('email', $user->email ?? '') }}" placeholder="example@example.com" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 郵便番号 -->
        <div>
            <label for="postal_code" class="block font-medium text-gray-700 mb-1">
                郵便番号 <span class="text-red-500">（必須）</span>
            </label>
            <input type="text" name="postal_code" id="postal_code"
                    class="w-full border rounded-md px-3 py-2 bg-white
                    @error('postal_code') border-red-500 @enderror
                    {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                    value="{{ old('postal_code', $user->postal_code ?? '') }}" placeholder="1234567" required>
            @error('postal_code')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 住所 -->
        <div>
            <label for="address" class="block font-medium text-gray-700 mb-1">
                住所 <span class="text-red-500">（必須）</span>
            </label>
            <input type="text" name="address" id="address"
                    class="w-full border rounded-md px-3 py-2 bg-white
                    @error('address') border-red-500 @enderror
                    {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                    value="{{ old('address', $user->address ?? '') }}" placeholder="東京都渋谷区1-1-1 ◯◯マンション101号室" required>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 電話番号 -->
        <div>
            <label for="phone_number" class="block font-medium text-gray-700 mb-1">
                電話番号
            </label>
            <input type="tel" name="phone_number" id="phone_number"
                    class="w-full border rounded-md px-3 py-2 bg-white
                    @error('phone_number') border-red-500 @enderror
                    {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                    value="{{ old('phone_number', $user->phone_number ?? '') }}" placeholder="09012345678">
            @error('phone_number')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- お支払い方法 -->
        <div>
            <label for="payment_method" class="block font-medium text-gray-700 mb-1">
                お支払い方法 <span class="text-red-500">（必須）</span>
            </label>
            <select name="payment_method" id="payment_method"
                    class="w-full border rounded-md px-3 py-2 bg-white
                    @error('payment_method') border-red-500 @enderror
                    {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                    required>
                <option value="">選択してください</option>
                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : ''}}>クレジットカード決済</option>
                <option value="convenient_store" {{ old('payment_method') == 'convenient_store' ? 'selected' : ''}}>コンビニ決済</option>
                <option value="cash_on_delivery" {{ old('payment_method') == 'cash_on_delivery' ? 'selected' : ''}}>代金引換（着払い）</option>
                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : ''}}>銀行振込</option>            </select>
            @error('payment_method')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        <!-- 送信ボタン -->
        <div class="flex justify-evenly pt-4 border-t">
            <a href="{{ route('cart.index') }}"
            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                カートに戻る
            </a>

            <button type="submit" 
            class="bg-blue-600 text-white rounded-lg px-6 py-3 cursor-pointer hover:bg-blue-700">
                注文内容確認へ
            </button>
        </div>
    </form>
</div>



@endsection
