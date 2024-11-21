<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord du bibliothecaire</title>
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
            <h1>Systeme de Gestion de Bibliotheque</h1>
            <p>Bienvenue, Bibliothecaire !</p>
        </div>

        <!-- Vue du bibliothecaire -->
        <h2>Tableau de bord du bibliothecaire</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Suivre les livres</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#viewBookModal">Voir tous les livres</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#statusBookModal">Verifier l'etat d'un livre</button>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Gerer les prets</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#issueBookModal">emettre un livre</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#viewLoansModal">Voir tous les prets</button>
                <button class="btn btn-warning" data-toggle="modal" data-target="#recieveBookModal">Recevoir un livre</button>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Rappels de prets</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#reminderModal">Envoyer un rappel</button>
            </div>
        </div>

        <!-- Modal Voir tous les livres -->
        <div class="modal fade" id="viewBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tous les livres</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Voici une liste de tous les livres disponibles dans la bibliothèque :</p>

                        <!-- Barre de recherche pour filtrer par Book ID -->
                        <div class="form-group">
                            <label for="filterBookID">Filtrer par ID du livre :</label>
                            <input type="text" id="filterBookID" class="form-control" placeholder="Entrez l'ID du livre">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Verifier l'etat d'un livre -->
        <div class="modal fade" id="statusBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Verifier l'etat d'un livre</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="statusBookId">Entrez l'ID du livre</label>
                                <input type="text" id="statusBookId" class="form-control" placeholder="ID du livre" required>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="checkBookStatus()">Verifier l'etat</button>
                        </form>
                        <div id="bookStatusResult" class="mt-3">
                            <!-- L'etat dynamique s'affichera ici -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal emettre un livre -->
        <div class="modal fade" id="issueBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">emettre un livre</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="issueBookId">ID du livre</label>
                                <input type="text" id="issueBookId" class="form-control" placeholder="ID du livre" required>
                            </div>
                            <div class="form-group">
                                <label for="issueUserId">ID de l'utilisateur</label>
                                <input type="text" id="issueUserId" class="form-control" placeholder="ID de l'utilisateur" required>
                            </div>
                            <button type="submit" class="btn btn-primary">emettre</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Voir tous les prêts -->
        <div class="modal fade" id="viewLoansModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tous les prêts</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Voici une liste de tous les prêts en cours :</p>
                        <div class="form-group">
                            <label for="userFilter">Filtrer par ID utilisateur :</label>
                            <input type="text" id="userFilter" class="form-control" placeholder="Entrer l'ID utilisateur" oninput="filterLoans()">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Recevoir un livre -->
        <div class="modal fade" id="recieveBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Recevoir un livre</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="returnBookId">ID du livre</label>
                                <input type="text" id="returnBookId" class="form-control" placeholder="ID du livre" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Marquer comme retourne</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Envoyer un rappel -->
        <div class="modal fade" id="reminderModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Envoyer un rappel</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="reminderForm">
                            <div class="form-group">
                                <label for="reminderUserId">ID de l'utilisateur</label>
                                <input type="text" id="reminderUserId" class="form-control" placeholder="ID de l'utilisateur" required>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="sendReminder()">Envoyer le rappel</button>
                        </form>
                        <div id="reminderResult" class="mt-3">
                            <!-- Résultat dynamique -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
