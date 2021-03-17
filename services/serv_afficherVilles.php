<?php

    $pdo = new PDO("mysql:host=127.0.0.1; dbname=epoka;charset=UTF8", "root", "root");
    $stmt = $pdo->prepare ("SELECT * FROM ville WHERE vil_categorie < 3 ORDER BY vil_categorie, vil_nom");
    $stmt->execute ();

    while ($ligne = $stmt->fetch()) {
        $output[] = $ligne;
    };
    echo(json_encode($output));

?>