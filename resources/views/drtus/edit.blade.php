@extends('layout')
<title>Edit DRTU</title>
@section('konten')
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
    <div class="container">
        <h1>Edit DRTU</h1>

        <form action="{{ route('drtus.update', $drtu->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Input fields for the rest of the columns -->
            <div class="form-group">
                <label for="doc_date">Date</label>
                <input type="date" class="form-control" id="doc_date" name="doc_date" 
                    value="{{ old('doc_date', $drtu->doc_date ? $drtu->doc_date->format('Y-m-d') : '') }}" required>
            </div>

            <div class="form-group">
                <label for="number_of_purchase_requisition">No Purchase Requisition</label>
                <input type="text" class="form-control" id="number_of_purchase_requisition" name="number_of_purchase_requisition" 
                    value="{{ old('number_of_purchase_requisition', $drtu->number_of_purchase_requisition) }}" required>
            </div>

            <div class="form-group">
                <label for="number_of_memo_dinas">No Memo Dinas</label>
                <input type="text" class="form-control" id="number_of_memo_dinas" name="number_of_memo_dinas" 
                    value="{{ old('number_of_memo_dinas', $drtu->number_of_memo_dinas) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description', $drtu->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="metode_pengadaan">Metode Pengadaan</label>
                <select name="metode_pengadaan" id="metode_pengadaan" class="form-control">
                    <option value="Pengadaan Langsung (Pembelian Langsung)" {{ old('metode_pengadaan', $drtu->metode_pengadaan) == 'Pengadaan Langsung (Pembelian Langsung)' ? 'selected' : '' }}>Pengadaan Langsung (Pembelian Langsung)</option>
                    <option value="Penunjukan Langsung" {{ old('metode_pengadaan', $drtu->metode_pengadaan) == 'Penunjukan Langsung' ? 'selected' : '' }}>Penunjukan Langsung</option>
                    <option value="Penunjukan Langsung (Repeat Order)" {{ old('metode_pengadaan', $drtu->metode_pengadaan) == 'Penunjukan Langsung (Repeat Order)' ? 'selected' : '' }}>Penunjukan Langsung (Repeat Oreder)</option>
                    <option value="Tender Terbatas/Seleksi Terbatas (Pemilihan Langsung)" {{ old('metode_pengadaan', $drtu->metode_pengadaan) == 'Tender Terbatas/Seleksi Terbatas (Pemilihan Langsung)' ? 'selected' : '' }}>Tender Terbatas/Seleksi Terbatas (Pemilihan Langsung)</option>
                    <option value="Tender/Seleksi Umum (Pelelangan Umum)" {{ old('metode_pengadaan', $drtu->metode_pengadaan) == 'Tender/Seleksi Umum (Pelelangan Umum)' ? 'selected' : '' }}>Tender/Seleksi Umum (Pelelangan Umum)</option>
                </select>
            </div>

            <!-- Peserta Section -->
            <h4>Peserta</h4>
            <table class="table table-bordered" id="peserta">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Perusahaan</th>
                        <th>PIC</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($drtu->peserta as $index => $peserta)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><input type="text" name="peserta[{{ $index }}][nama_perusahaan]" class="form-control" value="{{ old('peserta.'.$index.'.nama_perusahaan', $peserta->nama_perusahaan) }}" required /></td>
                            <td><input type="text" name="peserta[{{ $index }}][pic]" class="form-control" value="{{ old('peserta.'.$index.'.pic', $peserta->pic) }}" required /></td>
                            <td><input type="tel" name="peserta[{{ $index }}][no_hp]" class="form-control" value="{{ old('peserta.'.$index.'.no_hp', $peserta->no_hp) }}" required /></td>
                            <td><input type="text" name="peserta[{{ $index }}][email]" class="form-control" value="{{ old('peserta.'.$index.'.email', $peserta->email) }}" required /></td>
                            <td><button type="button" class="btn btn-danger remove-item">Hapus</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-success" id="addItem">Tambah Peserta</button>

            <!-- Penanggung Jawab -->
            <div class="form-group mt-3">
                <label for="penanggung_jawab">Penanggung Jawab</label>
                <select name="penanggung_jawab" id="penanggung_jawab" class="form-control">
                    <option value="Hasmawati Z" {{ old('penanggung_jawab', $drtu->penanggung_jawab) == 'Hasmawati Z' ? 'selected' : '' }}>Hasmawati Z</option>
                    <option value="Hendriyanto" {{ old('penanggung_jawab', $drtu->penanggung_jawab) == 'Hendriyanto' ? 'selected' : '' }}>Hendriyanto</option>
                </select>
            </div>

            <!-- Submit button -->
            <a href="{{ route('drtus.index') }}" class="btn btn-secondary floating-button">Kembali</a>
            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Update</button>
        </form>

        <!-- jQuery for adding/removing rows -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                let itemCount = {{ count($drtu->peserta) }};

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
    </div>
@endsection
