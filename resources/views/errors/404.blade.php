@extends('layouts.nav')

@section('content')
<div class="container mx-auto p-4 text-center">
    <h1 class="text-4xl font-bold mb-4">404 - Halaman Tidak Ditemukan</h1>
    <p class="text-lg mb-4">Maaf, halaman yang Anda cari tidak ada.</p>
    <a href="{{ url('/') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Kembali ke Beranda</a>
</div>
@endsection