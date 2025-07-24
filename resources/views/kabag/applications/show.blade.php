<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Detail Pengajuan Kredit') }} #{{ $creditApplication->id }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <a href="{{ route('kabag.applications.index') }}" class="btn btn-secondary mb-4">
                        &larr; Kembali ke Daftar Pengajuan
                    </a>

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
                             @if ($errors->any())
                                <ul class="mt-3 list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <h3 class="h5 mb-3">Informasi Nasabah</h3>
                            <p><strong>Nama:</strong> {{ $creditApplication->customer->name }}</p>
                            <p><strong>NIK:</strong> {{ $creditApplication->customer->nik }}</p>
                            <p><strong>Telepon:</strong> {{ $creditApplication->customer->phone_number ?? '-' }}</p>
                            <p><strong>Alamat:</strong> {{ $creditApplication->customer->address ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h5 mb-3">Detail Pengajuan</h3>
                            <p><strong>Jumlah Pinjaman:</strong> Rp{{ number_format($creditApplication->loan_amount, 0, ',', '.') }}</p>
                            <p><strong>Jangka Waktu:</strong> {{ $creditApplication->tenor_months }} bulan</p>
                            <p><strong>Tanggal Pengajuan:</strong> {{ $creditApplication->application_date->format('d/m/Y') }}</p>
                            <p><strong>Diajukan oleh Teller:</strong> {{ $creditApplication->createdBy->name }}</p>
                            <p><strong>Status:</strong>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning',
                                        'on_review' => 'bg-info',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                    ][$creditApplication->application_status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $creditApplication->application_status)) }}
                                </span>
                            </p>
                             @if($creditApplication->rejection_reason)
                                <p class="text-danger mt-2"><strong>Alasan Penolakan:</strong> {{ $creditApplication->rejection_reason }}</p>
                            @endif
                        </div>
                    </div>

                    <h3 class="h5 mt-4 mb-3">Analisis Skor Kredit</h3>
                    <div class="p-4 border rounded bg-light mb-4">
                        <p class="fs-5 fw-bold mb-3">Skor Total: {{ $creditApplication->final_score ?? 'Belum Terhitung' }}</p>
                        <p class="fw-semibold mb-2">Skor Per Parameter:</p>
                        <ul class="list-unstyled">
                            @forelse($creditApplication->scoringDetails as $detail)
                                <li>
                                    <strong>{{ $detail->scoringParameter->parameter_name }}:</strong>
                                    {{ $detail->input_value ?? 'N/A' }} (Skor: {{ $detail->calculated_score }})
                                </li>
                            @empty
                                <li>Detail skor belum tersedia.</li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- Aksi Kabag: Review atau Tolak --}}
                    @if ($creditApplication->application_status === 'pending')
                        <div class="mt-4 d-flex gap-3">
                            <form action="{{ route('kabag.applications.review', $creditApplication->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <x-primary-button>
                                    {{ __('Teruskan ke Direksi (On Review)') }}
                                </x-primary-button>
                            </form>

                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reject-application-modal">
                                {{ __('Tolak Pengajuan') }}
                            </button>
                        </div>
                    @elseif ($creditApplication->application_status === 'on_review')
                        <div class="mt-4 d-flex gap-3">
                            <p class="text-info fw-semibold">Pengajuan ini sedang menunggu persetujuan Direksi.</p>
                             <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reject-application-modal">
                                {{ __('Tolak Pengajuan') }}
                            </button>
                        </div>
                    @else
                         <div class="mt-4">
                            <p class="text-muted fw-semibold">Pengajuan ini sudah {{ ucfirst(str_replace('_', ' ', $creditApplication->application_status)) }}.</p>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk Alasan Penolakan --}}
    <div class="modal fade" id="reject-application-modal" tabindex="-1" aria-labelledby="rejectApplicationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('kabag.applications.reject', $creditApplication->id) }}" class="p-4">
                    @csrf
                    @method('PUT')

                    <h2 class="h5 mb-3">
                        {{ __('Tolak Pengajuan Kredit') }}
                    </h2>

                    <p class="text-muted mb-3">
                        {{ __('Mohon berikan alasan penolakan untuk pengajuan ini.') }}
                    </p>

                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label visually-hidden">{{ __('Alasan Penolakan') }}</label>
                        <textarea id="rejection_reason" name="rejection_reason" class="form-control @error('rejection_reason', 'rejectApplication') is-invalid @enderror" rows="5" required>{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason', 'rejectApplication')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            {{ __('Konfirmasi Tolak') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->rejectApplication->isNotEmpty())
        <script>
            // Auto-open modal if there are validation errors from the reject form
            document.addEventListener('DOMContentLoaded', function() {
                var rejectModal = new bootstrap.Modal(document.getElementById('reject-application-modal'));
                rejectModal.show();
            });
        </script>
    @endif
</x-app-layout>
