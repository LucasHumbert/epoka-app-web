<!DOCTYPE html>
<html>
<head>
	<title>Modification des paramètres</title>
    <link rel="stylesheet" href="../style/style_chargement.css">
</head>
<body onload="decompte()">

    <section id="contenu">
        <p>Modifications effectuées</p>
        <span class="loader loader-quart"></span>
        <p>Redirection dans <span id="decompte">3</span> secondes</p>
    </section>

    <?php
        $prixKm = $_GET['remboursement'];
        $prixJournee = $_GET['indemnite'];

        $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
        $stmt = $pdo->prepare ("UPDATE param SET prixKm = :prixKm, prixJournee = :prixJournee");
        $stmt->bindParam ("prixKm", $prixKm,PDO::PARAM_STR);
        $stmt->bindParam ("prixJournee", $prixJournee,PDO::PARAM_STR);
        $stmt->execute ();

    ?>

    <script type="text/javascript">
            function decompte() {
                let compteur = 2;
                let spanCompteur = document.getElementById('decompte');

                let timer = setInterval(function(){
                    console.log(compteur);
                    spanCompteur.innerHTML = compteur;
                    compteur--;
                    if (compteur === 1){
                        setTimeout(function(){
                            clearInterval(timer);
                            document.location.href="../vues/parametres.php";
                        },1000);
                    }
                },1000);
            }
        </script>
</body>
</html>