<?php
session_start();
include "but_deco.php";
$conn = mysqli_connect("localhost", "root", "", "qcm");

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupérer l'ID utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Requête pour récupérer les scores
$sql = "SELECT score, date_joue FROM scores WHERE user_id = ? ORDER BY date_joue DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$somme = 0;
$i = 0; 
echo "<h3>Historique des scores :</h3>";
if (mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li>Score : " . $row['score'] . " | Date : " . $row['date_joue'] . "</li>";
        $somme += $row['score'];
        $i++;
    }
    echo "</ul>";
} else {
    echo "Aucun score enregistré.";
}

$moyenne = $somme / $i;

echo "<h2> Moyenne </h2>";
echo "La moyenne de vos scores est de <b>".$moyenne."</b>";






mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
