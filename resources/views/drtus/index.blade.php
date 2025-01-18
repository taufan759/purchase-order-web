@extends('layout')
<title>DRTU</title>
@section('konten')
    <div class="container">
        <h1 style="text-align: center; color:navy;">List of Daftar Rekanan Terundang (DRTU)</h1>
        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

<style>
    .container {
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        margin-top: 50px;
    }
    body {
        background-image: url('{{ asset('img/bg.jpg') }}');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: black;
    }

    /* Style untuk tabel agar memiliki scroll */
    .table-container {
        max-height: 400px; /* Batasi tinggi tabel agar bisa discroll */
        overflow-y: auto; /* Scroll secara vertikal */
        margin-bottom: 20px;
    }

    /* Header tabel agar tetap di atas saat discroll */
    thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #343a40; /* Warna latar belakang header tabel */
        color: white;
    }

    /* Style untuk scrollbar */
    .table-container::-webkit-scrollbar {
        width: 8px;
    }

    .table-container::-webkit-scrollbar-track {
        background-color: #f1f1f1;
    }

    .table-container::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 10px;
    }

    .table-container::-webkit-scrollbar-thumb:hover {
        background-color: #555;
    }
    /* Pastikan bahwa kolom Description tidak melampaui batas */
td.description-column {
    max-width: 250px; /* Sesuaikan dengan lebar maksimal yang Anda inginkan */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    word-wrap: break-word; /* Untuk memotong kata-kata yang terlalu panjang */
}
td.no-pr {
    max-width: 150px; /* Sesuaikan dengan lebar maksimal yang Anda inginkan */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    word-wrap: break-word; /* Untuk memotong kata-kata yang terlalu panjang */
}
td.memodinas {
    max-width: 150px; /* Sesuaikan dengan lebar maksimal yang Anda inginkan */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    word-wrap: break-word; /* Untuk memotong kata-kata yang terlalu panjang */
}
td.pengadaan {
    max-width: 250px;
    width: auto;
    text-align: center;
}
td.penanggungjawab{
    width: 100px;
    max-width: 100px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

    
</style>

        <!-- Add New DRTU button -->
@if(Auth::user()->role === 'admin')
<a href="{{ route('drtus.create') }}" class="btn btn-primary mb-3">Add New DRTU</a>
@endif
        <a href="{{ route('purchase_orders.index') }}" class="btn btn-success" style="margin-bottom: 15px; margin-left:50px">Kembali Ke OE</a>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <form action="{{ route('drtus.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Cari Id" value="{{ request('search') }}" autocomplete="off">
                    <select name="filter_year" class="form-control mr-sm-2">
            <option value="">-</option>
            <option value="2024" {{ request('filter_year') == '2024' ? 'selected' : '' }}>2024</option>
            <option value="2025" {{ request('filter_year') == '2025' ? 'selected' : '' }}>2025</option>
        </select>
        <button type="submit" style="margin-top: 5px;">cari</button>
                </form>
            </div>
        </div>

        <div class="table-container">
            <table class="table table-bordered table-striped" style="border: 5px black solid;">
                <thead class="table-dark" style="text-align: center;">
                    <tr>
                        <th>ID</th>
                        <th  >Doc Date</th>
                        <th style="max-width: 90px;">NO PR</th>
                        <th  >NO Memo Dinas</th>
                        <th>Metode Pengadaan</th>
                        <th>Description</th>
                        <th >Penanggung Jawab</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="drtu-table-body">
                    @foreach($drtus as $drtu)
                        <tr>
                            <td>{{ sprintf('DRTU%04d', $drtu->id) }}</td>
                            <td style="width: 100px; max-width:100px">{{ \Carbon\Carbon::parse($drtu->doc_date)->format('Y-m-d') }}</td>
                            <td class="no-pr">{{ $drtu->number_of_purchase_requisition }}</td>
                            <td class="memodinas">{{ $drtu->number_of_memo_dinas }}</td>
                            <td class="pengadaan">{{ $drtu->metode_pengadaan }}</td>
                            <td class="description-column">{{ $drtu->description }}</td>
                            <td class="penanggungjawab">{{ $drtu->penanggung_jawab }}</td>
                            <td class="text-center">
                                @if(Auth::user()->role === 'admin')
                                    <!-- Admin: Bisa Edit, Cetak, Hapus -->
                                    <!-- Edit Button -->
                                    <a href="{{ route('drtus.edit', $drtu->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                
                                    <!-- Print Button -->
                                    <a href="{{ route('drtus.pdf', $drtu->id) }}" class="btn btn-success btn-sm" target="_blank" data-toggle="tooltip" title="Cetak">
                                        <i class="bi bi-printer"></i>
                                    </a>
                
                                    <!-- Delete Button -->
                                    <form action="{{ route('drtus.destroy', $drtu->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- User: Hanya Bisa Cetak -->
                                    <!-- Print Button -->
                                    <a href="{{ route('drtus.pdf', $drtu->id) }}" class="btn btn-success btn-sm" target="_blank" data-toggle="tooltip" title="Cetak">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>

        <div>
            <body>
                <a href="{{ route('welcome2') }}" class="btn btn-secondary floating-button">Kembali</a>
            </body>
        </div>

        <script>
            document.getElementById('search').addEventListener('keyup', function() {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('#drtu-table-body tr');

                rows.forEach(function(row) {
                    const id = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const namaPerusahaan = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                    if (id.includes(query) || namaPerusahaan.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>
@endsection
