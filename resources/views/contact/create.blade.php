@extends('layouts.common')

@section('title','Sound Space/お問い合わせ')

@section('content_title','お問い合わせ')

@section('content')
    {{--お問い合わせフォーム--}}

    <form action={{ route('contact.confirm') }} method="post" class="space-y-6">

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

        <!-- お問い合わせ種類 -->
        <div>
            <label for="contact_type" class="block font-medium text-gray-700 mb-1">
                お問い合わせの種類 <span class="text-red-500">（必須）</span>
            </label>
            <select name="contact_type" id="contact_type"
                    class="w-full border rounded-md px-3 py-2 bg-white
                    @error('contact_type') border-red-500 @enderror
                    {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                    required>
                <option value="">選択してください</option>
                <option value="product" {{ old('contact_type') == 'product' ? 'selected' : ''}}>商品について</option>
                <option value="order" {{ old('contact_type') == 'order' ? 'selected' : ''}}>注文・発送について</option>
                <option value="return" {{ old('contact_type') == 'return' ? 'selected' : ''}}>返品・交換</option>
                <option value="payment" {{ old('contact_type') == 'payment' ? 'selected' : ''}}>支払いについて</option>
                <option value="other" {{ old('contact_type') == 'other' ? 'selected' : ''}}>その他</option>
            </select>
            @error('contact_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 件名 -->
        <div>
            <label for="contact_title" class="block font-medium text-gray-700 mb-1">
                件名 <span class="text-red-500">（必須）</span>
            </label>
            <input type="text" name="contact_title" id="contact_title"
                    class="w-full border rounded-md px-3 py-2 bg-white
                    @error('contact_title') border-red-500 @enderror
                    {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                    value="{{ old('contact_title') }}" placeholder="件名を入力してください" required>
            @error('contact_title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- メッセージ -->
        <div>
            <label for="message" class="block font-medium text-gray-700 mb-1">
                メッセージ <span class="text-red-500">（必須）</span>
            </label>
            <textarea name="message" id="message" rows="6"
                    class="w-full border rounded-md px-3 py-2 bg-white
                    @error('message') border-red-500 @enderror
                    {{-- focus:outline-none focus:ring focus:ring-blue-300 --}}"
                    placeholder="お問い合わせ内容を入力してください" required>{{ old('message') }}</textarea>
            @error('message')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 送信ボタン -->
        <div class="text-right">
            <button type="submit" 
            class="bg-blue-600 text-white rounded-md px-6 py-2 cursor-pointer
            hover:bg-blue-700 transition">次へ進む</button>
        </div>
    </form>
</div>



@endsection
