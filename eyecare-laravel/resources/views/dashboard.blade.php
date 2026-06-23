<x-app-layout>
    <div class="panel panel-headers">
        <div class="headermenu-title">
            <h3 class="headerstitle">Welcome to the Dashboard</h3>
        </div>
    </div>

    <div class="summary-cards">
        <div class="card-summary">Products<br>{{ $stats['products'] }}</div>
        <div class="card-summary">Patients<br>{{ $stats['patients'] }}</div>
        <div class="card-summary">Total Sales<br>₱{{ number_format($stats['sales'] ?? 0, 2) }}</div>
        @if(auth()->user()->role === 'Admin')
        <div class="card-summary">Users<br>{{ $stats['users'] }}</div>
        @endif
    </div>

    <div class="charts-section">
        <div class="chart-box">
            <h5>Products by Category</h5>
            <canvas id="categoryChart"></canvas>
        </div>
        <div class="chart-box">
            <h5>Monthly Sales Trend</h5>
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Chart(document.getElementById('categoryChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($productsByCategory->pluck('label')) !!},
                    datasets: [{
                        data: {!! json_encode($productsByCategory->pluck('count')) !!},
                        backgroundColor: ['#85abf2','#f28e2c','#e15759','#76b7b2','#59a14f','#edc949','#af7aa1','#ff9da7']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            new Chart(document.getElementById('salesChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlySales->map(fn($s) => date('M', mktime(0, 0, 0, $s['month'], 1)))) !!},
                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($monthlySales->pluck('total')) !!},
                        backgroundColor: '#3498db',
                        borderRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return '₱' + value.toLocaleString(); }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
