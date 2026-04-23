<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Sound Space')</title>
    @stack('styles')
    {{-- Vite が生成したCSS/JSを自動読み込み --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-gray-50">

@include('layouts.partials.header')

<main class="max-w-7xl mx-auto mt-5 px-4">
    



        <div class="mt-4 mb-5">
            <h1 class="text-2xl font-semibold">@yield('content_title')</h1>
        </div>

        <div class="content">
            @yield('content')
        </div>

@include('layouts.partials.flash-message')

</main>

@include('layouts.partials.footer')

@stack('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>


</html>
