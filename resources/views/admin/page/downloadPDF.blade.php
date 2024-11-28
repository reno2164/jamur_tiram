<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pesanan Selesai</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #000;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Laporan Pesanan Selesai</h2>
    <p>Periode: {{ request('start_date') ?? 'Awal' }} - {{ request('end_date') ?? 'Akhir' }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Transaksi</th>
                <th>Pelanggan</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($completedOrders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->transaction_code }}</td>
                <td>{{ $order->user->username }}</td>
                <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
