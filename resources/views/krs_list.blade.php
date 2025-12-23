<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem KRS Online - Reno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .quota-box { font-weight: bold; font-size: 1.1em; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary">Sistem KRS Online</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Daftar Mata Kuliah Tersedia</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th class="text-center">Minat (Weak)</th>
                            <th class="text-center">Kuota (Strong)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                        @php
                            $sisa = $course->quota - $course->taken;
                            $isFull = $sisa <= 0;
                        @endphp
                        <tr>
                            <td><span class="badge bg-secondary">{{ $course->code }}</span></td>
                            <td class="fw-bold">{{ $course->name }}</td>
                            
                            <td class="text-center">
                                <span class="text-muted d-block mb-1">{{ $course->interested_count }} peminat</span>
    
                                    <div class="d-flex justify-content-center gap-1">
                                        <form action="{{ route('tertarik') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Tertarik">
                                            👍
                                            </button>
                                        </form>

                                        <form action="{{ route('batal.tertarik') }}" method="POST">
                                         @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Batal">
                                                👎
                                             </button>
                                        </form>
                                    </div>
                            </td>

                            <td class="text-center">
                                <span class="quota-box {{ $isFull ? 'text-danger' : 'text-success' }}">
                                    {{ $course->taken }} / {{ $course->quota }}
                                </span>
                                <br>
                                <small class="text-muted">Sisa: {{ $sisa }}</small>
                            </td>

                            <td class="text-center">
                                <form action="{{ route('ambil.kelas') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    
                                    @if($isFull)
                                        <button type="button" class="btn btn-secondary" disabled>Penuh</button>
                                    @else
                                        <button type="submit" class="btn btn-primary">
                                            Ambil Kelas
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4 text-muted">
        <small>AYO MABAR MOBILE LEGENDS</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>