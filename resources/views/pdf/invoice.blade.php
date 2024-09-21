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

            #table-detail > th {
                padding:5px;
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
            <table class="table-content" cellpadding="0" cellspacing="0" width="90%" style="margin:auto;">
				<tr>
                    <td width="50%" style="border:1px solid black;vertical-align:top;">
                        <h3>Ditujukan Kepada Yth:</h3>
                        <div style="padding-left:5px;">
                            {{ $data->receive_from }}
                        </div>
                    </td>
                    <td width="15%">
                        <div style="padding-left:5px;">
                            No<br>
                            Tanggal<br>
                            Tagihan<br>
                        </div>
                    </td>
                    <td width="35%">
                        <div>
                            : {{ $data->code }}<br>
                            : {{ date('d/m/Y',strtotime($data->post_date)) }}<br>
                            : {{ $data->termin_no }}
                        </div>
                    </td>
                </tr>
			</table>
            <br>
            <table class="table-content" id="table-detail" cellpadding="0" cellspacing="0" width="90%" border="1" style="border:1px solid black;margin:auto;">
                <thead>
				<tr>
                    <th width="5%">
                        No
                    </th>
                    <th width="35%">
                        Diskripsi
                    </th>
                    <th width="20%">
                        Total Nilai
                    </th>
                    <th width="20%">
                        Persentase
                    </th>
                    <th width="20%">
                        Jumlah
                    </th>
                </tr>
                </thead>
			</table>
		</div>
	</body>
</html>