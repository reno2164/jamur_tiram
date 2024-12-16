@extends('admin.layout.index')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-black">Sistem Pendukung Keputusan (SPK) - Input Matriks AHP</h1>

        <!-- Keterangan Skala Perbandingan -->
        <div class="mb-4 card">
            <div class="card-header">
                <h3 class="my-2">Keterangan Skala Perbandingan</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Kriteria/Alternatif A sama penting dengan kriteria/alternatif B</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>A sedikit lebih penting dari B</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>A jelas lebih penting dari B</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>A sangat jelas lebih penting dari B</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>A mutlak lebih penting dari B</td>
                        </tr>
                        <tr>
                            <td>2, 4, 6, 8</td>
                            <td>Apabila ragu-ragu antara dua nilai yang berdekatan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Input Matriks -->
        <div class="card">
            <form action="{{ route('spk.calculate') }}" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="my-2">Input Matriks Perbandingan Kriteria</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                @foreach ($kriteria as $k)
                                    <th>{{ $k }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $i => $rowKriteria)
                                <tr>
                                    <th>{{ $rowKriteria }}</th>
                                    @foreach ($kriteria as $j => $colKriteria)
                                        <td>
                                            <input type="number" step="0.001"
                                                name="matriks[{{ $i }}][{{ $j }}]"
                                                class="form-control"
                                                value="{{ $i == $j ? 1 : old('matriks.' . $i . '.' . $j) }}"
                                                {{ $i == $j ? 'readonly' : '' }} data-row="{{ $i }}"
                                                data-col="{{ $j }}" oninput="updateInverse(this)" />
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('spk.saw.index') }}" class="btn btn-md btn-danger mx-3">kembali</a>
                    <button type="submit" class="btn btn-primary">Proses</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        /**
         * Update the inverse value of the input field.
         *
         * @param {HTMLInputElement} element The input element being updated.
         */
        function updateInverse(element) {
            const row = element.dataset.row; // Current row index
            const col = element.dataset.col; // Current column index
            const value = parseFloat(element.value); // Current input value

            if (!isNaN(value) && value > 0) {
                // Find the inverse input element
                const inverseInput = document.querySelector(
                    `input[data-row='${col}'][data-col='${row}']`
                );

                if (inverseInput) {
                    // Set the value to its reciprocal
                    inverseInput.value = (1 / value).toFixed(3);
                }
            }
        }
    </script>
@endsection
