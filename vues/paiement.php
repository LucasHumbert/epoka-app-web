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
    <link rel="stylesheet" href="../style/style-bulle-etat.css">
    <title>Epoka - Paiement</title>
</head>
<body>

    <?php
        /* HEADER */
        require_once("../vues/header.php");

        if($_SESSION ["peutPayer"] != 1){
        ?>

        <div id="bulle-etat-non-autorise">
            <p>Vous n'êtes pas autorisé à accéder à cette page !</p>
        </div>

        <?php
        } else {

            /* CONTENUE DE LA PAGE */



            
        }
    ?>

</body>
</html>