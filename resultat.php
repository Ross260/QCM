<?php

include "connect.php";

// var_dump($_POST);

$score = 0;
foreach ($_POST as $key => $value) {
    // echo "A la question $key tu as repondu $value <br>";

    $sql = "select * from reponses where idr = $value";
    $resultat = mysqli_query($id, $sql);
    $ligne = mysqli_fetch_assoc($resultat);
    if($ligne["verite"] == 1) {
        $score = $score + 2;
    }else {
        $sql2 = "select * from questions where idq = $key";
        $resultat2 = mysqli_query($id, $sql2);
        $ligne2 = mysqli_fetch_assoc($resultat2);
        echo "Erreur à la question : ".$ligne2["libelleQ"]."<br>";
        $sql2 = "select * from reponses where idq = $key and verite = 1";
        $resultat2 = mysqli_query($id, $sql2);
        $ligne2 = mysqli_fetch_assoc($resultat2);
        echo "La bonne reponse était : ".$ligne2["libeller"]."br";


    }


}
echo "<br> $score / 20";

?>

<!-- 


-->