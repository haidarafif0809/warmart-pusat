<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Boardicle Email</title>
  <style>
  /* -------------------------------------
  GLOBAL RESETS
  ------------------------------------- */
  img {
    border: none;
    -ms-interpolation-mode: bicubic;
    max-width: 100%; }
    body {
      background-color: #f6f6f6;
      font-family: sans-serif;
      -webkit-font-smoothing: antialiased;
      font-size: 14px;
      line-height: 1.4;
      margin: 0;
      padding: 0;
      -ms-text-size-adjust: 100%;
      -webkit-text-size-adjust: 100%; }
      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; 
      }
      table td {
        font-family: sans-serif;
        verifikasitical-align: top; }
        /* -------------------------------------
 BODY & CONTAINER
 ------------------------------------- */
 .body {
  background-color: #f6f6f6;
  width: 100%; }
  /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
  .container {
    display: block;
    Margin: 0 auto !important;
    /* makes it centered */
    max-width: 580px;
    padding: 10px;
    width: 580px; }
    /* This should also be a block element, so that it will fill 100% of the .container */
    .content {
      box-sizing: border-box;
      display: block;
      Margin: 0 auto;
      max-width: 580px;
      padding: 10px; 
    }
/* -------------------------------------
HEADER, FOOTER, MAIN
------------------------------------- */
.main {
  background: #ffffff;
  border-radius: 3px;
  width: 100%; 
}
.wrapper {
  box-sizing: border-box;
  padding: 20px; 
}
.content-block {
  padding-bottom: 10px;
  padding-top: 10px;
}
.footer {
  clear: both;
  Margin-top: 10px;
  text-align: center;
  width: 100%; }
  .footer td,
  .footer p,
  .footer span,
  .footer a 
  {
    color: #999999;
    font-size: 12px;
    text-align: center; 
  }
        /* -------------------------------------
            TYPOGRAPHY
            ------------------------------------- */
            h1,
            h2,
            h3,
            h4 
            {
              color: #000000;
              font-family: sans-serif;
              font-weight: 400;
              line-height: 1.4;
              margin: 0;
              Margin-bottom: 30px; 
            }
            h1 
            {
              font-size: 35px;
              font-weight: 300;
              text-align: center;
              text-transform: capitalize;
            }
            p,
            ul,
            ol {
              font-family: sans-serif;
              font-size: 14px;
              font-weight: normal;
              margin: 0;
              Margin-bottom: 15px; 
            }
            p li,
            ul li,
            ol li {
              list-style-position: inside;
              margin-left: 5px;
            }
            a {
              color: #3498db;
              text-decoration: underline; 
            }
        /* -------------------------------------
            BUTTONS
            ------------------------------------- */
            .btn {
              box-sizing: border-box;
              width: 100%; 
            }
            .btn > tbody > tr > td {
              padding-bottom: 15px; 
            }
            .btn table {
              width: auto; 
            }
            .btn table td {
              background-color: #ffffff;
              border-radius: 5px;
              text-align: center; 
            }
            .btn a {
              background-color: #ffffff;
              border: solid 1px #3498db;
              border-radius: 5px;
              box-sizing: border-box;
              color: #3498db;
              cursor: pointer;
              display: inline-block;
              font-size: 14px;
              font-weight: bold;
              margin: 0;
              padding: 12px 25px;
              text-decoration: none;
              text-transform: capitalize; 
            }
            .btn-primary table td {
              background-color: #3498db;
            }
            .btn-primary a {
              background-color: #3498db;
              border-color: #3498db;
              color: #ffffff; 
            }
        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
            ------------------------------------- */
            .last {
              margin-bottom: 0; 
            }
            .first {
              margin-top: 0; 
            }
            .align-center {
              text-align: center; 
            }
            .align-right {
              text-align: right; 
            }
            .align-left {
              text-align: left; 
            }
            .clear {
              clear: both; 
            }
            .mt0 {
              margin-top: 0; 
            }
            .mb0 {
              margin-bottom: 0; 
            }
            .preheader {
              color: transparent;
              display: none;
              height: 0;
              max-height: 0;
              max-width: 0;
              opacity: 0;
              overflow: hidden;
              mso-hide: all;
              visibility: hidden;
              width: 0; 
            }
            .powered-by a {
              text-decoration: none; 
            }
            hr {
              border: 0;
              border-bottom: 1px solid #f6f6f6;
              Margin: 20px 0; 
            }
        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
            ------------------------------------- */
            @media only screen and (max-width: 620px) {
              table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important; 
              }
              table[class=body] p,
              table[class=body] ul,
              table[class=body] ol,
              table[class=body] td,
              table[class=body] span,
              table[class=body] a {
                font-size: 16px !important; 
              }
              table[class=body] .wrapper,
              table[class=body] .article {
                padding: 10px !important; 
              }
              table[class=body] .content {
               padding: 0 !important; 
             }
             table[class=body] .container {
               padding: 0 !important;
               width: 100% !important; 
             }
             table[class=body] .main {
               border-left-width: 0 !important;
               border-radius: 0 !important;
               border-right-width: 0 !important; 
             }
             table[class=body] .btn table {
              width: 100% !important; 
            }
            table[class=body] .btn a {
              width: 100% !important; 
            }
            table[class=body] .img-responsive {
             height: auto !important;
             max-width: 100% !important;
             width: auto !important; 
           }
         }
        /* -------------------------------------
            PRESERVE THESE STYLES IN THE HEAD
            ------------------------------------- */
            @media all {
              .ExternalClass {
                width: 100%; 
              }
              .ExternalClass,
              .ExternalClass p,
              .ExternalClass span,
              .ExternalClass font,
              .ExternalClass td,
              .ExternalClass div {
                line-height: 100%; 
              }
              .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important; 
              }
              .btn-primary table td:hover {
                background-color: #34495e !important; 
              }
              .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important; 
              } 
            }
          </style>
        </head>
        <body class="">
          <table border="0" cellpadding="0" cellspacing="0" class="body">
            <tr>
              <td>&nbsp;</td>
              <td class="container">
                <div class="content">

                  <!-- START CENTERED WHITE CONTAINER -->
                  <span class="preheader">Email Untuk Kamu !.</span>
                  <table class="main">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                      <td class="wrapper">
                        <table border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td>

                              <h4><p><b>Hai {{$data->name}},</b></p></h4>
                              <p>Terima kasih atas kepercayaan anda telah berbelanja di <b>{{$pesanan_pelanggan->warung->name}}</b>. <br> Pesanan anda dengan nomor <b>Order # {{$pesanan_pelanggan->id}}</b> sudah berhasil dilakukan pada tanggal <b>{{$pesanan_pelanggan->WaktuPesan}}</b> dengan metode pembayaran <b>{{$pesanan_pelanggan->metode_pembayaran}}</b>.</p>

                              <table border="0" cellpadding="0" cellspacing="0">
                                <tbody>
                                  <tr>
                                    <td align="left">
                                      <table border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                          <tr>
                                            <td>
                                             <div class="container">
                                              <hr>

                                              <p><b>Detail Pesanan :</b></p>

                                              <table>
                                                <thead>

                                                </thead> 
                                                <tbody>

                                                  @foreach($detail_pesanan as $detail_pesanans)
                                                  <tr>
                                                    <td>
                                                      <div class="img-container"  style="padding-left: 5px; padding-top: 1px; padding-bottom: 1px; padding-right: 1px; width:75px;">
                                                        @if($detail_pesanans->foto != "")
                                                        <img src="{{url('foto_produk/'.$detail_pesanans->foto)}}">
                                                        @else
                                                        <img src="{{url('image/foto_default.png')}}">
                                                        @endif
                                                      </div>
                                                    </td>
                                                    <td> {{title_case($detail_pesanans->nama_barang)}} <br> 
                                                      Quantity : {{ number_format($detail_pesanans->jumlah_produk, 0, ',', '.')}}<br>
                                                      Harga : {{number_format($detail_pesanans->harga_jual, 0, ',', '.')}}<br>
                                                      <small><i>{{$pesanan_pelanggan->warung->name}}</i></small> 
                                                    </td>
                                                    <td align="right"><b align="right">Rp. {{ number_format($detail_pesanans->harga_jual * $detail_pesanans->jumlah_produk ,0, ',', '.')}}</b></td>
                                                  </tr>
                                                  @endforeach
                                                </tbody>
                                              </table>

                                              <hr><hr>

                                              <table>
                                                <thead></thead> 
                                                <tbody>
                                                  <tr>
                                                    <td style="padding: 3px;"></td>
                                                    <td style="padding: 3px;"></td>
                                                    <td  align="right" style="padding:3px">Subtotal :</td>
                                                    @if($pesanan_pelanggan->metode_pembayaran == 'Bayar di Tempat')
                                                    <td align="right" style="padding:3px"><b>Rp. {{ number_format($pesanan_pelanggan->subtotal ,0, ',', '.')}}</b></td>
                                                    @else
                                                    <td align="right" style="padding:3px"><b>Rp. {{ number_format(($pesanan_pelanggan->subtotal - $pesanan_pelanggan->biaya_kirim) - $pesanan_pelanggan->kode_unik_transfer ,0, ',', '.')}}</b></td>
                                                    @endif
                                                  </tr>
                                                  @if($pesanan_pelanggan->metode_pembayaran == 'TRANSFER')
                                                  <tr>
                                                    <td style="padding: 3px;"></td>
                                                    <td  align="right" style="padding:3px"></td>
                                                    <td  align="right" style="padding:3px">Kode Unik :</td>
                                                    <td  align="right" style="padding:3px"><b>Rp. {{number_format($pesanan_pelanggan->kode_unik_transfer ,0, ',', '.')}}</b></td>
                                                  </tr>
                                                  @endif
                                                  <tr>
                                                    <td style="padding: 3px;"></td>
                                                    <td  align="right" style="padding:3px"></td>
                                                    <td  align="right" style="padding:3px">Ongkos Kirim :</td>
                                                    <td  align="right" style="padding:3px"><b>Rp. {{number_format($pesanan_pelanggan->biaya_kirim ,0, ',', '.')}}</b></td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding: 3px;"></td>
                                                    <td  align="right" style="padding:3px"></td>
                                                    <td  align="right" style="padding:3px"><b>Total :</b></td>
                                                    <td align="right" style="padding:3px"><b>Rp. {{number_format($pesanan_pelanggan->subtotal,0, ',', '.')}}</b></td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                              <hr>

                                              @if($pesanan_pelanggan->metode_pembayaran == 'Bayar di Tempat')
                                              <p><b>Informasi mengenai Pembayaran</b></p>
                                              <ul>
                                                <li>Dimohon untuk menyiapkan uang pas saat anda menerima pesanan.</li>
                                              </ul>
                                              <hr>
                                              @endif

                                              @if($pesanan_pelanggan->layanan_kurir != "")
                                              <p>Waktu Pengiriman: <b>{{$pesanan_pelanggan->WaktuBarangSampai}}</b> </p><br>
                                              @endif
                                              @if($pesanan_pelanggan->metode_pembayaran == 'TRANSFER')
                                              <p>Pembayaran dapat dilakukan ke Rekening Bank <b>{{strtoupper($bank->nama_bank)}} ({{$bank->no_rek}})</b> a/n <b>{{$bank->atas_nama}}</b></p>
                                              @endif
                                              <p>Pesanan Anda akan dikirimkan ke: <b>{{$data->name}}</b> </p>
                                              <p><b>{{$data->alamat}}
                                                Phone: {{$data->no_telp}}</b></p>
                                                <br>
                                                <center><p>Jika Anda membutuhkan bantuan, silahkan hubungi kami di <b>{{$pesanan_pelanggan->warung->no_telpon}}</b> atau email ke <b>{{$pesanan_pelanggan->warung->email}}</b> </p></center><p>
                                                  Salam,<br>

                                                  <p><b>{{$pesanan_pelanggan->warung->name}}</b></p>
                                                </p>
                                              </div>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              {{--<p>This is a really simple email template. Its sole purpose is to get the recipient to click the button with no distractions.</p>--}}
                              {{--<p>Good luck! Hope it works.</p>--}}
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                  </table>

                  <!-- START FOOTER -->
                  <div class="footer">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td class="content-block">
                          <span class="apple-link">&copy; 2018 PT Andaglos Global Teknologi - All Rights Reserved</span>
                          {{--<br> Don't like these emails? <a href="http://andaglos.id">Unsubscribe</a>.--}}
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block powered-by">
                          Powered by <a href="http://andaglos.id" font-color="blue">PT Andaglos Global Teknologi</a>.
                        </td>
                      </tr>
                    </table>
                  </div>
                  <!-- END FOOTER -->

                  <!-- END CENTERED WHITE CONTAINER -->
                </div>
              </td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </body>
        </html>
