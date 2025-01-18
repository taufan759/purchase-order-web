<!DOCTYPE html>
<html>
<head>
    <title>DRTU</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
</head>
<style>
    body, html {
    font-family: 'Times New Roman', Times, serif;
    margin: 0;
    padding: 0;
    background: white;
    color: black;
}
.header h1 {
    margin-bottom: 0; /* Menghilangkan margin bawah */
    text-decoration: underline;
}

.header p {
    margin-top: 0; /* Menghilangkan margin atas */
}


.container {
    width: 90%;
    margin: 20px auto;
    padding: 20px;
}

.header {
    text-align: center;
}

.logo {
    display: block;
    margin: 0 auto;
    margin-top: auto;
}
/* Three image containers (use 25% for four, and 50% for two, etc) */
.column {
    display: inline-block; /* mengganti dari float menjadi inline-block untuk kontrol yang lebih baik */
    width: auto; /* lebar otomatis berdasarkan konten */
    padding: 5px; /* padding untuk tidak terlalu berdesakan */
    vertical-align: middle; /* menyelaraskan elemen d*/
}

/* Clear floats after image containers */
.row::after {
  content: "";
  clear: both;
  display: table;
}


.info-table, .data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.info-table td, .data-table th, .data-table td {
    border: 1px solid black;
    padding: 8px;
}

.description {
    margin-top: 5px;
    border: 1px solid black;
    padding: 5px; /* Berikan ruang di dalam border */
    width: 98.3%; /* Pastikan deskripsi mengambil seluruh lebar yang tersedia */
    box-sizing: border-box; /* Pastikan padding tidak mengurangi lebar elemen */
}

.description-content {
    white-space: normal; /* Mengizinkan teks terbungkus */
    word-wrap: break-word; /* Bungkus kata yang panjang */
    margin-top: -10px ; /* Hindari margin negatif */
    
    text-indent: 10px;
    
    font-size: 13px;
}

    
.data-table th {
    background-color: none;
}

.footer {
    text-align: center;
    margin-top: 100px auto;
    float: right;
    
}

/* Add word-wrap to handle long text */
table td {
    word-wrap: break-word;
    white-space: normal; /* Ensure text wraps */
    vertical-align: top; /* Align text to the top */
}

/* Set a max-width for the Number of Purchase Requisition and Memo Dinas fields */
td:nth-child(2), td:nth-child(3) {
    max-width: 200px; /* Adjust based on your needs */
    word-wrap: break-word;
    white-space: normal;
}
@page :first {
  margin: 0;
  margin-bottom: 3cm;
  size: letter-potrait;
}
@page:even {
    margin:0cm;
    margin-top: 2cm;
    margin-bottom: 3cm;
}
@page:odd{
    margin: 0;
    margin-top: 2cm;
    margin-bottom: 3cm;
}

</style>
<body>
<div class="container">
    <div class="header">
        <!-- Header Section -->
        <div class="row">
            <div class="column">
                <img src="{{('img/logokjs.png')}}" alt="Krakatau Stevadoring & Equipment" class="logo" style="width: 250px;">
            </div>
            <div class="column" style="text-align:center;">
                <img src="{{('img/logosmk3.png')}}" alt="Logo SMK" class="logo" style="width: 50px; display:inline; margin-top: 50px;">
                <img src="{{('img/logobsi.jpg')}}" alt="Logo BSI" class="logo" style="width: 150px; display:inline;">
            </div>
        </div>
        <div class="header">
            <h1 style="margin-bottom: 0; font-size: 20px; text-align: center; margin-top: -10px">DAFTAR REKANAN TERUNDANG (DRTU)</h1>
            <p style="text-align: center; font-size: 13px; margin-top: 0;">
                        Nomor : DRTU{{ substr($drtu->id_tahun, -4) }}
                    </p>
        </div>
    </div>

    <!-- DRTU -->
    <table style="font-size: 15px;">
        <tr>
            <td style="width: 150px;">Doc. Date</td>
            <td>: {{ \Carbon\Carbon::parse($drtu->doc_date)->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td style="width: 150px;">No Purchase Requisition</td>
            <td style="max-width: 200px; word-wrap: break-word;">: {{ $drtu->number_of_purchase_requisition }}</td>
        </tr>
        <tr>
            <td style="width: 150px;">No Memo Dinas</td>
            <td style="max-width:none; word-wrap: break-word;">: {{ $drtu->number_of_memo_dinas }}</td>
        </tr>
        <tr>
            <td style="width: 150px;">Metode Pengadaan</td>
            <td style="max-width:none; word-wrap: break-word;">: {{ $drtu->metode_pengadaan }}</td>
        </tr>
    </table>

    <!-- Description Section -->
    <div class="description" style="font-size: 15px;">
        <p style="margin-top: 0;"><b>Description</b></p>
        <div class="description-content">
            <p style="margin-left: 10px; margin-top:auto;">{{ $drtu->description }}</p>
        </div>
    </div>

    <!-- Peserta Section -->
    <table class="data-table" style="margin-top: 7px; font-size: 15px; width: 100%;">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 200px;">Nama Perusahaan</th>
                <th>PIC</th>
                <th style="width:fit-content;">No HP</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody style="font-size: 13px;">
            @foreach($drtu->peserta as $index => $peserta)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $peserta->nama_perusahaan }}</td>
                <td style="text-align: center;width:100px;">{{ $peserta->pic }}</td>
                <td style="text-align: center; width: 100px;max-width:100px;">{{ $peserta->no_hp }}</td>
                <td style="max-width:150px;">{{ $peserta->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer Section -->
    <table class="footer" style="font-size: 15px; margin-right:20px;">
        <tr>
            <th style="text-align: center;" class="footer">
                <p style="margin-bottom: 70%; margin-top: 20px; font-weight: normal;">Disetujui Oleh,</p>
                <p style="margin-bottom: none; text-decoration: underline; font-style: italic;">{{ $drtu->penanggung_jawab }}</p>
                @if($drtu->penanggung_jawab == 'Hasmawati Z')
                    <p style="margin-top: 0px; font-weight: normal; font-size: 13px;">Kadiv Pengadaan</p>
                @elseif($drtu->penanggung_jawab == 'Hendriyanto')
                    <p style="margin-top: 0px; font-weight: normal; font-size: 13px;">Kadis Pengadaan</p>
                @else($drtu->penanggung_jawab == 'Ridi Djajakusuma')
                    <p style="margin-top: 0px; font-weight: normal; font-size: 13px;">Direktur Utama</p>
                @endif
            </th>
        </tr>
    </table>        

    </table>        
        <table class="footer" style="font-size: 15px; margin-right:40px; float:right;">
    <tr>
        <th style="text-align: center;" class="footer">
            <p style="margin-bottom: 70%; margin-top: 20px; font-weight: normal;">Disiapkan Oleh,</p>
            
            @if($drtu->penanggung_jawab == 'Hasmawati Z')
                <p style="margin-top: 80.6px; font-weight: normal; max-width:100px; font: size 13px;"><span style="text-decoration: overline;">Perencanaan</span>Pengadaan</p>
             @elseif($drtu->penanggung_jawab == 'Ridi Djajakusuma')
                <p style="margin-top: 95.6px; font-weight: normal; max-width:100px; font: size 13px;"><span style="text-decoration: overline;">Perencanaan</span>Pengadaan</p>
               @else($drtu->penanggung_jawab == 'Hendriyanto')
                <p style="margin-top: 80.6px; font-weight: normal; max-width:100px; font: size 13px;"><span style="text-decoration: overline;">Perencanaan</span>Pengadaan</p>

                @endif

        </th>
    </tr>
</table>        
</div>

</body>
</html>