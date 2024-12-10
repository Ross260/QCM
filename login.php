
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
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .form-container input[type="email"], 
        .form-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-container a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .form-container a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Connexion</h2>
        <form action="" method="post">
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" placeholder="Entrez votre e-mail" required>
            
            <label for="mdp">Mot de passe</label>
            <input type="password" id="mdp" name="mdp" placeholder="Entrez votre mot de passe" required>

            <input type="submit" name="con" value="Se connecter">
        </form>
        <a href="register.php">Créer un compte</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoA4UQNQ1epGvrIV+UhYkxUCpGTs9d7fA6llDxWfNpGAxiq" crossorigin="anonymous"></script>
</body>
</html>
