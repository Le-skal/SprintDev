<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord utilisateur</title>
    <!-- Lien vers le fichier CSS externe -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="text-right">
            <div>
                <a href="connextion.php" class="btn btn-danger">Deconnexion</a>
            </div>
        </div>
        <div class="text-center">
            <h1>Systeme de gestion de bibliotheque</h1>
            <p>Bienvenue, Utilisateur !</p>
        </div>

        <!-- Vue utilisateur -->
        <h2>Tableau de bord utilisateur</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Parcourir les livres</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#searchBookModal">Rechercher des livres</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#filterBookModal">Filtrer par categorie</button>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Vos reservations</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addReservationsModal">Ajouter une reservation</button>
                <button class="btn btn-info" data-toggle="modal" data-target="#viewReservationsModal">Voir les reservations</button>
                <button class="btn btn-warning" data-toggle="modal" data-target="#cancelReservationsModal">Annuler une reservation</button>
            </div>
        </div>

        <!-- Modal de recherche de livres -->
        <div class="modal fade" id="searchBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rechercher des livres</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="searchKeyword">Mot-cle</label>
                                <input type="text" id="searchKeyword" class="form-control" placeholder="Entrez le titre, l'auteur ou un mot-cle" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Rechercher</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de filtrage par categorie -->
        <div class="modal fade" id="filterBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filtrer les livres par categorie</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="bookCategory">Categorie</label>
                                <select id="bookCategory" class="form-control" required>
                                    <option value="">Selectionnez une categorie</option>
                                    <option value="fiction">Fiction</option>
                                    <option value="non-fiction">Non-fiction</option>
                                    <option value="science">Science</option>
                                    <option value="history">Histoire</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal d'ajout de reservation -->
        <div class="modal fade" id="addReservationsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une reservation</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="reservationBookId">ID du livre</label>
                                <input type="text" id="reservationBookId" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Reserver</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de visualisation des reservations -->
        <div class="modal fade" id="viewReservationsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Vos reservations</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Voici les livres que vous avez reserves :</p>
                        <ul id="reservationList" class="list-group">
                            <!-- Liste dynamique des livres reserves -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal d'annulation de reservation -->
        <div class="modal fade" id="cancelReservationsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Annuler une reservation</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="cancelReservationId">ID de reservation</label>
                                <input type="text" id="cancelReservationId" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Annuler</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
