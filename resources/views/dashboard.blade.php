<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="h5 mb-4">Ringkasan Pengajuan Kredit Sistem</h3>

                    {{-- Summary Cards --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="bg-primary-subtle p-4 rounded shadow-sm text-primary">
                                <h4 class="fs-6">Total Pengajuan</h4>
                                <p class="fs-3 fw-bold">{{ $total_applications }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="bg-success-subtle p-4 rounded shadow-sm text-success">
                                <h4 class="fs-6">Disetujui</h4>
                                <p class="fs-3 fw-bold">{{ $approved_applications }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="bg-danger-subtle p-4 rounded shadow-sm text-danger">
                                <h4 class="fs-6">Ditolak</h4>
                                <p class="fs-3 fw-bold">{{ $rejected_applications }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="bg-warning-subtle p-4 rounded shadow-sm text-warning">
                                <h4 class="fs-6">Menunggu Review</h4>
                                <p class="fs-3 fw-bold">{{ $pending_applications + $on_review_applications }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Charts Section --}}
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <h3 class="h5 mb-4">Status Pengajuan</h3>
                            <div class="chart-container" style="position: relative; height:300px; width:100%">
                                <canvas id="applicationStatusChart"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h3 class="h5 mb-4">Tren Pengajuan Bulanan</h3>
                            <div class="chart-container" style="position: relative; height:300px; width:100%">
                                <canvas id="monthlyApplicationsChart"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Push scripts to the 'scripts' stack in app.blade.php --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Pie Chart for Application Status
            const statusCtx = document.getElementById('applicationStatusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: @json($application_status_chart_data['labels']),
                    datasets: [{
                        data: @json($application_status_chart_data['data']),
                        backgroundColor: @json($application_status_chart_data['colors']),
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
                            text: 'Status Pengajuan Kredit'
                        }
                    }
                }
            });

            // Bar Chart for Monthly Applications
            const monthlyCtx = document.getElementById('monthlyApplicationsChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: @json($monthly_applications_chart_data['labels']),
                    datasets: [{
                        label: 'Jumlah Pengajuan',
                        data: @json($monthly_applications_chart_data['data']),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: false,
                            text: 'Tren Pengajuan Bulanan'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // Ensure whole numbers for counts
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
