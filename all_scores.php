<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "qcm");

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Étape 1 : Récupérer tous les utilisateurs pour afficher dans une liste déroulante
$sql_users = "SELECT id_utilisateur, nom FROM utilisateur";
$result_users = mysqli_query($conn, $sql_users);

// Initialisation des variables
$selected_user_id = null;
$scores = [];
$moyenne = 0;

// Étape 2 : Si un utilisateur est sélectionné dans le formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $selected_user_id = $_POST['user_id'];

    // Récupérer les scores de l'utilisateur sélectionné
    $sql_scores = "SELECT score, date_joue FROM scores WHERE user_id = ? ORDER BY date_joue DESC";
    $stmt = mysqli_prepare($conn, $sql_scores);
    mysqli_stmt_bind_param($stmt, "i", $selected_user_id);
    mysqli_stmt_execute($stmt);
    $result_scores = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result_scores)) {
        $scores[] = $row; // Ajouter chaque score dans un tableau
    }

    // Calculer la moyenne des scores
    if (count($scores) > 0) {
        $total = array_sum(array_column($scores, 'score'));
        $moyenne = $total / count($scores);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scores des utilisateurs</title>
</head>
<body>
    <h1>Consulter les scores des utilisateurs</h1>

    <!-- Étape 3 : Formulaire pour sélectionner un utilisateur -->
    <form method="POST" action="">
        <label for="user_id">Sélectionnez un utilisateur :</label>
        <select name="user_id" id="user_id" required>
            <option value="">-- Choisir un utilisateur --</option>
            <?php while ($user = mysqli_fetch_assoc($result_users)) : ?>
                <option value="<?= $user['id_utilisateur']; ?>" <?= ($selected_user_id == $user['id_utilisateur']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($user['nom']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Afficher les scores</button>
    </form>

    <!-- Étape 4 : Affichage des scores et de la moyenne -->
    <?php if (!is_null($selected_user_id)) : ?>
        <h2>Scores pour l'utilisateur sélectionné :</h2>

        <?php if (count($scores) > 0) : ?>
            <ul>
                <?php foreach ($scores as $score) : ?>
                    <li>Score : <?= $score['score']; ?> | Date : <?= $score['date_joue']; ?></li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Moyenne des scores :</strong> <?= number_format($moyenne, 2); ?></p>
        <?php else : ?>
            <p>Aucun score enregistré pour cet utilisateur.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
