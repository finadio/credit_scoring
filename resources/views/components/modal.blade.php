@props(['name', 'show' => false, 'maxWidth' => '2xl', 'closeable' => true])

@php
    // Menentukan ukuran modal Bootstrap berdasarkan prop maxWidth
    $maxWidth = [
        'sm' => 'modal-sm',
        'md' => '', // Ukuran default Bootstrap
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        '2xl' => 'modal-xl', // Bootstrap tidak memiliki 2xl, gunakan xl
    ][$maxWidth];
@endphp

{{-- Div utama yang dikontrol oleh Alpine.js --}}
<div
    x-data="{
        show: @js($show), // State Alpine.js untuk menunjukkan/menyembunyikan modal
        // Fungsi untuk mengelola fokus elemen di dalam modal (dipertahankan dari Breeze)
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)].filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
    }"
    {{-- Inisialisasi Alpine.js dan interaksi dengan Bootstrap Modal --}}
    x-init="$watch('show', value => {
        if (value) {
            // Jika 'show' menjadi true, tampilkan modal Bootstrap
            document.body.classList.add('overflow-hidden'); // Tambahkan kelas untuk mencegah scroll body
            let myModal = new bootstrap.Modal(document.getElementById('{{ $name }}'));
            myModal.show();
        } else {
            // Jika 'show' menjadi false, sembunyikan modal Bootstrap
            document.body.classList.remove('overflow-hidden'); // Hapus kelas
            let myModal = bootstrap.Modal.getInstance(document.getElementById('{{ $name }}'));
            if (myModal) myModal.hide();
        }
    })"
    {{-- Event listener untuk membuka modal dari luar --}}
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    {{-- Event listener untuk menutup modal --}}
    x-on:close.stop="show = false"
    {{-- Event listener untuk menutup modal dengan tombol Escape --}}
    x-on:keydown.escape.window="if (show && {{ $closeable ? 'true' : 'false' }}) { show = false }"
    {{-- Awalnya sembunyikan div ini, Bootstrap akan mengontrol display modal --}}
    style="display: {{ $show ? 'block' : 'none' }};"
>
    {{-- Struktur Bootstrap Modal --}}
    <div class="modal fade" id="{{ $name }}" tabindex="-1" aria-labelledby="{{ $name }}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered {{ $maxWidth }}">
            <div class="modal-content">
                {{ $slot }} {{-- Konten modal akan dimasukkan di sini --}}
            </div>
        </div>
    </div>
</div>
