<div {{ $attributes->merge(['class'=>'border-double border-4 border-gray-300 m-2 p-4 bg-white']) }}>
    @if($title)
        <p class="text-gray-500 text-xl font-bold mb-2">{{ $title }}</p>
    @endif
    {{ $slot }} <!-- ここに任意のHTML/Blade構文が入る -->
</div>