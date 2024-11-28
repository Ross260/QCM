
<?php
if (isset($_POST['con'])) {
    
// Connexion à la base de données avec mysqli
$id = mysqli_connect("localhost", "root", "", "qcm");
if (!$id) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $mdp = trim($_POST['mdp']);

    // Préparer la requête SQL pour trouver l'utilisateur
    $sql = "SELECT * FROM utilisateur WHERE email = ?";
    $stmt = $id->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'utilisateur existe
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Vérifier le mot de passe (hashé)
        if ($mdp == $user['mdp']) {
            // Connexion réussie
            // echo "Bienvenue, " . htmlspecialchars($user['nom']) . "!";
            echo "connexion reussi";
            sleep(2);
            // Vérification des identifiants (exemple simplifié)
            session_start();
            $_SESSION['user_name'] = $user["nom"];
            $_SESSION['user_id'] = $user["id_utilisateur"];
            
            header("Location: listeQuestions.php");
        } else {
            // Mot de passe incorrect
            echo " <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            Erreur : mot de passe incorrect.";
        }
    } else {
        // Utilisateur non trouvé
        echo "Erreur : utilisateur non trouvé.";
    }

    $stmt->close();
}

$id->close();
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
        }
        .form-container input[type="password"], 
        .form-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>

    <title>Formulaire</title>
</head>
<body>
    <div class="form-container">
        <h2>Connecter vous</h2>
        <form action="" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="mdp">Mot de passe:</label>
            <input type="password" id="mdp" name="mdp" required>

            <input type="submit" name="con" value="Envoyer">
        </form>
        <br>
        <a href="register.php">Créé un compte</a>
    </div>
</body>
</html>
