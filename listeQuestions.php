<?php

session_start();
include "but_deco.php";

// Vérifiez que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Gestion de la visibilité admin
$isAdmin = $_SESSION['user_id'] == 1;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - Tentez votre chance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        h1, h3 {
            color: #343a40;
        }

        h1 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 20px;
        }

        .admin-btn {
            display: block;
            margin: 20px auto;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            border: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .admin-btn:hover {
            background-color: #218838;
        }

        .question {
            margin-top: 20px;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .welcome-message {
            text-align: center;
            font-size: 1.5rem;
            color: #555;
        }

        hr {
            margin: 30px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($isAdmin): ?>
        <a href="all_scores.php" class="admin-btn">Score des utilisateurs</a>
    <?php endif; ?>

    <p class="welcome-message">Salut, <strong><?= htmlspecialchars($_SESSION["user_name"]); ?></strong> !</p>
    <h1>Tentez votre chance</h1>
    <hr>

    <form action="resultat.php" method="post">
        <?php
        include "./connect.php";
        $sql = "SELECT * FROM questions ORDER BY RAND() LIMIT 10";
        $resultat = mysqli_query($id, $sql);

        // Boucle d'affichage des questions
        $i = 1;
        while ($ligne = mysqli_fetch_assoc($resultat)) {
            echo "<div class='question'>";
            echo "<h3>$i. " . htmlspecialchars($ligne['libelleQ']) . "</h3>";
            $i++;

            $sql2 = "SELECT * FROM reponses WHERE idq = " . $ligne["idq"];
            $resultat2 = mysqli_query($id, $sql2);
            while ($ligne2 = mysqli_fetch_assoc($resultat2)) {
                $idq = $ligne2['idq'];
                $idr = $ligne2['idr'];
                echo "<div><label><input type='radio' name='$idq' value='$idr'> " . htmlspecialchars($ligne2["libeller"]) . "</label></div>";
            }
            echo "</div>";
        }
        ?>
        <hr>
        <button type="submit" class="submit-btn">Envoyer</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoA4UQNQ1epGvrIV+UhYkxUCpGTs9d7fA6llDxWfNpGAxiq" crossorigin="anonymous"></script>
</body>
</html>
