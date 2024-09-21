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
            table > tr > td {
                padding:0px !important;
            }
            table.table-bordered{
                border:1px solid black !important;
                margin-top:20px;
            }
            table.table-bordered > thead > tr > th{
                border:1px solid black !important;
            }
            table.table-bordered > tbody > tr > td{
                border:1px solid black !important;
            }

			#atas tr td {
                padding:0px !important;
            }
            table.table-bordered{
                border:1px solid black;
                margin-top:20px;
            }
            table.table-bordered > thead > tr > th{
                border:1px solid black;
            }
            table.table-bordered > tbody > tr > td{
                border:1px solid black;
            }
            .bg {
                position: absolute;
                z-index: 99;
                top: -50px;
                bottom: 0;
                left: 0;
                right: 0;
                background: url(dist/images/logo.png) center center no-repeat;
                opacity: .15;
                width: 100%;
                height: 100%;
            }
            .ttd {
                background: url(dist/images/ttd.jpg) center center no-repeat;
                background-size: 80%;
                background-position:
                    -20px 20px,
                    100%,
                    20px 20px,
                    50%,
                    0 90%;
            }
            .kwitansi {
                position: absolute;
                z-index: 99;
                top: -350px;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                height: 100%;
                writing-mode: tb-rl;
                transform: rotate(-180deg);
                font-size:50px;
                background: -webkit-linear-gradient(#eee, #333);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                -webkit-text-stroke: 1px black;
                //text-shadow: -3px -3px black;
                letter-spacing: 5px;
            }
            .borderless td, .borderless th {
                border: none;
            }
		</style>
	</head>
	<body>
		<div class="invoice-box">
            <table border="0" width="100%">
                <tr>
                    <td width="15%">
                        <h2 class="page-header">
                          <img src="{{ public_path('assets/images/square_logo.png') }}" width="100px" height="100%" style="margin-left:30px;">
                        </h2>
                    </td>
                    <td width="40%" style="font-size:13px !important;">
                        <table border="0" width="100%" id="atas">
                            <tr>
                                <td colspan=2><b><u>CV. VICTORY KONSULTAN</u></b></td>
                            </tr>
                            <tr>
                                <td colspan=2><b>STUDY KELAYAKAN, REKAYASA, EVALUASI, DAN MANAJEMEN</b></td>
                            </tr>
                            <tr>
                                <td width="20%">Alamat</td>
                                <td width="80%">: Perum. Graha Kota D 12 No. 20 Suko - Sidoarjo</td>
                            </tr>
                            <tr>
                                <td width="20%">Telp/Fax</td>
                                <td width="80%">: 031-51517878</td>
                            </tr>
                            <tr>
                                <td width="20%">Email</td>
                                <td width="80%">: victorykonsultan@gmail.com</td>
                            </tr>
                            <tr>
                                <td width="20%">Web</td>
                                <td width="80%">: victorykonsultan.co.id</td>
                            </tr>
                        </table>
                    </td>
                    <td width="45%">
                        <table border="0" width="100%">
                            <tr>
                                <td width="20%" style="padding:0px !important;">
                                Tanggal
                                </td>
                                <td width="50%" style="padding:0px !important;">
                                    : {{ date('d/m/Y',strtotime($data->pay_date)) }}
                                </td>
                                <td rowspan="2" width="30%" class="text-center" style="padding:0px !important;">
                                    <img src="" alt="" id="image" />
                                </td>
                            </tr>
                            <tr>
                                <td width="20%" style="padding:0px !important;">
                                Invoice No
                                </td>
                                <td width="50%" style="padding:0px !important;">
                                    : {{ $data->code }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <table class="table borderless">
                <tbody>
                    <tr>
                      <td width="25%">KWITANSI</td>
                      <td width="1%">:</td>
                      <td width="74%">{{ $data->receipt_code }}</td>
                    </tr>
                    <tr>
                      <td>TELAH DITERIMA DARI</td>
                      <td>:</td>
                      <td>{{ $data->receive_from }}</td>
                    </tr>
                    <tr>
                      <td>UNTUK PEMBAYARAN</td>
                      <td>:</td>
                      <td>Pembayaran {{ $data->paymentNoText() }} untuk Pekerjaan Dokumen Andalalin Pembangunan {{ $data->project->name }} di {{ $data->project->location }}</td>
                    </tr>
                    <tr>
                      <td>JUMLAH UANG</td>
                      <td>:</td>
                      <td>Rp. <?=number_format($data->nominal,0,',','.')?>,-</td>
                    </tr>
                    <tr>
                      <td>TERBILANG</td>
                      <td>:</td>
                      <td style="border: 1px solid black;"><b><i>{{ CustomHelper::terbilangWithKoma($data->nominal) }} Rupiah</i></b></td>
                    </tr>
                </tbody>
              </table>
            <br>
            <div style="margin-left:50px;font-size:14px;">
                Keterangan :
                <p>
                    Adapun pembayaran mohon di transfer ke nomor rekening berikut :<br>
                    {!! $data->bank->bank.' Cab. '.$data->bank->branch.' No. Rekening <b>'.$data->bank->no.'</b> A.n. : <b>'.$data->bank->name.'</b>' !!}
                </p>
            </div>
            <br>
            <br>
            <table class="table-content" id="table-detail" cellpadding="0" cellspacing="0" width="90%"style="margin:auto;font-weight:700;">
                <tbody>
                    <tr>
                        <td align="center" width="60%">
                            
                        </td>
                        <td align="center" width="40%">
                            CV. VICTORY KONSULTAN
                            <br><br><br><br><br><br><br>
                            <u>.DEDDY CHRISTIANTO., S.T.</u>
                            <br>Direktur
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
	</body>
</html>