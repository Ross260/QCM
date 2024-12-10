<?php
session_start();

include "but_deco.php";
include "connect.php";

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialisation des variables
$score = 0;
$feedback = "";

foreach ($_POST as $key => $value) {
    // Requête pour récupérer la réponse choisie
    $sql = "SELECT * FROM reponses WHERE idr = $value";
    $resultat = mysqli_query($id, $sql);
    $ligne = mysqli_fetch_assoc($resultat);

    if ($ligne["verite"] == 1) {
        $score += 2;
    } else {
        // Récupérer la question
        $sqlQuestion = "SELECT * FROM questions WHERE idq = $key";
        $resultatQuestion = mysqli_query($id, $sqlQuestion);
        $ligneQuestion = mysqli_fetch_assoc($resultatQuestion);

        // Récupérer la bonne réponse
        $sqlCorrectAnswer = "SELECT * FROM reponses WHERE idq = $key AND verite = 1";
        $resultatCorrectAnswer = mysqli_query($id, $sqlCorrectAnswer);
        $ligneCorrectAnswer = mysqli_fetch_assoc($resultatCorrectAnswer);

        // Ajouter au feedback
        $feedback .= "<div class='alert alert-danger mt-3'>
            <strong>Erreur à la question :</strong> " . htmlspecialchars($ligneQuestion["libelleQ"]) . "<br>
            <strong>La bonne réponse était :</strong> " . htmlspecialchars($ligneCorrectAnswer["libeller"]) . "
        </div>";
    }
}

// Enregistrer le score dans la base de données
$user_id = $_SESSION['user_id'];
$sql = "INSERT INTO scores (user_id, score) VALUES (?, ?)";
$stmt = mysqli_prepare($id, $sql);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $score);

if (mysqli_stmt_execute($stmt)) {
    $feedback .= "<div class='alert alert-success mt-3'>Score enregistré avec succès !</div>";
} else {
    $feedback .= "<div class='alert alert-danger mt-3'>Erreur lors de l'enregistrement du score : " . mysqli_error($id) . "</div>";
}

mysqli_stmt_close($stmt);
mysqli_close($id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        .btn-custom {
            margin: 10px 0;
            width: 100%;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            border: none;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background-color: #218838;
        }

        .alert {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Résultats du Quiz</h2>
        <div class="alert alert-info text-center mt-4">
            <strong>Votre score :</strong> <?= $score; ?> / 20
        </div>

        <?= $feedback; ?>

        <div class="mt-4">
            <a href="listeQuestions.php" class="btn btn-custom">Rejouer</a>
            <a href="mes_scores.php" class="btn btn-custom">Afficher mes scores</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoA4UQNQ1epGvrIV+UhYkxUCpGTs9d7fA6llDxWfNpGAxiq" crossorigin="anonymous"></script>
</body>
</html>
