<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="<?= csrf_hash() ?>">

    <title>My Borrowed Books - Library System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css">
    <style>
        .container {
            margin-top: 50px;
        }

        .card {
            width: 18rem;
            margin: 1rem;
            height: 400px;
            display: flex;
            flex-direction: column;
        }

        .card-body {
            overflow: hidden;
            flex-grow: 1;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .grid-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card-img-top {
            height: 150px;
            object-fit: cover;
        }

        .card-body p {
            font-size: 0.9rem;
        }

        .return-btn {
            margin-top: auto;
        }

        .no-books-message {
            text-align: center;
            font-size: 1.2rem;
            color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <i class="bx bx-book-reader"></i> Library System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('dashboard') ?>">
                        <i class="bx bx-home"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-user-circle"></i> <?= session()->get('firstname') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url('student/my-borrowed-books') ?>">
                            <i class="bx bx-bookmark"></i> My Borrowed Books
                        </a>
                        <a class="dropdown-item" href="<?= base_url('student/view-profile') ?>">
                            <i class="bx bx-id-card"></i> View Profile
                        </a>
                        <a class="dropdown-item" href="<?= base_url('user/logout') ?>">
                            <i class="bx bx-log-out"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- Check if borrowed books exist -->
        <?php if (empty($borrowed)): ?>
            <div class="no-books-message">
                <p>You have no borrowed books at the moment.</p>
            </div>
        <?php else: ?>
            <div class="text-center mt-4" id="returnAllBooksContainer">
                <button class="btn btn-danger" id="returnAllBooksBtn">
                    <i class="bx bx-rotate-left"></i> Return All Books
                </button>
            </div>

            <div class="grid-container">
                <?php foreach ($borrowed as $transaction): ?>
                    <div class="card">
                        <img src="<?= base_url('uploads/books/' . esc($transaction['photo'])) ?>" class="card-img-top" alt="<?= esc($transaction['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($transaction['title']) ?></h5>
                            <p class="card-text">
                                <strong>Author:</strong> <?= esc($transaction['author']) ?><br>
                                <strong>ISBN:</strong> <?= esc($transaction['isbn']) ?><br>
                                <strong>Published Date:</strong> <?= esc($transaction['published_date']) ?><br>
                                <strong>Borrowed On:</strong> <?= esc($transaction['borrow_date']) ?><br>
                                <strong>Due Date:</strong> <?= esc($transaction['due_date']) ?>
                            </p>
                            <button class="btn btn-warning btn-sm return-btn" data-id="<?= esc($transaction['transaction_id']) ?>">
                                <i class="bx bx-check-circle"></i> Return
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).on('click', '.return-btn', function() {
            var transaction_id = $(this).data('id');
            Swal.fire({
                title: 'Confirm Return',
                text: "Do you want to return this book?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, return it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/library_system/index.php/transaction/returnBook',
                        type: 'POST',
                        data: {
                            transaction_id: transaction_id
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait.',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Success!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '#returnAllBooksBtn', function() {
            Swal.fire({
                title: 'Confirm Return All Books',
                text: "Do you want to return all your borrowed books at once?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, return all!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/library_system/index.php/transaction/returnAllBooks',
                        type: 'POST',
                        data: {
                            user_id: <?= session()->get('user_id') ?>
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we return all your books.',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Success!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>