<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord de l'administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="text-right">
            <div>
                <a href="connexion.php" class="btn btn-danger">Deconnexion</a>
            </div>
        </div>
        <div class="text-center">
            <h1>Systeme de gestion de bibliotheque</h1>
            <p>Bienvenue, Administrateur !</p>
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
                        <form>
                            <div class="form-group">
                                <label for="bookTitle">Titre</label>
                                <input type="text" id="bookTitle" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="bookAuthor">Auteur</label>
                                <input type="text" id="bookAuthor" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="bookCategory">Categorie</label>
                                <input type="text" id="bookCategory" class="form-control" required>
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
                        <form>
                            <div class="form-group">
                                <label for="editBookId">ID du livre</label>
                                <input type="text" id="editBookId" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editBookTitle">Titre</label>
                                <input type="text" id="editBookTitle" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editBookAuthor">Auteur</label>
                                <input type="text" id="editBookAuthor" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editBookCategory">Categorie</label>
                                <input type="text" id="editBookCategory" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

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
                        <form>
                            <div class="form-group">
                                <label for="deleteBookId">ID du livre</label>
                                <input type="text" id="deleteBookId" class="form-control" required>
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
                        <form>
                            <div class="form-group">
                                <label for="userName">Nom</label>
                                <input type="text" id="userName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="userEmail">Email</label>
                                <input type="email" id="userEmail" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="text" id="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="userRole">Role</label>
                                <select id="userRole" class="form-control" required>
                                    <option value="admin">Administrateur</option>
                                    <option value="librarian">Bibliothecaire</option>
                                    <option value="user">Utilisateur</option>
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
                        <form>
                            <div class="form-group">
                                <label for="editUserId">ID de l'utilisateur</label>
                                <input type="text" id="editUserId" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editUserName">Nom</label>
                                <input type="text" id="editUserName" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editUserEmail">Email</label>
                                <input type="email" id="editUserEmail" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editpassword">Mot de passe</label>
                                <input type="text" id="editPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editUserRole">Role</label>
                                <select id="editUserRole" class="form-control">
                                    <option value="admin">Administrateur</option>
                                    <option value="librarian">Bibliothecaire</option>
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
                        <form>
                            <div class="form-group">
                                <label for="deleteUserId">ID de l'utilisateur</label>
                                <input type="text" id="deleteUserId" class="form-control" required>
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
