@extends('admin.layout.index')

@section('content')
    <style>
        .filter-container {
            margin: 20px auto;
            max-width: 800px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .filter-container form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .filter-container input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: calc(50% - 10px);
        }

        .filter-container button {
            padding: 10px 15px;
            background-color: #49443a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filter-container button:hover {
            background-color: #3a372f;
        }

        .tpk-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .tpk-table th, .tpk-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .tpk-table th {
            background-color: #49443a;
            color: white;
        }

        .empty-state {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
    </style>

    <div class="filter-container">
        <form action="{{ route('admin.tpk.index') }}" method="GET">
            <input type="date" name="start_date" value="{{ request('start_date') }}" required>
            <input type="date" name="end_date" value="{{ request('end_date') }}" required>
            <button type="submit">Filter</button>
        </form>
    </div>

    @if ($results->isNotEmpty())
        <table class="tpk-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Jumlah Quantity (Kg)</th>
                    <th>Total Harga (Rp)</th>
                    <th>Total Pembelian</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($results as $result)
                    <tr>
                        <td>{{ $result->username }}</td>
                        <td>{{ $result->total_quantity }}</td>
                        <td>{{ number_format($result->total_price, 0, ',', '.') }}</td>
                        <td>{{ $result->total_transactions }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <p>Belum ada data untuk tanggal yang dipilih.</p>
        </div>
    @endif
@endsection
