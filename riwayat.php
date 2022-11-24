<?php
    require 'functions.php';

    if(!isset($_SESSION["login"])){
        header("Location: login");
        exit;
    }
    
    $url=$_SERVER['REQUEST_URI'];
    header("Refresh: 2; URL=$url");
?>    
<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/png" href="SmartEventLogo/black.png"/>
        <meta https-equiv="refresh" content="2">

        <!-- Responsif -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Riwayat Kerumunan</title>
        
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
                    <!-- Riwayat Kerumunan -->
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="white-box">
                                <div class="d-md-flex mb-3">
                                    <h3 class="box-title mb-0"><i class="bi bi-clock-history px-1"></i> | Riwayat Kerumunan</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table no-wrap">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Nomor</th>
                                                <th class="border-top-0">Foto Hasil Deteksi</th>
                                                <th class="border-top-0">Jumlah Didalam Ruangan</th>
                                                <th class="border-top-0">Waktu Terdeteksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($dataKerumunan as $ulangiDataKerumunan): ?>
                                                <tr>
                                                    <td><?php echo $nomor++; ?></td>
                                                    <td class="txt-oflo"><img draggable="false" width="460" src="<?= $ulangiDataKerumunan["nama_file"]?>"></td>
                                                    <td class="txt-oflo font-bold"><?= $ulangiDataKerumunan["jumlah_orang"], " Orang"?></td>
                                                    <td class="txt-oflo font-bold"><?= $ulangiDataKerumunan["waktu_deteksi"];?></td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <a class="page-link" <?php if($halaman > 1){echo "href=?halaman=$previous";} ?>>Previous</a>
                                </li>
                                        
                                <?php for($x=1; $x<=$totalHalaman; $x++): ?>
                                    <li class="page-item"><a class="page-link" href="?halaman=<?php echo $x ?>"><?php echo $x; ?></a></li>
                                <?php endfor ?>	

                                <li class="page-item">
                                    <a  class="page-link" <?php if($halaman < $totalHalaman) {echo "href=?halaman=$next";} ?>>Next</a>
                                </li>
                            </ul>
                            <!-- End Pagination -->

                        </div>
                    </div>
                </div>
                <!-- End Container fluid  -->

                <!-- footer -->
                <footer class="bg-light footer text-center mt-auto text-dark font-bold">Copyright &copy; 2022 Smart Event | Template oleh <a
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