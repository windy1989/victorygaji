@php
    use App\Helpers\CustomHelper;
@endphp
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>{{ $title }}</title>
		<style>
			body {
				font-family:"Calibri", sans-serif;
                font-size:12px;
            }

            #table-header{
                margin-top:25px;
                font-weight: 800;
            }

            table {
                border-collapse:collapse;
            }

            td {
                padding: 0px !important;
            }

            @page { margin: 1cm 0cm 1cm 0cm; }
            
            hr {
                background-color: black;
                border:none;
            }
            
            .table-content {
                font-size:15px;
                line-height: 1.8;
            }

            .table-content-body {
                font-size:15px;
                line-height: 1.8;
                text-align: justify;
            }

            #table-detail th {
                padding:5px;
            }

            #table-detail > tbody > tr > td {
                padding:25px 5px 25px 5px !important;
            }
		</style>
	</head>
	<body>
		<div class="invoice-box">
            <img src="{{ public_path('storage/sign/footer.png') }}" width="50px" height="auto" style="position:fixed;bottom:30px;margin:auto;">
			<table id="table-header" cellpadding="0" cellspacing="0" width="100%">
				<tr>
                    <td align="right" width="25%">
                        <img src="{{ public_path('assets/images/square_logo.png') }}" width="125px" height="auto" style="margin-left:65px;position:absolute;">
                    </td>
                    <td width="75%">
                        <div style="font-size:25px;">CV. VICTORY KONSULTAN</div>
                        <div style="font-size:13px;">
                            <br>STUDY KELAYAKAN, REKAYASA, EVALUASI DAN MANAJEMEN
                            <br>KANTOR PUSAT : PERUM. GRAHA KOTA D 12 NO. 20 SUKO - SIDOARJO
                            <br>Telp.Fax : 031 - 51517878
                            <br>Email : victorykonsultan@gmail.com
                            <br>Web : victorykonsultan.com
                        </div>
                    </td>
                </tr>
			</table>
            <br>
            <br>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;">
				<tr>
                    <td width="60%">
                        <table class="table-content" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td width="25%" style="vertical-align: top;">Nomor</td>
                                <td width="1%" style="vertical-align: top;">:</td>
                                <td width="74%" style="vertical-align: top;">{{ $data->code }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Perihal</td>
                                <td style="vertical-align: top;">:</td>
                                <td style="vertical-align: top;">Penawaran Penyusunan Andalalin Jalan {{ $data->type_road }}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="40%" align="center">
                        Sidoarjo, {{ CustomHelper::tgl_indo($data->post_date) }}
                        <br>
                        Kepada Yth,
                        <br>
                        {{ $data->to_name }}
                        <br>
                        <div align="left" style="margin-left:50px;">Di -</div>
                        <b><u>tempat</u></b>
                    </td>
                </tr>
			</table>
            <br>
            <table class="table-content-body" cellpadding="0" cellspacing="0" width="75%" style="margin:auto;">
				<tr>
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dengan Hormat,
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bedasarkan Undang-undang nomor 22 tahun 2009 tentang Lalu Lintas dan Angkutan
                        Jalan, dan Peraturan Menteri nomor 17 Tahun 2021 tentang Penyelenggaraan Analisis Dampak
                        Lalu Lintas (ANDALALIN), dan kebutuhan untuk perijinan ANDALALIN, maka bersama ini kami
                        sampaikan penawaran pekerjaan penyusunan laporan Andalalin untuk jenis bangunan
                        {{ $data->type_building }} yang terletak di {{ $data->location_building }} yang merupakan Jalan {{ $data->type_road }}
                        Kami mengusulkan biaya sebesar Rp {{ number_format($data->project->cost,0,',','.') }},- ( {{ CustomHelper::terbilangWithKoma($data->project->cost) }} Rupiah ), include
                        PNBP, Exclude Pajak dengan rincian pekerjaan sebagai berikut :
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <ol>
                            <li>Biaya Survey Lapangan Tata Guna lahan dan Pemetaan (Mapping dan Planologi Wilayah dengan Menggunakan Drone)</li>
                            <li>Biaya Survey Lalu lintas yang terdiri dari Persimpangan dan Ruas Jalan.</li>
                            <li>Biaya Survey Angkutan Umum angkutan jalan.</li>
                            <li>Biaya Survey Kecepatan Kendaraan dan Tingkat Kebisingan Lalu Lintas.</li>
                            <li>Biaya Rekapitulasi hasil Survey.</li>
                            <li>Biaya Analisis dan Evaluasi Data ( Analisis dan Simulasi Manajemen dan Rekayasa Lalu Lintas ).</li>
                            <li>Biaya Gambar 3D beserta simulasi Gerak sirkulasi lalu lintas dan perambuan lalu lintas.</li>
                            <li>Biaya Pembuatan Laporan dan Dokumentasi.</li>
                            <li>Biaya Tinjau Lapang.</li>
                            <li>Biaya Penggandaan Laporan.</li>
                            <li>Surat Rekomendasi Andalalin.</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td>
                        Adapun mekanisme pembayarannya adalah sebagai berikut :
                        <ol>
                            <li>DP sebesar 30% sejak Surat perintah kerja (SPK) diterbitkan,</li>
                            <li>Tahap 2 sebesar 50% ketika laporan sudah selesai dan siap untuk disidangkan</li>
                            <li>Tahap 3 sebesar 20% ketika surat Rekomendasi sudah keluar dan diturunkan.</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Waktu Pelaksanaan Pekerjaan penyusunan dokumen hingga siap untuk disahkan (sampai dokumen siap di ajukan ke Instansi Terkait) adalah 60 (anam puluh) hari kerja sejak kami menerima DP dan berkas-berkas persyaratan Andalalin dengan lengkap, yaitu Akta Pendirian, Akta Perubahan (jika ada), Ijin Lokasi/SKRK/PKKPR, KTP, NPWP, Akta tanah/bukti kepemilikan/penguasaan lahan dan gambar siteplan.
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Untuk informasi lebih lanjut, dapat menghubungi/Whatsapp ke nomor 08113000902 (Deddy) atau ke nomor 081330212393 (Chichi).
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikian penawaran dari kami, Semoga kita dapat bekerjasama dengan baik dan atas perhatian serta kerjasamanya kami ucapkan Terima kasih.
                    </td>
                </tr>
			</table>
            <br>
            <br>
            <table class="table-content" id="table-detail" cellpadding="0" cellspacing="0" width="90%"style="margin:auto;font-weight:700;">
                <tbody>
                    <tr>
                        <td align="center" width="60%">
                            
                        </td>
                        <td align="center" width="40%">
                            <img src="{{ public_path('storage/sign/sign_and_logo.png') }}" width="250px" height="auto" style="margin:20px 0 0 15px;position:absolute;z-index:-1;">
                            CV. VICTORY KONSULTAN
                            <br><br><br><br><br>
                            <u>.DEDDY CHRISTIANTO., S.T.</u>
                            <br>Direktur
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
	</body>
</html>