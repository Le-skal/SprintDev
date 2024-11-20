<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="text-right">
            <div>
                <a href="connextion.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        <div class="text-center">
            <h1>Library Management System</h1>
            <p>Welcome, User!</p>
        </div>

        <!-- User View -->
        <h2>User Dashboard</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Browse Books</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#searchBookModal">Search Books</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#filterBookModal">Filter by Category</button>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Your Reservations</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addReservationsModal">Add Reservations</button>
                <button class="btn btn-info" data-toggle="modal" data-target="#viewReservationsModal">View Reservations</button>
                <button class="btn btn-warning" data-toggle="modal" data-target="#cancelReservationsModal">Cancel Reservation</button>
            </div>
        </div>

        <!-- Search Books Modal -->
        <div class="modal fade" id="searchBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Search Books</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="searchKeyword">Keyword</label>
                                <input type="text" id="searchKeyword" class="form-control" placeholder="Enter book title, author, or keyword" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter by Category Modal -->
        <div class="modal fade" id="filterBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter Books by Category</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="bookCategory">Category</label>
                                <select id="bookCategory" class="form-control" required>
                                    <option value="">Select a category</option>
                                    <option value="fiction">Fiction</option>
                                    <option value="non-fiction">Non-Fiction</option>
                                    <option value="science">Science</option>
                                    <option value="history">History</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Reservations Modal -->
        <div class="modal fade" id="addReservationsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Reservation</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="reservationBookId">Book ID</label>
                                <input type="text" id="reservationBookId" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Reserve</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Reservations Modal -->
        <div class="modal fade" id="viewReservationsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Your Reservations</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Here are the books you have reserved:</p>
                        <ul id="reservationList" class="list-group">
                            <!-- Dynamically populate the list with reserved books -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancel Reservation Modal -->
        <div class="modal fade" id="cancelReservationsModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Reservation</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="cancelReservationId">Reservation ID</label>
                                <input type="text" id="cancelReservationId" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Cancel Reservation</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
