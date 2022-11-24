<?php 
    session_start();
    
    if(isset($_SESSION["login"])){
        header("Location: dashboard.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Smart Event</title>
        <link rel="icon" type="image/x-icon" href="SmartEventLogo/black.png">

        <!-- CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/footer.css">
    </head>
    <body style="background-color: #EDF1F5;">
    <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-light">
            <div class="container">
                <a class="navbar-brand" href="" style="margin-left: 35px">
                    <img src="SmartEventLogo/transparan.png" alt="" class="d-inline-block align-text-top img-fluid" style="height: 50px">
                </a>

                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="#tentang">Tentang</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link fw-bold" href="#kontak">Hubungi Kami</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-bold" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Masuk / Daftar
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="login.php">Masuk</a></li>
                                <li><a class="dropdown-item" href="daftar.php">Daftar</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

        <!-- Main Wrapper -->
        <section class="py-3">
            <div class="container px-4 px-lg-5 my-1">
                <div class="row gx-4 gx-lg-5 align-items-center">
                    <div class="col-md-6 mb-5">
                        <h1 class="display-5 fw-bolder">Tentang Kami</h1>
                        <div class="fs-5 mb-2">
                            <span>Apa itu Smart Event?</span>
                        </div>
                        <p class="lead text-justify">Smart Event merupakan perangkat IoT (Internet of Things) yang digunakan untuk memantau kerumunan pada suatu event. Perangkat ini dapat memberikan informasi kepada panitia mengenai jumlah peserta di dalam ruangan, status ruang event, dan siaran langsung ketika pendeteksian ruangan berlangsung.</p>
                        <div class="d-flex">
                            <a role="button" class="btn btn-outline-dark flex-shrink-0" href="daftar.php"><i class="bi bi-arrow-right-square pe-2"></i>Gabung bersama kami</a>
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <img class="card-img-top" id="tentang" src="gambarhomepage/1.png" draggable="false" />
                    </div>
                </div>
            </div>
        </section>

        <section class="py-3" style="background-color: #dde4ec;">
            <div class="container px-4 px-lg-5 my-1" style="padding-bottom: 5px">
                <div class="row gx-4 gx-lg-5 align-items-center pb-5">
                    <div class="col-md-6 my-auto">
                        <img class="img-fluid" src="gambarhomepage/14.svg" draggable="false" />
                    </div>
                    <div class="col-md-6 my-auto">
                        <h1 class="display-5 fw-bolder" id="kontak">Hubungi Kami</h1>
                        <div class="fs-5 mb-3">
                            <span>Punya pertanyaan lebih lanjut?</span>
                        </div>
                        <span class="lead">Email : Admin@iotsmartevent.my.id</span></br>
                        <span class="lead">Instagram : iotsmartevent</span>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Main Wrapper -->

        <!-- Footer -->
        <footer class="footer mt-auto py-4 bg-light">
            <div class="container mt-1">
                <h6 class="text-center text-dark ps-2">Copyright &copy; 2022 Smart Event</h6>
            </div>
        </footer>
        <!-- End of Footer -->

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
    </body>
</html>