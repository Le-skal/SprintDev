<!-- data base structure: ID(int), Nom(varchar), Email(varchar), Role(varchar mais 3 choix: Admin, Bibliothecaire, User), DateInscription(date), MotDePasse(varchar) -->
<!-- Ici, un personne rentre son nom, son email et son mot de passe 2 fois (case sensitive). si les 2 mot de passe sont les memes, un nouveau utilisateur est creer dans la base de donne sprintdev (DateInscription(date du jour), Role(User)). -->


<?php
session_start();

if (isset($_POST["bout"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Connexion à la base de données
    $id = mysqli_connect("localhost", "root", "", "sprintdev");

    if (!$id) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    // Requête sécurisée avec case-sensitivity pour le mot de passe
    $req = $id->prepare("SELECT * FROM utilisateurs WHERE Email = ? AND BINARY MotDePasse = ?");
    $req->bind_param("ss", $email, $password); // "ss" signifie que les deux variables sont des chaînes
    $req->execute();
    $res = $req->get_result();

    if ($res->num_rows > 0) {
        $ligne = $res->fetch_assoc();
        $_SESSION['user_id'] = $ligne["ID"];
        $_SESSION["Nom"] = $ligne["Nom"];
        $_SESSION["Email"] = $ligne["Email"];
        $_SESSION["Role"] = $ligne["Role"];

        // Redirection selon le rôle
        switch ($ligne["Role"]) {
            case "Admin":
                header("Location: Admin.php");
                exit();
            case "Bibliothecaire":
                header("Location: Bibliothecaire.php");
                exit();
            case "User":
                header("Location: user.php");
                exit();
            default:
                echo "<h3>Rôle inconnu. Veuillez contacter l'administrateur.</h3>";
        }
    } else {
        $erreur = "<h3 class='text-danger'>Erreur de connexion : Email ou mot de passe incorrect.</h3>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Systeme de Gestion de Bibliotheque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Système de Gestion de Bibliothèque</h1>
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Connexion</h5>
                        <?php if (isset($erreur)) echo $erreur; ?>
                        <form action="connexion.php" method="POST">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="loginEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="loginPassword" name="password" required>
                            </div>
                            <button type="submit" name="bout" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                        <p class="text-center mt-3">
                            Vous n'avez pas de compte ? <a href="inscription.php">S'inscrire</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
