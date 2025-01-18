@extends('layout')
<title>Edit</title>
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

<div class="container">
    
<a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary floating-button">Kembali</a>

    <h1>Edit OE</h1>
    <form action="{{ route('purchase_orders.update', $purchaseOrder->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="number_of_purchase_requisition">No Purchase Requisition</label>
            <input type="text" class="form-control" id="number_of_purchase_requisition" name="number_of_purchase_requisition" value="{{ $purchaseOrder->number_of_purchase_requisition }}" required>
        </div>

        <div class="form-group">
            <label for="number_of_memo_dinas">No Memo Dinas</label>
            <textarea class="form-control" id="number_of_memo_dinas" name="number_of_memo_dinas" required>{{ $purchaseOrder->number_of_memo_dinas }}</textarea>
        </div>

        <div class="form-group">
            <label for="doc_date">Doc.Date</label>
            <input type="date" class="form-control" id="doc_date" name="doc_date" value="{{ $purchaseOrder->doc_date }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" required>{{ $purchaseOrder->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="penanggung_jawab">Penanggung Jawab</label>
            <select name="penanggung_jawab" id="penanggung_jawab" class="form-control" required>
                <option value="Hasmawati Z" {{ $purchaseOrder->penanggung_jawab == 'Hasmawati Z' ? 'selected' : '' }}>Hasmawati Z</option>
                <option value="Hendriyanto" {{ $purchaseOrder->penanggung_jawab == 'Hendriyanto' ? 'selected' : '' }}>Hendriyanto</option>
                <option value="Ridi Djajakusuma" {{ $purchaseOrder->penanggung_jawab == 'Ridi Djajakusuma' ? 'selected' : '' }}>Ridi Djajakusuma</option>
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
                @foreach($purchaseOrder->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><input type="text" name="items[{{ $index }}][deskripsi]" class="form-control" value="{{ $item->deskripsi }}" required /></td>
                        <td><input type="number" name="items[{{ $index }}][qty]" class="form-control qty" data-index="{{ $index }}" value="{{ $item->qty }}" required /></td>
                        <td><input type="text" name="items[{{ $index }}][satuan]" class="form-control" value="{{ $item->satuan }}" required /></td>
                        <td><input type="text" name="items[{{ $index }}][hps_satuan]" class="form-control hps_satuan" data-index="{{ $index }}" value="{{ $item->hps_satuan }}" required /></td>
                        <td><input type="text" name="items[{{ $index }}][hps_total]" class="form-control hps_total" data-index="{{ $index }}" value="{{ $item->hps_total }}" readonly /></td>
                        <td><input type="text" name="items[{{ $index }}][dasar_acuan_harga]" class="form-control" value="{{ $item->dasar_acuan_harga }}" required /></td>
                        <td><button type="button" class="btn btn-danger remove-item">Hapus</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" class="btn btn-success" id="addItem">Tambah Barang</button>
        <br><br>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        let itemCount = {{ $purchaseOrder->items->count() }};

        // Function to calculate HPS Total
        function calculateHPSTotal(index) {
            let qty = $(`.qty[data-index='${index}']`).val();
            let hpsSatuan = $(`.hps_satuan[data-index='${index}']`).val();
            let hpsTotal = qty * hpsSatuan;

            $(`.hps_total[data-index='${index}']`).val(hpsTotal);
        }

        // Trigger calculation on qty or hps_satuan change
        $(document).on('input', '.qty, .hps_satuan', function() {
            let index = $(this).data('index');
            calculateHPSTotal(index);
        });

        // Add new item
        $('#addItem').click(function () {
            let newRow = `
            <tr>
                <td>${itemCount + 1}</td>
                <td><input type="text" name="items[${itemCount}][deskripsi]" class="form-control" required /></td>
                <td><input type="number" name="items[${itemCount}][qty]" class="form-control qty" data-index="${itemCount}" required /></td>
                <td><input type="text" name="items[${itemCount}][satuan]" class="form-control" required /></td>
                <td><input type="text" name="items[${itemCount}][hps_satuan]" class="form-control hps_satuan" data-index="${itemCount}" required /></td>
                <td><input type="text" name="items[${itemCount}][hps_total]" class="form-control hps_total" data-index="${itemCount}" readonly /></td>
                <td><input type="text" name="items[${itemCount}][dasar_acuan_harga]" class="form-control" required /></td>
                <td><button type="button" class="btn btn-danger remove-item">Hapus</button></td>
            </tr>
            `;
            $('#purchaseOrderItems tbody').append(newRow);
            itemCount++;
        });

        // Remove item
        $(document).on('click', '.remove-item', function () {
            $(this).closest('tr').remove();
            itemCount--;
        });
    });
</script>

@endsection
