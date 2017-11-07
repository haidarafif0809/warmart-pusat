<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon.jpg" />
    <link rel="icon" type="image/png" href="img/favicon.jpg" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <title>War-Mart.id</title>

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
    <nav class="navbar  navbar-fixed-top " color-on-scroll=" " id="sectionsNav" style="background-color:purple;">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="" ><p style="color:white;">War-mart.id</p></a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right" style="background-color:purple;"> 
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
                     
                        <center><b><h3 style="font-weight:bold;">Syarat dan Ketentuan sebagai Member WarMart</h3></b></center>

                        <p>Kepatuhan Anda: Sebelum menggunakan, mengakses atau memanfaatkan Platform ini, Anda sudah membaca dengan baik setiap dan seluruh Syarat dan Ketentuan ini yang antara lain berisi mengenai pedoman, pemberitahuan, aturan operasional, kebijakan dan instruksi yang berkaitan dengan pelangganan produk melalui Platform. Dan dengan melanjutkan penggunaan atau pemanfaatan fasilitas yang diberikan oleh Platform maka Anda telah menyatakan persetujuan Anda terhadap setiap dan seluruh ketentuan dalam Syarat dan Ketentuan ini. Kami akan mempublikasikan setiap perubahan yang bersifat substansial atau amandemen dari Syarat dan Ketentuan ini (apabila ada) melalui Platform dan Anda diwajibkan untuk membaca dengan baik setiap perubahan atau amandemen tersebut sehingga apabila Anda tetap menggunakan, mengakses atau memanfaatkan Platform, Anda dianggap telah mengetahui, memahami dan menyetujui perubahan atau amandemen tersebut. Apabila Anda menghendakinya, kami akan menginformasikan kepada Anda melalui e-mail Anda yang terdaftar di data kami terhadap setiap perubahan yang bersifat substansial atau amandemen dari Syarat dan Ketentuan pada saat perubahan atau amandemen tersebut dipublikasikan melalui Platform. Setiap keberatan atas adanya perubahan yang bersifat substansial atau amandemen dari Syarat dan Ketentuan dapat diajukan kepada kami selambat-lambatnya dalam jangka waktu 7 (tujuh) hari sejak tanggal perubahan atau amandemen tersebut dipublikasikan melalui Platform.</p>

                        <h4 style="font-weight:bold;">Informasi Umum</h4>

                        <ol>
                            <li>WarMart sebagai sarana penunjang bisnis berusaha menyediakan berbagai fitur dan layanan untuk menjamin keamanan dan kenyamanan para penggunanya.</li>
                            <li>WarMart sebagai sarana perantara antara Warung dan Pelanggan, untuk mengamankan setiap transaksi yang berlangsung di dalam platform WarMart melalui mekanisme WarMart. Adanya biaya ekstra (termasuk pajak dan biaya lainnya) atas segala transaksi yang terjadi di WarMart berada di luar kewenangan WarMart sebagai perantara, dan akan diurus oleh pihak-pihak yang bersangkutan (baik Warung atau pun Pelanggan) sesuai ketentuan yang berlaku di Indonesia.</li>
                            <li>WarMart mengizinkan jual beli barang dan jasa yang bisa dikirim melalui jasa pengiriman ataupun datang langsung ke Warung.</li>
                            <li>Barang atau jasa yang dapat diperdagangkan di WarMart merupakan barang yang tidak tercantum di daftar “Barang Terlarang”.</li>
                            <li>WarMart tidak bertanggung jawab atas kualitas barang, proses pengiriman, rusaknya reputasi pihak lain, dan/atau segala bentuk perselisihan yang dapat terjadi antar Pengguna.</li>
                            <li>WarMart memiliki kewenangan untuk mengambil tindakan yang dianggap perlu terhadap akun yang diduga dan/atau terindikasi melakukan penyalahgunaan, memanipulasi, dan/atau melanggar Aturan Penggunaan di WarMart, mulai dari melakukan moderasi, menghentikan layanan “Jual Barang”, membatasi jumlah pembuatan akun, membatasi atau mengakhiri hak setiap Pengguna untuk menggunakan layanan, maupun menutup akun tersebut tanpa memberikan pemberitahuan atau informasi terlebih dahulu kepada pemilik akun yang bersangkutan.</li>
                            <li>WarMart memiliki kewenangan untuk mengambil tindakan yang dianggap perlu terhadap akun Pengguna, mulai dari melakukan moderasi, menghentikan layanan “Jual Barang”, membatasi jumlah pembuatan akun, membatasi atau mengakhiri hak setiap Pengguna untuk menggunakan layanan, maupun menutup akun tersebut tanpa memberikan pemberitahuan atau informasi terlebih dahulu kepada pemilik akun yang bersangkutan.</li>
                            <li>WarMart memiliki kewenangan untuk mengambil keputusan atas permasalahan yang terjadi pada setiap transaksi.</li>
                            <li>Jika Pengguna gagal untuk mematuhi setiap ketentuan dalam Aturan Penggunaan di WarMart ini, maka WarMart berhak untuk mengambil tindakan yang dianggap perlu termasuk namun tidak terbatas pada melakukan moderasi, menghentikan layanan “Jual Barang”, menutup akun dan/atau mengambil langkah hukum selanjutnya.</li>
                            <li>WarMart hanya menjamin dana Pelanggan tetap aman jika proses transaksi dilakukan melalui transfer bank ke WarMart. Kerugian yang diakibatkan dari transaksi tunai di warung, tidak menjadi tanggung jawab WarMart.
                                <li>WarMart berhak meminta data-data pribadi Pengguna jika diperlukan.</li>
                                <li>Aturan Penggunaan WarMart dapat berubah sewaktu-waktu dan/atau diperbaharui dari waktu ke waktu tanpa pemberitahuan terlebih dahulu. Dengan mengakses WarMart, Pengguna dianggap menyetujui perubahan-perubahan dalam Aturan Penggunaan WarMart.</li>
                                <li>Aturan Penggunaan WarMart pada Situs WarMart berlaku mutatis mutandis untuk penggunaan Aplikasi WarMart.</li>
                                <li>Hati-hati terhadap penipuan yang mengatasnamakan WarMart. </li>
                            </ol>

                            <h4 style="font-weight:bold;">Pengguna</h4>
                            <ol>
                                <li>Pengguna wajib mengisi data pribadi secara lengkap dan jujur di halaman web atau aplikasi WarMart dengan identitas asli (KTP/Pasport/Sim), Nomor Handphone, Alamat Valid, Nama, dan e-mail yang masih aktif untuk digunakan (dapat menerima e-mail). Mengunakan e-mail milik pribadi pendaftar sendiri, tidak menggunakan e-mail orang lain atau e-mail yang sudah tidak aktif.</li>
                                <li>Pengguna bertanggung jawab atas keamanan dari akun termasuk penggunaan e-mail, nomor hand phone dan password.</li>
                                <li>Penggunaan fasilitas apapun yang disediakan oleh WarMart mengindikasikan bahwa Pengguna telah memahami dan menyetujui segala aturan yang diberlakukan oleh WarMart.</li>
                                <li>Pengguna tidak diperbolehkan menggunakan WarMart untuk melanggar peraturan yang ditetapkan oleh hukum di Indonesia maupun di negara lainnya.</li>
                                <li>Pengguna bertanggung jawab atas segala risiko yang timbul di kemudian hari atas informasi yang diberikannya ke dalam WarMart, termasuk namun tidak terbatas pada hal-hal yang berkaitan dengan hak cipta, merek, desain industri, desain tata letak industri dan hak paten atas suatu produk.</li>
                                <li>Pengguna diwajibkan menghargai hak-hak Pengguna lainnya dengan tidak memberikan informasi pribadi ke pihak lain tanpa izin pihak yang bersangkutan.</li>
                                <li>Pengguna tidak diperkenankan mengirimkan e-mail spam dengan merujuk ke bagian apapun dari WarMart.</li>
                                <li>Administrator WarMart berhak menyesuaikan dan/atau menghapus informasi barang, dan menonaktifkan akun Pengguna.</li>
                                <li>WarMart memiliki hak untuk memblokir penggunaan sistem terhadap Pengguna yang melanggar peraturan perundang-undangan yang berlaku di wilayah Indonesia.
                                    <li>Pengguna akan mendapatkan beragam informasi promo terbaru dan penawaran eksklusif. Namun Pengguna dapat berhenti berlangganan (unsubscribe) jika tidak ingin menerima informasi tersebut.</li>
                                    <li>Pengguna dilarang menggunakan kata-kata kasar yang tidak sesuai norma, baik saat berdiskusi di fitur kirim pesan atau chat maupun kolom diskusi retur. Jika ditemukan pelanggaran, WarMart berhak memberikan sanksi seperti menonaktifkan sementara fitur pesan, dan membekukan atau menonaktifkan akun Pengguna.</li>
                                    <li>Pengguna dilarang menggunakan fitur kirim pesan atau chat sebagai iklan promosi barang dagangan di WarMart maupun di platform atau situs lain yang dapat mengganggu Pengguna lainnya. Jika ditemukan pelanggaran, WarMart berhak memberikan sanksi seperti menonaktifkan fitur pesan dan/atau akun Pengguna.</li>
                                    <li>Pengguna dilarang menggunakan fitur kirim pesan atau chat sebagai sarana penelitian, kuesioner, atau survey. Jika ditemukan pelanggaran, WarMart berhak memberikan sanksi seperti menonaktifkan fitur pesan dan/atau akun Pengguna.</li>
                                    <li>Pengguna dilarang melakukan transfer atau menjual akun Pengguna ke Pengguna lain atau ke pihak lain tanpa persetujuan dari WarMart.</li>
                                    <li>Pengguna dengan ini menyatakan bahwa Pengguna telah mengetahui seluruh peraturan perundang- undangan yang berlaku di wilayah Republik Indonesia dalam setiap transaksi di WarMart, dan tidak akan melakukan tindakan apapun yang mungkin melanggar peraturan perundang-undangan yang berlaku di wilayah Republik Indonesia.</li>
                                    <li>Pengguna dilarang membuat salinan, modifikasi, turunan atau distribusi konten atau mempublikasikan tampilan yang berasal dari WarMart yang dapat melanggar Hak Kekayaan Intelektual WarMart.</li>
                                    <li>Pengguna dilarang membuat akun WarMart dengan tujuan menghindari batasan pelangganan, penyalahgunaan akun atau konsekuensi kebijakan Aturan Penggunaan WarMart lainnya.</li>
                                </ol>

                                <h4 style="font-weight:bold;">Warung</h4>
                                <ol>
                                    <li>Warung adalah pihak yang melakukan penjualan barang baik melalui aplikasi mobile maupun langsung di warung.</li>
                                    <li>Pemilik Warung wajib beragama Islam dibuktikan dengan foto KTP yang dikirim saat pendaftaran.</li>
                                    <li>WarMart dapat melakukan tindakan termasuk namun tidak terbatas pada pembekuan akun apabila warung terbukti melakukan pemalsuan identitas.</li>
                                    <li>WarMart dapat melakukan tindakan termasuk namun tidak terbatas pada peringatan dan pembekuan akun apabila warung melakukan memasang sticker “Produk Muslim” pada produk-produk yang dibuat oleh orang dan atau perusahaan-perusahaan yang dimiliki atau dikendalikan oleh non muslim.</li>
                                    <li>Warung bertanggung jawab secara penuh atas segala risiko yang timbul di kemudian hari terkait dengan informasi yang dibuatnya, termasuk, namun tidak terbatas pada hal-hal yang berkaitan dengan hak cipta, merek, desain industri, desain tata letak sirkuit, hak paten dan/atau izin lain yang telah ditetapkan atas suatu produk menurut hukum yang berlaku di Indonesia.</li>
                                    <li>Warung hanya diperbolehkan menjual barang-barang yang tidak tercantum di daftar “Barang Terlarang”.</li>
                                    <li>Warung wajib menempatkan barang dagangan sesuai dengan kategori dan subkategorinya.</li>
                                    <li>Warung wajib mengisi nama atau judul barang dengan jelas, singkat dan padat.</li>
                                    <li>Warung wajib menampilkan gambar barang yang sesuai dengan deskripsi barang yang dijual dan tidak mencantumkan logo ataupun alamat situs jual-beli lain pada gambar. </li>
                                    <li>Warung wajib mengisi harga yang sesuai dengan harga sebenarnya.</li>
                                    <li>Warung dilarang melakukan duplikasi penjualan barang dengan menyalin atau menggunakan gambar dari penjual  Warung lain.</li>
                                    <li>Warung wajib memperbarui (update) ketersediaan dan status barang yang dijual.</li>
                                    <li>Catatan Warung diperuntukkan bagi Warung yang ingin memberikan catatan tambahan yang tidak terkait dengan deskripsi barang kepada calon Pelanggan. Catatan Warung tetap tunduk terhadap Aturan Penggunaan WarMart.</li>
                                    <li>Warung wajib mengisi kolom Deskripsi Barang sesuai dengan Aturan Penggunaan di WarMart.</li>
                                    <li>Warung dilarang membuat transaksi fiktif atau palsu demi kepentingan menaikkan feedback. WarMart berhak mengambil tindakan seperti pemblokiran akun atau tindakan lainnya apabila ditemukan tindakan kecurangan. </li>
                                    <li>Warung dapat menyediakan jasa pengiriman sendiri, apabila pelanggan memilih jasa ekspedisi pengiriman internal warung, maka semua hal terkait risiko pengiriman barang adalah tanggung jawab warung.</li>
                                    <li>Warung dapat menentukan pilihan jasa ekspedisi diluar jasa pengiriman internal yang disediakan warung.</li>
                                    <li>Warung wajib mengirimkan barang menggunakan jasa ekspedisi sesuai dengan yang dipilih oleh Pelanggan pada saat melakukan transaksi di dalam sistem WarMart.</li>
                                    <li>Apabila Warung menggunakan jasa ekspedisi yang berbeda dengan jasa dan/atau jenis jasa ekspedisi yang dipilih oleh Pelanggan pada saat melakukan transaksi di dalam sistem WarMart maka Warung bertanggung jawab atas segala hal selama proses pengiriman yang disebabkan oleh penggunaan jasa dan/atau jenis jasa ekspedisi yang berbeda tersebut.</li>
                                    <li>Warung memahami dan menyetujui bahwa kekurangan dana biaya kirim yang disebabkan oleh penggunaan jasa dan/atau jenis jasa yang berbeda dari pilihan Pelanggan pada saat melakukan transaksi di dalam sistem WarMart merupakan tanggung jawab Warung terkecuali perbedaan tersebut atas permintaan Pelanggan.</li>
                                    <li>Pelanggan berhak atas kelebihan dana dari biaya kirim yang diakibatkan perbedaan penggunaan jasa dan/atau jenis jasa ekspedisi oleh Warung dari pilihan Pelanggan pada saat melakukan transaksi di dalam sistem WarMart.</li>
                                    <li>Warung wajib memenuhi ketentuan yang sudah ditetapkan oleh pihak jasa ekspedisi berkaitan dengan packing barang yang aman serta menggunakan asuransi dan/atau packing kayu pada barang-barang tertentu sehingga apabila barang rusak atau hilang Warung dapat mengajukan klaim ke pihak jasa ekspedisi.</li>
                                    <li>Warung dikenakan biaya pengembangan teknologi dan pemasaran sebesar:
                                        <ul>
                                            <li>Barang/Hitung Stok : 20% dari margin keuntungan kotor. Margin dihitung dari Harga Jual Bersih – Harga Beli Bersih. Harga Jual Bersih dan Harga Jual Bersih adalah harga setelah dipotong diskon dan setelah dikenakan pajak (jika ada).</li>
                                            <li>Jasa/tidak hitung stok : 2% dari total penjualan bersih. Penjualan bersih adalah total penjualan dikurangi diskon dan dikenakan pajak (jika ada).</li>
                                        </ul></li>
                                        <li>Biaya tersebut diatas dibayar oleh warung setiap tanggal 1 – 5 setiap bulan untuk periode tanggal 1 – akhir bulan sebelumnya.</li>
                                        <li>Biaya pemasaran digunakan termasuk namun tidak terbatas pada bagi hasil dengan penggiat komunitas, bagi hasil dengan orang yang “Ajak Teman”, iklan di media online maupun offline, memberikan program promo diskon/hadiah, sosialisasi dan kegiatan-kegiatan yang mendukung pertumbuhan jumlah pengguna WarMart.</li>
                                        <li>Pengelolaan biaya pemasaran adalah hak WarMart dan WarMart tidak berkewajiban untuk memberikan laporan penggunaan dbiaya promosi tersebut kepada Warung.</li>
                                        <li>Biaya Pengembangan Teknologi adalah biaya yang digunakan oleh WarMart untuk menyediakan sumber daya dalam pengembangan teknologi termasuk namun tidak terbatas pada: pengembangan perangkat lunak, infrastruktur server, koneksi internet ke server, perawatan database, penanganan verifikasi dan customer service.</li>
                                        <li>Biaya Pengembangan Teknologi sepenuhnya akan dikelola oleh PT Andaglos Global Teknologi.</li>
                                        <li>Jika hingga akhir tanggal jatuh tempo, Warung tidak melakukan pembayaran biaya Pengembangan Teknologi dan Biaya Pemasaran, maka secara otomatis Warung dinonaktifkan sementara hingga Warung menyelesaikan kewajibanya.</li>
                                        <li>Pembayaran Biaya tersebut wajib melalui rekening yang telah ditetapkan oleh WarMart yang tercantum pada aplikasi WarMart.</li>

                                    </ol>

                                    <h6 style="font-weight:bold;">Transaksi Melalui Aplikasi</h6>
                                    <ol>
                                        <li>Pelanggan dapat melakukan order barang melalui aplikasi WarMart</li>
                                        <li>Pilihan pembayaran yang tersedia termasuk namun tidak terbatas pada:</li>
                                        <ul>
                                            <li>Pembayaran di Warung</li>
                                            <li>COD (Cash On Delivery)</li>
                                            <li>Transfer Bank</li>
                                        </ul>
                                        <li>Pembayaran di warung berlaku apabila pelanggan melakukan order dari aplikasi yang ingin mengambil barang sendiri ke warung.</li>
                                        <li>Pembayaran COD (Cash On Delivery) berlaku apabila pelanggan melakukan order melalui aplikasi dan barang dikirim ke lokasi pelanggan, pembayaran dilakukan setelah barang sampai ke lokasi pengiriman.</li>
                                        <li>Pembayaran melalui transfer bank berlaku apabila pelanggan melakukan order dari aplikasi kemudian memilih jenis pembayaran transfer bank.</li>
                                        <li>Demi keamanan dan kenyamanan para Pengguna, yang menggunakan fasilitas transfer dapat melakukan pembayaran ke WarMart melalui ke No Rek: </li>
                                        <li>Jika pelanggan memilih cara pembayaran transfer, wajib transfer sesuai dengan nominal total belanja dari transaksi dalam waktu 2 jam (dengan asumsi Pelanggan telah mempelajari informasi barang yang telah dipesannya). Jika dalam waktu 2 jam barang dipesan tetapi Pelanggan tidak mentransfer dana maka transaksi akan dibatalkan secara otomatis.</li>
                                        <li>Pelanggan tidak dapat membatalkan transaksi setelah melunasi pembayaran.</li>
                                        <li>Warung wajib mengirimkan barang sesuai dengan pilihan pengiriman barang oleh pelanggan.</li>
                                        <li>Apabila menggunakan jasa ekspedisi eksternal, warung wajib mendaftarkan nomor resi pengiriman yang benar dan asli setelah status transaksi “Dibayar”. Satu nomor resi hanya berlaku untuk satu nomor transaksi di WarMart.</li>
                                        <li>Jika Warung tidak mengirimkan barang dalam batas waktu pengiriman sejak pembayaran (1x24) jam untuk biaya pengiriman reguler atau 3 jam untuk pengiriman lokal, maka Warung dianggap telah menolak pesanan. Sehingga sistem secara otomatis memberikan feedback negatif dan reputasi tolak pesanan, serta mengembalikan seluruh dana (refund) ke pelanggan.</li>
                                        <li>Pengembalian dana transaksi dilakukan dengan mentransfer ke Nomor Rekening Pelanggan. Pengembalian dana dilakukan dalam waktu maksimal 14 hari kerja setelah pembayaran.</li>
                                        <li>Jika transaksi ditolak oleh warung, WarMart akan otomatis memberikan pilihan Warung-warung lain terdekat yang menyediakan barang tersebut. </li>
                                        <li>Apabila harga barang pengganti lebih mahal maka dana selisih akan ditanggung oleh pelanggan. Barang pengganti tersebut adalah barang yang sesuai dengan transaksi awal dengan spesifikasi yang sama dan harga yang tidak terlalu berbeda. Jika harga barang pengganti lebih murah, maka dana selisih akan dikembalikan ke pelanggan setelah transaksi dinyatakan selesai oleh sistem WarMart.</li>
                                        <li>Sistem WarMart secara otomatis mengecek status pengiriman barang melalui nomor resi yang diberikan Warung. Apabila nomor resi terdeteksi tidak valid dan Warung tidak melakukan ubah resi valid dalam 1x24 jam maka seluruh dana akan dikembalikan ke Pelanggan. Jika Warung memasukkan nomor resi tidak valid lebih dari satu kali maka WarMart akan mengembalikan seluruh dana transaksi kepada Pelanggan dan Warung mendapatkan feedback negatif.</li>
                                        <li>Jika Pelanggan tidak memberikan konfirmasi penerimaan barang dalam waktu 1x24 jam sejak status resi pengiriman dinyatakan telah diterima/delivered oleh sistem tracking jasa pengiriman, WarMart akan mentransfer dana langsung ke Warung tanpa memberikan konfirmasi ke Pelanggan.</li>
                                        <li>Sistem secara otomatis memberikan feedback (rekomendasi) positif dan mentransfer dana pembayaran ke Warung jika status resi menunjukkan ‘Barang diterima’ dan Pelanggan telah melewati batas waktu untuk konfirmasi.</li>
                                        <li>Pelanggan dapat memperbarui feedback maksimal 3x24 jam setelah transaksi dinyatakan selesai oleh sistem WarMart.</li>
                                        <li>Retur (Pengembalian Barang) hanya diperbolehkan jika kesalahan dilakukan oleh Warung dan barang tidak sesuai deskripsi.</li>
                                        <li>Retur tidak dapat dilakukan setelah transaksi selesai menurut sistem general tracking Warung atau Pelanggan telah melakukan konfirmasi barang diterima dan tidak memilih retur.</li>
                                        <li>Batas waktu retur maksimal 1x24 jam, jika sudah melewati waktu yang ditentukan maka dianggap selesai.</li>
                                        <li>Penanganan retur dilakukan setelah verifikasi dari petugas WarMart.</li>
                                    </ol>


                                    <h6 style="font-weight:bold;">Transaksi di Warung</h6>
                                    <ol>
                                        <li>Pelanggan dapat melihat lokasi warung terdekat melalui aplikasi WarMart.</li>
                                        <li>Pelanggan dapat langsung mendatangi ke lokasi warung tersebut dan melakukan transaksi serta pembayaran di warung.</li>
                                        <li>Warung wajib menginput transaksi ke aplikasi WarMart.</li>
                                        <li>Apabila warung tidak melakukan input ke WarMart dan dapat dibuktikan dengan bukti-bukti maka WarMart berhak melakukan tindakan peringatan hingga pembekuan akun warung.</li>
                                        <li>Hal-hal mengenai retur barang dan refund merupakan tanggung jawab warung kepada pelanggan secara langsung.</li>
                                    </ol>


                                    <h4 style="font-weight:bold;">Penggiat Komunitas</h4>
                                    <ol>
                                        <li>Penggiat Komunitas adalah orang yang berperan sebagai perwakilan dari komunitas untuk mendaftarkan anggota komunitasnya dan menerima share bagi hasil setiap anggota komunitas yang didaftakranya berbelanja di warung-warung yang tergabung dengan WarMart.</li>
                                        <li>Nilai share bagi hasil akan ditentukan dalam ketetapan yang dikeluarkan WarMart secara terpisah yang menjadi bagian tak terpisahkan dengan syarat dan ketentuan ini.</li>
                                        <li>Penggiat komunitas wajib beragama Islam yang dibuktikan dengan foto KTP pada saat melakukan pendaftaran.</li>
                                        <li>WarMart dapat melakukan tindakan termasuk namun tidak terbatas pada peringatan hingga pembekuan akun apabila penggiat komunitas terbukti melakukan pemalsuan identitas dan atau memberikan informasi yang tidak sesuai dengan fakta. </li>
                                        <li>Penggiat Komunitas wajib mengisi data pribadi secara lengkap dan jujur di halaman web WarMart dengan identitas asli (KTP/Paspor), No HP, Alamat Valid, Nama, dan e-mail yang masih aktif untuk digunakan (dapat menerima e-mail). Mengunakan e-mail milik pribadi pendaftar sendiri, tidak menggunakan e-mail orang lain atau e-mail yang sudah tidak aktif.</li>
                                        <li>Penggiat Komunitas bertanggung jawab secara penuh atas segala risiko yang timbul di kemudian hari terkait dengan informasi yang dibuatnya, termasuk, namun tidak terbatas pada hal-hal yang berkaitan dengan hak cipta, merek, desain industri, desain tata letak sirkuit, hak paten dan/atau izin lain yang telah ditetapkan atas suatu produk menurut hukum yang berlaku di Indonesia.</li>
                                        <li>Keikutsertaan Anda didasari atas dasar kesadaran penuh dan bukan karena paksaan oleh siapapun dan dari pihak manapun  selain karena keinginan sadar untuk bersama-sama membangun kebangkitan pasar Muslim Indonesia.</li>
                                        <li>Penggiat Komunitas wajib mengisi profil secara lengkap di website www.war-mart.id dengan data-data yang benar.</li>
                                        <li>Penggiat Komunitas wajib memberikan pemahaman atau edukasi tentang WarMart kepada anggotanya.</li>
                                        <li>Penggiat Komunitas wajib mendaftakarkan anggotanya ke WarMart.</li>
                                        <li>Penggiat Komunitas mempunyai minimal 10 anggota yang didaftarkan ke WarMart.</li>
                                        <li>Dalam proses penyampaian dan sosialisasi WarMart, setiap Penggiat Komunitas tidak diperkenankan mengiming-imingi setiap orang yang belum menjadi anggota WarMart dengan informasi palsu atau informasi yang dibesar-besarkan..</li>
                                        <li>Penggiat komunitas dilarang mengatas namakan sebagai perwakilan WarMart dalam berkomunikasi terhadap anggota komunitas maupun pihak lain, kecuali mendapatkan surat penunjukan secara langsung dari WarMart untuk mewakili pihak WarMart.</li>
                                        <li>Penggiat komunitas dilarang melakukan penggalangan dana atas nama WarMart kecuali mendapatkan surat penjunjukan secara langsung dari WarMart untuk melakukan penggalangan dana.</li>
                                        <li>WarMart berhak melakukan pembekuan akun dan menahan pencairan dana share bagi hasil Penggiat Komunitas apabila Penggiat Komunitas melakukan pelanggaran atas peraturan, syarat dan ketentuan WarMart oleh Penggiat Komunitas.</li>
                                    </ol>


                                    <h4 style="font-weight:bold;">Ajak Teman</h4>
                                    <ol>
                                        <li>Member adalah bentuk penawaran kerjasama yang sangat menguntungkan dalam memperoleh provit.</li>
                                        <li>Member wajib mengisi data pribadi secara lengkap dan jujur di halaman web WarMart dengan identitas asli (KTP/Paspor), No HP, Alamat Valid, Nama, dan e-mail yang masih aktif untuk digunakan (dapat menerima e-mail). Mengunakan e-mail milik pribadi pendaftar sendiri, tidak menggunakan e-mail orang lain atau e-mail yang sudah tidak aktif</li>
                                        <li>Member yang sudah melengkapi profil, dapat memiliki web replika dan dapat mempromosikan keanggotaan dan kegiatan Anda sebagai Member WaraMart dan hal-hal yang berhubungan dengan WarMart.</li>
                                        <li>Setiap Member berhak mengajak Member baru tanpa ada paksaan.</li>
                                        <li>Anda harus memahami dan setuju bahwa menjadi Member WarMart akan tunduk pada Kebijakan Privasi kami sebagaimana dapat diubah dari waktu ke waktu. </li>
                                        <li>Dalam proses penyampaian dan sosialisasi WarMart, setiap Member tidak diperkenankan mengiming-imingi setiap orang yang belum menjadi anggota WarMart dengan informasi palsu atau informasi yang dibesar-besarkan.</li>
                                        <li>Member dilarang mengatas namakan sebagai perwakilan WarMart dalam berkomunikasi terhadap calon Member maupun pihak lain, kecuali mendapatkan surat penunjukan secara langsung dari WarMart untuk mewakili pihak WarMart.</li>
                                        <li>Nilai share bagi hasil akan ditentukan dalam ketetapan yang dikeluarkan WarMart secara terpisah yang menjadi bagian tak terpisahkan dengan syarat dan ketentuan ini.</li>
                                        <li>WarMart dapat melakukan tindakan termasuk namun tidak terbatas pada peringatan hingga pembekuan akun apabila Member terbukti melakukan pemalsuan identitas dan atau memberikan informasi yang tidak sesuai dengan fakta.</li>
                                        <li>Apabila dipandang perlu, WarMart berhak untuk setiap saat merubah sebagian ataupun keseluruhan perjanjian Member tanpa perlu mendapatkan persetujuan terlebih dahulu dari Member</li>
                                    </ol>


                                    <hr style="list-style:bold;">


                                    <h4 style="font-weight:bold;">Barang Terlarang</h4>

                                    <p>Barang yang dijual oleh Warung harus barang Halal dan Toyib. WarMart telah dan akan terus melakukan hal-hal sebagaimana dipersyaratkan oleh peraturan perundang-undangan dan hal-hal yang dilarang atau di haramkan oleh MUI (Majelis Ulama Indonesia) untuk mencegah terjadinya perdagangan barang-barang yang melanggar ketentuan hukum dan Agama yang berlaku dan/atau hak pribadi pihak ketiga. Berkenaan dengan hal tersebut, berikut adalah barang-barang yang dilarang untuk diperjualbelikan melalui WarMart:</p>
                                    <ol>
                                        <li>Segala bentuk tulisan yang dapat berpengaruh negatif terhadap WarMart.</li>
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
                                        <p>Segala tindakan yang melanggar peraturan di WarMart akan dikenakan sanksi berupa termasuk namun tidak terbatas pada:</p>
                                        <li>Warung mendapatkan 1 feedback negatif apabila tidak mengirimkan barang dalam batas waktu pengiriman sejak pembayaran (1x24 jam kerja untuk biaya pengiriman reguler atau 3 jam untuk biaya pengiriman kilat).</li>
                                        <li>Warung mendapatkan 1 feedback negatif jika sudah 5 kali menolak pesanan.</li>
                                        <li>Warung mendapatkan 3 feedback negatif jika sudah memroses pesanan namun tidak kirim barang dalam batas waktu pengiriman sejak pembayaran (1x24 jam kerja untuk pengiriman reguler atau 3 jam untuk pengiriman kilat).</li>
                                        <li>Pelaporan ke pihak terkait (Kepolisian, dll).
                                        </li>
                                    </ol>


                                    <h4 style="font-weight:bold;">Pembatasan Tanggung Jawab</h4>
                                    <ol>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul dari dan dalam kaitannya dengan informasi yang dituliskan oleh Pengguna WarMart.</li>
                                        <li>WarMart tidak bertanggung jawab atas segala pelanggaran hak cipta, merek, desain industri, desain tata letak sirkuit, hak paten atau hak-hak pribadi lain yang melekat atas suatu barang, berkenaan dengan segala informasi yang dibuat oleh Warung. Untuk melaporkan pelanggaran hak cipta, merek, desain industri, desain tata letak sirkuit, hak paten atau hak-hak pribadi lain.  </li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul berkenaan dengan penggunaan barang yang dibeli melalui WarMart, dalam hal terjadi pelanggaran peraturan perundang-undangan.</li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul berkenaan dengan diaksesnya akun Pengguna oleh pihak lain.</li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat transaksi di luar Nomor Rekening WarMart yang telah ditentukan.</li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat kesalahan atau perbedaan nominal yang seharusnya ditransfer ke rekening atas nama WarMart.</li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul apabila transaksi telah selesai secara sistem (dana telah masuk ke Rekening Warung ataupun Pelanggan).</li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat kehilangan barang ketika proses transaksi berjalan dan/atau selesai.</li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat kesalahan Pengguna ataupun pihak lain dalam transfer dana ke rekening WarMart.</li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul apabila akun dibekukan dan/atau dinonaktifkan karena melanggar aturan-aturan yang telah ditetapkan oleh WarMart.</li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat penyalahgunaan nomor kontak dan/atau alamat e-mail milik Pengguna maupun pihak lainnya.</li>
                                        <li>WarMart tidak bertanggung jawab atas segala risiko dan kerugian yang timbul akibat tindakan Penggiat Komunitas yang tidak sesuai dengan peraturan, syarat dan ketentuan WarMart.</li>
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

