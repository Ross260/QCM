<?php

session_start();
include "but_deco.php";

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Gestion de la visibilité admin
if ($_SESSION['user_id'] == 1) {
    echo "<a href='all_scores.php' >
    <input type='button' style='font-size: 30px; border-radius:20px; background-color:lightgreen;' value='Score des utilisateurs'>
    </a>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p style="font-size: 50px">Salut <?= $_SESSION["user_name"]; ?> 
    
        <?php 
        // echo $_SESSION['user_id'];
        ?>

    </p>
    <h1>Tentez votre chance </h1><hr>

    <form action="resultat.php" method="post">
        <?php
            include "./connect.php";
            $sql = "SELECT * FROM questions order by rand() limit 10";
            $resultat = mysqli_query($id, $sql);

            //boucle d'affichage des questions
            $i = 1;
            while ($ligne = mysqli_fetch_assoc($resultat)) {
                echo "<h3> $i : ".$ligne['libelleQ']."<br>";
                $i++;

                $sql2 = "select * from reponses where idq = ".$ligne["idq"];
                $resultat2 = mysqli_query($id, $sql2);
                while ($ligne2 = mysqli_fetch_assoc($resultat2)) {
                    $idq = $ligne2['idq'];
                    $idr = $ligne2['idr'];
                    echo "<input type='radio' name='$idq' value='$idr' checked> ".$ligne2["libeller"]."<br>";
                }

            }

        ?>
        <hr>
        <input type="submit" value="ENVOYER">
    </form>
</body>
</html>