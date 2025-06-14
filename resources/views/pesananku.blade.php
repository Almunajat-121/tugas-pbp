@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Pesananku</h2>
    <ul class="nav nav-tabs mb-3" id="pesananTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="proses-tab" data-bs-toggle="tab" data-bs-target="#proses" type="button" role="tab">Dalam Proses</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="selesai-tab" data-bs-toggle="tab" data-bs-target="#selesai" type="button" role="tab">Selesai</button>
        </li>
    </ul>
    <div class="tab-content" id="pesananTabContent">
        <div class="tab-pane fade show active" id="proses" role="tabpanel">
            @php
                $proses = $pesanan->filter(fn($trx) => in_array($trx->status, ['diajukan','diterima']));
            @endphp
            @if($proses->isEmpty())
                <div class="alert alert-info">Tidak ada pesanan yang sedang diproses.</div>
            @else
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($proses as $trx)
                <div class="col">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{ $trx->barang->foto->first() ? asset('storage/' . $trx->barang->foto->first()->url_foto) : 'https://via.placeholder.com/200x150?text=No+Image' }}" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $trx->barang->nama }}</h5>
                                    <p class="card-text mb-1">Harga: <span class="fw-bold">{{ $trx->barang->tipe == 'jual' ? 'Rp. ' . number_format($trx->barang->harga,0,',','.') : 'Gratis' }}</span></p>
                                    <p class="card-text mb-1">
                                        Status: 
                                        @if($trx->status == 'diajukan')
                                            <span class="badge bg-warning text-dark">Diajukan</span>
                                        @elseif($trx->status == 'diterima')
                                            <span class="badge bg-success">Diterima</span>
                                        @endif
                                    </p>
                                    <p class="card-text"><small class="text-muted">
                                        Diajukan pada 
                                        @if(!empty($trx->created_at))
                                            {{ is_object($trx->created_at) ? $trx->created_at->format('d M Y H:i') : '-' }}
                                        @else
                                            -
                                        @endif
                                    </small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <div class="tab-pane fade" id="selesai" role="tabpanel">
            @php
                $selesai = $pesanan->filter(fn($trx) => $trx->status == 'selesai');
            @endphp
            @if($selesai->isEmpty())
                <div class="alert alert-info">Belum ada pesanan yang selesai.</div>
            @else
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($selesai as $trx)
                <div class="col">
                    <div class="card h-100 border-primary">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{ $trx->barang->foto->first() ? asset('storage/' . $trx->barang->foto->first()->url_foto) : 'https://via.placeholder.com/200x150?text=No+Image' }}" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $trx->barang->nama }}</h5>
                                    <p class="card-text mb-1">Harga: <span class="fw-bold">{{ $trx->barang->tipe == 'jual' ? 'Rp. ' . number_format($trx->barang->harga,0,',','.') : 'Gratis' }}</span></p>
                                    <p class="card-text mb-1">
                                        Status: <span class="badge bg-primary">Selesai</span>
                                    </p>
                                    @if(!isset($trx->ulasan))
                                        <a href="{{ route('ulasan.form', $trx->id) }}" class="btn btn-outline-primary btn-sm">Beri Ulasan</a>
                                    @else
                                        <div class="mt-2">
                                            <span class="fw-bold">Rating:</span> {{ $trx->ulasan->rating }}<br>
                                            <span class="fw-bold">Ulasan:</span> {{ $trx->ulasan->isi }}
                                        </div>
                                    @endif
                                    <p class="card-text"><small class="text-muted">
                                        Diajukan pada 
                                        @if(!empty($trx->created_at))
                                            {{ is_object($trx->created_at) ? $trx->created_at->format('d M Y H:i') : '-' }}
                                        @else
                                            -
                                        @endif
                                    </small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
