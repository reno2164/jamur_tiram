@extends('admin.layout.index')

@section('content')
<div class="container">
    <h1 class="mb-4">Proses TPK</h1>

    <!-- Filter Tanggal -->
    <form method="POST" action="{{ route('admin.tpk.process') }}">
        @csrf
        <div class="form-group">
            <label for="filterDate">Pilih Tanggal</label>
            <select id="filterDate" name="date" class="form-control">
                <option value="November">November</option>
                <!-- Tambahkan opsi bulan lainnya -->
            </select>
        </div>

        <!-- Input Bobot SAW -->
        <div class="form-group">
            <h4>Input Bobot</h4>
            <label for="priceWeight">Bobot Harga:</label>
            <input type="number" id="priceWeight" name="priceWeight" class="form-control" required>

            <label for="weightWeight">Bobot Berat:</label>
            <input type="number" id="weightWeight" name="weightWeight" class="form-control" required>

            <label for="purchaseWeight">Bobot Pembelian:</label>
            <input type="number" id="purchaseWeight" name="purchaseWeight" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Proses</button>
    </form>
</div>
@endsection
