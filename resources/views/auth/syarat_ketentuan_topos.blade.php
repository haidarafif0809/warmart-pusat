<!doctype html>
<html lang="en">
<?php 
    if (Auth::check()) {
        $user      = \Auth::user()->id;
        $foto_logo = \App\UserWarung::find($user);
    }
 ?>
<head>

    <meta charset="utf-8" />
    @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    @else
    <link rel="apple-touch-icon" sizes="76x76" href="img/icon_topos.png?v=1" />
    <link rel="icon" type="image/png" href="img/icon_topos.png?v=1" />
    @endif
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
    <title>War-Mart.id</title>
    @else
    <title>To-Pos.id</title>
    @endif

    <meta name="viewport" content="width=device-width" />
    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <!--  Material Dashboard CSS    -->
    <link href="{{ asset('css/material-dashboard.css?v=1.2.0') }}" rel="stylesheet" />
    
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/material-kit.css?v=1.2.0')}}" rel="stylesheet"/>
    <link href="{{ asset('assets/assets-for-demo/vertical-nav.css')}}" rel="stylesheet" />

</head>
<body class="off-canvas-sidebar">
    <nav class="navbar  navbar-fixed-top " color-on-scroll=" " id="sectionsNav" style="background-color:#2ac326;">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                  @if(Auth::check() && Auth::user()->tipe_user == 4)
                    <a class="navbar-brand" href="{{ url('/')}}"><img class="navbar-brand" src="{{asset('/foto_ktp_user/'.$foto_logo->foto_ktp.'').'?v=1'}}"/></a>
                 @else
                    <a class="navbar-brand" href="{{ url('/')}}"><img class="navbar-brand" src="{{asset('/assets/img/examples/topos_logo.png'.'?v=1')}}"/></a>
                 @endif
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right"> 
                    <li>
                        <a data-toggle="collapse" href="#pagesExamples">
                            <p>
                                <i class="material-icons">person_add</i> Registrasi
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="pagesExamples">
                            <ul class="nav">
                                <li class="">
                                    <a href="{{ url('/register-customer') }}">
                                        <i class="material-icons">person_add</i> Pelanggan
                                    </a>
                                </li>
                                @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
                                <li class="">
                                    <a href="{{ url('/register') }}">
                                        <i class="material-icons">people</i> Komunitas
                                    </a>
                                </li> 
                                <li class="">
                                    <a href="{{ url('/register-warung') }}">
                                        <i class="material-icons">store</i> Warung
                                    </a>
                                </li> 
                                @endif
                            </ul>
                        </div>
                    </li>

                    <li class=" active ">
                        <a href="{{ url('/login') }}">
                            <i class="material-icons">fingerprint</i> Login
                        </a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper wrapper-full-page">
        <div class="full-page login-page" filter-color="black" data-image="{{ asset('img/login_bg.jpg') }}">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="content">

              <div class="main main-raised">
                <div class="section section-basic">
                    <div class="container" style="color:black;">

                        <center><b><h3 style="font-weight:bold;">Syarat dan Ketentuan sebagai Member ToPos</h3></b></center>

                        <p>Kepatuhan Anda: Sebelum menggunakan, mengakses atau memanfaatkan Platform ini, Anda sudah membaca dengan baik setiap dan seluruh Syarat dan Ketentuan ini yang antara lain berisi mengenai pedoman, pemberitahuan, aturan operasional, kebijakan dan instruksi yang berkaitan dengan pelangganan produk melalui Platform. Dan dengan melanjutkan penggunaan atau pemanfaatan fasilitas yang diberikan oleh Platform maka Anda telah menyatakan persetujuan Anda terhadap setiap dan seluruh ketentuan dalam Syarat dan Ketentuan ini. Kami akan mempublikasikan setiap perubahan yang bersifat substansial atau amandemen dari Syarat dan Ketentuan ini (apabila ada) melalui Platform dan Anda diwajibkan untuk membaca dengan baik setiap perubahan atau amandemen tersebut sehingga apabila Anda tetap menggunakan, mengakses atau memanfaatkan Platform, Anda dianggap telah mengetahui, memahami dan menyetujui perubahan atau amandemen tersebut. Apabila Anda menghendakinya, kami akan menginformasikan kepada Anda melalui e-mail Anda yang terdaftar di data kami terhadap setiap perubahan yang bersifat substansial atau amandemen dari Syarat dan Ketentuan pada saat perubahan atau amandemen tersebut dipublikasikan melalui Platform. Setiap keberatan atas adanya perubahan yang bersifat substansial atau amandemen dari Syarat dan Ketentuan dapat diajukan kepada kami selambat-lambatnya dalam jangka waktu 7 (tujuh) hari sejak tanggal perubahan atau amandemen tersebut dipublikasikan melalui Platform.</p>

                        <h4 style="font-weight:bold;">Informasi Umum</h4>

                        <ol>
                            <li>ToPos sebagai sarana penunjang bisnis berusaha menyediakan berbagai fitur dan layanan untuk menjamin keamanan dan kenyamanan para penggunanya.</li>
                            <li>ToPos sebagai sarana perantara antara Toko dan Pelanggan, untuk mengamankan setiap transaksi yang berlangsung di dalam platform ToPos melalui mekanisme ToPos. Adanya biaya ekstra (termasuk pajak dan biaya lainnya) atas segala transaksi yang terjadi di ToPos berada di luar kewenangan ToPos sebagai perantara, dan akan diurus oleh pihak-pihak yang bersangkutan (baik Toko atau pun Pelanggan) sesuai ketentuan yang berlaku di Indonesia.</li>
                            <li>ToPos mengizinkan jual beli barang dan jasa yang bisa dikirim melalui jasa pengiriman ataupun datang langsung ke Toko.</li>
                            <li>Barang atau jasa yang dapat diperdagangkan di ToPos merupakan barang yang tidak tercantum di daftar “Barang Terlarang”.</li>
                            <li>ToPos tidak bertanggung jawab atas kualitas barang, proses pengiriman, rusaknya reputasi pihak lain, dan/atau segala bentuk perselisihan yang dapat terjadi antar Pengguna.</li>
                            <li>ToPos memiliki kewenangan untuk mengambil tindakan yang dianggap perlu terhadap akun yang diduga dan/atau terindikasi melakukan penyalahgunaan, memanipulasi, dan/atau melanggar Aturan Penggunaan di ToPos, mulai dari melakukan moderasi, menghentikan layanan “Jual Barang”, membatasi jumlah pembuatan akun, membatasi atau mengakhiri hak setiap Pengguna untuk menggunakan layanan, maupun menutup akun tersebut tanpa memberikan pemberitahuan atau informasi terlebih dahulu kepada pemilik akun yang bersangkutan.</li>
                            <li>ToPos memiliki kewenangan untuk mengambil tindakan yang dianggap perlu terhadap akun Pengguna, mulai dari melakukan moderasi, menghentikan layanan “Jual Barang”, membatasi jumlah pembuatan akun, membatasi atau mengakhiri hak setiap Pengguna untuk menggunakan layanan, maupun menutup akun tersebut tanpa memberikan pemberitahuan atau informasi terlebih dahulu kepada pemilik akun yang bersangkutan.</li>
                            <li>ToPos memiliki kewenangan untuk mengambil keputusan atas permasalahan yang terjadi pada setiap transaksi.</li>
                            <li>Jika Pengguna gagal untuk mematuhi setiap ketentuan dalam Aturan Penggunaan di ToPos ini, maka ToPos berhak untuk mengambil tindakan yang dianggap perlu termasuk namun tidak terbatas pada melakukan moderasi, menghentikan layanan “Jual Barang”, menutup akun dan/atau mengambil langkah hukum selanjutnya.</li>
                            <li>ToPos hanya menjamin dana Pelanggan tetap aman jika proses transaksi dilakukan melalui transfer bank ke ToPos. Kerugian yang diakibatkan dari transaksi tunai di Toko, tidak menjadi tanggung jawab ToPos.
                                <li>ToPos berhak meminta data-data pribadi Pengguna jika diperlukan.</li>
                                <li>Aturan Penggunaan ToPos dapat berubah sewaktu-waktu dan/atau diperbaharui dari waktu ke waktu tanpa pemberitahuan terlebih dahulu. Dengan mengakses ToPos, Pengguna dianggap menyetujui perubahan-perubahan dalam Aturan Penggunaan ToPos.</li>
                                <li>Aturan Penggunaan ToPos pada Situs ToPos berlaku mutatis mutandis untuk penggunaan Aplikasi ToPos.</li>
                                <li>Hati-hati terhadap penipuan yang mengatasnamakan ToPos. </li>
                            </ol>

                            <h4 style="font-weight:bold;">Pengguna</h4>
                            <ol>
                                <li>Pengguna wajib mengisi data pribadi secara lengkap dan jujur di halaman web atau aplikasi ToPos dengan identitas asli (KTP/Pasport/Sim), Nomor Handphone, Alamat Valid, Nama, dan e-mail yang masih aktif untuk digunakan (dapat menerima e-mail). Mengunakan e-mail milik pribadi pendaftar sendiri, tidak menggunakan e-mail orang lain atau e-mail yang sudah tidak aktif.</li>
                                <li>Pengguna bertanggung jawab atas keamanan dari akun termasuk penggunaan e-mail, nomor hand phone dan password.</li>
                                <li>Penggunaan fasilitas apapun yang disediakan oleh ToPos mengindikasikan bahwa Pengguna telah memahami dan menyetujui segala aturan yang diberlakukan oleh ToPos.</li>
                                <li>Pengguna tidak diperbolehkan menggunakan ToPos untuk melanggar peraturan yang ditetapkan oleh hukum di Indonesia maupun di negara lainnya.</li>
                                <li>Pengguna bertanggung jawab atas segala risiko yang timbul di kemudian hari atas informasi yang diberikannya ke dalam ToPos, termasuk namun tidak terbatas pada hal-hal yang berkaitan dengan hak cipta, merek, desain industri, desain tata letak industri dan hak paten atas suatu produk.</li>
                                <li>Pengguna diwajibkan menghargai hak-hak Pengguna lainnya dengan tidak memberikan informasi pribadi ke pihak lain tanpa izin pihak yang bersangkutan.</li>
                                <li>Pengguna tidak diperkenankan mengirimkan e-mail spam dengan merujuk ke bagian apapun dari ToPos.</li>
                                <li>Administrator ToPos berhak menyesuaikan dan/atau menghapus informasi barang, dan menonaktifkan akun Pengguna.</li>
                                <li>ToPos memiliki hak untuk memblokir penggunaan sistem terhadap Pengguna yang melanggar peraturan perundang-undangan yang berlaku di wilayah Indonesia.
                                    <li>Pengguna akan mendapatkan beragam informasi promo terbaru dan penawaran eksklusif. Namun Pengguna dapat berhenti berlangganan (unsubscribe) jika tidak ingin menerima informasi tersebut.</li>
                                    <li>Pengguna dilarang menggunakan kata-kata kasar yang tidak sesuai norma, baik saat berdiskusi di fitur kirim pesan atau chat maupun kolom diskusi retur. Jika ditemukan pelanggaran, ToPos berhak memberikan sanksi seperti menonaktifkan sementara fitur pesan, dan membekukan atau menonaktifkan akun Pengguna.</li>
                                    <li>Pengguna dilarang menggunakan fitur kirim pesan atau chat sebagai iklan promosi barang dagangan di ToPos maupun di platform atau situs lain yang dapat mengganggu Pengguna lainnya. Jika ditemukan pelanggaran, ToPos berhak memberikan sanksi seperti menonaktifkan fitur pesan dan/atau akun Pengguna.</li>
                                    <li>Pengguna dilarang menggunakan fitur kirim pesan atau chat sebagai sarana penelitian, kuesioner, atau survey. Jika ditemukan pelanggaran, ToPos berhak memberikan sanksi seperti menonaktifkan fitur pesan dan/atau akun Pengguna.</li>
                                    <li>Pengguna dilarang melakukan transfer atau menjual akun Pengguna ke Pengguna lain atau ke pihak lain tanpa persetujuan dari ToPos.</li>
                                    <li>Pengguna dengan ini menyatakan bahwa Pengguna telah mengetahui seluruh peraturan perundang- undangan yang berlaku di wilayah Republik Indonesia dalam setiap transaksi di ToPos, dan tidak akan melakukan tindakan apapun yang mungkin melanggar peraturan perundang-undangan yang berlaku di wilayah Republik Indonesia.</li>
                                    <li>Pengguna dilarang membuat salinan, modifikasi, turunan atau distribusi konten atau mempublikasikan tampilan yang berasal dari ToPos yang dapat melanggar Hak Kekayaan Intelektual ToPos.</li>
                                    <li>Pengguna dilarang membuat akun ToPos dengan tujuan menghindari batasan pelangganan, penyalahgunaan akun atau konsekuensi kebijakan Aturan Penggunaan ToPos lainnya.</li>
                                </ol>

                                <h4 style="font-weight:bold;">Toko</h4>
                                <ol>
                                    <li>Toko adalah pihak yang melakukan penjualan barang baik melalui aplikasi mobile maupun langsung di Toko.</li>
                                    <li>Pemilik Toko wajib beragama Islam dibuktikan dengan foto KTP yang dikirim saat pendaftaran.</li>
                                    <li>ToPos dapat melakukan tindakan termasuk namun tidak terbatas pada pembekuan akun apabila Toko terbukti melakukan pemalsuan identitas.</li>
                                    <li>ToPos dapat melakukan tindakan termasuk namun tidak terbatas pada peringatan dan pembekuan akun apabila Toko melakukan memasang sticker “Produk Muslim” pada produk-produk yang dibuat oleh orang dan atau perusahaan-perusahaan yang dimiliki atau dikendalikan oleh non muslim.</li>
                                    <li>Toko bertanggung jawab secara penuh atas segala risiko yang timbul di kemudian hari terkait dengan informasi yang dibuatnya, termasuk, namun tidak terbatas pada hal-hal yang berkaitan dengan hak cipta, merek, desain industri, desain tata letak sirkuit, hak paten dan/atau izin lain yang telah ditetapkan atas suatu produk menurut hukum yang berlaku di Indonesia.</li>
                                    <li>Toko hanya diperbolehkan menjual barang-barang yang tidak tercantum di daftar “Barang Terlarang”.</li>
                                    <li>Toko wajib menempatkan barang dagangan sesuai dengan kategori dan subkategorinya.</li>
                                    <li>Toko wajib mengisi nama atau judul barang dengan jelas, singkat dan padat.</li>
                                    <li>Toko wajib menampilkan gambar barang yang sesuai dengan deskripsi barang yang dijual dan tidak mencantumkan logo ataupun alamat situs jual-beli lain pada gambar. </li>
                                    <li>Toko wajib mengisi harga yang sesuai dengan harga sebenarnya.</li>
                                    <li>Toko dilarang melakukan duplikasi penjualan barang dengan menyalin atau menggunakan gambar dari penjual  Toko lain.</li>
                                    <li>Toko wajib memperbarui (update) ketersediaan dan status barang yang dijual.</li>
                                    <li>Catatan Toko diperuntukkan bagi Toko yang ingin memberikan catatan tambahan yang tidak terkait dengan deskripsi barang kepada calon Pelanggan. Catatan Toko tetap tunduk terhadap Aturan Penggunaan ToPos.</li>
                                    <li>Toko wajib mengisi kolom Deskripsi Barang sesuai dengan Aturan Penggunaan di ToPos.</li>
                                    <li>Toko dilarang membuat transaksi fiktif atau palsu demi kepentingan menaikkan feedback. ToPos berhak mengambil tindakan seperti pemblokiran akun atau tindakan lainnya apabila ditemukan tindakan kecurangan. </li>
                                    <li>Toko dapat menyediakan jasa pengiriman sendiri, apabila pelanggan memilih jasa ekspedisi pengiriman internal Toko, maka semua hal terkait risiko pengiriman barang adalah tanggung jawab Toko.</li>
                                    <li>Toko dapat menentukan pilihan jasa ekspedisi diluar jasa pengiriman internal yang disediakan Toko.</li>
                                    <li>Toko wajib mengirimkan barang menggunakan jasa ekspedisi sesuai dengan yang dipilih oleh Pelanggan pada saat melakukan transaksi di dalam sistem ToPos.</li>
                                    <li>Apabila Toko menggunakan jasa ekspedisi yang berbeda dengan jasa dan/atau jenis jasa ekspedisi yang dipilih oleh Pelanggan pada saat melakukan transaksi di dalam sistem ToPos maka Toko bertanggung jawab atas segala hal selama proses pengiriman yang disebabkan oleh penggunaan jasa dan/atau jenis jasa ekspedisi yang berbeda tersebut.</li>
                                    <li>Toko memahami dan menyetujui bahwa kekurangan dana biaya kirim yang disebabkan oleh penggunaan jasa dan/atau jenis jasa yang berbeda dari pilihan Pelanggan pada saat melakukan transaksi di dalam sistem ToPos merupakan tanggung jawab Toko terkecuali perbedaan tersebut atas permintaan Pelanggan.</li>
                                    <li>Pelanggan berhak atas kelebihan dana dari biaya kirim yang diakibatkan perbedaan penggunaan jasa dan/atau jenis jasa ekspedisi oleh Toko dari pilihan Pelanggan pada saat melakukan transaksi di dalam sistem ToPos.</li>
                                    <li>Toko wajib memenuhi ketentuan yang sudah ditetapkan oleh pihak jasa ekspedisi berkaitan dengan packing barang yang aman serta menggunakan asuransi dan/atau packing kayu pada barang-barang tertentu sehingga apabila barang rusak atau hilang Toko dapat mengajukan klaim ke pihak jasa ekspedisi.</li>
                                    <li>Toko dikenakan biaya pengembangan teknologi dan pemasaran sebesar:
                                        <ul>
                                            <li>Barang/Hitung Stok : 20% dari margin keuntungan kotor. Margin dihitung dari Harga Jual Bersih – Harga Beli Bersih. Harga Jual Bersih dan Harga Jual Bersih adalah harga setelah dipotong diskon dan setelah dikenakan pajak (jika ada).</li>
                                            <li>Jasa/tidak hitung stok : 2% dari total penjualan bersih. Penjualan bersih adalah total penjualan dikurangi diskon dan dikenakan pajak (jika ada).</li>
                                        </ul></li>
                                        <li>Biaya tersebut diatas dibayar oleh Toko setiap tanggal 1 – 5 setiap bulan untuk periode tanggal 1 – akhir bulan sebelumnya.</li>
                                        <li>Biaya pemasaran digunakan termasuk namun tidak terbatas pada bagi hasil dengan penggiat komunitas, bagi hasil dengan orang yang “Ajak Teman”, iklan di media online maupun offline, memberikan program promo diskon/hadiah, sosialisasi dan kegiatan-kegiatan yang mendukung pertumbuhan jumlah pengguna ToPos.</li>
                                        <li>Pengelolaan biaya pemasaran adalah hak ToPos dan ToPos tidak berkewajiban untuk memberikan laporan penggunaan dbiaya promosi tersebut kepada Toko.</li>
                                        <li>Biaya Pengembangan Teknologi adalah biaya yang digunakan oleh ToPos untuk menyediakan sumber daya dalam pengembangan teknologi termasuk namun tidak terbatas pada: pengembangan perangkat lunak, infrastruktur server, koneksi internet ke server, perawatan database, penanganan verifikasi dan customer service.</li>
                                        <li>Biaya Pengembangan Teknologi sepenuhnya akan dikelola oleh PT Andaglos Global Teknologi.</li>
                                        <li>Jika hingga akhir tanggal jatuh tempo, Toko tidak melakukan pembayaran biaya Pengembangan Teknologi dan Biaya Pemasaran, maka secara otomatis Toko dinonaktifkan sementara hingga Toko menyelesaikan kewajibanya.</li>
                                        <li>Pembayaran Biaya tersebut wajib melalui rekening yang telah ditetapkan oleh ToPos yang tercantum pada aplikasi ToPos.</li>

                                    </ol>

                                    <h6 style="font-weight:bold;">Transaksi Melalui Aplikasi</h6>
                                    <ol>
                                        <li>Pelanggan dapat melakukan order barang melalui aplikasi ToPos</li>
                                        <li>Pilihan pembayaran yang tersedia termasuk namun tidak terbatas pada:</li>
                                        <ul>
                                            <li>Pembayaran di Toko</li>
                                            <li>COD (Cash On Delivery)</li>
                                            <li>Transfer Bank</li>
                                        </ul>
                                        <li>Pembayaran di Toko berlaku apabila pelanggan melakukan order dari aplikasi yang ingin mengambil barang sendiri ke Toko.</li>
                                        <li>Pembayaran COD (Cash On Delivery) berlaku apabila pelanggan melakukan order melalui aplikasi dan barang dikirim ke lokasi pelanggan, pembayaran dilakukan setelah barang sampai ke lokasi pengiriman.</li>
                                        <li>Pembayaran melalui transfer bank berlaku apabila pelanggan melakukan order dari aplikasi kemudian memilih jenis pembayaran transfer bank.</li>
                                        <li>Demi keamanan dan kenyamanan para Pengguna, yang menggunakan fasilitas transfer dapat melakukan pembayaran ke ToPos melalui ke No Rek: </li>
                                        <li>Jika pelanggan memilih cara pembayaran transfer, wajib transfer sesuai dengan nominal total belanja dari transaksi dalam waktu 2 jam (dengan asumsi Pelanggan telah mempelajari informasi barang yang telah dipesannya). Jika dalam waktu 2 jam barang dipesan tetapi Pelanggan tidak mentransfer dana maka transaksi akan dibatalkan secara otomatis.</li>
                                        <li>Pelanggan tidak dapat membatalkan transaksi setelah melunasi pembayaran.</li>
                                        <li>Toko wajib mengirimkan barang sesuai dengan pilihan pengiriman barang oleh pelanggan.</li>
                                        <li>Apabila menggunakan jasa ekspedisi eksternal, Toko wajib mendaftarkan nomor resi pengiriman yang benar dan asli setelah status transaksi “Dibayar”. Satu nomor resi hanya berlaku untuk satu nomor transaksi di ToPos.</li>
                                        <li>Jika Toko tidak mengirimkan barang dalam batas waktu pengiriman sejak pembayaran (1x24) jam untuk biaya pengiriman reguler atau 3 jam untuk pengiriman lokal, maka Toko dianggap telah menolak pesanan. Sehingga sistem secara otomatis memberikan feedback negatif dan reputasi tolak pesanan, serta mengembalikan seluruh dana (refund) ke pelanggan.</li>
                                        <li>Pengembalian dana transaksi dilakukan dengan mentransfer ke Nomor Rekening Pelanggan. Pengembalian dana dilakukan dalam waktu maksimal 14 hari kerja setelah pembayaran.</li>
                                        <li>Jika transaksi ditolak oleh Toko, ToPos akan otomatis memberikan pilihan Toko-Toko lain terdekat yang menyediakan barang tersebut. </li>
                                        <li>Apabila harga barang pengganti lebih mahal maka dana selisih akan ditanggung oleh pelanggan. Barang pengganti tersebut adalah barang yang sesuai dengan transaksi awal dengan spesifikasi yang sama dan harga yang tidak terlalu berbeda. Jika harga barang pengganti lebih murah, maka dana selisih akan dikembalikan ke pelanggan setelah transaksi dinyatakan selesai oleh sistem ToPos.</li>
                                        <li>Sistem ToPos secara otomatis mengecek status pengiriman barang melalui nomor resi yang diberikan Toko. Apabila nomor resi terdeteksi tidak valid dan Toko tidak melakukan ubah resi valid dalam 1x24 jam maka seluruh dana akan dikembalikan ke Pelanggan. Jika Toko memasukkan nomor resi tidak valid lebih dari satu kali maka ToPos akan mengembalikan seluruh dana transaksi kepada Pelanggan dan Toko mendapatkan feedback negatif.</li>
                                        <li>Jika Pelanggan tidak memberikan konfirmasi penerimaan barang dalam waktu 1x24 jam sejak status resi pengiriman dinyatakan telah diterima/delivered oleh sistem tracking jasa pengiriman, ToPos akan mentransfer dana langsung ke Toko tanpa memberikan konfirmasi ke Pelanggan.</li>
                                        <li>Sistem secara otomatis memberikan feedback (rekomendasi) positif dan mentransfer dana pembayaran ke Toko jika status resi menunjukkan ‘Barang diterima’ dan Pelanggan telah melewati batas waktu untuk konfirmasi.</li>
                                        <li>Pelanggan dapat memperbarui feedback maksimal 3x24 jam setelah transaksi dinyatakan selesai oleh sistem ToPos.</li>
                                        <li>Retur (Pengembalian Barang) hanya diperbolehkan jika kesalahan dilakukan oleh Toko dan barang tidak sesuai deskripsi.</li>
                                        <li>Retur tidak dapat dilakukan setelah transaksi selesai menurut sistem general tracking Toko atau Pelanggan telah melakukan konfirmasi barang diterima dan tidak memilih retur.</li>
                                        <li>Batas waktu retur maksimal 1x24 jam, jika sudah melewati waktu yang ditentukan maka dianggap selesai.</li>
                                        <li>Penanganan retur dilakukan setelah verifikasi dari petugas ToPos.</li>
                                    </ol>


                                    <h6 style="font-weight:bold;">Transaksi di Toko</h6>
                                    <ol>
                                        <li>Pelanggan dapat melihat lokasi Toko terdekat melalui aplikasi ToPos.</li>
                                        <li>Pelanggan dapat langsung mendatangi ke lokasi Toko tersebut dan melakukan transaksi serta pembayaran di Toko.</li>
                                        <li>Toko wajib menginput transaksi ke aplikasi ToPos.</li>
                                        <li>Apabila Toko tidak melakukan input ke ToPos dan dapat dibuktikan dengan bukti-bukti maka ToPos berhak melakukan tindakan peringatan hingga pembekuan akun Toko.</li>
                                        <li>Hal-hal mengenai retur barang dan refund merupakan tanggung jawab Toko kepada pelanggan secara langsung.</li>
                                    </ol>


                                    <hr style="list-style:bold;">


                                    <h4 style="font-weight:bold;">Barang Terlarang</h4>

                                    <p>Barang yang dijual oleh Toko harus barang Halal dan Toyib. ToPos telah dan akan terus melakukan hal-hal sebagaimana dipersyaratkan oleh peraturan perundang-undangan dan hal-hal yang dilarang atau di haramkan oleh MUI (Majelis Ulama Indonesia) untuk mencegah terjadinya perdagangan barang-barang yang melanggar ketentuan hukum dan Agama yang berlaku dan/atau hak pribadi pihak ketiga. Berkenaan dengan hal tersebut, berikut adalah barang-barang yang dilarang untuk diperjualbelikan melalui ToPos:</p>
                                    <ol>
                                        <li>Segala bentuk tulisan yang dapat berpengaruh negatif terhadap ToPos.</li>
                                        <li>Segala jenis rokok baik rokok tembakau maupun rokok elektrik.</li>
                                        <li>Narkotika, obat-obat tidak terdaftar di Dinkes dan/atau BPOM, dan obat yang harus disertai dengan resep dari Dokter.</li>
                                        <li>Senjata api, kelengkapan senjata api, replika senjata api, airsoft gun, air gun, dan peluru atau sejenis peluru, senjata tajam, serta jenis senjata lainnya.</li>
                                        <li>Dokumen pemerintahan dan perjalanan.</li>
                                        <li>Bagian/organ manusia.</li>
                                        <li>Mailing list dan informasi pribadi.</li>
                                        <li>Barang-barang yang melecehkan pihak/ras tertentu atau dapat menyinggung perasaan orang lain.</li>
                                        <li>Barang yang berhubungan dengan kepolisian.</li>
                                        <li>Barang yang belum tersedia (pre order) terkecuali sanggup kirim barang dalam waktu 2 jam kerja sejak transaksi terbayar.</li>
                                        <li>Barang curian.</li>
                                        <li>Barang mistis.</li>
                                        <li>Pembuka kunci dan segala aksesori penunjang tindakan perampokan/pencurian.</li>
                                        <li>Barang yang dapat dan atau mudah meledak, menyala atau terbakar sendiri.</li>
                                        <li>Pornografi, sex toys, alat untuk memperbesar organ vital pria, maupun barang asusila lainnya.</li>
                                        <li>Barang cetakan/rekaman yang isinya dapat mengganggu keamanan & ketertiban serta stabilitas nasional.</li>
                                        <li>CD, DVD, dan Software bajakan.</li>
                                        <li>Gadget (ponsel, tablet, phablet, smartwatch, dan sejenisnya) replika atau berasal dari pasar gelap (black market).</li>
                                        <li>Barang-barang lain yang dilarang untuk diperjualbelikan secara bebas berdasarkan hukum yang berlaku di Indonesia dan hukum MUI (Majelis Ulama Indonesia).</li>
                                    </ol>

                                    <h4 style="font-weight:bold;">Sanksi</h4>
                                    <ol>
                                        <p>Segala tindakan yang melanggar peraturan di ToPos akan dikenakan sanksi berupa termasuk namun tidak terbatas pada:</p>
                                        <li>Toko mendapatkan 1 feedback negatif apabila tidak mengirimkan barang dalam batas waktu pengiriman sejak pembayaran (1x24 jam kerja untuk biaya pengiriman reguler atau 3 jam untuk biaya pengiriman kilat).</li>
                                        <li>Toko mendapatkan 1 feedback negatif jika sudah 5 kali menolak pesanan.</li>
                                        <li>Toko mendapatkan 3 feedback negatif jika sudah memroses pesanan namun tidak kirim barang dalam batas waktu pengiriman sejak pembayaran (1x24 jam kerja untuk pengiriman reguler atau 3 jam untuk pengiriman kilat).</li>
                                        <li>Pelaporan ke pihak terkait (Kepolisian, dll).
                                        </li>
                                    </ol>


                                    <h4 style="font-weight:bold;">Pembatasan Tanggung Jawab</h4>
                                    <ol>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul dari dan dalam kaitannya dengan informasi yang dituliskan oleh Pengguna ToPos.</li>
                                        <li>ToPos tidak bertanggung jawab atas segala pelanggaran hak cipta, merek, desain industri, desain tata letak sirkuit, hak paten atau hak-hak pribadi lain yang melekat atas suatu barang, berkenaan dengan segala informasi yang dibuat oleh Toko. Untuk melaporkan pelanggaran hak cipta, merek, desain industri, desain tata letak sirkuit, hak paten atau hak-hak pribadi lain.  </li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul berkenaan dengan penggunaan barang yang dibeli melalui ToPos, dalam hal terjadi pelanggaran peraturan perundang-undangan.</li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul berkenaan dengan diaksesnya akun Pengguna oleh pihak lain.</li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat transaksi di luar Nomor Rekening ToPos yang telah ditentukan.</li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat kesalahan atau perbedaan nominal yang seharusnya ditransfer ke rekening atas nama ToPos.</li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul apabila transaksi telah selesai secara sistem (dana telah masuk ke Rekening Toko ataupun Pelanggan).</li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat kehilangan barang ketika proses transaksi berjalan dan/atau selesai.</li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat kesalahan Pengguna ataupun pihak lain dalam transfer dana ke rekening ToPos.</li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul apabila akun dibekukan dan/atau dinonaktifkan karena melanggar aturan-aturan yang telah ditetapkan oleh ToPos.</li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat penyalahgunaan nomor kontak dan/atau alamat e-mail milik Pengguna maupun pihak lainnya.</li>
                                        <li>ToPos tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat tindakan Penggiat Komunitas yang tidak sesuai dengan peraturan, syarat dan ketentuan ToPos.</li>
                                    </ol>




                                </div>
                            </div>
                        </div>
                    </div>

                    <footer class="footer">
                        <div class="container">
                            <p class="copyright pull-right">
                                &copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>
                                <a href="https://andaglos.id">PT Andaglos Global Teknologi</a>, made with love for a better web
                            </p>
                        </div>
                    </footer>
                </div>
            </div>
        </body>







        <!--   Core JS Files   -->
        <script src="{{ asset('js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/material.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
        <!-- Library for adding dinamically elements -->
        <script src="{{ asset('js/arrive.min.js') }}" type="text/javascript"></script>
        <!-- Forms Validations Plugin -->
        <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
        <!-- Promise Library for SweetAlert2 working on IE -->
        <script src="{{ asset('js/es6-promise-auto.min.js') }}"></script>
        <!--  Plugin for Date Time Picker and Full Calendar Plugin-->
        <script src="{{ asset('js/moment.min.js') }}"></script>
        <!--  Charts Plugin, full documentation here: https://gionkunz.github.io/chartist-js/ -->
        <script src="{{ asset('js/chartist.min.js') }}"></script>
        <!--  Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
        <script src="{{ asset('js/jquery.bootstrap-wizard.js') }}"></script>
        <!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
        <script src="{{ asset('js/bootstrap-notify.js') }}"></script>
        <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
        <script src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>
        <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
        <script src="{{ asset('js/jquery-jvectormap.js') }}"></script>
        <!-- Sliders Plugin, full documentation here: https://refreshless.com/nouislider/ -->
        <script src="{{ asset('js/nouislider.min.js') }}"></script>
        <!--  Google Maps Plugin    -->
        <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
        <!--  Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
        <script src="{{ asset('js/jquery.select-bootstrap.js') }}"></script>
        <!--  DataTables.net Plugin, full documentation here: https://datatables.net/    -->
        <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
        <!-- Sweet Alert 2 plugin, full documentation here: https://limonte.github.io/sweetalert2/ -->
        <script src="{{ asset('js/sweetalert2.js') }}"></script>
        <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
        <script src="{{ asset('js/jasny-bootstrap.min.js') }}"></script>
        <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
        <script src="{{ asset('js/fullcalendar.min.js') }}"></script>
        <!-- Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
        <script src="{{ asset('js/jquery.tagsinput.js') }}"></script>
        <!-- Material Dashboard javascript methods -->
        <script src="{{ asset('js/material-dashboard.js?v=1.2.0') }}"></script>

        <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('js/demo.js') }}"></script> 
        <script src="{{ asset('js/material-kit.js?v=1.2.0')}}" type="text/javascript"></script>

