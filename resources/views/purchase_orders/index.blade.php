@extends('layout')
<title>OE</title>
@section('konten')

<style>
    body {
        background-image: url('{{ asset('img/kapal.webp') }}');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: black;
    }

    .container {
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        margin-top: 50px;
    }

    .table-container {
        max-height: 550px; /* Atur tinggi maksimal container tabel */
        overflow-y: auto;  /* Aktifkan scroll di sumbu vertikal */
        margin-bottom: 20px; /* Jarak antara tabel dan elemen lain di bawahnya */
    }

    .table {
        width: 100%;
        background-color: white;
        border-collapse: collapse;
    }

    .table th, .table td {
        border: 1px solid black;
        padding: 10px;
        text-align: center;
    }

    /* Sticky header */
    .table th {
        position: sticky; /* Membuat elemen th "sticky" */
        top: 0; /* Tetap di atas saat scroll */    
        z-index: 10; /* Berikan z-index agar header tetap di depan konten */
        border: 1px solid black;
    }


    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .action-buttons a {
        font-size: 14px;
        padding: 5px 10px;
    }

    /* Search bar */
    .search-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 10px;
    }

    .search-container input[type="text"] {
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .search-container button {
        padding: 5px 10px;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        border: none;
        margin-left: 5px;
        font-size: 14px;
        cursor: pointer;
    }

    .search-container button:hover {
        background-color: #0056b3;
    }

    .table td {
        white-space: nowrap; /* Mencegah teks dari berpindah baris */
        overflow: hidden; /* Sembunyikan bagian teks yang melebihi batas */
        text-overflow: ellipsis; /* Tambahkan ellipsis jika teks terpotong */
        
    }
</style>

<!DOCTYPE html>
<html>
<head>
    <title>Owner Estimate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <a href="{{ route('drtus.index') }}" class="btn btn-danger" style="float:left; margin-left:150px; margin-top:25px">DRTU</a>
</head>
<body>


<div class="container">
    <h1 style="text-align: center;">Owner Estimate</h1>

    <!-- Add search form here -->
    <div class="d-flex justify-content-between mb-3">
        <!-- Untuk Admin: tombol Create New Order -->
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary" style="width: 150px; height:60px;">Create New Order</a>
        @else
            <!-- Untuk User: tombol Create New Order dinonaktifkan -->
            <button class="btn btn-secondary" style="width: 150px; height:60px;" disabled>Create New Order</button>
        @endif
    
        <!-- Search form -->
        <form action="{{ route('purchase_orders.index') }}" method="GET" class="form-inline">
            <input class="form-control mr-sm-2" type="search" name="search" autocomplete="off" value="{{ $search ?? '' }}" placeholder="Search by ID" aria-label="Search">
            
            <select name="filter_year" class="form-control mr-sm-2">
                <option value="">-</option>
                <option value="2024" {{ request('filter_year') == '2024' ? 'selected' : '' }}>2024</option>
                <option value="2025" {{ request('filter_year') == '2025' ? 'selected' : '' }}>2025</option>
            </select>
    
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
    

    <!-- Wrap table inside a div with scroll -->
    <div class="table-container">
        <table class="table" style="border: 1px solid black; background-color: white;">
            <thead class="table-info">
                <tr>
                    <th style="width:100px">ID</th>
                    <th style="width: 100px;">ID_Tahun</th>
                    <th  style="width: 100px;">Document Date</th>
                    <th style="width: 150px;">No Purchase Requisition</th>  
                    <th style="width: 170px;">Number of Memo Dinas</th>
                    <th>Description</th>
                    <th style="width: 125px;">Penanggung Jawab</th>
                    <th style="width:125px">Actions</th>
                </tr>
            </thead>
            <tbody id="order-table-body">
                @foreach($orders as $order)
                <tr>
                    <td>{{ sprintf('OE%04d', $order->id) }}</td>
                    <td style="max-width: 100px;">{{ $order->id_tahun }}</td>
                    <td style="width: 100px;">{{ $order->doc_date }}</td>
                    <td style="width: 150px;">{{ $order->number_of_purchase_requisition }}</td>
                    <td style="max-width: 170px;">{{ $order->number_of_memo_dinas }}</td>
                    <td style="max-width: 300px;">{{ $order->description }}</td>
                    <td>{{ $order->penanggung_jawab }}</td>
                    <td class="text-center">
                        @if(Auth::user()->role === 'admin')
                            <!-- Admin: Bisa Edit, Cetak, Hapus -->
                            <!-- Edit Button -->
                            <a href="{{ route('purchase_orders.edit', $order->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
            
                            <!-- Print Button -->
                            <a href="{{ route('purchase_orders.pdf', $order->id) }}" class="btn btn-success btn-sm" target="_blank" data-toggle="tooltip" title="Cetak">
                                <i class="bi bi-printer"></i>
                            </a>
            
                            <!-- Delete Button -->
                            <form action="{{ route('purchase_orders.destroy', $order->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus order ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @else
                            <!-- User: Hanya Bisa Cetak -->
                            <!-- Print Button -->
                            <a href="{{ route('purchase_orders.pdf', $order->id) }}" class="btn btn-success btn-sm" target="_blank" data-toggle="tooltip" title="Cetak">
                                <i class="bi bi-printer"></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            
        </table>
    </div>
</div>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>


</body>

<a href="{{ route('welcome2') }}" class="btn btn-secondary floating-button">Kembali</a>


</html>
@endsection
