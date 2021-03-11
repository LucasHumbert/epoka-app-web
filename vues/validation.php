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
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);

            $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
            $stmt = $pdo->prepare ("SELECT * FROM salarie, mission, ville WHERE sal_id = mis_idSalarie AND sal_idResponsable = :numero AND mis_idDestination = vil_id");
            $stmt->bindParam ("numero", $numero,PDO::PARAM_STR);
            $stmt->execute ();

            $answers = $stmt -> fetchAll();


            

        ?>
            <!-- CONTENUE DE LA PAGE -->
            <h1>Validation des missions de vos subordonnées</h1>
            <table>
                <tr id="titles">
                    <th>Nom du salarié</th>
                    <th>Prénom du salarié</th>
                    <th>Début de la mission</th>
                    <th>Fin de la mission</th>
                    <th>Lieu de la mission</th>
                    <th>Validation</th>
                </tr>

                <?php foreach($answers as $answer){ ?>
                    
                <tr>
                    <td><?php echo($answer['sal_nom']) ?></td>
                    <td><?php echo($answer['sal_prenom']) ?></td>
                    <td><?php echo(strftime("%A %e %B %Y", strtotime($answer['mis_dateDebut']))) ?></td>
                    <td><?php echo(strftime("%A %e %B %Y", strtotime($answer['mis_dateFin']))) ?></td>
                    <td><?php echo($answer['vil_nom']." (".$answer['vil_cp'].")") ?></td>
                    <td>
                        <?php if($answer['mis_validation'] == 0){ ?>

                        <form action="../script/updateMissionValidation.php" method="GET">

                            <input type="hidden" name="id" value="<?php echo($answer['mis_id']) ?>">

                            <input type="submit" value="Valider">

                        </form>

                        <?php } else { ?>

                            <p class="pValidee">Validée</p>

                        <?php 
                            } 
                            
                            if($answer['mis_paiement'] == 1){
                        ?>

                        <p class="pRemboursee">Remboursée</p>

                        <?php } ?>
                    </td>
                </tr>

                <?php } ?>
            
            </table>

        <?php
            }
        ?>

</body>
</html>