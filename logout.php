<?php 
    session_start();
    session_unset();
    session_destroy();

    setcookie('x', '', time()-3600);
    setcookie('y', '', time()-3600);

    header("Location: /");
    exit;
?>