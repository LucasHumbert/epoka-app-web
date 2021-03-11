<!DOCTYPE html>
<html>
<head>
	<title>Ajout des distances</title>
    <link rel="stylesheet" href="../style/style_chargement.css">
</head>
<body onload="decompte()">



    <?php
        $ville1 = $_GET["ville1"];
        $ville2 = $_GET["ville2"];
        $distance = $_GET["distance"];

        if($ville1 == $ville2){
            die("<center>Veuillez sélectionner deux villes différentes<br />
            <a href='javascript:history.back()' color='#5fa8d3';>Page précédente</a> </center>");
        } else {
            if($ville1 > $ville2){
                $temp = $ville2;
                $ville2 = $ville1;
                $ville1 = $temp;
            }
        }

        $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
        $stmt = $pdo->prepare ("SELECT * FROM distance WHERE dis_idVilleDepart = :ville1 AND dis_idVilleArrivee = :ville2");
        $stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
        $stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
        $stmt->execute ();

        if ($ligne = $stmt->fetch()){
            die("<center> Cette distance a déjà été renseignée <br />
            <a href='javascript:history.back()' color='#5fa8d3';>Page précédente</a> </center>");
        } else {
            $stmt = $pdo->prepare ("INSERT INTO distance(dis_idVilleDepart, dis_idVilleArrivee, dis_km) VALUES (:ville1, :ville2, :distance)");
            $stmt->bindParam ("ville1", $ville1,PDO::PARAM_INT);
            $stmt->bindParam ("ville2", $ville2,PDO::PARAM_INT);
            $stmt->bindParam ("distance", $distance,PDO::PARAM_INT);
            $stmt->execute ();
        }
    ?>

    <section id="contenu">
        <p>Ajouts effectués</p>
        <span class="loader loader-quart"></span>
        <p>Redirection dans <span id="decompte">3</span> secondes</p>
    </section>

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