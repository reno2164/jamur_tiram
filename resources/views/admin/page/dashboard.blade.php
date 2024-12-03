@extends('admin.layout.index')

@section('content')
<div class="d-flex flex-wrap gap-3 justify-content-center">
    <div class="card shadow" style="width: 200px; border: none; background-color: #F8F9FA;">
        <div class="card-body text-center">
            <div class="d-flex gap-2 align-items-center justify-content-center mb-2">
                <span class="material-icons p-1 rounded-circle" 
                    style="font-size:28px; color:green; background-color:#A6FF96">
                    inventory
                </span>
            </div>
            <h5 class="text-success">Total Product</h5>
            <span class="fs-2 fw-bold">2</span>
        </div>
    </div>
    <div class="card shadow" style="width: 200px; border: none; background-color: #F8F9FA;">
        <div class="card-body text-center">
            <div class="d-flex gap-2 align-items-center justify-content-center mb-2">
                <span class="material-icons p-1 rounded-circle" 
                    style="font-size:28px; color:#D80032; background-color:#F78CA2">
                    view_in_ar
                </span>
            </div>
            <h5 class="text-danger">Total Stock</h5>
            <span class="fs-2 fw-bold">30</span>
        </div>
    </div>
    <div class="card shadow" style="width: 200px; border: none; background-color: #F8F9FA;">
        <div class="card-body text-center">
            <div class="d-flex gap-2 align-items-center justify-content-center mb-2">
                <span class="material-icons p-1 rounded-circle" 
                    style="font-size:28px; color:#088395; background-color:#7ED7C1">
                    shopping_cart
                </span>
            </div>
            <h5 class="text-primary">Transaksi</h5>
            <span class="fs-2 fw-bold">50</span>
        </div>
    </div>
    <div class="card shadow" style="width: 200px; border: none; background-color: #F8F9FA;">
        <div class="card-body text-center">
            <div class="d-flex gap-2 align-items-center justify-content-center mb-2">
                <span class="material-icons p-1 rounded-circle" 
                    style="font-size:28px; color:#FFC436; background-color:#F4F27E">
                    payments
                </span>
            </div>
            <h5 class="text-warning">Penghasilan</h5>
            <span class="fs-2 fw-bold">40</span>
        </div>
    </div>
</div>

<div class="card shadow mt-4" style="border: none; background-color: #FFFFFF;">
    <div class="card-body">
        <h5 class="text-center text-dark mb-4">Grafik Transaksi Per Bulan</h5>
        <canvas id="myChart" style="height: 50vh;"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('myChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Transaksi',
                data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                pointBackgroundColor: 'rgb(75, 192, 192)',
                pointBorderColor: '#fff',
                pointRadius: 5,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function(context) {
                            return `Transaksi: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e9e9e9',
                    }
                }
            }
        }
    });
</script>
@endsection
