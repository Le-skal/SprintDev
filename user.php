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

$user_id = $_SESSION['user_id']; // Récupération de l'ID utilisateur

$sqlOverdueBooks = "
    SELECT e.Livre_ID, l.Titre, DATEDIFF(NOW(), e.DateEcheance) AS JoursRetard 
    FROM emprunts e
    JOIN livres l ON e.Livre_ID = l.ID
    WHERE e.Utilisateur_ID = ? AND e.Retard = 1
";

$stmtOverdue = $conn->prepare($sqlOverdueBooks);
$stmtOverdue->bind_param("i", $user_id);
$stmtOverdue->execute();
$resultOverdue = $stmtOverdue->get_result();

$overdueBooks = [];
while ($row = $resultOverdue->fetch_assoc()) {
    $overdueBooks[] = $row;
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

    // Exécution de la requête
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

if (isset($_POST['action']) && $_POST['action'] === 'addReservation') {
    $book_id = intval($_POST['book_id']); // Récupérer l'ID du livre depuis la requête POST
    $user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté
    $current_date = date('Y-m-d H:i:s'); // Date et heure actuelles

    // Vérifier si le livre existe et est disponible
    $checkBookQuery = "SELECT Etat FROM livres WHERE ID = ? AND Etat = 'Disponible'";
    $stmt = $conn->prepare($checkBookQuery);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Insérer la réservation dans la table `reservation`
        $insertReservationQuery = "INSERT INTO reservations (Utilisateur_ID, Livre_ID, DateReservation) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertReservationQuery);
        $stmt->bind_param("iis", $user_id, $book_id, $current_date);

        if ($stmt->execute()) {
            // Mettre à jour l'état du livre
            $updateBookStateQuery = "UPDATE livres SET Etat = 'Reserve' WHERE ID = ?";
            $stmt = $conn->prepare($updateBookStateQuery);
            $stmt->bind_param("i", $book_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Réservation ajoutée avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'état du livre.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de la réservation.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Livre non disponible pour la réservation.']);
    }
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'viewReservations') {
    $sql = "SELECT r.ID as ReservationID, r.DateReservation, l.Titre, l.Auteur 
            FROM reservations r
            JOIN livres l ON r.Livre_ID = l.ID
            WHERE r.Utilisateur_ID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservations = [];
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }

    echo json_encode($reservations);
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'cancelReservation') {
    $reservation_id = intval($_POST['reservation_id']); // Récupérer l'ID de la réservation

    // Vérifier si la réservation existe
    $checkReservationQuery = "SELECT Livre_ID FROM reservations WHERE ID = ?";
    $stmt = $conn->prepare($checkReservationQuery);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $reservation = $result->fetch_assoc();
        $book_id = $reservation['Livre_ID'];

        // Supprimer la réservation
        $deleteReservationQuery = "DELETE FROM reservations WHERE ID = ?";
        $stmt = $conn->prepare($deleteReservationQuery);
        $stmt->bind_param("i", $reservation_id);

        if ($stmt->execute()) {
            // Mettre à jour l'état du livre
            $updateBookStateQuery = "UPDATE livres SET Etat = 'Disponible' WHERE ID = ?";
            $stmt = $conn->prepare($updateBookStateQuery);
            $stmt->bind_param("i", $book_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Réservation annulée avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'état du livre.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'annulation de la réservation.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Réservation introuvable.']);
    }
    exit;
}



?>




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
                <a href="deconnexion.php" class="btn btn-danger">Deconnexion</a>
            </div>
        </div>
        <div class="text-center">
            <h1>Systeme de gestion de bibliotheque</h1>
            <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['Nom']); ?> !</p>
        </div>

        <!-- Vue utilisateur -->
        <h2>Tableau de bord utilisateur</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Parcourir les livres</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#bookSearchFilterModal">Rechercher des livres</button>
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
        <!-- Notifications pour les livres en retard -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Notifications</h5>
                <p>Livres en retard :</p>
                <?php if (count($overdueBooks) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($overdueBooks as $book): ?>
                            <li class="list-group-item">
                                <strong><?php echo htmlspecialchars($book['Titre']); ?></strong> 
                                est en retard de 
                                <span class="text-danger">
                                    <?php echo $book['JoursRetard']; ?> jour(s)
                                </span>.
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-success">Vous n'avez aucun livre en retard.</p>
                <?php endif; ?>
            </div>
        </div>

        <script>
        $(document).ready(function() {
            $('#searchFilterForm').on('submit', function(e) {
                e.preventDefault(); // Empêche la soumission classique du formulaire

                $.ajax({
                    url: 'user.php',
                    method: 'POST',
                    data: $(this).serialize() + '&ajaxRequest=true',
                    success: function(response) {
                        // Affiche les résultats dans le div #searchResults
                        $('#searchResults').html(response);
                    },
                    error: function() {
                        $('#searchResults').html('<p class="text-danger">Une erreur est survenue. Veuillez réessayer.</p>');
                    }
                });
            });
        });
        </script>



        <!-- Modal de recherche et filtrage de livres -->
        <div class="modal fade" id="bookSearchFilterModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rechercher et Filtrer les Livres</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form id="searchFilterForm">
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

        <!-- Modal d'ajout de réservation -->
        <div class="modal fade" id="addReservationsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter une réservation</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addReservationForm">
                            <div class="form-group">
                                <label for="reservationBookId">ID du livre</label>
                                <input type="number" id="reservationBookId" name="book_id" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Réserver</button>
                        </form>
                        <div id="reservationMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        $(document).ready(function() {
            $('#addReservationForm').on('submit', function(e) {
                e.preventDefault(); // Empêche le rechargement de la page

                const bookId = $('#reservationBookId').val();

                $.ajax({
                    url: 'user.php',
                    method: 'POST',
                    data: {
                        action: 'addReservation',
                        book_id: bookId
                    },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {
                            $('#reservationMessage').html(
                                `<p class="text-success">${result.message}</p>`
                            );
                            $('#reservationBookId').val(''); // Réinitialise le champ
                        } else {
                            $('#reservationMessage').html(
                                `<p class="text-danger">${result.message}</p>`
                            );
                        }
                    },
                    error: function() {
                        $('#reservationMessage').html(
                            `<p class="text-danger">Une erreur est survenue. Veuillez réessayer.</p>`
                        );
                    }
                });
            });
        });
        </script>


        <!-- Modal de visualisation des réservations -->
        <div class="modal fade" id="viewReservationsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Vos Réservations</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Voici les livres que vous avez réservés :</p>
                        <div id="reservationListContainer">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID Réservation</th>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>Date de Réservation</th>
                                    </tr>
                                </thead>
                                <tbody id="reservationList">
                                    <!-- Les réservations s'afficheront ici -->
                                </tbody>
                            </table>
                        </div>
                        <p class="text-danger" id="noReservations" style="display: none;">Aucune réservation trouvée.</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                // Action déclenchée à l'ouverture du modal
                $('#viewReservationsModal').on('show.bs.modal', function () {
                    // Réinitialise les données précédentes
                    $('#reservationList').empty();
                    $('#noReservations').hide();

                    // Effectue la requête AJAX
                    $.ajax({
                        url: 'user.php',
                        method: 'POST',
                        data: {
                            action: 'viewReservations'
                        },
                        success: function (response) {
                            const reservations = JSON.parse(response);
                            const $reservationList = $('#reservationList'); // Cible uniquement le tbody du tableau

                            if (reservations.length > 0) {
                                // Ajoute chaque réservation dans le tableau
                                reservations.forEach(reservation => {
                                    $reservationList.append(`
                                        <tr>
                                            <td>${reservation.ReservationID}</td>
                                            <td>${reservation.Titre}</td>
                                            <td>${reservation.Auteur}</td>
                                            <td>${reservation.DateReservation}</td>
                                        </tr>
                                    `);
                                });
                            } else {
                                // Si aucune réservation n'est trouvée
                                $('#noReservations').show();
                            }
                        },
                        error: function () {
                            // Affiche un message d'erreur en cas de problème
                            $('#reservationList').html(`
                                <tr>
                                    <td colspan="4" class="text-danger">Une erreur est survenue. Veuillez réessayer.</td>
                                </tr>
                            `);
                        }
                    });
                });
            });

        </script>


        <!-- Modal d'annulation de reservation -->
        <div class="modal fade" id="cancelReservationsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Annuler une réservation</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="cancelReservationForm">
                            <div class="form-group">
                                <label for="cancelReservationId">ID de réservation</label>
                                <input type="number" id="cancelReservationId" name="reservation_id" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Annuler</button>
                        </form>
                        <div id="cancelMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#cancelReservationForm').on('submit', function(e) {
                    e.preventDefault(); // Empêche le rechargement de la page

                    const reservationId = $('#cancelReservationId').val();

                    $.ajax({
                        url: 'user.php',
                        method: 'POST',
                        data: {
                            action: 'cancelReservation',
                            reservation_id: reservationId
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                $('#cancelMessage').html(
                                    `<p class="text-success">${result.message}</p>`
                                );
                                $('#cancelReservationId').val(''); // Réinitialise le champ
                            } else {
                                $('#cancelMessage').html(
                                    `<p class="text-danger">${result.message}</p>`
                                );
                            }
                        },
                        error: function() {
                            $('#cancelMessage').html(
                                `<p class="text-danger">Une erreur est survenue. Veuillez réessayer.</p>`
                            );
                        }
                    });
                });
            });
        </script>



    </div>



</body>
</html>
