@props(['active'])

@php
// Menentukan kelas untuk tautan responsif/mobile
$classes = ($active ?? false)
            ? 'd-block py-2 px-3 text-start text-decoration-none bg-primary text-white rounded' // Kelas aktif Bootstrap
            : 'd-block py-2 px-3 text-start text-decoration-none text-dark hover:bg-light rounded'; // Kelas default Bootstrap
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
