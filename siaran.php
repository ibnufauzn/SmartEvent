<?php
    require 'functions.php';

    if(!isset($_SESSION["login"])){
        header("Location: login");
        exit;
    }
    
    $hasilGabung = query("SELECT * FROM dataruang
                          INNER JOIN hasildeteksi ON hasildeteksi.id_foto = dataruang.id_deteksi AND dataruang.id_panitia = '$akunlogin'
                          ORDER BY hasildeteksi.waktu_deteksi DESC
                          LIMIT 1");
    
    $url=$_SERVER['REQUEST_URI'];
    header("Refresh: 2; URL=$url");
?>    
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta https-equiv="refresh" content="2">

        <!-- Responsif -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Siaran Langsung</title>
        <link rel="icon" type="image/png" href="SmartEventLogo/black.png"/>
        
        <!-- Custom CSS -->
        <link href="css/style.min.css" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
    </head>

    <body>
        <!-- Main wrapper - style you can find in pages.scss -->
        <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
            data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

            <!-- Left Sidebar - style you can find in sidebar.scss  -->
            <aside class="left-sidebar pt-1" data-sidebarbg="skin6">      
                <!-- Sidebar scroll-->
                <div class="scroll-sidebar">
                    <!-- Dark Logo icon -->
                <div class="ms-4 ps-1 pt-4 pb-2">
                    <a href="dashboard">
                        <img draggable="false" src="SmartEventLogo/logo.jpg" width="175px" alt="homepage" /> </a>
                </div>
                    <!-- Sidebar navigation-->
                    <nav class="sidebar-nav">
                        
                        <!-- Sidebar Menu -->
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
                    <!-- Riwayat Kerumunan -->
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="white-box">
                                <div class="d-md-flex mb-1">
                                    <h3 class="box-title mb-0 ms-2"><i class="bi bi-camera-reels"></i> | <span class="text-primary">S</span>iaran Langsung</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table no-wrap">
                                        <thead>
                                            <tr>
                                                <th>Siaran Langsung</th>
                                                <th class="pe-4">Tanggal Pendeteksian</th>
                                                <th>Status Ruangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <tr>
                                                <?php foreach ($hasilGabung as $hslDeteksi): ?>
                                                    <td class="txt-oflo pe-0" ><img draggable="false" width="580" src="<?= $hslDeteksi["nama_file"]?>"></td>
                                                    <td class="txt-oflo text-dark font-bold"><?= $hslDeteksi["waktu_deteksi"] ?></td>
                                                <?php endforeach ?>
                                                <?php foreach ($hasilGabung as $hslDataDeteksi): ?>
                                                    <td class="txt-oflo text-dark font-bold"><?= $hslDataDeteksi["status_ruang"]?></td>
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
        
        <!-- All Jquery -->
        <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>

        <!--Menu sidebar -->
        <script src="js/sidebarmenu.js"></script>
        <!--Custom JavaScript -->
        <script src="js/custom.js"></script>
    </body>
</html>