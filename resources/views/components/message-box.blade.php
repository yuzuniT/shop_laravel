<div class="border-double border-4 border-gray-300 m-2 p-4 bg-zinc-50">
    @if($title)
        <p class="text-gray-500 text-xl font-bold mb-2">{{ $title }}</p>
    @endif
    <p class="text-gray-400 text-base">
        {{ $slot }} <!-- ここに任意のHTML/Blade構文が入る -->
    </p>
</div>