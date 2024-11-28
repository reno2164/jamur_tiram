@extends('admin.layout.index')

@section('content')
    <div class="card rounded-full">
        <div class="card-header bg-transparent d-flex justify-content-between">
            <a href="{{ route('create.users') }}" class="btn btn-info">
                <i class="fa-solid fa-plus"></i> Tambah User
            </a>
            <input type="text" wire:model="search" class="form-control w-25" placeholder="Search....">
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <a href="" class="btn btn-sm btn-dark"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('edit.users', $user->id) }}" class="btn btn-sm btn-warning"><i
                                        class="fa fa-pencil"></i></a>

                                <!-- Tombol Hapus -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#delete{{ $user->id }}">
                                    <i class="fa fa-trash"></i>

                            </td>
                        </tr>
                        <!-- Modal Konfirmasi Hapus -->
                        <div class="modal fade" id="delete{{ $user->id }}" tabindex="-1"
                            aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Konfirmasi Hapus
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus User ini?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('delete.user', $user->id) }}" method="POST">
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
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert message handling
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
