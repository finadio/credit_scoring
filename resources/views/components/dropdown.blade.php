@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
    // Menentukan kelas alignment Bootstrap
    $alignmentClasses = match ($align) {
        'left' => 'dropdown-menu-start',
        'top' => 'dropup', // Untuk dropdown yang muncul ke atas
        'right' => 'dropdown-menu-end',
        default => 'dropdown-menu-end', // Default ke kanan
    };

    // Lebar dropdown (Bootstrap biasanya menyesuaikan otomatis atau butuh CSS kustom)
    $widthClasses = match ($width) {
        '48' => 'w-auto', // Bootstrap handles width automatically, or you can add custom CSS
        default => 'w-auto',
    };
@endphp

{{-- Div utama untuk komponen dropdown --}}
<div class="dropdown" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    {{-- Trigger dropdown (misalnya tombol nama pengguna) --}}
    <div @click="open = ! open" class="d-inline-block">
        {{ $trigger }}
    </div>

    {{-- Konten dropdown (menu item) --}}
    <ul x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="dropdown-menu {{ $alignmentClasses }} {{ $widthClasses }}"
        {{-- Style ini diperlukan agar Alpine.js bisa mengontrol display, tapi Bootstrap juga akan mengontrolnya --}}
        style="display: none; position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);"
        x-on:click="open = false">
        {{ $content }}
    </ul>
</div>
