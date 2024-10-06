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
                font-size:13px;
                line-height: 1.8;
            }

            .table-content-body {
                font-size:13px;
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