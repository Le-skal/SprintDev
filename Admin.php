<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard</title>
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
            <p>Welcome, Administrator!</p>
        </div>
        
        <!-- Administrator View -->
        <h2>Administrator Dashboard</h2>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Manage Books</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addBookModal">Add Book</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#editBookModal">Edit Book</button>
                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteBookModal">Delete Book</button>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Manage Users</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button>
                <button class="btn btn-secondary" data-toggle="modal" data-target="#editUserModal">Edit User</button>
                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal">Delete User</button>
            </div>
        </div>

        <!-- Add Book Modal -->
        <div class="modal fade" id="addBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Book</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="bookTitle">Title</label>
                                <input type="text" id="bookTitle" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="bookAuthor">Author</label>
                                <input type="text" id="bookAuthor" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="bookCategory">Category</label>
                                <input type="text" id="bookCategory" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Book Modal -->
        <div class="modal fade" id="editBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Book</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="editBookId">Book ID</label>
                                <input type="text" id="editBookId" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editBookTitle">Title</label>
                                <input type="text" id="editBookTitle" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editBookAuthor">Author</label>
                                <input type="text" id="editBookAuthor" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editBookCategory">Category</label>
                                <input type="text" id="editBookCategory" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Book Modal -->
        <div class="modal fade" id="deleteBookModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Book</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="deleteBookId">Book ID</label>
                                <input type="text" id="deleteBookId" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="userName">Name</label>
                                <input type="text" id="userName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="userEmail">Email</label>
                                <input type="email" id="userEmail" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" id="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="userRole">Role</label>
                                <select id="userRole" class="form-control" required>
                                    <option value="admin">Administrator</option>
                                    <option value="librarian">Librarian</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="editUserId">User ID</label>
                                <input type="text" id="editUserId" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editUserName">Name</label>
                                <input type="text" id="editUserName" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editUserEmail">Email</label>
                                <input type="email" id="editUserEmail" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editpassword">Password</label>
                                <input type="text" id="editPassword" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="editUserRole">Role</label>
                                <select id="editUserRole" class="form-control">
                                    <option value="admin">Administrator</option>
                                    <option value="librarian">Librarian</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete User Modal -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete User</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="deleteUserId">User ID</label>
                                <input type="text" id="deleteUserId" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
