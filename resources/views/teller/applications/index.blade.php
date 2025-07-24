<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Daftar Pengajuan Kredit Saya') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="h5 mb-4">{{ __('Pengajuan Kredit yang Diajukan') }}</h3>

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

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID Pengajuan</th>
                                    <th scope="col">Nama Nasabah</th>
                                    <th scope="col">Jumlah Pinjaman</th>
                                    <th scope="col">Jangka Waktu</th>
                                    <th scope="col">Tanggal Pengajuan</th>
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
                                        <td>{{ $application->application_date->format('d/m/Y') }}</td>
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
                                            {{-- Teller tidak bisa melihat skor atau edit detail, hanya status --}}
                                            <a href="#" class="btn btn-sm btn-secondary disabled" aria-disabled="true">Lihat Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada pengajuan kredit yang Anda ajukan.</td>
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
