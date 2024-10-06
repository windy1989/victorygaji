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

            @page { margin: 1cm 2cm 1cm 2cm; }
            
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
			<table id="table-header" cellpadding="0" cellspacing="0" width="100%" style="position:fixed;top:-25px;">
				<tr>
                    <td width="25%">
                        <img src="{{ public_path('assets/images/square_logo.png') }}" width="100px" height="auto" style="margin-left:65px;position:absolute;">
                    </td>
                    <td width="50%">
                        
                    </td>
                    <td width="25%">
                        <div style="border:1px solid black;width:100px;height:75px;text-align:center;vertical-align:middle;padding-top:25px;">
                            KOP PERUSAHAAN
                        </div>
                    </td>
                </tr>
			</table>
            <br><br><br><br><br><br>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;">
				<tr>
                    <td width="100%" align="center">
                        <h3>SURAT PERJANJIAN KERJASAMA<br><u>PENYUSUNAN ANDALALIN (ANALISIS DAMPAK LALU LINTAS)</u></h3>
                        No. {{ $data->code }}
                    </td>
                </tr>
			</table>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;">
				<tr>
                    <td width="100%" style="text-align:justify;">
                        Pada hari ini <b><i>{{ CustomHelper::hari($data->post_date) }}</i></b> Tanggal <b><i>{{ date('j',strtotime($data->post_date)) }} ( {{ CustomHelper::terbilang(date('j',strtotime($data->post_date))) }} )</i></b> Bulan <b><i>{{ CustomHelper::bulan($data->post_date) }}</i></b> tahun <b><i>{{ date('Y',strtotime($data->post_date)) }}</i></b>, yang bertanda tangan dibawah ini masing - masing
                    </td>
                </tr>
			</table>
            <br>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;text-align:justify;">
				<tr>
                    <td width="20%">
                        Nama
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td width="79%">
                        {{ $data->name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Alamat
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->address }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Jabatan
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->position }}
                    </td>
                </tr>
                <tr>
                    <td>
                        No. Telepon
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->phone }}
                    </td>
                </tr>
			</table>
            <br>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;">
				<tr>
                    <td width="100%" style="text-align:justify;">
                        Dalam hal ini bertindak dalam jabatan tersebut diatas atas nama {{ $data->name_ref }} selanjutnya disebut sebagai PIHAK KESATU.
                    </td>
                </tr>
			</table>
            <br>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;text-align:justify;">
				<tr>
                    <td width="20%">
                        Nama
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td width="79%">
                        DEDDY CHRISTIANTO., S.T.
                    </td>
                </tr>
                <tr>
                    <td>
                        Jabatan
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        Direktur
                    </td>
                </tr>
                <tr>
                    <td>
                        Alamat Operasional
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        PERUM. Graha Kota Blok D 12 No. 20 Suko - Sidoarjo
                    </td>
                </tr>
			</table>
            <br>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;">
				<tr>
                    <td width="100%" style="text-align:justify;">
                        Selanjutnya disebut sebagai PIHAK KEDUA atas nama <b>CV. VICTORY KONSULTAN</b>.
                    </td>
                </tr>
                <tr>
                    <td width="100%" style="text-align:justify;">
                        Bersama ini kedua belah pihak telah sepakat dan setuju untuk membuat suatu perjanjian kerjasama dalam pekerjaan Penyusunan ANDALALIN untuk :
                    </td>
                </tr>
			</table>
            <br>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;text-align:justify;">
				<tr>
                    <td width="30%">
                        Jenis Pembangunan
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td width="69%">
                        {{ $data->type_building }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Nama Pembangun
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->name_builder }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Lokasi Persil
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->persil_location }}
                    </td>
                </tr>
                <tr>
                    <td>
                        LL, LB
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->land_area.', '.$data->building_area }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Desa/Kelurahan 
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->subdistrict }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Kecamatan
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->district }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Kabupaten
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->city }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Provinsi
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->province }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Status Jalan
                    </td>
                    <td width="1%">
                        :
                    </td>
                    <td>
                        {{ $data->road_status }}
                    </td>
                </tr>
			</table>
            <br>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;">
				<tr>
                    <td width="100%" style="text-align:justify;">
                        Penyusunan yang dilakukan oleh PIHAK KEDUA meliputi :
                        <ol type="a">
                            <li>
                                Penyusunan seluruh Dokumen ANDALALIN meliputi
                                <ul>
                                    <li>Survey dan Inventarisasi data beserta seluruh pembiayannya, meliputi biaya :
                                        Survey lapangan tata guna lahan & pemetaan, survey lalu lintas, rekapitulasi
                                        hasil survey, analysis dan evaluasi data, analisis dan simulasi manajemen dan
                                        rekayasa lalu lintas, gambar 3Dimensi beserta simulasi perambuan lalu lintas,
                                        pembuatan laporan dan dokumentasi.</li>
                                    <li>Penggandaan seluruh laporan dokumen ANDALALIN dan dokumen pendukung
                                        yang diperlukan.</li>
                                </ul>
                            </li>
                            <li>
                                Presentasi/sidang dokumen ANDALALIN;
                            </li>
                            <li>
                                Asistensi dan koordinasi dengan pihak pertama dan Dinas Perhubungan selama proses penyusunan studi kajian ANDALALIN;
                            </li>
                            <li>
                                Dokumen ANDALALIN Siap disidangkan;
                            </li>
                            <li>
                                Surat rekomendasi ANDALALIN
                            </li>
                            <li>
                                Semua hasil pekerjaan dicetak dalam bentuk hardcopy sebanyak 2 dokumen (selanjutnya disebut sebagai “pekerjaan”).
                            </li>
                        </ol>
                    </td>
                    <td width="100%" style="text-align:justify;">
                        Adapun ketentuan – ketentuan dalam Surat Perjanjian Kerjasama ini adalah sebagai berikut :
                    </td>
                </tr>
			</table>
            <br>
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;">
				<tr>
                    <td width="100%" style="text-align:center;">
                        <b>Pasal 1</b>
                    </td>
                </tr>
                <tr>
                    <td width="100%" style="text-align:center;">
                        RUANG LINGKUP PEKERJAAN
                    </td>
                </tr>
                <tr>
                    <td width="100%" style="text-align:center;">
                        <ol type="1">
                            <li>
                                PIHAK KEDUA akan melaksanakan Pekerjaan dalam jangka waktu sebagaimana di maksud dalam ketentuan Perjanjian ini.
                            </li>
                            <li>
                                PIHAK KESATU wajib menyediakan seluruh dokumen persyaratan dan menyerahkan kepada PIHAK KEDUA.
                            </li>
                        </ol>
                    </td>
                </tr>
			</table>
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