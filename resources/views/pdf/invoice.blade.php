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
                        <h2 style="margin-top:30px;">INVOICE</h2>
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data->code, 'C128') }}" alt="barcode" style="top:50px;" height="35px" width="100%"/>
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
                    <td width="50%" style="border:1px solid black;padding:25px 5px 25px 5px !important;">
                        <h3 style="margin-top:-25px;">Ditujukan Kepada Yth:</h3>
                        <div>
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
                <tbody>
                    <tr>
                        <td align="center">
                            1.
                        </td>
                        <td>
                            Pembayaran {{ $data->paymentNoText() }} untuk Pekerjaan Dokumen Andalalin Pembangunan {{ $data->project->name }} di {{ $data->project->customer->address }}
                        </td>
                        <td align="right">
                            Rp {{ number_format($data->project->cost,0,',','.') }},-
                        </td>
                        <td align="center">
                            {{ number_format(round(($data->total / $data->project->cost) * 100,2),0,',','.') }}%
                        </td>
                        <td align="right">
                            Rp {{ number_format($data->total,0,',','.') }},-
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="left">
                            SUBTOTAL
                        </th>
                        <th align="right">
                            Rp {{ number_format($data->total,0,',','.') }},-
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>{{ $data->includeTax() }}</th>
                        <th align="left">
                            PPN {{ round($data->percent_tax,2).'%' }}
                        </th>
                        <th align="right">
                            Rp {{ number_format($data->tax,0,',','.') }},-
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="left">
                            PPh ({{ round($data->percent_wtax,2).'%' }})
                        </th>
                        <th align="right">
                            Rp {{ number_format($data->wtax,0,',','.') }},-
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th align="left">
                            TOTAL
                        </th>
                        <th align="right">
                            Rp {{ number_format($data->nominal,0,',','.') }},-
                        </th>
                    </tr>
                    <tr>
                        <th colspan="5">
                            <i>Terbilang : {{ CustomHelper::terbilangWithKoma($data->nominal) }} Rupiah</i>
                        </th>
                    </tr>
                </tfoot>
			</table>
            <br>
            <div style="margin-left:50px;font-size:14px;">
                Keterangan :
                <p>
                    Adapun pembayaran mohon di transfer ke nomor rekening berikut :<br>
                    {{-- @if (count($banks) > 0)
                        <ol>
                            @foreach ($banks as $rowbank)
                            <li>{!! $rowbank->bank.' Cab. '.$rowbank->branch.' No. Rekening <b>'.$rowbank->no.'</b> A.n. : <b>'.$rowbank->name.'</b>' !!}</li>
                            @endforeach
                        </ol>
                    @endif --}}
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
                            <u>DEDDY CHRISTIANTO., S.T.</u>
                            <br>Direktur
                        </td>
                    </tr>
                </tbody>
			</table>
		</div>
	</body>
</html>