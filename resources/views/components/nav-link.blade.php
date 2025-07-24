@props(['active'])

@php
// Menentukan kelas 'active' Bootstrap jika tautan sedang aktif
$classes = ($active ?? false)
            ? 'nav-link active' // Kelas aktif Bootstrap
            : 'nav-link'; // Kelas default Bootstrap
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
