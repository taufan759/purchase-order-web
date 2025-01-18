@extends('layout')
<title>Buat DRTU</title>
@section('konten')
    <div class="container">
        <h1>Add New DRTU</h1>
        <style>
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
    .container {
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        margin-top: 50px;}
        </style>

        <form action="{{ route('drtus.store') }}" method="POST">
            @csrf
            <!-- Input fields for the rest of the columns -->
            <div class="form-group">
            <label for="doc_date">Date</label>
            <input type="date" class="form-control" id="doc_date" name="doc_date" required>
        </div>

        <div class="form-group">
            <label for="number_of_purchase_requisition">No Purchase Requisition</label>
            <input type="text" class="form-control" id="number_of_purchase_requisition" name="number_of_purchase_requisition" autocomplete="off" required>
        </div>
        <div class="form-group">
            <label for="number_of_memo_dinas">NO Memo Dinas</label>
            <input type="text" class="form-control" id="number_of_memo_dinas" name="number_of_memo_dinas" autocomplete="off" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="metode_pengadaan">metode pengadaan</label>
                <select name="metode_pengadaan" id="metode_pengadaan" class="form-control">
                    <option value="">-- Pilih Metode --<</option>
                    <option value="Pengadaan Langsung (Pembelian Langsung)">Pengadaan Langsung (Pembelian Langsung)</option>
                    <option value="Penunjukan Langsung">Penunjukan Langsung</option>
                    <option value="Penunjukan Langsung (Repeat Order)">Penunjukan Langsung (Repeat Order)</option>
                    <option value="Tender Terbatas/seleksi Terbatas (Pemilihan Langsung)">Tender Terbatas / Seleksi Terbatas (pemilihan langsung)</option>
                    <option value="Tender/Seleksi Umum (Pelelangan Umum)">Tender/Seleksi Umum (Pelelangan Umum)</option>

                    
                </select>
        </div>
        
        <div class="form-group">
            <label for="penanggung_jawab">Penanggung Jawab</label>
                <select name="penanggung_jawab" id="penanggung_jawab" class="form-control">
                    <option value="">-- Pilih Penanggung Jawab --<</option>
                    <option value="Hasmawati Z">Hasmawati Z</option>
                    <option value="Hendriyanto">Hendriyanto</option>
                </select>
        </div>

        <h4>Peserta</h4>
<form action="{{ route('drtus.store') }}" method="POST">
    @csrf
    <table class="table table-bordered" id="peserta">
        <thead>
            <tr>
                <th>No</th>
                <th>Perusahaan</th>
                <th>PIC</th>
                <th>No Hp</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><input type="text" name="peserta[0][nama_perusahaan]" class="form-control" required /></td>
                <td><input type="text" name="peserta[0][pic]" class="form-control" required /></td>
                <td><input type="tel" name="peserta[0][no_hp]" class="form-control" required /></td>
                <td><input type="text" name="peserta[0][email]" class="form-control" required /></td>
                <td><button type="button" class="btn btn-danger remove-item">Hapus</button></td>
            </tr>
        </tbody>
    </table>

    <button type="button" class="btn btn-success" id="addItem">Tambah Peserta</button>
    <br><br>

    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{ route('drtus.index') }}" class="btn btn-secondary floating-button">Kembali</a>
</form>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        let itemCount = 1;

        $('#addItem').click(function () {
            let newRow = `
            <tr>
                <td>${itemCount + 1}</td>
                <td><input type="text" name="peserta[${itemCount}][nama_perusahaan]" class="form-control" required /></td>
                <td><input type="text" name="peserta[${itemCount}][pic]" class="form-control" required /></td>
                <td><input type="tel" name="peserta[${itemCount}][no_hp]" class="form-control" required /></td>
                <td><input type="text" name="peserta[${itemCount}][email]" class="form-control" required /></td>
                <td><button type="button" class="btn btn-danger remove-item">Hapus</button></td>
            </tr>
            `;
            $('#peserta tbody').append(newRow);
            itemCount++;
        });

        $(document).on('click', '.remove-item', function () {
            $(this).closest('tr').remove();

            // Update nomor baris setelah penghapusan
            $('#peserta tbody tr').each(function (index) {
                $(this).find('td:first').text(index + 1);
            });

            itemCount--;
        });
    });
</script>

@endsection


       


            

