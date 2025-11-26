@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Data Pengguna</h4>
    <form action="{{ route('petugas.users.update', $user->id) }}" method="POST">
        @csrf @method('PATCH')

        <div class="mb-3">
            <label>NIK</label>
            <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
        </div>

        <button class="btn btn-primary" type="submit">Simpan</button>
        <a href="{{ route('petugas.users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection