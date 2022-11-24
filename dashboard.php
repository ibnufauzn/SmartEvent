<?php
    require 'functions.php';

    if(!isset($_SESSION["login"])){
        header("Location: login");
        exit;
    }

    $url=$_SERVER['REQUEST_URI'];
    header("Refresh: 5; URL=$url");

    $akunlogin = $_SESSION['id'];
    $hasilGabung = query("SELECT * FROM dataruang
                          INNER JOIN hasildeteksi ON hasildeteksi.id_foto = dataruang.id_deteksi AND dataruang.id_panitia = '$akunlogin'
                          ORDER BY hasildeteksi.waktu_deteksi DESC
                          LIMIT 1")
?>    
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta https-equiv="refresh" content="5">
        <link rel="icon" type="image/x-icon" href="SmartEventLogo/black.png"/>

        <!-- Responsif -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard</title>
        
        <!-- Custom CSS -->
        <link href="css/style.min.css" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
    </head>

    <body>
        <!-- Main wrapper -->
        <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
            data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

            <!-- Left Sidebar -->
            <aside class="left-sidebar pt-1" data-sidebarbg="skin6">      
                <!-- Sidebar scroll-->
                <div class="scroll-sidebar">

                    <!-- Smart Event Logo -->
                    <div class="ms-4 ps-1 pt-4 pb-2">
                        <a href="dashboard">
                            <img draggable="false" src="SmartEventLogo/logo.jpg" width="175px" alt="homepage" /> </a>
                    </div>
                    <!-- End Smart Event Logo -->

                    <!-- Sidebar navigation-->
                    <nav class="sidebar-nav">
                        
                        <!-- Sidebar Menu-->
                        <ul id="sidebarnav">
                            <li class="sidebar-item pt-2">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link " href="dashboard"
                                    aria-expanded="false">
                                    <i class="bi bi-house text-dark"></i>
                                    <span class="hide-menu text-dark font-bold">Beranda</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link text-info" href="siaran"
                                    aria-expanded="false">
                                    <i class="bi bi-camera-video text-info"></i>
                                    <span class="hide-menu font-bold">Siaran Langsung</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link text-primary" href="riwayat"
                                    aria-expanded="false">
                                    <i class="bi bi-clock-history text-primary"></i>
                                    <span class="hide-menu font-bold">Riwayat Kerumunan</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link text-danger" href="logout"
                                onclick="return confirm('Apakah Anda yakin untuk Logout?')" aria-expanded="false">
                                    <i class="bi bi-person-x text-danger"></i>
                                    <span class="hide-menu font-bold">Logout</span>
                                </a>
                            </li>
                        </ul>
                        <!-- End Sidebar Menu -->

                    </nav>
                    <!-- End Sidebar navigation -->

                </div>
                <!-- End Sidebar scroll-->

            </aside>
            <!-- End Left Sidebar - style you can find in sidebar.scss  -->

            <!-- Page wrapper  -->
            <div class="page-wrapper">
            
                <!-- Container fluid  -->
                <div class="container-fluid">

                    <!-- Three charts -->
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-12">
                            <div class="white-box analytics-info border border-primary rounded-3">
                                <h3 class="box-title"><i class="bi bi-bell pe-1"></i> | <span class="text-primary">S</span>tatus Ruangan</h3>
                                <?php foreach ($hasilGabung as $hslDataDeteksi): ?>
                                    <h2 class="counter text-primary text-center"><?= $hslDataDeteksi["status_ruang"]?></h2>
                                <?php endforeach ?>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="white-box analytics-info border border-info rounded-3">
                                <h3 class="box-title"><i class="bi bi-people pe-1"></i> | <span class="text-primary">J</span>umlah Saat Ini</h3>
                                <?php foreach ($hasilGabung as $hslDataDeteksi): ?>
                                    <h2 class="counter text-primary text-center"><?= $hslDataDeteksi["jumlah_orang"], " Orang"?></h2>
                                <?php endforeach ?>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="white-box analytics-info border border-success rounded-3">
                                <h3 class="box-title"><i class="bi bi-person"></i> | <span class="text-primary">K</span>apasitas ruangan</h3>
                                <?php foreach ($hasilGabung as $hslDataDeteksi): ?>
                                    <h2 class="counter text-primary text-center"><?= $hslDataDeteksi["kapasitas_ruang"], " Orang"?></h2>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <!-- End Three charts -->

                    <!-- Riwayat Kerumunan -->
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="white-box">
                                <div class="d-md-flex mb-1">
                                    <h3 class="box-title mb-0 ms-2"><i class="bi bi-camera pe-1"></i> | <span class="text-primary">H</span>asil Deteksi Terkini</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table no-wrap">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Hasil Deteksi</th>
                                                <th class="border-top-0 pe-4">Tanggal Pendeteksian</th>
                                                <th class="border-top-0 pe-4">Luas Ruangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                <?php foreach ($hasilGabung as $hslDataDeteksi): ?>
                                                    <td class="txt-oflo pe-0" ><img width="560" src="<?= $hslDataDeteksi["nama_file"]?>"></td>
                                                    <td class="txt-oflo"><?= $hslDataDeteksi["waktu_deteksi"] ?></td>
                                                <?php endforeach ?>
                                                <?php foreach ($hasilGabung as $hslDataDeteksi): ?>
                                                    <td class="txt-oflo"><?= $hslDataDeteksi["luas_ruang"], " m²"?></td>
                                                <?php endforeach ?>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Riwayat Kerumunan -->

                </div>
                <!-- End Container fluid  -->

                <!-- footer -->
                <footer class="bg-light footer text-center text-dark font-bold">Copyright &copy; 2022 Smart Event | Template oleh <a
                        href="https://www.wrappixel.com/"> wrappixel.com</a>
                </footer>
                <!-- End footer -->

            </div>
            <!-- End Page wrapper  -->
            
        </div>
        <!-- End Wrapper -->
    </body>
</html>