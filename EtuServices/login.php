<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Site de Vente et d'Achat</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header { background: #333; color: white; padding: 15px; text-align: center; }
        nav ul { list-style: none; padding: 0; text-align: center; }
        nav ul li { display: inline; margin: 0 15px; }
        nav ul li a { color: white; text-decoration: none; }
        .container { width: 80%; margin: auto; padding: 20px; }
        .products { display: flex; flex-wrap: wrap; gap: 20px; }
        .product { border: 1px solid #ddd; padding: 15px; width: 30%; text-align: center; }
        footer { background: #333; color: white; text-align: center; padding: 10px; margin-top: 20px; }
    </style>
</head>


<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "test";

// Conection à la base de données
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Connexion Redis
// $redis_status = shell_exec("python3 ../client_redis.py connect 2>&1");
// $redis_response = json_decode($redis_status, true);
// if (!isset($redis_response["status"]) || $redis_response["status"] !== "OK") {
//     die("Erreur de connexion à Redis : " . $redis_status);
// }
// echo "Connexion Redis réussie !<br>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $userKey = "login_attempts:$email";

    // Récupération des tentatives
    $attempts_response = shell_exec("python3 ../client_redis.py get $email 2>&1");
    $attempts_data = json_decode($attempts_response, true);
    $attempts = isset($attempts_data["attempts"]) ? $attempts_data["attempts"] : 0;

    if ($attempts >= 10) {
        die("Trop de tentatives. Veuillez réessayer plus tard.");
    }

    // Vérification de l'email
    $sql = "SELECT * FROM info834_utilisateur WHERE mail LIKE '".$email."'";
    echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        echo "Email non trouvé.";
    } else {
        $row = $result->fetch_assoc();
        if ($password === $row["mdp"]) {
            $_SESSION["prenom"] = $row["prenom"];
            // Incrémentation des tentatives
            shell_exec("python3 ../client_redis.py increment $email 2>&1");
            header("Location: accueil.php");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    }
    // Incrémentation des tentatives
    shell_exec("python3 ../client_redis.py increment $email 2>&1");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <h1>Connexion</h1>
        <nav>
            <ul>
                <li><a href="accueil.php">Accueil</a></li>
                <li><a href="#">Achat</a></li>
                <li><a href="#">Vendre un produit</a></li>
                <li><a href="statistiques.php">Statistiques</a></li>
                <li><a href="login.php">Se connecter</a></li>
            </ul>
        </nav>
    </header>
    <form method="post" action="">
        <label for="email">Email :</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
