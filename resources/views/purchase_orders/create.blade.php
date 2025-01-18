@extends('layout')
<title>Tambah OE</title>
@section('konten')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<style>
    body {
        background: rgb(244,244,244);
        background: linear-gradient(191deg, rgba(244,244,244,1) 0%, rgba(88,140,201,0.9612219887955182) 76%);
    }
</style>

<div class="container">
<a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary floating-button">Kembali</a>

<h1 style="font-style: italic; text-align:center">Create OE</h1>
    <form action="{{ route('purchase_orders.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="doc_date">Doc.Date</label>
            <input type="date" class="form-control" id="doc_date" name="doc_date" required>
        </div>

        <div class="form-group">
            <label for="number_of_purchase_requisition">No Purchase Requisition</label>
            <input type="text" class="form-control" id="number_of_purchase_requisition" name="number_of_purchase_requisition" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label for="number_of_memo_dinas">No Memo Dinas</label>
            <textarea class="form-control" id="number_of_memo_dinas" name="number_of_memo_dinas" required></textarea>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>

        <div class="form-group">
            <label for="penanggung_jawab">Penanggung Jawab</label>
            <select name="penanggung_jawab" id="penanggung_jawab" class="form-control">
                <option value="">-- Pilih Penanggung Jawab --</option>
                <option value="Hasmawati Z">Hasmawati Z</option>
                <option value="Hendriyanto">Hendriyanto</option>
                <option value="Ridi Djajakusuma">Ridi Djajakusuma</option>
            </select>
        </div>

        <h4>Item Barang</h4>
        <table class="table table-bordered" id="purchaseOrderItems">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>HPS Satuan (Rp)</th>
                    <th>HPS Total (Rp)</th>
                    <th>Dasar Acuan Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><input type="text" name="items[0][deskripsi]" class="form-control" required /></td>
                    <td><input type="number" name="items[0][qty]" class="form-control qty-input" required /></td>
                    <td><input type="text" name="items[0][satuan]" class="form-control" required /></td>
                    <td><input type="number" name="items[0][hps_satuan]" class="form-control hps-satuan-input" required /></td>
                    <!-- HPS Total akan dihitung secara otomatis -->
                    <td><input type="text" class="form-control hps-total-preview" readonly /></td>
                    <td><input type="text" name="items[0][dasar_acuan_harga]" class="form-control" required /></td>
                    <td><button type="button" class="btn btn-danger remove-item">Hapus</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-success" id="addItem">Tambah Barang</button>
        <br><br>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        let itemCount = 1;

        // Tambah baris item baru
        $('#addItem').click(function () {
            let newRow = `
            <tr>
                <td>${itemCount + 1}</td>
                <td><input type="text" name="items[${itemCount}][deskripsi]" class="form-control" required /></td>
                <td><input type="number" name="items[${itemCount}][qty]" class="form-control qty-input" required /></td>
                <td><input type="text" name="items[${itemCount}][satuan]" class="form-control" required /></td>
                <td><input type="number" name="items[${itemCount}][hps_satuan]" class="form-control hps-satuan-input" required /></td>
                <td><input type="text" class="form-control hps-total-preview" readonly /></td>
                <td><input type="text" name="items[${itemCount}][dasar_acuan_harga]" class="form-control" required /></td>
                <td><button type="button" class="btn btn-danger remove-item">Hapus</button></td>
            </tr>
            `;
            $('#purchaseOrderItems tbody').append(newRow);
            itemCount++;
        });

        // Hapus baris item
        $(document).on('click', '.remove-item', function () {
            $(this).closest('tr').remove();
            itemCount--;
        });

        // Menghitung HPS Total saat QTY atau HPS Satuan diubah
        $(document).on('input', '.qty-input, .hps-satuan-input', function () {
            let row = $(this).closest('tr');
            let qty = row.find('.qty-input').val();
            let hpsSatuan = row.find('.hps-satuan-input').val();
            if (qty && hpsSatuan) {
                let hpsTotal = qty * hpsSatuan;
                row.find('.hps-total-preview').val(hpsTotal);
            } else {
                row.find('.hps-total-preview').val('');
            }
        });
    });
</script>
@endsection
