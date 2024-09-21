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
            <br>
            <hr style="height: 5px;">
            <hr style="height: 1px;">
            <table class="table-content" cellpadding="0" cellspacing="0" width="100%">
				<tr>
                    <td width="50%" style="border:1px solid black;">
                        <h3>Ditujukan Kepada Yth:</h3>
                        <div>
                            {{ $data->receive_from }}
                        </div>
                    </td>
                    <td width="15%" style="padding-left:5px;">
                        No<br>
                        Tanggal<br>
                        Tagihan<br>
                    </td>
                    <td width="35%">
                        : {{ $data->code }}<br>
                        : {{ date('d/m/Y',strtotime($data->post_date)) }}<br>
                        : {{ $data->termin_no }}
                    </td>
                </tr>
			</table>
		</div>
	</body>
</html>