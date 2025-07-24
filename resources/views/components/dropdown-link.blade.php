{{-- Menggunakan kelas Bootstrap 'dropdown-item' --}}
<a {{ $attributes->merge(['class' => 'dropdown-item']) }}>
    {{ $slot }}
</a>
