<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Persetujuan Pengajuan Kredit') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="h5 mb-4">{{ __('Daftar Pengajuan Kredit') }}</h3>

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
                                    <th scope="col">ID Pengajuan</th>
                                    <th scope="col">Nasabah</th>
                                    <th scope="col">Jumlah Pinjaman</th>
                                    <th scope="col">Jangka Waktu</th>
                                    <th scope="col">Diajukan Oleh</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $application)
                                    <tr>
                                        <td>{{ $application->id }}</td>
                                        <td>{{ $application->customer->name }}</td>
                                        <td>Rp{{ number_format($application->loan_amount, 0, ',', '.') }}</td>
                                        <td>{{ $application->tenor_months }} bulan</td>
                                        <td>{{ $application->createdBy->name }}</td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'pending' => 'bg-warning',
                                                    'on_review' => 'bg-info',
                                                    'approved' => 'bg-success',
                                                    'rejected' => 'bg-danger',
                                                ][$application->application_status] ?? 'bg-secondary';
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $application->application_status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('direksi.applications.show', $application->id) }}" class="btn btn-sm btn-primary">Lihat Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada pengajuan kredit yang perlu disetujui.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $applications->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
