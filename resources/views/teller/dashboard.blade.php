<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Dashboard Teller') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="h5 mb-4">Ringkasan Pengajuan Kredit Saya</h3>

                    {{-- Summary Cards for Teller's Applications --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="bg-primary-subtle p-4 rounded shadow-sm text-primary">
                                <h4 class="fs-6">Total Pengajuan Saya</h4>
                                <p class="fs-3 fw-bold">{{ $my_total_applications }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="bg-success-subtle p-4 rounded shadow-sm text-success">
                                <h4 class="fs-6">Disetujui</h4>
                                <p class="fs-3 fw-bold">{{ $my_approved_applications }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="bg-danger-subtle p-4 rounded shadow-sm text-danger">
                                <h4 class="fs-6">Ditolak</h4>
                                <p class="fs-3 fw-bold">{{ $my_rejected_applications }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="bg-warning-subtle p-4 rounded shadow-sm text-warning">
                                <h4 class="fs-6">Menunggu Review</h4>
                                <p class="fs-3 fw-bold">{{ $my_pending_applications + $my_on_review_applications }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Chart for Teller's Application Status --}}
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <h3 class="h5 mb-4">Status Pengajuan Saya</h3>
                            <div class="chart-container" style="position: relative; height:300px; width:100%">
                                <canvas id="myApplicationStatusChart"></canvas>
                            </div>
                        </div>
                        {{-- Teller mungkin tidak perlu tren bulanan global, atau bisa ditambahkan di sini jika diperlukan --}}
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Pie Chart for My Application Status
            const myStatusCtx = document.getElementById('myApplicationStatusChart').getContext('2d');
            new Chart(myStatusCtx, {
                type: 'pie',
                data: {
                    labels: @json($my_application_status_chart_data['labels']),
                    datasets: [{
                        data: @json($my_application_status_chart_data['data']),
                        backgroundColor: @json($my_application_status_chart_data['colors']),
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Status Pengajuan Kredit Saya'
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
