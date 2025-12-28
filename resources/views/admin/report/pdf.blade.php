<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Keuangan KoopEase</title>

    <style>
        /* Import Font Nunito */
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');

        @page {
            margin: 1cm 1.5cm;
            margin-top: 3cm;
            margin-bottom: 2cm;
        }

        body {
            /* Pastikan Nunito menjadi prioritas, dengan fallback sans-serif */
            font-family: 'Nunito', sans-serif;
            font-size: 10px;
            color: #334155;
            line-height: 1.5;
        }

        /* --- HEADER (KOP SURAT) --- */
        header {
            position: fixed;
            top: -2.2cm;
            left: 0;
            right: 0;
            height: 2.2cm;
            border-bottom: 1px solid #3b82f6;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Nunito', sans-serif;
            /* Paksa font di header */
        }

        .header-logo {
            width: 20%;
            vertical-align: top;
            padding-top: 5px;
        }

        .header-logo h1 {
            margin: 0;
            color: #000000;
            font-size: 22px;
            font-weight: 800;
            /* ExtraBold */
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .header-text {
            width: 80%;
            vertical-align: top;
            text-align: right;
            /* Header text tetap kanan agar rapi layoutnya */
            padding-top: 5px;
        }

        .header-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            color: #3b82f6;
            margin-bottom: 2px;
            letter-spacing: 1px;
        }

        .header-subtitle {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 2px;
        }

        .header-address {
            font-size: 8px;
            color: #94a3b8;
        }

        /* --- FOOTER --- */
        footer {
            position: fixed;
            bottom: -1.5cm;
            left: 0;
            right: 0;
            height: 1.2cm;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }

        .footer-table {
            width: 100%;
            font-family: 'Nunito', sans-serif;
        }

        .page-number:before {
            content: counter(page);
        }

        /* --- CONTENT STYLING --- */

        /* Meta Info Box */
        .meta-info {
            margin-bottom: 25px;
            padding: 12px 15px;
            background-color: #f8fafc;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        /* New Stats Table Design */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            /* Border radius trick for tables in PDF */
            border-radius: 8px;
            overflow: hidden;
        }

        .stats-table td {
            width: 33.33%;
            padding: 20px 25px;
            border-right: 1px solid #f1f5f9;
            vertical-align: middle;
            text-align: left;
            /* Rata Kiri */
        }

        .stats-table td:last-child {
            border-right: none;
        }

        .stat-label {
            display: block;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .stat-value {
            display: block;
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .stat-value.primary {
            color: #3b82f6;
        }

        /* Section Titles */
        h2.section-title {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 12px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Data Tables */
        table.clean-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        table.clean-table th {
            background-color: #f8fafc;
            color: #334155;
            padding: 10px 12px;
            text-align: left;
            /* Rata Kiri */
            text-transform: uppercase;
            font-size: 8px;
            font-weight: 800;
            border-bottom: 1px solid #e2e8f0;
        }

        table.clean-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
            vertical-align: top;
            text-align: left;
            /* Rata Kiri Semua Kolom */
        }

        table.clean-table tr:last-child td {
            border-bottom: none;
        }

        /* Utilities Override */
        /* Memaksa text-right menjadi text-left sesuai permintaan */
        .text-right {
            text-align: left !important;
        }

        .text-center {
            text-align: left !important;
        }

        .text-bold {
            font-weight: 700;
        }

        .text-green {
            color: #10b981;
        }

        .text-red {
            color: #ef4444;
        }

        .text-blue {
            color: #3b82f6;
        }

        .row-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .col-50 {
            width: 48%;
            vertical-align: top;
        }

        .col-spacer {
            width: 4%;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            background-color: #f1f5f9;
            color: #475569;
        }
    </style>
</head>

<body>

    <header>
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <h1>KOOPEASE</h1>
                </td>
                <td class="header-text">
                    <div class="header-title">LAPORAN KEUANGAN</div>
                    <div class="header-subtitle">Koperasi Digital Mahasiswa Telkom University Surabaya</div>
                    <div class="header-address">
                        Jl. Ketintang No.156, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table class="footer-table">
            <tr>
                <td width="70%" style="text-align: left;">
                    <i>Dokumen ini dibuat secara otomatis oleh sistem KoopEase. Valid tanpa tanda tangan basah.</i><br>
                    &copy; 2025 KoopEase Financial Report System.
                </td>
                <td width="30%" style="text-align: right;" valign="top">
                    Dicetak oleh: <b>{{ $generatorName }}</b><br>
                    Halaman <span class="page-number"></span>
                </td>
            </tr>
        </table>
    </footer>

    <div class="meta-info">
        <table width="100%">
            <tr>
                <td style="border: none; width: 50%; text-align: left;">
                    <span style="color: #64748b; font-size: 8px; text-transform: uppercase; font-weight: 700;">Periode
                        Laporan</span><br>
                    <span style="font-size: 12px; font-weight: 800; color: #0f172a;">
                        {{ $startDate->format('d F Y') }} &mdash; {{ $endDate->format('d F Y') }}
                    </span>
                </td>
                <td style="border: none; width: 50%; text-align: left;">
                    <span style="color: #64748b; font-size: 8px; text-transform: uppercase; font-weight: 700;">Waktu
                        Cetak</span><br>
                    <span style="font-size: 11px; font-weight: 600; color: #0f172a;">
                        {{ now()->format('d F Y, H:i') }} WIB
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <table class="stats-table">
        <tr>
            <td>
                <span class="stat-label">Total Pendapatan</span>
                <span class="stat-value primary">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="stat-label">Total Transaksi</span>
                <span class="stat-value">{{ number_format($stats['total_transactions']) }} <span
                        style="font-size:10px; font-weight:400; color:#94a3b8">Kali</span></span>
            </td>
            <td>
                <span class="stat-label">Produk Terjual</span>
                <span class="stat-value">{{ number_format($stats['items_sold']) }} <span
                        style="font-size:10px; font-weight:400; color:#94a3b8">Unit</span></span>
            </td>
        </tr>
    </table>

    <table class="row-table">
        <tr>
            <td class="col-50">
                <h2 class="section-title">Ringkasan Pesanan</h2>
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>Status Pesanan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Menunggu Pembayaran</td>
                            <td class="text-bold">{{ $statusSummary['menunggu_pembayaran'] }}</td>
                        </tr>
                        <tr>
                            <td>Menunggu Konfirmasi</td>
                            <td class="text-bold">{{ $statusSummary['menunggu_konfirmasi'] }}</td>
                        </tr>
                        <tr>
                            <td>Siap Diambil</td>
                            <td class="text-bold">{{ $statusSummary['siap_diambil'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-green text-bold">Selesai</td>
                            <td class="text-green text-bold">{{ $statusSummary['selesai'] }}</td>
                        </tr>
                        <tr>
                            <td class="text-red">Dibatalkan</td>
                            <td class="text-red">{{ $statusSummary['dibatalkan'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <td class="col-spacer"></td>

            <td class="col-50">
                <h2 class="section-title">Top 5 Produk</h2>
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jual</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $prod)
                            <tr>
                                <td>{{ Str::limit($prod->nama_produk_saat_pesan, 20) }}</td>
                                <td><span class="badge">{{ $prod->total_qty }}</span></td>
                                <td>{{ number_format($prod->total_sales, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="font-style:italic">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div style="margin-top: 10px;">
        <h2 class="section-title">Rincian Transaksi Selesai</h2>
        <table class="clean-table">
            <thead>
                <tr>
                    <th width="15%">Tanggal</th>
                    <th width="15%">ID Order</th>
                    <th width="20%">Pelanggan</th>
                    <th width="30%">Item Produk</th>
                    <th width="20%">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($finishedOrders as $order)
                    <tr>
                        <td>
                            <span
                                style="font-weight: 700; color: #0f172a;">{{ $order->created_at->format('d/m/Y') }}</span><br>
                            <span style="color: #94a3b8; font-size: 8px;">{{ $order->created_at->format('H:i') }}
                                WIB</span>
                        </td>
                        <td style="font-family: monospace; font-weight: 700; color: #64748b;">#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>
                            @foreach ($order->orderItems as $item)
                                <div style="margin-bottom: 2px;">
                                    <span style="color:#3b82f6; font-weight:700;">{{ $item->quantity }}x</span>
                                    {{ $item->product->nama_produk ?? 'Produk Dihapus' }}
                                </div>
                            @endforeach
                        </td>
                        <td class="text-bold" style="color: #0f172a;">
                            {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 30px; color: #94a3b8; text-align: center !important;">
                            Tidak ada data transaksi selesai pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>
