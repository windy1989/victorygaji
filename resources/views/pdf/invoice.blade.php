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
            @page { margin: 0.5cm; }
		</style>
	</head>
	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
                    <td align="right" width="20%" rowspan="4">
                        <img src="{{ public_path('assets/images/square_logo.png') }}" width="80px" height="auto" style="margin-right:10px;">
                    </td>
                    <td width="55%" colspan="2">
                        CV. VICTORY KONSULTAN
                    </td>
                    <td width="25%" rowspan="4" style="vertical-align:bottom;">
                        <h3>INVOICE</h3>
                    </td>
                </tr>
                <tr>
                    <td width="10%">Kantor</td>
                    <td width="45%">: Perum. Graha Kota D 12 No. 20 Suko - Sidoarjo</td>
                </tr>
                <tr>
                    <td>Telp/Fax</td>
                    <td>: 031-51517878</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>: victorykonsultan@gmail.com</td>
                </tr>
			</table>
		</div>
	</body>
</html>