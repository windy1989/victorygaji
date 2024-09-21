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
                    <td width="25%" rowspan="2">
                        <h2 style="margin-top:30px;">INVOICE</h2>
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
            <hr>
            <hr>
		</div>
	</body>
</html>