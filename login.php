<?php 
    session_start();
    $conn = mysqli_connect("localhost", "projectfa_ibnufauzan", "]$1XOLMOx3DB", "projectfa_event");

    //periksa cookie
    if(isset($_COOKIE['y'])){
        $simpanCookieEmail = hash('sha384', $_COOKIE['y']);
        $_SESSION['login'] = true;
    }

    if(isset($_SESSION["login"])){
        header("Location: dashboard");
        exit;
    }

    if(isset($_POST["login"])){
        $email = $_POST["email"];
        $pass = $_POST["password"];

        $cekEmail = mysqli_query($conn, "SELECT * FROM akunpanitia WHERE email_panitia = '$email'");
        $pengguna = mysqli_query($conn, "SELECT email_panitia, nama_lengkap FROM akunpanitia WHERE email_panitia = '$email'");

        if(mysqli_num_rows($cekEmail) === 1){ //hitung berapa baris dari fungsi select, apabila ketemu nilainya 1
            // cek password
            $user = mysqli_fetch_assoc($cekEmail);
            if ($pass == $user["password_panitia"]) {
                //set session
                $_SESSION["login"] = true;
                $data = mysqli_fetch_assoc($pengguna);
                $jumlahPengguna = mysqli_num_rows($pengguna);
                if ($jumlahPengguna > 0){
                    $_SESSION['id'] = $data['email_panitia'];
                    $_SESSION['nama'] = $data['nama_lengkap'];
                }
                //cek cookie
                if(isset($_POST['remember'])){
                    //buat cookie
                    setcookie('y', hash('sha384', $user["email_panitia"]), time()+3600);
                }
                //end set session
                header("Location: dashboard");
                exit;
            }
        }
        $error = true;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="SmartEventLogo/black.png"/>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
</head>
<body style="background-color: #EDF1F5">
    
<div class="container">
    <div class="row d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <!-- Wrong Username or Password -->
        <?php if(isset($error)): ?>
            <div class="alert shadow alert-danger alert-dismissible text-center fw-bold fade show" role="alert" style="max-width: 920px;">
            Username Atau Password Salah
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <!-- End of Wrong Username or Password -->

        <div class="card mb-4 ps-0 shadow-lg" style="max-width: 920px;">
            <div class="row g-0">
                <div class="col-md-4 my-auto">
                    <a href="/"><img src="SmartEventLogo/black.png" draggable="false" class="img-fluid rounded-start"></a>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h4 class="card-title text-center pt-1" style="letter-spacing:1.2px"><i class="bi bi-person pe-1"></i>PANITIA</h4>
                        <form method="POST">
                            <div class="mb-4">
                                <label for="email" class="form-label">Alamat Email <span class="text-danger"> *</span></label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text px-3"><i class="bi bi-envelope"></i></span>
                                    <input type="email" autocomplete="nope" name="email" class="form-control" id="email" required autofocus>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger"> *</span></label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text px-3"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input type="checkbox" name="remember" id="remember">
                                    <label for="remember">Ingat Saya</label><br>
                                </div>
                                <div class="col mt-1">
                                    <div class="text-end">
                                    <h6>Belum punya akun? Daftar <a href="daftar">disini</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="container mt-3">
                                <div class="row text-center justify-content-center mt-2 pb-1">
                                    <button type="submit" name="login" class="btn btn-secondary px-0" style="letter-spacing:1.5px">LOGIN</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
</body>
</html>