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

            @page { margin: 1cm 0cm 1cm 0cm; }
            
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
                        <img src="{{ public_path('assets/images/square_logo.png') }}" width="125px" height="auto" style="margin-left:65px;position:absolute;">
                    </td>
                    <td width="50%">
                        
                    </td>
                    <td width="25%" style="vertical-align:middle;">
                        KOP PERUSAHAAN
                    </td>
                </tr>
			</table>
            <br><br><br><br><br><br>
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