<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title></title>
  <style media="all">
    *:not(br):not(tr):not(html) {
      font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
    }

    body {
      width: 100% !important;
      height: 100%;
      margin: 0;
      line-height: 1.4;
      background-color: #F5F7F9;
      color: #839197;
      -webkit-text-size-adjust: none;
    }

    a {
      color: #414EF9;
    }

    .email-wrapper {
      width: 100%;
      margin: 0;
      padding: 0;
      background-color: #F5F7F9;
    }

    .email-content {
      width: 100%;
      margin: 0;
      padding: 0;
    }

    .email-masthead {
      padding: 25px 0;
      text-align: center;
    }

    .email-masthead_logo {
      max-width: 400px;
      border: 0;
    }

    .email-masthead_name {
      font-size: 16px;
      font-weight: bold;
      color: #839197;
      text-decoration: none;
      text-shadow: 0 1px 0 white;
    }

    .email-body {
      width: 100%;
      margin: 0;
      padding: 0;
      border-top: 1px solid #E7EAEC;
      border-bottom: 1px solid #E7EAEC;
      background-color: #FFFFFF;
    }

    .email-body_inner {
      width: 570px;
      margin: 0 auto;
      padding: 0;
    }

    .email-footer {
      width: 570px;
      margin: 0 auto;
      padding: 0;
      text-align: center;
    }

    .email-footer p {
      color: #839197;
    }

    .body-action {
      width: 100%;
      margin: 30px auto;
      padding: 0;
      text-align: center;
    }

    .body-sub {
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid #E7EAEC;
    }

    .content-cell {
      padding: 35px;
    }

    .align-right {
      text-align: right;
    }

    h1 {
      margin-top: 0;
      color: #292E31;
      font-size: 19px;
      font-weight: bold;
      text-align: left;
    }

    h2 {
      margin-top: 0;
      color: #292E31;
      font-size: 16px;
      font-weight: bold;
      text-align: left;
    }

    h3 {
      margin-top: 0;
      color: #292E31;
      font-size: 14px;
      font-weight: bold;
      text-align: left;
    }

    p {
      margin-top: 0;
      color: #839197;
      font-size: 16px;
      line-height: 1.5em;
      text-align: left;
    }

    p.sub {
      font-size: 12px;
    }

    p.center {
      text-align: center;
    }

    .button {
      display: inline-block;
      width: 200px;
      background-color: #414EF9;
      border-radius: 3px;
      color: #ffffff;
      font-size: 15px;
      line-height: 45px;
      text-align: center;
      text-decoration: none;
      -webkit-text-size-adjust: none;
      mso-hide: all;
    }

    .button--green {
      background-color: #28DB67;
    }

    .button--red {
      background-color: #FF3665;
    }

    .button--blue {
      background-color: #414EF9;
    }

    @media only screen and (max-width: 600px) {
      .email-body_inner, .email-footer {
        width: 100% !important;
        zoom:0.7;
      }
    }

    @media only screen and (max-width: 500px) {
      .button {
        width: 100% !important;
      }
    }
  </style>
</head>
<body>
  <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center">
        <table class="email-content" width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td class="email-masthead">
              <a class="email-masthead_name">Slip Gaji Bulan {{ $data['result']['bulan'] }}</a>
            </td>
          </tr>
          <tr>
            <td class="email-body" width="100%">
              <table class="email-body_inner" align="center" width="650" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="content-cell">
                    <h1>Hore. Gaji anda berhasil diproses!</h1>
                    <p>
                        Selamat pak/bu <b>{{ $data['user']['nama'] }}</b>, setelah anda menerima email ini, maka seharusnya gaji anda sudah masuk ke rekening {{ $data['result']['rekening_bca'] }} anda. Jika belum, silahkan tanyakan ke pihak administrasi.
                    </p>
                    <h4>Rinciannya adalah sebagai berikut :</h4>
                    <table class="body-action" width="100%" cellpadding="0" cellspacing="0" border="1">
                      <tr>
                        <td colspan="3" style="text-align:center;" valign="middle" width="60%"><h3 align="center">SLIP GAJI BULAN {{ $data['result']['bulan'] }}</h3></td>
                        <td style="text-align:center;" valign="middle" width="40%"><img src="{{ url('assets/images/logovictory_2.png') }}" width="35%"></td>
                      </tr>
                      <tr>
                        <td align="center">No.</td>
                        <td align="center">Nama</td>
                        <td align="center">Jumlah</td>
                        <td align="center">Total</td>
                      </tr>
                      <tr style="background-color:#d1f3dd;">
                        <td align="center">1.</td>
                        <td align="left">Gaji Pokok</td>
                        <td align="center">1</td>
                        <td align="right">{{ number_format($data['result']['gaji_pokok'],'2',',','.') }}</td>
                      </tr>
                      <tr style="background-color:#d1f3dd;">
                        <td align="center">2.</td>
                        <td align="left">Lembur Senin-Jumat</td>
                        <td align="center">{{ number_format($data['result']['jumlah_lembur_senin_jumat'],'2',',','.') }}</td>
                        <td align="right">{{ number_format($data['result']['total_lembur_senin_jumat'],'2',',','.') }}</td>
                      </tr>
                      <tr style="background-color:#d1f3dd;">
                        <td align="center">3.</td>
                        <td align="left">Lembur Sabtu-Minggu</td>
                        <td align="center">{{ number_format($data['result']['jumlah_lembur_sabtu_minggu'],'2',',','.') }}</td>
                        <td align="right">{{ number_format($data['result']['total_lembur_sabtu_minggu'],'2',',','.') }}</td>
                      </tr>
                      <tr style="background-color:#d1f3dd;">
                        <td align="center">4.</td>
                        <td align="left">Lembur Inap-Efektif</td>
                        <td align="center">{{ number_format($data['result']['jumlah_lembur_inap_efektif'],'2',',','.') }}</td>
                        <td align="right">{{ number_format($data['result']['total_lembur_inap_efektif'],'2',',','.') }}</td>
                      </tr>
                      <tr style="background-color:#d1f3dd;">
                        <td align="center">5.</td>
                        <td align="left">Lembur Inap-Weekend</td>
                        <td align="center">{{ number_format($data['result']['jumlah_lembur_inap_weekend'],'2',',','.') }}</td>
                        <td align="right">{{ number_format($data['result']['total_lembur_inap_weekend'],'2',',','.') }}</td>
                      </tr>
                      <tr style="background-color:#fcd0bb;">
                        <td align="center">6.</td>
                        <td align="left">Potongan Terlambat</td>
                        <td align="center">{{ number_format($data['result']['jumlah_potongan_terlambat'],'2',',','.') }}</td>
                        <td align="right">{{ number_format($data['result']['total_potongan_terlambat'],'2',',','.') }}</td>
                      </tr>
                      <tr style="background-color:#fcd0bb;">
                        <td align="center">7.</td>
                        <td align="left">Potongan Kehadiran</td>
                        <td align="center">-</td>
                        <td align="right">{{ number_format($data['result']['total_potongan_kehadiran'],'2',',','.') }}</td>
                      </tr>
                      <tr style="background-color:#fcd0bb;">
                        <td align="center">8.</td>
                        <td align="left">Potongan Pinjaman</td>
                        <td align="center">-</td>
                        <td align="right">{{ number_format($data['result']['total_potongan_pinjaman'],'2',',','.') }}</td>
                      </tr>
                      <tr style="background-color:#fcd0bb;">
                        <td align="center">9.</td>
                        <td align="left">Potongan Denda</td>
                        <td align="center">-</td>
                        <td align="right">{{ number_format($data['result']['total_potongan_denda'],'2',',','.') }}</td>
                      </tr>
                      <tr style="background-color:#fcd0bb;">
                        <td align="center">10.</td>
                        <td align="left">Potongan BPJS</td>
                        <td align="center">-</td>
                        <td align="right">{{ number_format($data['result']['total_potongan_bpjs'],'2',',','.') }}</td>
                      </tr>
                      <tr style="font-size:15px !important;font-weight:800 !important;">
                        <td align="right" colspan="3">JUMLAH TRANSFER</td>
                        <td align="right">{{ number_format($data['result']['jumlah_transfer'],'2',',','.') }}</td>
                      </tr>
                    </table>
                    <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center">
                          <div>
                            Silahkan LOGIN dengan tombol dibawah untuk mengunduh slip gaji anda menjadi pdf.
                          </div>
                          <div>
                            NIK anda adalah : <b>{{ $data['user']['nik'] }}</b> dan password anda : <b>{{ base64_decode($data['user']['code']) }}</b>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td align="center">
                          <div>
                            <a href="{{ $url }}/login" class="button button--green" style="color:white !important;">Login</a>
                          </div>
                        </td>
                      </tr>
                    </table>
                    <table class="body-sub">
                      <tr>
                        <td>
                          <p class="sub">Jika anda mengalami masalah dalam menekan tombol link di atas, silahkan salin dan tempel link dibawah ke browser anda.
                          </p>
                          <p class="sub">
                            <a href="{{ $url }}/login">{{ $url }}/login</a>
                          </p>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>
              <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="content-cell">
                    <p class="sub center">
                      <br>Perum Graha Kota D12 no 20, Sungon, Suko, Kec. Sidoarjo, Kabupaten Sidoarjo
                      <br>Jawa Timur 61224
                      <br>(+62) 813-3021-2393
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>