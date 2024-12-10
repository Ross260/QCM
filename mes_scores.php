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

// Calcul de la moyenne
$somme = 0;
$i = 0;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Scores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: #007bff;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #f1f1f1;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
        }

        .average {
            margin-top: 20px;
            padding: 15px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            font-size: 18px;
            text-align: center;
        }

        .no-scores {
            margin-top: 20px;
            padding: 15px;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            text-align: center;
        }

        .btn {
            display: block;
            margin: 20px auto;
            width: 50%;
            padding: 10px;
            font-size: 18px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Historique de vos scores</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <ul>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <li>
                        <strong>Score :</strong> <?= $row['score']; ?> 
                        <br>
                        <strong>Date :</strong> <?= $row['date_joue']; ?>
                    </li>
                    <?php 
                        $somme += $row['score'];
                        $i++;
                    ?>
                <?php endwhile; ?>
            </ul>

            <?php 
                $moyenne = $somme / $i; 
            ?>
            <div class="average">
                <strong>Moyenne :</strong> <?= round($moyenne, 2); ?>
            </div>
        <?php else: ?>
            <div class="no-scores">
                Aucun score enregistré.
            </div>
        <?php endif; ?>

        <a href="listeQuestions.php" class="btn">Rejouer</a>
    </div>

    <?php
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoA4UQNQ1epGvrIV+UhYkxUCpGTs9d7fA6llDxWfNpGAxiq" crossorigin="anonymous"></script>
</body>
</html>
