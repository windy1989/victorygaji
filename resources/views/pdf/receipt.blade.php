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
                margin-top:-15px;
            }

            table {
                border-collapse:collapse;
            }

            td {
                padding: 0px !important;
            }
            @page { margin: 0.5cm; }
            hr {
                background-color: black;
                border:none;
            }
            
            .table-content {
                font-size:14px;
            }

            #table-detail th {
                padding:5px;
            }

            #table-detail > tbody > tr > td {
                padding:25px 5px 25px 5px !important;
            }

            #table-receipt > tbody > tr > td {
                padding:10px 5px 10px 5px !important;
            }
		</style>
	</head>
	<body>
		<div class="invoice-box">
            <table id="table-header" cellpadding="0" cellspacing="0" width="100%">
				<tr>
                    <td align="right" width="20%" rowspan="2">
                        <img src="{{ public_path('assets/images/square_logo.png') }}" width="80px" height="auto" style="margin-left:50px;position:absolute;margin-top:10px;">
                    </td>
                    <td width="55%" colspan="2">
                        <b>CV. VICTORY KONSULTAN</b>
                    </td>
                    <td width="25%" rowspan="2" align="center">
                        <h2 style="margin-top:30px;">KWITANSI</h2>
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data->receipt_code, 'C128') }}" alt="barcode" style="top:50px;" height="35px" width="100%"/>
                    </td>
                </tr>
                <tr>
                    <td width="15%">
                        Kantor Pusat<br>
                        Telp/Fax<br>
                        Email
                    </td>
                    <td width="40%">
                        : Perum. Graha Kota D 12 No. 20 Suko - Sidoarjo<br>
                        : 031-51517878<br>
                        : victorykonsultan@gmail.com
                    </td>
                </tr>
			</table>
            <br>
            <hr style="height: 5px;">
            <hr style="height: 1px;">
            <table class="table-content" id="table-receipt" cellpadding="0" cellspacing="0" width="100%">
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