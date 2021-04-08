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
    <title>Epoka - Paiement</title>
</head>
<body>

    <?php
        /* HEADER */
        require_once("../vues/header.php");

        if($_SESSION ["peutPayer"] != 1){
        ?>

        <div id="bulle-etat-error">
            <p>Vous n'êtes pas autorisé à accéder à cette page !</p>
        </div>

        <?php
        } else {
            $numero = $_SESSION ['numero'];
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);

            $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
            $stmt = $pdo->prepare ("SELECT * FROM salarie, mission, ville WHERE sal_id = mis_idSalarie AND mis_idDestination = vil_id AND mis_validation = 1");
            $stmt->execute ();

            $answers = $stmt -> fetchAll();
            
        ?>
            <!-- CONTENU DE LA PAGE -->
            <section id="sect_tableau">
                <h1>Paiement des missions</h1>
                <table>
                    <tr id="titles">
                        <th>Nom du salarié</th>
                        <th>Prénom du salarié</th>
                        <th>Début de la mission</th>
                        <th>Fin de la mission</th>
                        <th>Lieu de la mission</th>
                        <th>Montant</th>
                        <th>Paiement</th>
                    </tr>

                    <?php 
                        foreach($answers as $answer){ 
                            $montantMission = calculMontant($answer['mis_id'], $answer['sal_idAgence']);
                    ?>
                    
                        
                    <tr>
                        <td><?php echo($answer['sal_nom']) ?></td>
                        <td><?php echo($answer['sal_prenom']) ?></td>
                        <td><?php echo(strftime("%A %e %B %Y", strtotime($answer['mis_dateDebut']))) ?></td>
                        <td><?php echo(strftime("%A %e %B %Y", strtotime($answer['mis_dateFin']))) ?></td>
                        <td><?php echo($answer['vil_nom']." (".$answer['vil_cp'].")") ?></td>
                        <td><center><?php

                            $stmt = $pdo->prepare ("SELECT mis_id, mis_montant FROM mission WHERE mis_id = :idMission AND mis_montant IS NOT NULL");
                            $stmt->bindParam ("idMission", $answer['mis_id'],PDO::PARAM_STR);
                            $stmt->execute ();

                            if ($ligne = $stmt -> fetch()){
                                echo(number_format($ligne['mis_montant'], 2, '.', '')."€");
                            } else {
                                echo($montantMission);
                            }

                        ?></center></td>
                        <td>
                            <?php if($answer['mis_paiement'] == 0){ ?>

                            <form action="../script/updateMissionPaiement.php" method="GET">

                                <input type="hidden" name="id" value="<?php echo($answer['mis_id']) ?>">

                                <input type="hidden" name="montant" value="<?php echo($montantMission) ?>">

                                <input type="submit" value="Rembourser" <?php if($_SESSION["error-distance"] == "erreur"){ echo("disabled");}; ?>>
                                
                            </form>

                            <?php } else { ?>

                                <p>Remboursée</p>

                            <?php } ?>

                        </td>
                    </tr>

                    <?php } ?>
                
                </table>
            </section>

        <?php
            }

            function calculMontant($idMission, $salarieIdAgence){
                
                //récupération de la ville de l'agence
                $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
                $stmt = $pdo->prepare ("SELECT age_ville, vil_nom FROM agence, ville WHERE age_ville = vil_id AND age_id = :idAgence");
                $stmt->bindParam ("idAgence", $salarieIdAgence,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtVille = $stmt -> fetch();

                $ville1 = $stmtVille['age_ville'];
                $nomVille1 = $stmtVille['vil_nom'];

                //récupération de la ville de destination
                $stmt = $pdo->prepare ("SELECT mis_idDestination, vil_nom FROM mission, ville WHERE mis_idDestination = vil_id AND mis_id = :idMission");
                $stmt->bindParam ("idMission", $idMission,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtVille = $stmt -> fetch();

                $ville2 = $stmtVille['mis_idDestination'];
                $nomVille2 = $stmtVille['vil_nom'];

                if ($ville1 > $ville2){
                    $temp = $ville2;
                    $ville2 = $ville1;
                    $ville1 = $temp;

                    $temp = $nomVille2;
                    $nomVille2 = $nomVille1;
                    $nomVille1 = $temp;
                }

                //récupération de la distance entre les deux villes
                $stmt = $pdo->prepare ("SELECT dis_km FROM distance WHERE dis_idVilleDepart = :ville1 AND dis_idVilleArrivee = :ville2");
                $stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
                $stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtKm = $stmt -> fetch();

                $distance = $stmtKm['dis_km'];

                $_SESSION["error-distance"] = "";
                if (!isset($distance)){
                    $_SESSION["error-distance"] = "erreur";
                    return("Distance entre $nomVille1 et <br />$nomVille2 non renseignée");
                }
             
                

                //nombre de jours de la mission
                $stmt = $pdo->prepare ("SELECT DATEDIFF(mis_dateFin, mis_dateDebut) + 1 as dateDiff FROM mission WHERE mis_id = :idMission");
                $stmt->bindParam ("idMission", $idMission,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtJour = $stmt -> fetch();

                $nbJours = $stmtJour['dateDiff'];



                //récupération des paramètres
                $stmt = $pdo->prepare ("SELECT * FROM param");
                $stmt->execute ();
                $stmtParam = $stmt -> fetch();

                $prixKm = $stmtParam['prixKm'];
                $prixJournee = $stmtParam['prixJournee'];



                //calcul
                $montant = ($distance * $prixKm) * 2 + ($nbJours * $prixJournee);

                return(number_format($montant, 2, '.', '')."€");
            }

        ?>

</body>
</html>