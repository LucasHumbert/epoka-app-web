<?php
    //détruit la session en cours
    session_start();
    session_destroy();
    header ('location: ../index.php');
?>