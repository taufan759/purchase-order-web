<!DOCTYPE html>
<html>
<head>
    <title>Owner Estimate</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">

</head>
<style>
    body, html {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: white;
    color: black;
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
  float: left;
  width: 33.33%;
  padding: 5px;
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
    padding: 5px;
}

.data-table th {
    background-color: none;
}

.footer {
    text-align: center;
    margin-top: 100px auto;
    float: right;
    
}

</style>
<body>
    <div class="container">
        <div class="header">
                <div class="row">
                    <div class="column">
                        <img src="{{asset('img/logokjs.png')}}" alt="Krakatau Stevadoring & Equipment" class="logo" style="width: 150px; flex:content;">                
                    </div>
                    <div class="column" style="padding-left: 250px; flex:content;">
                        <img src="{{asset('img/logosmk3.png')}}" alt="Logo SMK" class="logo" style="width: 50px; float:left;"> 
                   <img src="{{asset('img/logobsi.jpg')}}" alt="Logo BSI" class="logo" style="width: 100px; float:left">
                   </div>
                </div>           
                    <h1 style="text-decoration: underline; font-size: 20px;">OWNER ESTIMATE (OE) / HARGA PERKIRAAN SENDIRI (HPS)</h1>
        </div>

        <!-- Purchase Order Information -->
        <table style="font-size: 15px;">
            <tr>
                <td>Doc. Date</td>
                <td>: {{ $purchaseOrder->doc_date }}</td>
            </tr>
            <tr>
                <td>Number of Purchase Requisition</td>
                <td>: {{ $purchaseOrder->number_of_purchase_requisition }}</td>
            </tr>
            <tr>
                <td>Number of Memo Dinas</td>
                <td>: {{ $purchaseOrder->number_of_memo_dinas }}</td>
            </tr>
        </table>

        <!-- Description Section -->
        <div class="description" style="font-size: 15px;">
            <p><b>Description</b></p>
            <p>{{ $purchaseOrder->description }}</p>
        </div>

        <!-- Items Table -->
        <table class="data-table" style="font-size: 15px;">
            <thead>
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
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>{{ number_format($item->hps_satuan, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->hps_total, 0, ',', '.') }}</td>
                    <td>{{ $item->dasar_acuan_harga }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer Section -->
        
       <!-- Footer Section -->
<table class="footer" style="font-size: 15px;">
    <tr>
        <th style="text-align: center;" class="footer">
            <p style="margin-bottom: 70%; margin-top: 20px; font-weight: normal;">Disetujui Oleh,</p>
            <p style="margin-bottom: auto; text-decoration: underline; font-style: italic;">{{ $purchaseOrder->penanggung_jawab }}</p>
            @if($purchaseOrder->penanggung_jawab == 'Hasmawati Z')
                <p style="margin-bottom: auto; font-weight: normal;">Kadiv Pengadaan</p>
            @elseif($purchaseOrder->penanggung_jawab == 'Hendriyanto')
                <p style="margin-bottom: auto; font-weight: normal;">Kadis Pengadaan</p>
            @else
                <p style="margin-bottom: auto; font-weight: normal;">Direktur Utama</p>
            @endif
        </th>
    </tr>
</table>
<table class="footer" style="font-size: 15px;">
    <tr>
        <th style="text-align: center;" class="footer">
            <p style="margin-bottom: 70%; margin-top: 20px; font-weight: normal;">Disetujui Oleh,</p>
            <p style="margin-bottom: auto; text-decoration: underline; font-style: italic;">Ridi Djajakusuma</p> 
            <p style="font-weight: normal; margin-bottom: -5px">Perencanaan</p>
            <p style="font-weight: normal; margin-bottom: 0px;">Pengadaan</p>
        </th>
    </tr>
</table>




        <div class="print-button">
            <button onclick="window.print()">Cetak</button>
            <a href="{{ route('purchase_orders.index') }}" class="btn">Kembali</a>
        </div>
    </div>
</body>
</html>
