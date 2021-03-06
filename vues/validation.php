<?php
    session_start();

    if(!isset($_SESSION["numero"])){
        header('location: ../vues/accueil.php');
        die();
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Epoka - Validation</title>
</head>
<body>

    <?php
        /* HEADER */
        require_once("../vues/header.php");

        //si la personne connecté n'est pas autorisé à valider des missions alors on la jette
        if($_SESSION ["peutValider"] != 1){
        ?>

        <div id="bulle-etat-error">
            <p>Vous n'êtes pas autorisé à accéder à cette page !</p>
        </div>

        <?php

        } else {
            $numero = $_SESSION ['numero'];
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']); //permet d'afficher la date en français

            //reqête qui récupère toutes les infos qu'on va afficher dans le tableau
            $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
            $stmt = $pdo->prepare ("SELECT * FROM salarie, mission, ville WHERE sal_id = mis_idSalarie AND sal_idResponsable = :numero AND mis_idDestination = vil_id ORDER BY mis_validation");
            $stmt->bindParam ("numero", $numero,PDO::PARAM_STR);
            $stmt->execute ();

            $answers = $stmt -> fetchAll();

        ?>
            <!-- CONTENU DE LA PAGE -->
            <section id="sect_tableau">
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
                            <?php 
                                //si la mission n'est pas encore validé on affiche un bouton qui va appeler le script updateMissionValidation en lui transmettent l'id de la mission
                                if($answer['mis_validation'] == 0){ 
                            ?>

                            <form action="../script/updateMissionValidation.php" method="GET">

                                <input type="hidden" name="id" value="<?php echo($answer['mis_id']) ?>">

                                <input type="submit" value="Valider">

                            </form>

                            <?php
                                //sinon on affiche juste un texte comme quoi la mission est validée
                                } else { 
                            ?>

                                <p class="pValidee">Validée</p>

                            <?php 
                                } 

                                //et si la mission à  été ensuite payé on l'affiche aussi
                                if($answer['mis_paiement'] == 1){
                            ?>

                            <p class="pRemboursee">Remboursée</p>

                            <?php } ?>
                        </td>
                    </tr>

                    <?php } ?>
                
                </table>
            </section>

        <?php
            }
        ?>

</body>
</html>