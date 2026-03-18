@extends('layouts.common')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-7xl mx-auto">
        <!-- ヘッダー -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800">管理者ダッシュボード</h1>
            <p class="text-gray-600 mt-2">{{ Auth::user()->family_name . Auth::user()->last_name }}さん、こんにちは！</p>
        </div>

        <!-- メインメニューグリッド -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- 商品管理 -->
            <a href="{{ route('admin.products') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">商品管理</h2>
                        <p class="text-gray-600 mt-2">商品の追加、編集、削除</p>
                    </div>
                    <div class="text-4xl text-blue-500">📦</div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500">{{ App\Models\Product::count() }} 件の商品</p>
                </div>
            </a>

            <!-- ユーザー管理 -->
            <a href="{{ route('admin.users') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">ユーザー管理</h2>
                        <p class="text-gray-600 mt-2">ユーザー情報の管理</p>
                    </div>
                    <div class="text-4xl text-green-500">👥</div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500">{{ App\Models\User::count() }} 件のユーザー</p>
                    <p class="text-xs text-gray-400 mt-2">ユーザーの一覧・編集・無効化</p>
                </div>
            </a>

            <!-- 注文管理 -->
            <div class="bg-white rounded-lg shadow-md p-6 opacity-50 cursor-not-allowed">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">注文管理</h2>
                        <p class="text-gray-600 mt-2">注文内容の確認</p>
                    </div>
                    <div class="text-4xl text-purple-500">📋</div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500">{{ App\Models\Order::count() }} 件の注文</p>
                    <p class="text-xs text-gray-400 mt-2">計画中</p>
                </div>
            </div>
        </div>

        <!-- 統計セクション -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
                <div class="text-3xl font-bold">{{ App\Models\Product::count() }}</div>
                <div class="text-sm mt-2 text-blue-100">商品数</div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
                <div class="text-3xl font-bold">{{ App\Models\User::count() }}</div>
                <div class="text-sm mt-2 text-green-100">ユーザー数</div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
                <div class="text-3xl font-bold">{{ App\Models\Order::count() }}</div>
                <div class="text-sm mt-2 text-purple-100">注文数</div>
            </div>
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-md p-6 text-white">
                <div class="text-3xl font-bold">¥{{ number_format(App\Models\Order::sum('total_price') ?? 0) }}</div>
                <div class="text-sm mt-2 text-orange-100">売上合計</div>
            </div>
        </div>

        <!-- ログアウトボタン -->
        <div class="flex justify-end">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg transition">
                    ログアウト
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
