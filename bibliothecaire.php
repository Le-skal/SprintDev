<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard</title>
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
            <p>Welcome, Librarian!</p>
        </div>

        <!-- Librarian View -->
        <h2>Librarian Dashboard</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Track Books</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#viewBookModal">View All Books</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#statusBookModal">Check Book Status</button>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Handle Loans</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#issueBookModal">Issue Book</button>
                <button class="btn btn-warning" data-toggle="modal" data-target="#recieveBookModal">Receive Book</button>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Reminder Loans</h5>
                <button class="btn btn-primary">Send Reminder</button>
            </div>
        </div>

        <!-- View All Books Modal -->
        <div class="modal fade" id="viewBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">All Books</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Here is a list of all available books in the library:</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Check Book Status Modal -->
        <div class="modal fade" id="statusBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Check Book Status</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="statusBookId">Enter Book ID</label>
                                <input type="text" id="statusBookId" class="form-control" placeholder="Book ID" required>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="checkBookStatus()">Check Status</button>
                        </form>
                        <div id="bookStatusResult" class="mt-3">
                            <!-- Dynamic status will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Issue Book Modal -->
        <div class="modal fade" id="issueBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Issue Book</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="issueBookId">Book ID</label>
                                <input type="text" id="issueBookId" class="form-control" placeholder="Book ID" required>
                            </div>
                            <div class="form-group">
                                <label for="issueUserId">User ID</label>
                                <input type="text" id="issueUserId" class="form-control" placeholder="User ID" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Issue Book</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receive Book Modal -->
        <div class="modal fade" id="recieveBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Receive Book</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="returnBookId">Book ID</label>
                                <input type="text" id="returnBookId" class="form-control" placeholder="Book ID" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Mark as Returned</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
