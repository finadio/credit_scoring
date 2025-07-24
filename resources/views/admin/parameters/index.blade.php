<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Manajemen Parameter Scoring') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="h5 mb-0">{{ __('Daftar Parameter Scoring') }}</h3>
                        <a href="{{ route('admin.parameters.create') }}" class="btn btn-primary">
                            {{ __('Tambah Parameter Baru') }}
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Nama Parameter</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Aturan Scoring</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($parameters as $parameter)
                                    <tr>
                                        <td>{{ $parameter->parameter_name }}</td>
                                        <td>{{ $parameter->category }}</td>
                                        <td>{{ $parameter->description }}</td>
                                        <td>
                                            @if ($parameter->rules)
                                                <strong>Tipe:</strong> {{ ucfirst($parameter->rules['type']) }}<br>
                                                <strong>Opsi/Rentang:</strong>
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($parameter->rules['options'] as $option)
                                                        <li>
                                                            @if ($parameter->rules['type'] === 'discrete')
                                                                Nilai: "{{ $option['value'] ?? '-' }}" (Skor: {{ $option['score'] }})
                                                            @else
                                                                Rentang: {{ $option['min'] ?? 'Min' }} - {{ $option['max'] ?? 'Max' }} (Skor: {{ $option['score'] }})
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                Tidak ada aturan
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.parameters.edit', $parameter->id) }}" class="btn btn-sm btn-info me-2">Edit</a>
                                            <form action="{{ route('admin.parameters.destroy', $parameter->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus parameter ini? Ini akan mempengaruhi perhitungan skor yang sudah ada jika parameter ini digunakan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada parameter scoring ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
