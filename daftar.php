<?php 
    // SIGNUP
    $conn = mysqli_connect("localhost", "projectfa_ibnufauzan", "]$1XOLMOx3DB", "projectfa_event");

    if(isset($_SESSION["login"])){
        header("Location: dashboard");
        exit;
    }

    function registrasi($tangkapRegistrasi){
        global $conn;

        $namaLengkap = htmlspecialchars($tangkapRegistrasi["namaLengkap"]);
        $email = htmlspecialchars(strtolower($tangkapRegistrasi["email"])); 
        $password = htmlspecialchars(mysqli_real_escape_string($conn, $tangkapRegistrasi["password"]));
        $password2 = htmlspecialchars(mysqli_real_escape_string($conn, $tangkapRegistrasi["password2"]));

        $periksaEmail = mysqli_query($conn, "SELECT email_panitia FROM akunpanitia WHERE email_panitia = '$email'");

        if(mysqli_fetch_assoc($periksaEmail)){
            echo '<p style="color:red; text-align: center;">Email sudah terdaftar, silahkan gunakan email lain</p>';
            return false;
        }
        else if (strlen($password) < 8) {
            echo '<p style="color:red; text-align: center;">Password harus berisi 8 karakter atau lebih</p>';
            return false;
        }
        else if ($password !== $password2) {
            echo '<p style="color:red; text-align: center;">Konfirmasi password yang dimasukkan tidak sesuai</p>';
            return false;
        }
        
        mysqli_query($conn, "INSERT INTO akunpanitia (nama_lengkap, email_panitia, password_panitia) VALUES ('$namaLengkap', '$email', '$password')");
        return mysqli_affected_rows($conn);
    }
    // END OF SIGNUP ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <link rel="icon" type="image/png" href="SmartEventLogo/black.png"/>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
</head>
<body style="background-color: #EDF1F5">
    
<div class="container">
    <div class="row d-flex flex-column min-vh-100 justify-content-center align-items-center">

        <!-- Alert -->
        <?php if(!isset($_POST["daftar"])): ?>  
          <div class="alert shadow alert-info text-dark alert-dismissible fw-bold pe-2 text-center fade show" role="alert" style="max-width: 920px;">
            Bila Anda sudah memiliki akun, silahkan <a href="login" class="text-decoration-none">masuk</a> untuk melanjutkan
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>

        <?php elseif(registrasi($_POST)): ?>
          <div class="alert shadow alert-success alert-dismissible text-center fade show" role="alert" style="max-width: 920px;">
            Akun Anda berhasil dibuat, silahkan <a href="login" class="text-decoration-none">masuk</a> untuk melanjutkan.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
      <!-- End of Alert -->

        <div class="card mb-4 ps-0 shadow-lg" style="max-width: 920px;">
            <div class="row g-0">

               <!-- Logo Smart Event -->
                <div class="col-md-4 my-auto">
                    <a href="/"><img src="SmartEventLogo/black.png" draggable="false" class="img-fluid rounded-start"></a>
                </div>
                <!-- End Logo Smart Event -->

                <!-- Card Content -->
                <div class="col-md-8">
                    <div class="card-body">
                        <h4 class="card-title text-center pt-1" style="letter-spacing:1.2px"><i class="bi bi-person pe-1"></i>DAFTAR PANITIA</h4>
                        
                        <form method="POST">
                            <div class="mb-4">
                                <label for="namaLengkap" class="form-label">Nama Lengkap <span class="text-danger"> *</span></label>
                                <div class="input-group">
                                    <span class="input-group-text px-3"><i class="bi bi-file-person"></i></span>
                                    <input type="text" autocomplete="nope" name="namaLengkap" class="form-control" id="namaLengkap" required autofocus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Alamat Email <span class="text-danger"> *</span></label>
                                <div class="input-group">
                                    <span class="input-group-text px-3"><i class="bi bi-envelope"></i></span>
                                    <input type="email" autocomplete="nope" name="email" class="form-control" id="email" required autofocus>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password <span class="text-danger"> *</span></label>
                                <div class="input-group">
                                    <span class="input-group-text px-3"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>
                                <div class="comment small form-text text-muted small form-text text-muted">Password minimal terdapat 8 karakter</div>
                            </div>

                            <div class="mb-4">
                                <label for="password2" class="form-label">Konfirmasi Password <span class="text-danger"> *</span></label>
                                <div class="input-group">
                                    <span class="input-group-text px-3"><i class="bi bi-patch-check"></i></span>
                                    <input type="password" name="password2"  class="form-control" id="password2">
                                </div>
                            </div>

                            <div class="container mt-4">
                                <div class="row text-center justify-content-center pb-1">
                                    <button type="submit" name="daftar" class="btn btn-secondary px-0" style="letter-spacing:1.5px">DAFTAR</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- End Card Content -->

            </div>
        </div>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
</body>
</html>