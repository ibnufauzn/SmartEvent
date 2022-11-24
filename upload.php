<?php
date_default_timezone_set('Asia/Jakarta');						
$dateTime = date("Y-m-d H:i:s");
$servername = "localhost";
$username = "projectfa_ibnufauzan";
$password = "]$1XOLMOx3DB";
$dbname = "projectfa_event";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$currentDirectory = getcwd();
$uploadDirectory = "/foto/";

$errors = []; // Store errors here

$fileExtensionsAllowed = ['jpeg','jpg','png']; // These will be the only file extensions allowed 

$fileName = $_FILES['file']['name'];
$fileSize = $_FILES['file']['size'];
$fileTmpName  = $_FILES['file']['tmp_name'];
$fileType = $_FILES['file']['type'];
$fileExtension = strtolower(end(explode('.',$fileName)));
$newfileName = time().rand(0,99999).".".pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

$uploadPath = $currentDirectory . $uploadDirectory . basename($newfileName); 

if (!in_array($fileExtension,$fileExtensionsAllowed)) {
$errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
}

if ($fileSize > 4000000) {
$errors[] = "File exceeds maximum size (4MB)";
}

if (empty($errors)) {
$didUpload = move_uploaded_file($fileTmpName, $uploadPath);

    if ($didUpload) {
        $path_name = $uploadDirectory . basename($newfileName);
        $conn->query("INSERT INTO hasildeteksi (nama_file, waktu_deteksi) VALUES ('$path_name', '$dateTime')");
      echo "The file " . basename($newfileName) . " has been uploaded";
    } else {
        print_r($_FILES['file']);
      echo "An error occurred. Please contact the administrator.";
    }
} else {
    foreach ($errors as $error) {
      echo $error . "These are the errors" . "\n";
    }
}