<?php
    session_start();

    if(!isset($_SESSION["numero"])){
        require_once("../vues/error-not-connected.php");
        die();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Epoka - Paramètrage</title>
</head>
<body>
    
    <!---- HEADER ---->
    <?php require_once("../vues/header.php")   ?>


</body>
</html>