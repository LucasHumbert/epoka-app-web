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
    <link rel="stylesheet" href="../style/style_paiement.css">
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
            $numero = $_SESSION ['numero'];
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);

            $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
            $stmt = $pdo->prepare ("SELECT * FROM salarie, mission, ville WHERE sal_id = mis_idSalarie AND sal_idResponsable = :numero AND mis_idDestination = vil_id AND mis_validation = 1");
            $stmt->bindParam ("numero", $numero,PDO::PARAM_STR);
            $stmt->execute ();

            $answers = $stmt -> fetchAll();
            
        ?>
            <!-- CONTENUE DE LA PAGE -->
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

                <?php foreach($answers as $answer){ ?>
                    
                <tr>
                    <td><?php echo($answer['sal_nom']) ?></td>
                    <td><?php echo($answer['sal_prenom']) ?></td>
                    <td><?php echo(strftime("%A %e %B %Y", strtotime($answer['mis_dateDebut']))) ?></td>
                    <td><?php echo(strftime("%A %e %B %Y", strtotime($answer['mis_dateFin']))) ?></td>
                    <td><?php echo($answer['vil_nom']." (".$answer['vil_cp'].")") ?></td>
                    <td><?php echo(calculMontant($answer['mis_id'], $answer['sal_idAgence'])) ?></td>
                    <td>
                        <?php if($answer['mis_paiement'] == 0){ ?>

                        <form action="../script/updateMissionPaiement.php" method="GET">

                            <input type="hidden" name="id" value="<?php echo($answer['mis_id']) ?>">

                            <input type="submit" value="Rembourser">

                        </form>

                        <?php } else { ?>

                            <p>Remboursée</p>

                        <?php } ?>

                    </td>
                </tr>

                <?php } ?>
            
            </table>

        <?php
            }

            function calculMontant($idMission, $salarieIdAgence){
                
                //récupération de la ville de l'agence
                $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
                $stmt = $pdo->prepare ("SELECT age_ville FROM agence WHERE age_id = :idAgence");
                $stmt->bindParam ("idAgence", $salarieIdAgence,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtVille = $stmt -> fetch();

                $ville1 = $stmtVille['age_ville'];

                //récupération de la ville de destination
                $stmt = $pdo->prepare ("SELECT mis_idDestination FROM mission WHERE mis_id = :idMission");
                $stmt->bindParam ("idMission", $idMission,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtVille = $stmt -> fetch();

                $ville2 = $stmtVille['mis_idDestination'];

                if ($ville1 > $ville2){
                    $temp = $ville2;
                    $ville2 = $ville1;
                    $ville1 = $temp;
                }

                //récupération de la distance entre les deux villes
                $stmt = $pdo->prepare ("SELECT dis_km FROM distance WHERE dis_idVilleDepart = :ville1 AND dis_idVilleArrivee = :ville2");
                $stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
                $stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
                $stmt->execute ();
                $stmtKm = $stmt -> fetch();

                $distance = $stmtKm['dis_km'];

                return($distance);
            }

        ?>

</body>
</html>