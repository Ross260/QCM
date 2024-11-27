<?php

if(isset($_POST['send'])){
    session_start();
    $id = mysqli_connect("localhost","root","","qcm");
    $nom = $_POST['name'];
    $mdp = $_POST['mdp'];
    $email = $_POST['email'];
    
    $req = "INSERT INTO utilisateur (nom,mdp,email) VALUES ('$nom','$mdp','$email')";
    $res = mysqli_query($id,$req);
    echo "<h3>Inscription réussie, connectez vous....";
   
    header("location:login.php");
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
        .form-container input[type="text"], 
        .form-container input[type="email"],
        .form-container input[type="password"] {
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
        <h2>Création de compte</h2>
        <form action="" method="post">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="mdp">Mot de passe:</label>
            <input type="password" id="mdp" name="mdp" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <input type="submit" name="send" value="Envoyer">
        </form>
        <br>
        <a href="login.php">Déja un compte</a>
    </div>
</body>
</html>
