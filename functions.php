<?php 
    session_start();
    $conn = mysqli_connect("localhost", "projectfa_ibnufauzan", "]$1XOLMOx3DB", "projectfa_event");
    
    function query($tangkap_query){
        global $conn;
        $result = mysqli_query($conn, $tangkap_query);
        $records = [];
        while ($record = mysqli_fetch_assoc($result)) {
            $records[] = $record; 
        }
        return $records;
    }
    
    // PAGINATION
    session_start();
    
    $batas = 5;
    $halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
    $halamanAwal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

    $previous = $halaman - 1;
    $next = $halaman + 1;

    $akunlogin = $_SESSION['id'];

    $data = mysqli_query($conn, "SELECT * FROM dataruang 
                                 INNER JOIN hasildeteksi ON dataruang.id_deteksi = hasildeteksi.id_foto 
                                 AND dataruang.id_panitia = '$akunlogin'
                                 WHERE dataruang.jumlah_orang > dataruang.kapasitas_ruang
                                 ORDER BY hasildeteksi.waktu_deteksi DESC
                                ");

    $jumlahData = mysqli_num_rows($data);
    $totalHalaman = ceil($jumlahData / $batas);

    $dataKerumunan = mysqli_query($conn,"SELECT * FROM dataruang 
                                 INNER JOIN hasildeteksi ON dataruang.id_deteksi = hasildeteksi.id_foto
                                 AND dataruang.id_panitia = '$akunlogin'
                                 WHERE dataruang.jumlah_orang > dataruang.kapasitas_ruang 
                                 ORDER BY hasildeteksi.waktu_deteksi DESC 
                                         LIMIT $halamanAwal, $batas
                                        ");
    $nomor = $halamanAwal+1;
    // END OF PAGINATION
?>