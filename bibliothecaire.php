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
    header('Location: connexion.php'); // Redirection si l'utilisateur n'est pas connecté
    exit;
}

// Vérifiez si la requête est une requête AJAX
if (isset($_POST['ajaxRequest']) && $_POST['ajaxRequest'] == 'true') {
    // Récupération des filtres
    $keyword = isset($_POST['searchKeyword']) ? $_POST['searchKeyword'] : '';
    $category = isset($_POST['bookCategory']) ? $_POST['bookCategory'] : '';
    $state = isset($_POST['bookState']) ? $_POST['bookState'] : '';

    // Construction de la requête SQL
    $sql = "SELECT * FROM livres WHERE 1=1";

    if (!empty($keyword)) {
        $sql .= " AND (Titre LIKE '%" . $conn->real_escape_string($keyword) . "%' 
                    OR Auteur LIKE '%" . $conn->real_escape_string($keyword) . "%')";
    }

    if (!empty($category)) {
        $sql .= " AND Categorie = '" . $conn->real_escape_string($category) . "'";
    }

    if (!empty($state)) {
        $sql .= " AND Etat = '" . $conn->real_escape_string($state) . "'";
    }

    if (isset($_POST['searchBookId']) && !empty($_POST['searchBookId'])) {
        $bookId = $_POST['searchBookId'];
        $sql .= " AND ID = '" . $conn->real_escape_string($bookId) . "'";
    }

    $result = $conn->query($sql);


    // Construction de la réponse
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Catégorie</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['ID']}</td>
                    <td>{$row['Titre']}</td>
                    <td>{$row['Auteur']}</td>
                    <td>{$row['Categorie']}</td>
                    <td>{$row['Etat']}</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='text-danger'>Aucun livre trouvé avec ces critères.</p>";
    }

    

    $conn->close();
    exit;
}


// Si la requête AJAX est pour émettre un livre
if (isset($_POST['issueBookId']) && isset($_POST['issueUserId'])) {
    $bookId = $_POST['issueBookId'];
    $userId = $_POST['issueUserId'];

    // Vérification de l'état du livre
    $sql = "SELECT Etat FROM livres WHERE ID = '$bookId'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        if ($book['Etat'] === 'Disponible') {
            // Calcul de la DateEcheance (deux semaines après aujourd'hui)
            $dateEmprunt = date('Y-m-d');  // Date du jour
            $dateEcheance = date('Y-m-d', strtotime('+2 weeks'));

            // Insérer l'emprunt dans la table 'emprunts'
            $sqlInsert = "INSERT INTO emprunts (Utilisateur_ID, Livre_ID, DateEmprunt, DateEcheance, Retard) 
                          VALUES ('$userId', '$bookId', '$dateEmprunt', '$dateEcheance', FALSE)";
            if ($conn->query($sqlInsert) === TRUE) {
                // Mise à jour de l'état du livre à 'Indisponible'
                $sqlUpdate = "UPDATE livres SET Etat = 'Indisponible' WHERE ID = '$bookId'";
                $conn->query($sqlUpdate);
                echo "Le livre a été émis avec succès.";
            } else {
                echo "Erreur lors de l'émission du livre : " . $conn->error;
            }
        } elseif ($book['Etat'] === 'Reserve') {
            // Si le livre est réservé, vérifier si c'est par ce même utilisateur
            $sqlReserveCheck = "SELECT Utilisateur_ID FROM reservations WHERE Livre_ID = '$bookId' AND Utilisateur_ID = '$userId'";
            $reserveResult = $conn->query($sqlReserveCheck);
            if ($reserveResult->num_rows > 0) {
                // Si réservé par l'utilisateur, annuler la réservation
                $sqlDeleteReservation = "DELETE FROM reservations WHERE Livre_ID = '$bookId' AND Utilisateur_ID = '$userId'";
                if ($conn->query($sqlDeleteReservation) === TRUE) {
                    // Mettre à jour l'état du livre à 'Disponible'
                    $sqlUpdateState = "UPDATE livres SET Etat = 'Disponible' WHERE ID = '$bookId'";
                    $conn->query($sqlUpdateState);
                    echo "La réservation a été annulée, et le livre est désormais disponible pour vous.";
                } else {
                    echo "Erreur lors de la suppression de la réservation : " . $conn->error;
                }
            } else {
                echo "Le livre est réservé par un autre utilisateur.";
            }
        } else {
            echo "Le livre n'est pas disponible.";
        }
    } else {
        echo "Livre introuvable.";
    }
    $conn->close();
    exit;
}

// Vérifiez si la requête est pour afficher les prêts
if (isset($_POST['ajaxRequest']) && $_POST['ajaxRequest'] == 'viewLoans') {
    // Construction de la requête SQL
    $sql = "SELECT emprunts.ID AS loan_id, utilisateurs.Nom AS user_name, livres.Titre AS book_title, livres.ID AS book_ID, emprunts.DateEmprunt, emprunts.DateEcheance, emprunts.Retard 
            FROM emprunts
            JOIN utilisateurs ON emprunts.Utilisateur_ID = utilisateurs.ID
            JOIN livres ON emprunts.Livre_ID = livres.ID";

    
    // Exécution de la requête
    $result = $conn->query($sql);

    // Construction de la réponse
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>ID Prêt</th>
                        <th>Nom de l'utilisateur</th>
                        <th>ID du livre</th>
                        <th>Date d'emprunt</th>
                        <th>Date d'échéance</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['loan_id']}</td>
                <td>{$row['user_name']}</td>
                <td>{$row['book_ID']}</td>
                <td>{$row['DateEmprunt']}</td>
                <td>{$row['DateEcheance']}</td>
            </tr>";

        }
        echo "</tbody></table>";
    } else {
        echo "<p class='text-danger'>Aucun prêt en cours.</p>";
    }

    $conn->close();
    exit;
}

// Vérifier si la requête AJAX est pour recevoir un livre
if (isset($_POST['returnBookId'])) {
    $bookId = $_POST['returnBookId'];

    // Vérification si l'emprunt existe pour ce livre
    $sql = "SELECT * FROM emprunts WHERE Livre_ID = '$bookId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si l'emprunt existe, récupérer les informations de l'emprunt
        $loan = $result->fetch_assoc();
        
        // Suppression de l'emprunt
        $sqlDeleteLoan = "DELETE FROM emprunts WHERE ID = '{$loan['ID']}'";
        if ($conn->query($sqlDeleteLoan) === TRUE) {
            // Mise à jour de l'état du livre à 'Disponible'
            $sqlUpdateState = "UPDATE livres SET Etat = 'Disponible' WHERE ID = '$bookId'";
            if ($conn->query($sqlUpdateState) === TRUE) {
                echo "Le livre a été reçu et l'emprunt a été supprimé avec succès.";
            } else {
                echo "Erreur lors de la mise à jour de l'état du livre : " . $conn->error;
            }
        } else {
            echo "Erreur lors de la suppression de l'emprunt : " . $conn->error;
        }
    } else {
        echo "Aucun emprunt en cours pour ce livre.";
    }
    $conn->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'checkLateLoans') {
    $currentDate = date('Y-m-d');

    $sqlUpdateLateLoans = "
        UPDATE emprunts 
        SET Retard = TRUE 
        WHERE DateEcheance < '$currentDate' AND Retard = FALSE";
    
    if ($conn->query($sqlUpdateLateLoans) === TRUE) {
        $message = "Les retards ont été mis à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour des retards : " . $conn->error;
    }
    echo "<script>alert('$message');</script>";
 
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord du bibliothecaire</title>
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
            <h1>Systeme de Gestion de Bibliotheque</h1>
            <p>Bienvenue, Bibliothecaire !</p>
        </div>

        <!-- Vue du bibliothecaire -->
        <h2>Tableau de bord du bibliothecaire</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Suivre les livres</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#viewBookModal">Voir livres</button>
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
                <form method="POST" action="bibliothecaire.php">
                    <input type="hidden" name="action" value="checkLateLoans">
                    <button type="submit" class="btn btn-primary">Vérifier les retards et envoyer des rappels</button>
                </form>
            </div>
        </div>

        <!-- Modal Voir tous les livres -->
        <div class="modal fade" id="viewBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rechercher et Filtrer les Livres</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="bookSearchForm" method="POST" action="bibliothecaire.php">
                            <div class="form-group">
                                <label for="searchBookId">ID du livre</label>
                                <input type="text" id="searchBookId" name="searchBookId" class="form-control" placeholder="Entrez l'ID du livre">
                            </div>
                            <div class="form-group">
                                <label for="searchKeyword">Mot-clé</label>
                                <input type="text" id="searchKeyword" name="searchKeyword" class="form-control" placeholder="Entrez le titre, l'auteur ou un mot-clé">
                            </div>
                            <div class="form-group">
                                <label for="bookCategory">Catégorie</label>
                                <select id="bookCategory" name="bookCategory" class="form-control">
                                    <option value="">Toutes les catégories</option>
                                    <option value="fiction">Fiction</option>
                                    <option value="non-fiction">Non-fiction</option>
                                    <option value="science">Science</option>
                                    <option value="history">Histoire</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bookState">État</label>
                                <select id="bookState" name="bookState" class="form-control">
                                    <option value="">Tous les états</option>
                                    <option value="Disponible">Disponible</option>
                                    <option value="Indisponible">Indisponible</option>
                                    <option value="Reserve">Reserve</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Rechercher</button>
                        </form>
                        <div id="searchResults" class="mt-4">
                            <!-- Les résultats de la recherche s'afficheront ici -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $("#bookSearchForm").on("submit", function (e) {
                    e.preventDefault();

                    $.ajax({
                        url: "bibliothecaire.php",
                        type: "POST",
                        data: $(this).serialize() + "&ajaxRequest=true", // Ajoutez ce paramètre
                        success: function (response) {
                            $("#searchResults").html(response); // Afficher uniquement les résultats
                        },
                        error: function () {
                            $("#searchResults").html("<p>Erreur lors du chargement des données.</p>");
                        }
                    });
                });
            });
        </script>




        <!-- Modal emettre un livre -->
        <div class="modal fade" id="issueBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Emettre un livre</h5>
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
                            <button type="submit" class="btn btn-primary">Émettre</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                // Soumission du formulaire d'émission de livre
                $("#issueBookModal form").on("submit", function (e) {
                    e.preventDefault();
                    
                    var bookId = $("#issueBookId").val();
                    var userId = $("#issueUserId").val();

                    $.ajax({
                        url: "bibliothecaire.php",
                        type: "POST",
                        data: {
                            issueBookId: bookId,
                            issueUserId: userId
                        },
                        success: function (response) {
                            alert(response);  // Afficher la réponse
                            $('#issueBookModal').modal('hide'); // Fermer le modal après l'émission
                        },
                        error: function () {
                            alert("Erreur lors de l'émission du livre.");
                        }
                    });
                });
            });
        </script>



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
                        <div id="loansTable">
                            <!-- Les prêts en cours s'afficheront ici -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                // Fonction pour récupérer et afficher les prêts
                $("#viewLoansModal").on("show.bs.modal", function () {
                    $.ajax({
                        url: "bibliothecaire.php",
                        type: "POST",
                        data: { ajaxRequest: 'viewLoans' },  // Demande des prêts
                        success: function (response) {
                            $("#loansTable").html(response); // Afficher les résultats dans le modal
                        },
                        error: function () {
                            $("#loansTable").html("<p>Erreur lors du chargement des prêts.</p>");
                        }
                    });
                });
            });
        </script>


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
        <script>
            $(document).ready(function () {
                // Soumission du formulaire de réception du livre
                $("#recieveBookModal form").on("submit", function (e) {
                    e.preventDefault();

                    var bookId = $("#returnBookId").val();

                    $.ajax({
                        url: "bibliothecaire.php",
                        type: "POST",
                        data: {
                            returnBookId: bookId
                        },
                        success: function (response) {
                            alert(response);  // Afficher la réponse du serveur
                            $('#recieveBookModal').modal('hide'); // Fermer le modal après la réception
                        },
                        error: function () {
                            alert("Erreur lors de la réception du livre.");
                        }
                    });
                });
            });
        </script>

    </div>
</body>
</html>
