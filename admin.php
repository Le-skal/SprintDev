<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sprintdev";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_book'])) {
        // Gestion de la mise à jour
        $book_id = $conn->real_escape_string($_POST['book_id']);
        $titre = $conn->real_escape_string($_POST['titre']);
        $auteur = $conn->real_escape_string($_POST['auteur']);
        $categorie = $conn->real_escape_string($_POST['categorie']);

        // Vérifie si le livre existe
        $check_sql = "SELECT * FROM livres WHERE ID = '$book_id'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            // Mise à jour des données
            $update_sql = "UPDATE livres 
                           SET Titre = '$titre', Auteur = '$auteur', Categorie = '$categorie' 
                           WHERE ID = '$book_id'";
            if ($conn->query($update_sql) === TRUE) {
                echo "Livre mis à jour avec succès.";
            } else {
                echo "Erreur lors de la mise à jour : " . $conn->error;
            }
        } else {
            echo "Erreur : Livre introuvable.";
        }
    } elseif (isset($_POST['titre']) && isset($_POST['auteur']) && isset($_POST['categorie'])) {
        // Gestion de l'ajout d'un livre
        $titre = $conn->real_escape_string($_POST['titre']);
        $auteur = $conn->real_escape_string($_POST['auteur']);
        $categorie = $conn->real_escape_string($_POST['categorie']);
        $etat = 'Disponible'; // Par défaut

        $sql = "INSERT INTO livres (Titre, Auteur, Categorie, Etat) VALUES ('$titre', '$auteur', '$categorie', '$etat')";

        if ($conn->query($sql) === TRUE) {
            echo "Livre ajouté avec succès.";
        } else {
            echo "Erreur : " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['delete_book']) && isset($_POST['book_id'])) {
        // Récupération et nettoyage de l'ID
        $book_id = $conn->real_escape_string($_POST['book_id']);
        
        // Vérification si le livre existe
        $check_sql = "SELECT * FROM livres WHERE ID = '$book_id'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            // Suppression du livre
            $delete_sql = "DELETE FROM livres WHERE ID = '$book_id'";
            if ($conn->query($delete_sql) === TRUE) {
                echo "Livre supprimé avec succès.";
            } else {
                echo "Erreur lors de la suppression : " . $conn->error;
            }
        } else {
            echo "Erreur : Livre introuvable.";
        }
    }if (isset($_POST['add_user'])) {
        // Ajout d'un utilisateur
        $nom = $conn->real_escape_string($_POST['nom']);
        $email = $conn->real_escape_string($_POST['email']);
        $mot_de_passe = $conn->real_escape_string($_POST['mot_de_passe']);
        $role = $conn->real_escape_string($_POST['role']);

        $sql = "INSERT INTO utilisateurs (Nom, Email, MotDePasse, Role, DateInscription) VALUES ('$nom', '$email', '$mot_de_passe', '$role', NOW())";

        if ($conn->query($sql) === TRUE) {
            echo "Utilisateur ajouté avec succès.";
        } else {
            echo "Erreur : " . $conn->error;
        }
    } elseif (isset($_POST['update_user'])) {
        // Mise à jour d'un utilisateur
        $user_id = $conn->real_escape_string($_POST['user_id']);
        $nom = isset($_POST['nom']) ? $conn->real_escape_string($_POST['nom']) : null;
        $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
        $mot_de_passe = isset($_POST['mot_de_passe']) && !empty($_POST['mot_de_passe']) ? $conn->real_escape_string($_POST['mot_de_passe']) : null;
        $role = isset($_POST['role']) ? $conn->real_escape_string($_POST['role']) : null;

        // Construire la requête dynamique
        $update_sql = "UPDATE utilisateurs SET ";
        $fields = [];
        if ($nom) $fields[] = "Nom = '$nom'";
        if ($email) $fields[] = "Email = '$email'";
        if ($mot_de_passe) $fields[] = "MotDePasse = '$mot_de_passe'";
        if ($role) $fields[] = "Role = '$role'";
        $update_sql .= implode(', ', $fields) . " WHERE ID = '$user_id'";

        if ($conn->query($update_sql) === TRUE) {
            echo "Utilisateur mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour : " . $conn->error;
        }
    } elseif (isset($_POST['delete_user'])) {
        // Suppression d'un utilisateur
        $user_id = $conn->real_escape_string($_POST['user_id']);

        $check_sql = "SELECT * FROM utilisateurs WHERE ID = '$user_id'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            $delete_sql = "DELETE FROM utilisateurs WHERE ID = '$user_id'";
            if ($conn->query($delete_sql) === TRUE) {
                echo "Utilisateur supprimé avec succès.";
            } else {
                echo "Erreur lors de la suppression : " . $conn->error;
            }
        } else {
            echo "Erreur : Utilisateur introuvable.";
        }
    }

}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord de l'administrateur</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="text-right">
            <div>
                <a href="deconnexion.php" class="btn btn-danger">Deconnexion</a>
            </div>
        </div>
        <div class="text-center">
            <h1>Systeme de gestion de bibliotheque</h1>
            <p>Bienvenue <?php echo htmlspecialchars($_SESSION['Nom']); ?> !</p>
        </div>
        
        <!-- Vue de l'administrateur -->
        <h2>Tableau de bord de l'administrateur</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Gerer les livres</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addBookModal">Ajouter un livre</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#editBookModal">Modifier un livre</button>
                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteBookModal">Supprimer un livre</button>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Gerer les utilisateurs</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Ajouter un utilisateur</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#editUserModal">Modifier un utilisateur</button>
                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal">Supprimer un utilisateur</button>
            </div>
        </div>

        <!-- Modal Ajouter un livre -->
        <div class="modal fade" id="addBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un livre</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="admin.php" method="POST">
                            <div class="form-group">
                                <label for="bookTitle">Titre</label>
                                <input type="text" id="bookTitle" name="titre" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="bookAuthor">Auteur</label>
                                <input type="text" id="bookAuthor" name="auteur" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="bookCategory">Categorie</label>
                                <select id="bookCategory" name="categorie" class="form-control" required>
                                    <option value="" disabled selected>Toutes les catégories</option>
                                    <option value="fiction">Fiction</option>
                                    <option value="non-fiction">Non-fiction</option>
                                    <option value="science">Science</option>
                                    <option value="history">Histoire</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Modifier un livre -->
        <div class="modal fade" id="editBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier un livre</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="admin.php" method="POST">
                            <!-- Champ caché pour différencier la mise à jour -->
                            <input type="hidden" name="update_book" value="1">
                            <div class="form-group">
                                <label for="editBookId">ID du livre</label>
                                <input type="text" id="editBookId" name="book_id" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editBookTitle">Titre</label>
                                <input type="text" id="editBookTitle" name="titre" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editBookAuthor">Auteur</label>
                                <input type="text" id="editBookAuthor" name="auteur" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editBookCategory">Categorie</label>
                                <select id="editBookCategory" name="categorie" class="form-control" required>
                                    <option value="" disabled>Toutes les catégories</option>
                                    <option value="fiction">Fiction</option>
                                    <option value="non-fiction">Non-fiction</option>
                                    <option value="science">Science</option>
                                    <option value="history">Histoire</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Fonction pour ouvrir le modal avec les données du livre
            function openEditBookModal(id, title, author, category) {
                document.getElementById('editBookId').value = id;
                document.getElementById('editBookTitle').value = title;
                document.getElementById('editBookAuthor').value = author;
                document.getElementById('editBookCategory').value = category;
                $('#editBookModal').modal('show');
            }
        </script>



        <!-- Modal Supprimer un livre -->
        <div class="modal fade" id="deleteBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Supprimer un livre</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="admin.php" method="POST">
                            <input type="hidden" name="delete_book" value="1">
                            <div class="form-group">
                                <label for="deleteBookId">ID du livre</label>
                                <input type="text" id="deleteBookId" name="book_id" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Ajouter un utilisateur -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="admin.php" method="POST">
                            <input type="hidden" name="add_user" value="1">
                            <div class="form-group">
                                <label for="userName">Nom</label>
                                <input type="text" id="userName" name="nom" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="userEmail">Email</label>
                                <input type="email" id="userEmail" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="password" id="password" name="mot_de_passe" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="userRole">Role</label>
                                <select id="userRole" name="role" class="form-control" required>
                                    <option value="Admin">Admin</option>
                                    <option value="Bibliothecaire">Bibliothecaire</option>
                                    <option value="User">User</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Modifier un utilisateur -->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier un utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="admin.php" method="POST">
                            <input type="hidden" name="update_user" value="1">
                            <div class="form-group">
                                <label for="editUserId">ID de l'utilisateur</label>
                                <input type="text" id="editUserId" name="user_id" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editUserName">Nom</label>
                                <input type="text" id="editUserName" name="nom" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editUserEmail">Email</label>
                                <input type="email" id="editUserEmail" name="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editPassword">Mot de passe</label>
                                <input type="password" id="editPassword" name="mot_de_passe" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editUserRole">Role</label>
                                <select id="editUserRole" name="role" class="form-control">
                                    <option value="admin">Administrateur</option>
                                    <option value="librarian">Bibliothécaire</option>
                                    <option value="user">Utilisateur</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Supprimer un utilisateur -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Supprimer un utilisateur</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="admin.php" method="POST">
                            <input type="hidden" name="delete_user" value="1">
                            <div class="form-group">
                                <label for="deleteUserId">ID de l'utilisateur</label>
                                <input type="text" id="deleteUserId" name="user_id" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
</body>
</html>
