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
		</style>
	</head>
	<body>
		<div class="invoice-box">
			<table id="table-header" cellpadding="0" cellspacing="0" width="100%">
				<tr>
                    <td align="right" width="20%" rowspan="2">
                        <img src="{{ public_path('assets/images/square_logo.png') }}" width="100px" height="auto" style="margin-left:80px;position:absolute;">
                    </td>
                    <td width="80%" colspan="2">
                        <b>CV. VICTORY KONSULTAN</b>
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