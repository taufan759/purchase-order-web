<!DOCTYPE html>
<html>
<head>
    <title>Owner Estimate</title>
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
    
    
    
    font-size: 13px;
}

    
.data-table th {
    background-color: none;
}

.footer {
    text-align: center;
    
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
 /* Mencegah halaman terputus di tengah baris tabel */
 tr {
        page-break-inside:avoid;
        page-break-after:auto;
    }

    table {
        page-break-inside:auto;
    }
@page :first {
  margin: 0;
  margin-bottom: 2cm;
  size: letter-potrait;
}
@page:even {
    margin:0;
    margin-top: 2cm;
    margin-bottom: 3cm;
}
@page:odd{
    margin: 0;
    margin-top: 2cm;
    margin-bottom: 3cm;
}
table.data-table thead {
    display: table-row-group; /* Ini mencegah header muncul di halaman selanjutnya */
}



</style>
<body>
<div class="container">
<div class="header">
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
                    <h1 style="margin-bottom: 0; font-size: 20px; text-align: center; margin-top: -10px">OWNER ESTIMATE (OE) / HARGA PERKIRAAN SENDIRI (HPS)</h1>
                    <p style="text-align: center; font-size: 15px; margin-top: 0;">
                        Nomor : OE{{ substr($purchaseOrder->id_tahun, -4) }}
                    </p>
</div>


                </div>
                

        <!-- Purchase Order Information -->
<table style="font-size: 15px;">
    <tr>
        <td style="width: 150px;">Doc. Date</td>
        <td>: {{ $purchaseOrder->doc_date }}</td>
    </tr>
    <tr>
        <td style="width: 150px;">Number of Purchase Requisition</td>
        <td style="max-width: 200px; word-wrap: break-word;">: {{ $purchaseOrder->number_of_purchase_requisition }}</td>
    </tr>
    <tr>
        <td style="width: 150px;">Number of Memo Dinas</td>
        <td style="max-width:none; word-wrap: break-word;">: {{ $purchaseOrder->number_of_memo_dinas }}</td>
    </tr>
</table>

        <!-- Description Section -->
        <div class="description" style="font-size: 15px;">
    <p style="margin-top: 5px;"><b>Description</b></p>
    <div class="description-content">
        <p style="margin-left: 10px; margin-top:auto;margin-bottom:2.5px;">{!! nl2br(e($purchaseOrder->description)) !!}</p>
    </div>
</div>





        <!-- Items Table -->
        <table class="data-table" style="font-size: 13px; margin-top:7px">
            <thead style="border: 1px solid black;">
                <tr>
                    <th>No</th>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>HPS Satuan (Rp)</th>
                    <th>HPS Total (Rp)</th>
                    <th>Dasar Acuan Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $key => $item)
                <tr>
                    <td style="text-align: center;">{{ $key + 1 }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ $item->qty }}</td>
                    <td style="text-align: center;">{{ $item->satuan }}</td>
                    <td style="text-align: center;">{{ number_format($item->hps_satuan, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ number_format($item->hps_total, 0, ',', '.') }}</td>
                    <td>{{ $item->dasar_acuan_harga }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer Section -->
        <footer >
            <table  class="footer"  style="font-size: 15px; margin-right:20px; ">
    <tr>
        <th style="text-align: center;" class="footer" >
            <p style="margin-bottom: 70%; margin-top: 10px; font-weight: normal; ">Disetujui Oleh,</p>
            <p style="margin-bottom: none; text-decoration: underline; font-style: italic;">{{ $purchaseOrder->penanggung_jawab }}</p>
            @if($purchaseOrder->penanggung_jawab == 'Hasmawati Z')
                <p style="margin-top: 0px; font-weight: normal; font-size: 13px;">Kadiv Pengadaan</p>
            @elseif($purchaseOrder->penanggung_jawab == 'Hendriyanto')
                <p style="margin-top: 0px; font-weight: normal; font-size: 13px; ">Kadis Pengadaan</p>
            @else($purchaseOrder->penanggung_jawab == 'Ridi Djajakusuma')
                <p style="margin-top: 0px; font-weight: normal; font-size: 13px;">Direktur Utama</p>
            @endif
        </th>
    </tr>
</table>        
        <table class="footer" style="font-size: 15px; margin-right:40px; float:right;  ">
    <tr>
        <th style="text-align: center;" class="footer" >
            <p style="margin-bottom: 70%; margin-top: 10px; font-weight: normal; ">Disiapkan Oleh,</p>
            
            @if($purchaseOrder->penanggung_jawab == 'Hasmawati Z')
                <p style="margin-top: 80.6px; font-weight: normal; max-width:100px; font-size:13px; margin-bottom: -3px"><span style="text-decoration: overline; font-size:13px">Perencanaan</span>Pengadaan</p>
             @elseif($purchaseOrder->penanggung_jawab == 'Ridi Djajakusuma')
                <p style="margin-top: 95.6px; font-weight: normal; max-width:100px; font-size:13px;"><span style="text-decoration: overline; font-size:13px">Perencanaan</span>Pengadaan</p>
               @else($purchaseOrder->penanggung_jawab == 'Hendriyanto')
                <p style="margin-top: 80.6px; font-weight: normal; max-width:100px; font-size:13px; margin-bottom: -3px"><span style="text-decoration: overline; font-size:13px">Perencanaan</span>Pengadaan</p>

                @endif

        </th>
    </tr>
</table>        

        </footer>
    </div>
</body>
</html>
