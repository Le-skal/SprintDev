<!-- data base structure: ID(int), Nom(varchar), Email(varchar), Role(varchar mais 3 choix: Admin, Bibliothecaire, User), DateInscription(date), MotDePasse(varchar) -->
<!-- Ici, un personne rentre son nom, son email et son mot de passe 2 fois (case sensitive). si les 2 mot de passe sont les memes, un nouveau utilisateur est creer dans la base de donne sprintdev, table utilisateur (DateInscription(date du jour), Role(User)). -->
<!-- corriger le code car il y a une erreur a la lignre 35 et un nouvelle utilisateur n'est pas creer dans la table-->
<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "sprintdev");

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nom = trim($_POST["Nom"]);
    $Email = trim($_POST["Email"]);
    $MotDePasse = $_POST["MotDePasse"];
    $ConfirmMotDePasse = $_POST["confirm_MotDePasse"];

    // Validation des champs
    if (empty($Nom) || empty($Email) || empty($MotDePasse) || empty($ConfirmMotDePasse)) {
        echo "<h3>Tous les champs sont obligatoires.</h3>";
        exit;
    }

    // Validation des mots de passe
    if ($MotDePasse !== $ConfirmMotDePasse) {
        $erreur = "<h3>Les mots de passe ne correspondent pas.</h3>";
    }
    else{
        // Vérification si l'email existe déjà
        $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE Email = ?");
        $stmt->bind_param("s", $Email); // ligne 35
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $erreur = "<h3>Cette adresse email est déjà utilisée.</h3>";
            $stmt->close();
        }
        else{
            $stmt->close();


            // Insertion dans la base de données
            $stmt = $conn->prepare("INSERT INTO utilisateurs (Nom, Email, MotDePasse, Role, DateInscription) VALUES (?, ?, ?, 'User', NOW())");
            $stmt->bind_param("sss", $Nom, $Email, $MotDePasse);

            if ($stmt->execute()) {
                echo "<h3>Inscription réussie, vous pouvez maintenant vous connecter.</h3>";
                header("refresh:3;url=connexion.php");
            } else {
                echo "<h3>Une erreur est survenue lors de l'inscription.</h3>";
            }

            $stmt->close();
        }
    }
}
$conn->close();
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire - Gestion de Bibliotheque</title>
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
        <h1 class="text-center">Systeme de Gestion de Bibliotheque</h1>
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">S'inscrire</h5>
                        <?php if (isset($erreur)) echo $erreur; ?>
                        <form action="inscription.php" method="POST">
                            <div class="mb-3">
                                <label for="registerName" class="form-label">Nom Complet</label>
                                <input type="text" class="form-control" id="registerName" name="Nom" required>
                            </div>
                            <div class="mb-3">
                                <label for="registerEmail" class="form-label">Email</label>
                                <input type="Email" class="form-control" id="registerEmail" name="Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="registerPassword" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="registerPassword" name="MotDePasse" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_MotDePasse" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                        </form>
                        <p class="text-center mt-3">
                            Vous avez dejà un compte ? <a href="connexion.php">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
