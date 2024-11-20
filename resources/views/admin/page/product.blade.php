@extends('admin.layout.index')

@section('content')
<div class="card rounded-full">
    <div class="card-header bg-transparent d-flex justify-content-between">
        <a href="{{route('product.create')}}" class="btn btn-info">
            <i class="fa-solid fa-plus"></i>Tambah Produk</a>
        <input type="text" wire:model="search" class="form-control w-25" placeholder="Search....">
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">no</th>
                    <th scope="col">Gambar</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga/kg</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($products->isEmpty())
                        <tr class="text-center">
                            <td colspan="6">Belum ada produk</td>
                        </tr>
                    @else
                @forelse ($products as $no => $product)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td class="text-center">
                            <img src="{{ asset('/storage/products/' . $product->image) }}" class="rounded"
                                style="width: 150px">
                        </td>
                        <td>{{ $product->title }}</td>
                        <td>{{ 'Rp ' . number_format($product->price, 2, ',', '.') }}</td>
                        <td>{{ $product->stok }}</td>
                        <td class="">
                                <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-dark"><i
                                        class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-warning"><i
                                        class="fa fa-pencil"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#delete{{ $product->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                        </td>
                    </tr>
                    <div class="modal fade" id="delete{{ $product->id }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Konfirmasi
                                        Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus produk ini?
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('product.destroy', $product->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-danger">
                        Data Products belum Tersedia.
                    </div>
                @endforelse
                @endif
            </tbody>
        </table>
        <div class="pagination d-flex flex-row justify-content-between">
            <div class="showData">
                Data ditampilkan {{ $products->count() }} dari {{ $products->total() }}
            </div>
            <div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    //message with sweetalert
    @if (session('success'))
        Swal.fire({
            icon: "success",
            title: "BERHASIL",
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @elseif (session('error'))
        Swal.fire({
            icon: "error",
            title: "GAGAL!",
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif
</script>
@endsection