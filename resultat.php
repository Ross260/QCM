<?php
session_start();

include "but_deco.php";
include "connect.php";

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// var_dump($_POST);
echo "<p style='margin-top:30px;'></P>";
$score = 0;
foreach ($_POST as $key => $value) {
    // echo "A la question $key tu as repondu $value <br>";

    $sql = "select * from reponses where idr = $value";
    $resultat = mysqli_query($id, $sql);
    $ligne = mysqli_fetch_assoc($resultat);
    if($ligne["verite"] == 1) {
        $score = $score + 2;
    }else {
        $sql2 = "select * from questions where idq = $key"; // pour recuperer toute la ligne de la question
        $resultat2 = mysqli_query($id, $sql2);
        $ligne2 = mysqli_fetch_assoc($resultat2);
        echo "Erreur à la question : ".$ligne2["libelleQ"]."<br>";
        $sql2 = "select * from reponses where idq = $key and verite = 1"; // recupération de la bonne reponse
        $resultat2 = mysqli_query($id, $sql2);
        $ligne2 = mysqli_fetch_assoc($resultat2);
        echo "La bonne reponse était : ".$ligne2["libeller"]."br";


    }


}
echo "<br> $score / 20";

// Récupérer l'ID utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Insérer le score dans la table
$sql = "INSERT INTO scores (user_id, score) VALUES (?, ?)";
$stmt = mysqli_prepare($id, $sql);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $score);
    // "ii" signifie :
    // Le premier i correspond à un entier (user_id).
    // Le deuxième i correspond à un entier (score).

if (mysqli_stmt_execute($stmt)) {
    echo "<br><br>Score enregistré avec succès !";
} else {
    echo "Erreur lors de l'enregistrement du score : " . mysqli_error($id);
}

mysqli_stmt_close($stmt);
mysqli_close($id);

?>

<br><br>
<a href="listeQuestions.php">
    <input type="button" style="font-size: 30px; border-radius:20px; background-color:lightgreen;" value="Rejouer">
</a>
<a href="mes_scores.php">
    <input type="button" style="font-size: 30px; border-radius:20px; background-color:lightgreen;" value="Afficher mes scores">
</a>
<!-- 


-->