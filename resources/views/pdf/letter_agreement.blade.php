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
                font-size:15px;
                line-height: 1.8;
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

            @page { margin: 3.75cm 2cm 3cm 2cm; }
            
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

            header { position: fixed; top: -135px; left: 0px; right: 0px; height: 150px; margin-bottom: 10em }

            footer { position: fixed; bottom: -80px; left: 50px; right: 0px; height: 150px; margin-bottom: 10em }

            table tr {
                page-break-after:auto;
                page-break-inside:auto;
            }
		</style>
	</head>
	<body>
        <header>
            <table id="table-header" cellpadding="0" cellspacing="0" width="100%">
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
        </header>
        <footer>
            <table id="table-header" cellpadding="0" cellspacing="0" width="100%">
				<tr>
                    <td width="25%">
                        <div style="border:1px solid black;width:100px;height:75px;text-align:center;vertical-align:middle;padding-top:25px;">
                            KOP PERUSAHAAN
                        </div>
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
        </footer>
        <main style="margin-top:20px;">
            <div class="invoice-box">
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
                    </tr>
                    <tr>
                        <td width="100%" style="text-align:justify;">
                            Adapun ketentuan – ketentuan dalam Surat Perjanjian Kerjasama ini adalah sebagai berikut :
                        </td>
                    </tr>
                </table>
                <br>
                <div style="margin:auto;width:90%;text-align:justify !important;">
                    <p style="text-align:center;"><b>Pasal 1</b></p>
                    <p style="text-align:center;">RUANG LINGKUP PEKERJAAN</p>
                    <p>
                        <ol type="1">
                            <li>
                                PIHAK KEDUA akan melaksanakan Pekerjaan dalam jangka waktu sebagaimana di maksud dalam ketentuan Perjanjian ini.
                            </li>
                            <li>
                                PIHAK KESATU wajib menyediakan seluruh dokumen persyaratan dan menyerahkan kepada PIHAK KEDUA.
                            </li>
                        </ol>
                    </p>
                </div>
                <br>
                <div style="margin:auto;width:90%;text-align:justify !important;">
                    <p style="text-align:center;"><b>Pasal 2</b></p>
                    <p style="text-align:center;">PEMBIAYAAN</p>
                    <p>
                        <ol type="1">
                            <li>
                                Atas pelaksanaan Pekerjaan, PIHAK KESATU wajib membayar Total biaya Pekerjaan sebesar Rp {{ number_format($data->project->cost,0,',','') }},- ( {{ CustomHelper::terbilang($data->project->cost) }} ) kepada PIHAK KEDUA. Pembayaran biaya pelaksanaan Pekerjaan :
                                <ul style="list-style-type:disc">
                                    <li>
                                        Tahap I sebesar ; Rp {{ number_format($data->nominal_1,0,',','') }},- ( {{ CustomHelper::terbilang($data->nominal_1) }} ) dari nilai kontrak dibayarkan pada saat penandatanganan kontrak dan setelah diterimanya invoice.
                                    </li>
                                    <li>
                                        Tahap II sebesar ; Rp {{ number_format($data->nominal_2,0,',','') }},- ( {{ CustomHelper::terbilang($data->nominal_2) }} ) dari nilai kontrak dibayarkan pada saat PIHAK KEDUA menyerahkan laporan Analisis Dampak Lalu Lintas yang kondisinya siap disidangkan ke instansi terkait dengan menyertakan Tanda Terima Berkas oleh Dinas terkait.
                                    </li>
                                    <li>
                                        Tahap III sebesar ; Rp {{ number_format($data->nominal_3,0,',','') }},- ( {{ CustomHelper::terbilang($data->nominal_3) }} ) Dari nilai kontrak dibayarkan saat pekerjaan sudah selesai dan surat rekomendasi Analisis Dampak Lalu Lintas yang diterbitkan instansi terkait sudah terbit.
                                    </li>
                                </ul>
                            </li>
                            <li>
                                Penagihan pembayaran kami sertai dengan dokumen pendukung berupa:
                                <ol type="a">
                                    <li>Invoice;</li>
                                </ol>
                            </li>
                            <li>
                                Pembayaran akan dilakukan PIHAK KESATU selambat-lambatnya 3 (tiga) hari kerja / setelah diterimanya Invoice oleh PIHAK KESATU dan akan dilakukan sesuai jadwal pembayaran yang di berikan oleh PIHAK KEDUA.
                            </li>
                            <li>
                                Pembayaran dilakukan oleh PIHAK KESATU kepada PIHAK KEDUA dengan cara mentransfer ke rekening PIHAK KEDUA ke rekening Bank BCA Cabang Sidoarjo dengan nomor rekening 0183 - 88 - 6140 an. DEDDY CHRISTIANTO.
                            </li>
                        </ol>
                    </p>
                </div>
                <br>
                <div style="margin:auto;width:90%;text-align:justify !important;">
                    <p style="text-align:center;"><b>Pasal 3</b></p>
                    <p style="text-align:center;">WAKTU PELAKSANAAN</p>
                    <p>
                        <ol type="1">
                            <li>
                                Pekerjaan penyusunan Dokumen ini hingga siap untuk diajukan ke Dinas akan diselesaikan dalam waktu {{ CustomHelper::countDays($data->estimate_date_start,$data->estimate_date_finish) }} ( {{ CustomHelper::terbilang(CustomHelper::countDays($data->estimate_date_start,$data->estimate_date_finish)) }} ) Hari Kerja sejak diterimanya DP ( Down Payment ) dan berkas persyaratan ANDALALIN dengan lengkap yaitu pada tanggal {{ CustomHelper::tgl_indo($data->estimate_date_start) }} sampai dengan tanggal {{ CustomHelper::tgl_indo($data->estimate_date_finish) }}.
                            </li>
                        </ol>
                    </p>
                </div>
                <br>
                <div style="margin:auto;width:90%;text-align:justify !important;">
                    <p style="text-align:center;"><b>Pasal 4</b></p>
                    <p style="text-align:center;">AMANDEMEN/ADDENDUM</p>
                    <p>
                        <ol type="1">
                            <li>
                                Segala sesuatu yang belum diatur dalam Surat Perjanjian ini atau perubahan-perubahan yang dipandang perlu oleh kedua belah pihak akan diatur dalam Surat Perjanjian tambahan (Addendum) atau Surat Perjanjian Perubahan (Amandemen) yang merupakan kesatuan yang tidak terpisahkan dari Surat Perjanjian ini.
                            </li>
                        </ol>
                    </p>
                </div>
                <br>
                <div style="margin:auto;width:90%;text-align:justify !important;">
                    <p style="text-align:center;"><b>Pasal 5</b></p>
                    <p style="text-align:center;">HAK DAN KEWAJIBAN PARA PIHAK</p>
                    <p>
                        <ol type="1">
                            <li>
                                Hak dan Kewajiban PIHAK KESATU :
                                <ol type="a">
                                    <li>
                                        PIHAK KESATU berhak atas kepemilikan dari keseluruhan hasil pekerjaan (Dokumen asli, bukan copy) dari PIHAK KEDUA yang diatur dalam Perjanjian ini;
                                    </li>
                                    <li>
                                        PIHAK KESATU berkewajiban membayar Nilai Kontrak sesuai dengan ketentuan pasal 3 Perjanjian ini;
                                    </li>
                                    <li>
                                        PIHAK KESATU berkewajiban menyerahkan dokumen-dokumen persyaratan yang diminta melengkapi oleh PIHAK KEDUA.
                                    </li>
                                    <li>
                                        Adapun Dokumen - Dokumen persayaratan yang dibutuhkan adalah :
                                        <ol type="1">
                                            <li>KTP Direktur</li>
                                            <li>NPWP Perusahaan dan NPWP Direktur</li>
                                            <li>Persetujuan Izin Lokasi / Copy P2R/SKRK/IRK</li>
                                            <li>Copy Izin Usaha</li>
                                            <li>Akta Perusahaan Pendirian dan Perubahan (Jika Ada)</li>
                                            <li>Akta Tanah (SHM/HGB/Letter C, dll)</li>
                                            <li>Gambar Siteplan / Autocad</li>
                                            <li>SIUP dan NIB</li>
                                            <li>KEMENKUMHAM PERUSAHAAN</li>
                                            <li>Kerjasama pengangkutan Limbah B3 (Jika Ada)</li>
                                        </ol>
                                        Untuk Dokumen tersebut dikirim via email : victorykonsultan@gmail.com
                                    </li>
                                    <li>
                                        PIHAK PERTAMA wajib menjaga Keorisinilan (Keaslian) hasil pekerjaan yaitu Dokumen ANDALALIN dan Surat Rekomendasi ANDALALIN. Tidak merubah isi maupun gambar yang ada pada Dokumen dan Rekomendasi ANDALALIN.
                                    </li>
                                </ol>
                            </li>
                            <li>
                                Hak dan Kewajiban PIHAK KEDUA;
                                <ol type="a">
                                    <li>
                                        PIHAK KEDUA berhak atas penerimaan pembayaran Nilai Kontrak yang diatur dalam pasal 3 Perjanjian ini;
                                    </li>
                                    <li>
                                        PIHAK KEDUA berkewajiban memberikan laporan kepada PIHAK KESATU untuk setiap perkembangan dari pekerjaan yang dilakukan oleh PIHAK KEDUA kepada PIHAK KESATU segera setelah diminta oleh PIHAK KESATU.
                                    </li>
                                    <li>
                                        PIHAK KEDUA berhak mengajukan draft Dokumen Andalalin ke Dinas berwenang untuk disidangkan setelah menerima persetujuan dari Pihak Pertama, yang ditunjukan dengan Berita Acara persertujuan yang di tandatangani oleh PIHAK PERTAMA.
                                    </li>
                                </ol>
                            </li>
                        </ol>
                    </p>
                </div>
                <br>
                <div style="margin:auto;width:90%;text-align:justify !important;">
                    <p style="text-align:center;"><b>Pasal 6</b></p>
                    <p style="text-align:center;">FORCE MAJEURE</p>
                    <p>
                        <ol type="1">
                            <li>
                                Hal-hal yang dianggap sebagai Force Majeure dalam Perjanjian ini adalah peristiwa atau kejadian di luar kekuasaan manusia, termasuk tetapi tidak terbatas pada bencana alam, kebakaran, aksi pemogokan umum, epidemic, peperangan, huru-hara, terganggunya aliran komunikasi dan atau listrik
                            </li>
                            <li>
                                Pihak yang tidak melaksanakan kewajibannya dikarenakan peristiwa Force Majeure, wajib memberitahukan pihak lainnya secara tertulis disertai surat keterangan dari Kepolisian atau instansi yang berwenang, selambat-lambatnya 10 (sepuluh) hari kerja terhitung sejak tanggal terjadinya peristiwa tersebut.
                            </li>
                            <li>
                                Apabila dalam waktu 10 (sepuluh) hari kerja terhitung sejak diterimanya pemberitahuan tersebut, pihak yang menerima pemberitahuan tidak menanggapi, maka akan dianggap bahwa peristiwa tersebut telah diketahuinya
                            </li>
                            <li>
                                Segala permasalahan yang timbul sebagai akibat dari terjadinya Force Majeure akan diselesaikan secara musyawarah oleh Para Pihak.
                            </li>
                        </ol>
                    </p>
                </div>
                <br>
                <div style="margin:auto;width:90%;text-align:justify !important;">
                    <p style="text-align:center;"><b>Pasal 7</b></p>
                    <p style="text-align:center;">PENGAKHIRAN PERJANJIAN</p>
                    <p>
                        <ol type="1">
                            <li>
                                Para Pihak dapat mengakhiri Perjanjian ini secara sepihak sebelum jangka waktu Perjanjian berakhir dengan memberikan pemberitahuan secara tertulis selambatlambatnya 30 (tiga puluh) hari kalender sebelum tanggal pengakhiran yang dikehendaki pihak yang bersangkutan.
                            </li>
                            <li>
                                Dalam hal PIHAK KESATU mengakhiri Perjanjian ini sebelum jangka waktu berakhirnya Perjanjian ini dimana harus diinformasikan dan disetujui PIHAK KEDUA, maka PIHAK KESATU wajib membayar seluruh biaya pelaksanaan Pekerjaan sesuai progress yang telah dikerjakan oleh PIHAK KEDUA.
                            </li>
                            <li>
                                Sehubungan dengan pengakhiran Perjanjian ini Para Pihak sepakat untuk mengesampingkan berlakunya ketentuan Pasal 1266 dan 1267 Kitab Undang-Undang Hukum Perdata khususnya ketentuan yang mengharuskan adanya putusan pengadilan untuk pengakhiran suatu Perjanjian, sehingga untuk pengakhiran Perjanjian ini tidak diperlukan adanya putusan pengadilan.
                            </li>
                            <li>
                                Dengan berakhirnya Perjanjian ini, tidak menghapuskan kewajiban Para Pihak yang telah timbul sebelum diakhirinya Perjanjian ini sampai kewajiban tersebut selesai sesuai dengan ketentuan-ketentuan dalam Perjanjian ini.
                            </li>
                        </ol>
                    </p>
                </div>
                <br>
                <div style="margin:auto;width:90%;text-align:justify !important;">
                    <p style="text-align:center;"><b>Pasal 8</b></p>
                    <p style="text-align:center;">PENYELESAIAN PERSELISIHAN</p>
                    <p>
                        <ol type="1">
                            <li>
                                Apabila timbul perselisihan dalam pelaksanaan Perjanjian ini, Para Pihak sepakat untuk menyelesaikan secara musyawarah.
                            </li>
                            <li>
                                Apabila penyelesaian sebagaimana dimaksud dalam ayat 1 Pasal ini tidak dapat dicapai, maka Para Pihak sepakat untuk menyelesaikannya di Pengadilan Negeri Sidoarjo.
                            </li>
                        </ol>
                    </p>
                </div>
                <br>
                <div style="margin:auto;width:90%;text-align:justify !important;">
                    <p style="text-align:center;"><b>Pasal 9</b></p>
                    <p style="text-align:center;">LAIN-LAIN</p>
                    <p>
                        <ol type="1">
                            <li>
                                Bahwa dokumen ANDALALIN yang dibuat oleh PIHAK KEDUA sebagai bagian pekerjaan, dimana isi dokumen tersebut merupakan data dan fakta sesuai kondisi di lapangan hasil penelitian PIHAK KEDUA.
                            </li>
                            <li>
                                PIHAK KEDUA dalam keadaan apapun turut bertangung jawab atas semua data lapangan yang dibuat oleh PIHAK KEDUA sehubungan dengan pekerjaan dan hasil pekerjaan tersebut yang kemudian menjadi pertimbangan dinas/instansi terkait untuk memberikan rekomendasi bagi penerbitan ijin lingkungan yang di ajukan PIHAK KESATU.
                            </li>
                            <li>
                                Para Pihak menjamin bahwa Pihak-Pihak yang menandatangani Perjanjian ini adalah pihak - pihak yang berwenang dalam mewakili Perseroan dalam Perjanjian ini sesuai dengan Anggaran Dasar Perseroan masing-masing.
                            </li>
                        </ol>
                    </p>
                </div>
                <br>
                <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;">
                    <tr>
                        <td width="100%" style="text-align:justify;">
                            Demikian Perjanjian ini dibuat dan ditandatangani oleh Para Pihak, dibuat dalam rangkap 2 (dua), masing-masing bermaterai cukup, diberi cap perusahaan dan mempunyai kekuatan hukum yang sama.
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
        </main>
	</body>
</html>