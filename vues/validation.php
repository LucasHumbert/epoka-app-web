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
    <link rel="stylesheet" href="../style/style_header.css">
    <link rel="stylesheet" href="../style/style-bulle-etat.css">
    <link rel="stylesheet" href="../style/style_validation.css">
    <title>Epoka - Validation</title>
</head>
<body>

    <?php
        /* HEADER */
        require_once("../vues/header.php");

        if($_SESSION ["peutValider"] != 1){
        ?>

        <div id="bulle-etat-non-autorise">
            <p>Vous n'êtes pas autorisé à accéder à cette page !</p>
        </div>

        <?php

        } else {
            $numero = $_SESSION ['numero'];

            $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
            $stmt = $pdo->prepare ("SELECT sal_nom, sal_prenom, mis_dateDebut, mis_dateFin FROM salarie, mission WHERE sal_id = mis_idSalarie AND sal_idResponsable = :numero");
            $stmt->bindParam ("numero", $numero,PDO::PARAM_STR);
            $stmt->execute ();

            $answers = $stmt -> fetchAll();

        ?>
            <!-- CONTENUE DE LA PAGE -->
            <h1>Validation des missions de vos subordonnées</h1>
            <table>
                <tr id="titles">
                    <td>Nom du salarié</td>
                    <td>Prénom du salarié</td>
                    <td>Début de la mission</td>
                    <td>Fin de la mission</td>
                    <td>Lieu de la mission</td>
                    <td>Validation</td>
                </tr>

                <?php foreach($answers as $answer){ ?>

                <tr>
                    <td><?php echo($answer['sal_nom']) ?></td>
                    <td><?php echo($answer['sal_prenom']) ?></td>
                    <td><?php echo($answer['mis_dateDebut']) ?></td>
                    <td><?php echo($answer['mis_dateFin']) ?></td>
                    <td></td>
                    <td></td>
                </tr>

                <?php } ?>
            
            </table>

        <?php
            }
        ?>

</body>
</html>